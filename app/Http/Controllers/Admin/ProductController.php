<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    protected $categoryService;

    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        return view('admin.products.index');
    }

    public function create()
    {
        $statuses = $this->productService->validStatuses();
        $available_categories = $this->categoryService
            ->allQuery()
            ->active()
            ->get();
        return view('admin.products.create', compact('statuses', 'available_categories'));
    }

    public function edit($id)
    {
        $product = $this->productService->find($id);
        $statuses = $this->productService->validStatuses();
        $product_categories = $product
            ->categories()
            ->get();
        $available_categories = $this->categoryService
            ->allQuery()
            ->active()
            ->whereNotIn('id', $product_categories->pluck('id'))
            ->get();
        $product_images = $product->getMedia('product_images')
            ->all();

        return view('admin.products.edit', compact('id', 'product', 'statuses', 'product_categories', 'available_categories', 'product_images'));
    }
}
