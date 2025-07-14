<?php

namespace App\Services;

use App\Enums\PaymentPlan;
use App\Exceptions\CustomException;
use App\Repositories\CartItemRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public static $cart_key = 'cart.key';

    protected $cartItemRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(CartItemRepository $cartItemRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
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

    public function allQuery($search)
    {
        return $this->cartItemRepository->allQuery($search);
    }

    public function addToCart($product_id, $quantity = 1, $payment_plan = PaymentPlan::Once->value)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $existing_item = $this->allQuery([
                'user_id' => $user_id,
                'product_id' => $product_id,
            ])->first();

            if (!$existing_item) {
                $this->store([
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'payment_plan' => $payment_plan,
                ]);
            } else {
                $existing_item->quantity += $quantity;
                $existing_item->save();
            }
        } else {
            $cart_items = collect(Session::get(self::$cart_key, []));
            $existing_item = $cart_items->firstWhere('product_id', $product_id);

            if ($existing_item) {
                $existing_item['quantity'] += $quantity;
                $cart_items = $cart_items->map(function ($item) use ($existing_item) {
                    return $item['product_id'] == $existing_item['product_id'] ? $existing_item : $item;
                });
            } else {
                $cart_items->push([
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'payment_plan' => $payment_plan,
                ]);
            }

            Session::put(self::$cart_key, $cart_items);
        }
    }

    public function updateQuantity($product_id, int $quantity, $payment_plan = PaymentPlan::Once->value)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $existing_item = $this->allQuery([
                'user_id' => $user_id,
                'product_id' => $product_id,
            ])->first();

            if (!$existing_item) {
                throw new CustomException('Item not found in cart');
            }

            if ($quantity > 0) {
                $existing_item->quantity = $quantity;
                $existing_item->payment_plan = $payment_plan;
                $existing_item->save();
            } else {
                $existing_item->delete();
            }
        } else {
            $cart_items = collect(Session::get(self::$cart_key, []));
            $existing_item = $cart_items->firstWhere('product_id', $product_id);

            if (!$existing_item) {
                throw new CustomException('Item not found in cart');
            }

            if ($quantity > 0) {
                $existing_item['quantity'] = $quantity;
                $existing_item['payment_plan'] = $payment_plan;
            } else {
                $cart_items = $cart_items->reject(fn($item) => $item['product_id'] == $product_id);
            }

            Session::put(self::$cart_key, $cart_items);
        }
    }

    public function handleCartItem($product_id, $quantity = 1, $payment_plan = PaymentPlan::Once->value, $mode = 'increase')
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $existing_item = $this->allQuery([
                'user_id' => $user_id,
                'product_id' => $product_id,
            ])->first();

            if (!$existing_item && $mode == 'increase') {
                $this->store([
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'payment_plan' => $payment_plan,
                ]);
            } else if ($existing_item) {
                if ($mode == 'increase') {
                    $existing_item->quantity += $quantity;
                    $existing_item->payment_plan = $payment_plan;
                    $existing_item->save();
                } else if ($mode == 'decrease') {
                    $existing_item->quantity -= $quantity;
                    $existing_item->payment_plan = $payment_plan;

                    if ($existing_item->quantity <= 0) {
                        $existing_item->delete();
                    } else {
                        $existing_item->save();
                    }
                }
            }
        } else {
            if ($payment_plan != PaymentPlan::Once->value) {
                throw new CustomException('You need to be logged in and have KYC approved before accessing this feature');
            }
            $cart_items = collect(Session::get(self::$cart_key, []));
            $existing_item = $cart_items->firstWhere('product_id', $product_id);

            if ($existing_item) {
                if ($mode == 'increase') {
                    $existing_item['quantity'] += $quantity;
                    $existing_item['payment_plan'] = $payment_plan;
                } elseif ($mode == 'decrease') {
                    $existing_item['quantity'] -= $quantity;
                    $existing_item['payment_plan'] = $payment_plan;
                }

                if ($existing_item['quantity'] <= 0) {
                    $cart_items = $cart_items->reject(fn($item) => $item['product_id'] == $product_id);
                } else {
                    $cart_items = $cart_items->map(function ($item) use ($existing_item) {
                        return $item['product_id'] == $existing_item['product_id'] ? $existing_item : $item;
                    });
                }
            } elseif ($mode == 'increase') {
                $cart_items->push([
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'payment_plan' => $payment_plan,
                ]);
            }

            Session::put(self::$cart_key, $cart_items->values()->toArray());
        }
    }

    public function removeFromCart($product_id)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $this->allQuery([
                'user_id' => $user_id,
                'product_id' => $product_id,
            ])->get()
                ->each
                ->delete();
        } else {
            $cart_items = collect(Session::get(self::$cart_key, []));
            $cart_items = $cart_items->reject(fn($item) => $item['product_id'] == $product_id);
            Session::put(self::$cart_key, $cart_items);
        }
    }

    public function deleteCart()
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $this->allQuery([
                'user_id' => $user_id,
            ])->get()
                ->each
                ->delete();
        } else {
            Session::forget(self::$cart_key);
        }
    }

    public function syncSessionCartToDatabase()
    {
        if (!Auth::check()) {
            logger()->warning('Cannot sync cart to database, no user logged in');
            return;
        };

        $user_id = Auth::id();
        $session_cart_items = Session::get(self::$cart_key, []);

        foreach ($session_cart_items as $item) {
            $existing = $this->allQuery([
                'user_id' => $user_id,
                'product_id' => $item['product_id'],
            ])->first();

            if ($existing) {
                $existing->quantity += $item['quantity'];
                $existing->save();
            } else {
                $this->store([
                    'user_id' => $user_id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'payment_plan' => $item['payment_plan'],
                ]);
            }
        }

        Session::forget(self::$cart_key);
    }

    public function userCart()
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $cart_items = $this->allQuery([
                'user_id' => $user_id,
            ])->get();
        } else {
            $cart_items = collect(Session::get(self::$cart_key, []));
            $cart_items = $cart_items->map(function ($item) {
                return (object) $item;
            });
        }

        return $cart_items;
    }
}
