<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\StoreSettingsService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $productService;
    protected $storeSettingsService;

    public function __construct(ProductService $productService, StoreSettingsService $storeSettingsService)
    {
        $this->productService = $productService;
        $this->storeSettingsService = $storeSettingsService;
    }

    public function index()
    {
        $products = $this->productService->products()->get();
        $currency = $this->storeSettingsService
            ->allQuery(['code' => 'currency_symbol'])
            ->first()
            ->value;

        return view('client.index', compact('products', 'currency'));
    }
}
