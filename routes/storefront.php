<?php

use App\Http\Controllers\MockPaymentController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\ProductController;
use App\Http\Controllers\Storefront\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/search', [ShopController::class, 'search'])->name('search');
Route::get('/categories/{category:slug}', [ShopController::class, 'category'])->name('categories.show');
Route::get('/collections/{collection:slug}', [ShopController::class, 'collection'])->name('collections.show');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::view('/shipping', 'storefront.information.shipping')->name('shipping');
Route::view('/returns', 'storefront.information.returns')->name('returns');
Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/webhooks/payments', [PaymentWebhookController::class, 'handle'])->name('webhooks.payments');
Route::get('/mock/pay/{reference}', [MockPaymentController::class, 'pay'])->name('mock.payment.pay');
Route::post('/mock/pay/{reference}/success', [MockPaymentController::class, 'success'])->name('mock.payment.success');
