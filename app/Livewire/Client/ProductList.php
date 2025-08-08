<?php

namespace App\Livewire\Client;

use App\Enums\PaymentPlan;
use App\Services\CartService;
use App\Services\ProductService;
use App\Services\StoreSettingsService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class ProductList extends Component
{
    use WithPagination, HandlesErrorMessage;

    protected $productService;
    protected $storeSettingsService;
    protected $cartService;

    public function boot(ProductService $productService, StoreSettingsService $storeSettingsService, CartService $cartService)
    {
        $this->productService = $productService;
        $this->storeSettingsService = $storeSettingsService;
        $this->cartService = $cartService;
    }

    public function mount()
    {
        try {
            // $this->loadData();
        } catch (Throwable $err) {
            $message = $this->handle($err)->message;
            toastr()->error(__('Error mounting component') . ': '. $message);
        }
    }

    // public function loadData()
    // {
    //     $this->products = $this->productService->products()->paginate(1);
    //     // dd($this->products);
    // }

    #[Computed]
    public function currency()
    {
        return $this->storeSettingsService
            ->currencySymbol();
    }

    #[Computed]
    public function repaymentMonths()
    {
        return $this->storeSettingsService
            ->repaymentMonths();
    }

    #[Computed]
    public function downPaymentPercentage()
    {
        return $this->storeSettingsService
            ->downPaymentPercentage();
    }

    public function addToCart($product_id, $is_installment = false)
    {
        try {
            $product = $this->productService->find($product_id);
            $payment_plan = $is_installment ? PaymentPlan::Installment->value : PaymentPlan::Once->value;
            DB::beginTransaction();
            $this->cartService->addToCart(product_id: $product->id, payment_plan: $payment_plan);
            DB::commit();
            toastr()->success('Item added to cart');
        } catch (Throwable $err) {
            DB::rollBack();
            $default_message = __('Error adding item to cart');
            $message = $this->handle($err, $default_message)->message;
            toastr()->error($message);
        }
    }

    public function render()
    {
        $products = $this->productService->allQuery()->active()->quantityAvailable()->paginate(20);

        return view('livewire.client.product-list', [
            'products' => $products,
        ]);
    }
}
