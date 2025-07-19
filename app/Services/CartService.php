<?php

namespace App\Services;

use App\Enums\PaymentPlan;
use App\Exceptions\CustomException;
use App\Repositories\CartItemRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use stdClass;

class CartService
{
    public static $cart_key = 'cart.key';
    public static $guest_key = 'guest_key';
    public static $guest_cart_ttl = 10080; // in minutes

    protected $cartItemRepository;
    protected $productService;
    protected $storeSettingsService;

    /**
     * Create a new class instance.
     */
    public function __construct(CartItemRepository $cartItemRepository, ProductService $productService, StoreSettingsService $storeSettingsService)
    {
        $this->cartItemRepository = $cartItemRepository;
        $this->productService = $productService;
        $this->storeSettingsService = $storeSettingsService;
    }

    public function find($id)
    {
        return $this->cartItemRepository->find($id);
    }

    public function all()
    {
        return $this->cartItemRepository->all();
    }

    public function store($input)
    {
        return $this->cartItemRepository->create($input);
    }

    public function update($id, $input)
    {
        return $this->cartItemRepository->update($input, $id);
    }

    public function delete($ids)
    {
        $this->cartItemRepository->delete($ids);
    }

    public function allQuery($search = [])
    {
        return $this->cartItemRepository->allQuery($search);
    }

    public function getGuestId(): string
    {
        $guest_id = request()->cookie(self::$guest_key);

        if (!$guest_id) {
            $guest_id = Str::uuid()->toString();
            cookie()->queue(self::$guest_key, $guest_id, self::$guest_cart_ttl);
        }

        return $guest_id;
    }

    public function getCartUser(): stdClass
    {
        if (Auth::check()) {
            $user_key = 'user_id';
            $user_id = Auth::id();
        } else {
            $user_key = 'guest_id';
            $user_id = $this->getGuestId();
        }

        $obj = new stdClass();
        $obj->user_key = $user_key;
        $obj->user_id = $user_id;

        return $obj;
    }

    public function getCartItem($product_id)
    {
        $obj = $this->getCartUser();
        $user_key = $obj->user_key;
        $user_id = $obj->user_id;

        $cart_item = $this->allQuery([
            $user_key => $user_id,
            'product_id' => $product_id,
        ])->first();

        return $cart_item;
    }

    public function addToCart($product_id, $quantity = 1, $payment_plan = PaymentPlan::Once->value)
    {
        $cart_item = $this->getCartItem($product_id);

        if (!$cart_item) {
            $obj = $this->getCartUser();
            $user_key = $obj->user_key;
            $user_id = $obj->user_id;

            $this->store([
                $user_key => $user_id,
                'product_id' => $product_id,
                'quantity' => $quantity,
                'payment_plan' => $payment_plan,
            ]);
        } else {
            $cart_item->quantity += $quantity;
            $cart_item->payment_plan = $payment_plan;
            $cart_item->save();
        }
    }

    public function updateQuantity($product_id, int $quantity, $payment_plan = PaymentPlan::Once->value)
    {
        $cart_item = $this->getCartItem($product_id);

        if (!$cart_item) {
            throw new CustomException('Item not found in cart');
        }

        if ($quantity > 0) {
            $cart_item->quantity = $quantity;
            $cart_item->payment_plan = $payment_plan;
            $cart_item->save();
        } else {
            $cart_item->delete();
        }
    }

    public function removeFromCart($product_id)
    {
        $this->getCartItem($product_id)->delete();
    }

    public function deleteCart()
    {
        $obj = $this->getCartUser();
        $user_key = $obj->user_key;
        $user_id = $obj->user_id;

        $this->allQuery([
            $user_key => $user_id,
        ])
        ->get()
        ->each
        ->delete();
    }

    public function syncSessionCartToDatabase()
    {
        if (!Auth::check()) {
            logger()->warning('Cannot sync cart to database, no user logged in');
            return;
        };

        $user_id = Auth::id();
        $guest_id = request()->cookie(self::$guest_key);

        if (!$guest_id) {
            logger()->info('Cannot sync cart to database, no items in guest cart');
            return;
        }

        $guest_items = $this->allQuery(['guest_id' => $guest_id])->get();
        $user_items = $this->allQuery(['user_id' => $user_id])->get()->keyBy('product_id');

        foreach ($guest_items as $guest_item) {
            $product_id = $guest_item->product_id;

            if ($user_items->has($product_id)) {
                // Product exists in both carts → merge quantity
                $user_item = $user_items[$product_id];
                $user_item->quantity += $guest_item->quantity;

                // Update to the most recent payment plan
                $user_item->payment_plan = $guest_item->payment_plan;
                $user_item->save();

                // Delete guest version
                $guest_item->delete();
            } else {
                // Product only exists in guest cart → transfer to user
                $guest_item->user_id = $user_id;
                $guest_item->guest_id = null;
                $guest_item->save();
            }
        }

        cookie()->queue(cookie()->forget(self::$guest_key));
    }

    public function userCart()
    {
        $user = $this->getCartUser();
        $user_key = $user->user_key;
        $user_id = $user->user_id;

        $cart_items = $this->allQuery([
            $user_key => $user_id,
        ])->get();

        $down_payment_percentage = $this->storeSettingsService->downPaymentPercentage();
        $installment_months = $this->storeSettingsService->repaymentMonths();

        $cart_items = $cart_items->map(function ($elem) use ($down_payment_percentage, $installment_months) {
            $product = $this->productService->allQuery(['id' => $elem->product_id])->first();
            if ($product) {
                $elem->name = $product->name;
                $elem->price = $product->selling_price ?? 0;
                $elem->payment_plan = $elem->payment_plan instanceof PaymentPlan ? $elem->payment_plan->value : $elem->payment_plan;
                if ($elem->payment_plan->value == PaymentPlan::Installment->value) {
                    $elem->down_payment_percentage = $down_payment_percentage;
                    $elem->down_payment_amount = ($down_payment_percentage / 100) * $elem->price;
                    $balance = $elem->price - $elem->down_payment_amount;
                    $elem->installment_months = $installment_months;
                    $elem->installment_amount = $balance / $installment_months;
                }

                return $elem;
            }
        });

        return $cart_items;
    }

    public function calculateCartValue()
    {
        $cart = $this->userCart();
        $value = 0;
        foreach ($cart as $elem) {
            // if ($elem->payment_plan == PaymentPlan::Installment->value) {
            //     $value += ($elem->down_payment_amount + ($elem->installment_amount * $elem->installment_months) * $elem->quantity);
            // } else {
                $value += $elem->price * $elem->quantity;
            // }
        }

        return $value;
    }
}
