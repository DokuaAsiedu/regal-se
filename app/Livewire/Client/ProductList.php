<?php

namespace App\Livewire\Client;

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

    public function addToCart($product_id)
    {
        try {
            $product = $this->productService->find($product_id);
            DB::beginTransaction();
            $this->cartService->addToCart($product->id);
            DB::commit();
            toastr()->success('Item added to cart');
        } catch (Throwable $err) {
            DB::rollBack();
            $this->handle($err);
            toastr()->error(__('Error adding item to cart'));
        }
    }

    public function render()
    {
        $products = $this->productService->allQuery([])->active()->quantityAvailable()->paginate(20);

        return view('livewire.client.product-list', [
            'products' => $products,
        ]);
    }
}
