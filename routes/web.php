<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return redirect()->route('shop');
})->name('home');

Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('shop', [App\Http\Controllers\ShopController::class, 'index'])
    ->name('shop');

Route::get('products/{id}', [App\Http\Controllers\ProductController::class, 'show'])
    ->name('products.show');

Route::get('cart', [App\Http\Controllers\CartController::class, 'index'])
    ->name('cart');

Route::post('cart/add', [App\Http\Controllers\CartController::class, 'add'])
    ->name('cart.add');

Route::patch('cart/{id}', [App\Http\Controllers\CartController::class, 'update'])
    ->name('cart.update');

Route::delete('cart/{id}', [App\Http\Controllers\CartController::class, 'remove'])
    ->name('cart.remove');

Route::get('checkout', [App\Http\Controllers\CheckoutController::class, 'index'])
    ->name('checkout');

Route::post('checkout/place-order', [App\Http\Controllers\CheckoutController::class, 'placeOrder'])
    ->name('checkout.place-order');

Route::get('orders/{id}/invoice', [App\Http\Controllers\CheckoutController::class, 'showInvoice'])
    ->name('order.invoice');

Route::post('addresses', [App\Http\Controllers\AddressController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('addresses.store');

Route::put('addresses/{id}', [App\Http\Controllers\AddressController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('addresses.update');

Route::delete('addresses/{id}', [App\Http\Controllers\AddressController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('addresses.destroy');

require __DIR__.'/settings.php';
