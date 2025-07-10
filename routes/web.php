<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('dashboard', 'admin.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard')
    ->prefix('admin');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [UserController::class, 'editProfile'])->name('settings.profile');
    Route::get('settings/password', [UserController::class, 'changePassword'])->name('settings.password');
    Route::get('settings/appearance', [UserController::class, 'changeAppearance'])->name('settings.appearance');
});

require __DIR__.'/auth.php';
