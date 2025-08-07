<?php

use App\Http\Controllers\Client\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('transactions/paystack/webhook', [TransactionController::class, 'webhook']);

Route::get('test', function () {
    return 'It works!';
});
