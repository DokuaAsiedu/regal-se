<?php

use App\Http\Controllers\Client\API\ProductController;
use App\Http\Controllers\Client\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('transactions/paystack/webhook', [TransactionController::class, 'webhook']);

Route::post('product/update', [ProductController::class, 'update']);
Route::post('product/store', [ProductController::class, 'store']);

Route::get('test', function () {
    return 'It works!';
});
