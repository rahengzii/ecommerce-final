<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// These routes are defined in front/home.php and should not conflict with web.php
// Home route (keeping this as primary)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Product detail route (using show method for product details)
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');

// Product quantity management routes
Route::post('/product/increase-quantity', [ProductController::class, 'increaseQuantity'])->name('product.increase_quantity');
Route::post('/product/decrease-quantity', [ProductController::class, 'decreaseQuantity'])->name('product.decrease_quantity');
Route::post('/product/update-quantity', [ProductController::class, 'updateQuantity'])->name('product.update_quantity');
