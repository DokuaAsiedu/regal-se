<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StoreSettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('cart', [CartController::class, 'index'])->name('cart');
});

Route::view('dashboard', 'admin.dashboard')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.dashboard')
    ->prefix('admin');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
    Route::get('store-settings', [StoreSettingsController::class, 'edit'])->name('store-settings.edit');
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [UserController::class, 'editProfile'])->name('settings.profile');
    Route::get('settings/password', [UserController::class, 'changePassword'])->name('settings.password');
    Route::get('settings/appearance', [UserController::class, 'changeAppearance'])->name('settings.appearance');
});

require __DIR__.'/auth.php';
