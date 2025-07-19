<?php

namespace App\Livewire\Client\Orders;

use App\Services\CartService;
use App\Services\ProductService;
use App\Services\StoreSettingsService;
use App\Traits\HandlesErrorMessage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Throwable;

class Cart extends Component
{
    use HandlesErrorMessage;

    public $cart_items;
    public $products = [];

    public $allow_order = false;

    protected $cartService;
    protected $productService;
    protected $storeSettingsService;

    public function boot(CartService $cartService, ProductService $productService, StoreSettingsService $storeSettingsService)
    {
        $this->cartService = $cartService;
        $this->productService = $productService;
        $this->storeSettingsService = $storeSettingsService;
    }

    public function mount()
    {
        try {
            $this->loadData();
        } catch (Throwable $err) {
            $message = $this->handle($err)->message;
            toastr()->error(__('Error mounting component') . ': '. $message);
        }
    }

    public function loadData()
    {
        $this->cart_items = $this->cartService->userCart();

        if (count($this->cart_items) > 0) {
            $this->allow_order = true;
        } else {
            $this->allow_order = false;
        }
    }

    #[Computed]
    public function currency()
    {
        return $this->storeSettingsService
            ->currencySymbol();
    }

    public function increment($product_id)
    {
        try {
            $product = $this->cart_items->where('product_id', $product_id)->first();
            $quantity = $product->quantity + 1;
            $payment_plan = $product->payment_plan;
            $this->cartService->updateQuantity($product_id, $quantity, $payment_plan);
            $this->loadData();
        } catch (Throwable $err) {
            $this->handle($err)->message;
            toastr()->error(__('Error updating quantity'));
        }
    }

    public function decrement($product_id)
    {
        try {
            $product = $this->cart_items->where('product_id', $product_id)->first();
            $quantity = $product->quantity - 1;
            $payment_plan = $product->payment_plan;
            $this->cartService->updateQuantity($product_id, $quantity, $payment_plan);
            $this->loadData();
        } catch (Throwable $err) {
            $this->handle($err)->message;
            toastr()->error(__('Error updating quantity'));
        }
    }

    public function updateQuantity(int $quantity, $product_id)
    {
        try {
            $product = $this->cart_items->where('product_id', $product_id)->first();
            $payment_plan = $product->payment_plan;
            $this->cartService->updateQuantity($product_id, $quantity, $payment_plan);
            $this->loadData();
        } catch (Throwable $err) {
            $this->handle($err)->message;
            toastr()->error(__('Error updating quantity'));
        }
    }

    public function removeFromCart($product_id)
    {
        try {
            $this->cartService->removeFromCart($product_id);
            $this->loadData();
        } catch (Throwable $err) {
            $this->handle($err)->message;
            toastr()->error(__('Error updating quantity'));
        }
    }

    public function render()
    {
        return view('livewire.client.orders.cart');
    }
}
