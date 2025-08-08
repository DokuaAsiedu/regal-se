<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\KYCController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StoreSettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\KYCController as ClientKYCController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\TransactionController;
use App\Http\Controllers\Client\UserController as ClientUserController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('cart', [OrderController::class, 'cart'])->name('cart');
    Route::get('checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('kyc', [ClientKYCController::class, 'index'])->name('client.kyc');
    Route::get('transactions/paystack/verify', [TransactionController::class, 'verifyPaystackTransaction'])->name('transactions.paystack.verify');
    Route::get('settings/profile', [ClientUserController::class, 'editProfile'])->name('client.settings.profile');
    Route::get('settings/password', [ClientUserController::class, 'changePassword'])->name('client.settings.password');
    Route::get('settings/appearance', [ClientUserController::class, 'changeAppearance'])->name('client.settings.appearance');
});

Route::view('dashboard', 'admin.dashboard')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.dashboard')
    ->prefix('admin');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('orders', AdminOrderController::class);
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
    Route::resource('kyc', KYCController::class);
    Route::get('store-settings', [StoreSettingsController::class, 'edit'])->name('store-settings.edit');
    Route::resource('payments', AdminPaymentController::class);
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [UserController::class, 'editProfile'])->name('settings.profile');
    Route::get('settings/password', [UserController::class, 'changePassword'])->name('settings.password');
    Route::get('settings/appearance', [UserController::class, 'changeAppearance'])->name('settings.appearance');
});

require __DIR__.'/auth.php';
