<?php

namespace App\Livewire\Client\Orders;

use App\Services\OrderService;
use App\Services\CartService;
use App\Services\ProductService;
use App\Services\StoreSettingsService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Throwable;

class Checkout extends Component
{
    use HandlesErrorMessage;

    public $cart_items;
    public $subtotal;
    public $total_amount;

    public $allow_order;

    public $customer_name;
    public $customer_phone;
    public $customer_phone_prefix;
    public $customer_phone_country_code;
    public $customer_email;
    public $delivery_address;
    public $landmark;
    public $delivery_note;

    protected $cartService;
    protected $productService;
    protected $storeSettingsService;
    protected $orderService;

    protected $rules = [
        'customer_name' => 'required|string|min:1',
        'customer_phone' => 'required|string',
        'customer_phone_prefix' => 'required|string',
        'customer_phone_country_code' => 'required|string',
        'customer_email' => 'nullable|email',
        'delivery_address' => 'required|string',
        'landmark' => 'required|string',
        'delivery_note' => 'nullable|string',
    ];

    public function boot(CartService $cartService, ProductService $productService, StoreSettingsService $storeSettingsService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->productService = $productService;
        $this->storeSettingsService = $storeSettingsService;
        $this->orderService = $orderService;
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

        if (Auth::check()) {
            $user = Auth::user();
            $this->customer_name = $user->name ?? '';
            $this->customer_phone = $user->phone ?? '';
            $this->customer_email = $user->email ?? '';
            $this->delivery_address = $user->delivery_address ?? '';
            $this->landmark = $user->delivery_landmark ?? '';
            $this->customer_phone_country_code = $user->phone_country_code ?? 'gh';
        }
        $this->subtotal = $this->cartService->calculateCartValue();
        $this->total_amount = $this->subtotal;

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

    public function placeOrder()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $customer_full_phone = str_replace(' ', '', '+' . $this->customer_phone_prefix . $this->customer_phone);
            $payload = [
                'customer_name' => $this->customer_name,
                'customer_phone' => $customer_full_phone,
                'customer_email' => $this->customer_email,
                'delivery_address' => $this->delivery_address,
                'landmark' => $this->landmark,
                'delivery_note' => $this->delivery_note,
            ];
            $this->orderService->placeOrder($payload);
            DB::commit();
            toastr()->success(__('Your order has been placed!'));
            redirect()->route('home');
        } catch (Throwable $err) {
            DB::rollBack();
            $default_message = __('Sorry something went wrong whiles placing your order. Please try again later');
            $message = $this->handle($err, $default_message)->message;
            toastr()->error($message);
        }
    }

    public function render()
    {
        return view('livewire.client.orders.checkout');
    }
}
