<?php
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/cart', [CartController::class, 'index'])
    ->name('cart_index');

Route::post('/cart/add-to-cart', [CartController::class, 'addToCart']);

Route::post('/cart/remove', [CartController::class, 'removeCart'])
    ->name('cart_remove');

Route::post('/cart/update', [CartController::class, 'updateCart'])
    ->name('cart_update');




Route::post('/cart/increase', [CartController::class, 'increase'])->name('cart_increase');
Route::post('/cart/decrease', [CartController::class, 'decrease'])->name('cart_decrease');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart_update_quantity');
