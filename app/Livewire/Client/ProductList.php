<?php

namespace App\Livewire\Client;

use App\Services\ProductService;
use App\Services\StoreSettingsService;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class ProductList extends Component
{
    use WithPagination;

    protected $productService;
    protected $storeSettingsService;

    public function boot(ProductService $productService, StoreSettingsService $storeSettingsService)
    {
        $this->productService = $productService;
        $this->storeSettingsService = $storeSettingsService;
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


    public function render()
    {
        $products = $this->productService->products()->paginate(20);

        return view('livewire.client.product-list', [
            'products' => $products,
        ]);
    }
}
