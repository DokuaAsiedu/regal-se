<?php

namespace App\Livewire\Client;

use App\Enums\PaymentPlan;
use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\StoreSettingsService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class ProductList extends Component
{
    use WithPagination, HandlesErrorMessage;

    #[Url]
    public $category_code = '';

    protected $productService;
    protected $storeSettingsService;
    protected $cartService;
    protected $categoryService;

    public function boot(ProductService $productService, StoreSettingsService $storeSettingsService, CartService $cartService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->storeSettingsService = $storeSettingsService;
        $this->cartService = $cartService;
        $this->categoryService = $categoryService;
    }

    public function mount()
    {
        try {
            // $this->loadData();
        } catch (Throwable $err) {
            $message = $this->handle($err)->message;
            flash()->error(__('Error mounting component') . ': '. $message);
        }
    }

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
            flash()->success('Item added to cart');
        } catch (Throwable $err) {
            DB::rollBack();
            $default_message = __('Error adding item to cart');
            $message = $this->handle($err, $default_message)->message;
            flash()->error($message);
        }
    }

    public function render()
    {
        if (isset($this->category_code) && !empty($this->category_code)) {
            $products = $this->categoryService
                ->allQuery(['code' => $this->category_code])
                ->first()
                ->products()
                ->active()
                ->quantityAvailable()
                ->paginate(20);
        } else {
            $products = $this->productService->allQuery()->active()->quantityAvailable()->paginate(20);
        }

        return view('livewire.client.product-list', [
            'products' => $products,
        ]);
    }
}
