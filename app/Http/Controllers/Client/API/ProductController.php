<?php

namespace App\Http\Controllers\Client\API;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $this->productService->store($request->all());

            return response()->json([
                'status' => true,
                'redirect' => route('products.index'),
            ]);
        });
    }

    public function update(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $this->productService->update($request->input("id"), $request->all());

            return response()->json([
                'status' => true,
                'redirect' => route('products.index'),
            ]);
        });
    }
}
