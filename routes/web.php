<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\KhqrController;

include 'front/home.php';
include 'front/cart.php';
include 'front/auth.php';
include 'front/khqr.php';
include 'front/telegram.php';

// Main pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Product Routes
Route::get('/products', [HomeController::class, 'allProducts'])->name('all_products');

// Food and Drink Routes
Route::get('/food', [ProductController::class, 'food'])->name('food');
Route::get('/drink', [ProductController::class, 'drink'])->name('drink');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart_index');
Route::post('/cart/add-to-cart', [CartController::class, 'addToCart']);
Route::post('/cart/remove', [CartController::class, 'removeCart'])->name('cart_remove');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart_update');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout_index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout_process');
Route::get('/checkout/qr-payment/{orderId}', [CheckoutController::class, 'showQRPayment'])->name('qr_payment');
Route::get('/order-confirmation/{orderId}', [CheckoutController::class, 'orderConfirmation'])->name('order_confirmation');

// KHQR Routes
Route::post('/khqr/generate', [KhqrController::class, 'generateQRCode'])->name('khqr.generate');
Route::get('/khqr/check-transaction/{md5}', [KhqrController::class, 'checkTransactionByMD5'])->name('khqr.check_transaction');
Route::post('/khqr/payment-callback', [KhqrController::class, 'paymentCallback'])->name('khqr.payment_callback');
Route::get('/khqr/verify-payment/{orderId}', [KhqrController::class, 'verifyPayment'])->name('khqr.verify_payment');

// New KHQR API Routes
Route::get('/generate-khqr/{orderId}', [KhqrController::class, 'generateKHQR'])->name('generate.khqr');
Route::post('/check-khqr-payment', [KhqrController::class, 'checkKHQRPayment'])->name('check.khqr.payment');
Route::post('/approve-test-payment', [KhqrController::class, 'approveTestPayment'])->name('approve.test.payment');

// API Routes for AJAX calls
Route::get('/api/order-status/{orderId}', [KhqrController::class, 'checkOrderStatus'])->name('api.order_status');
Route::get('/checkout/generate-qr/{orderId}', [KhqrController::class, 'generateQRCode'])->name('checkout.generate_qr');

// Profile Routes
Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
Route::get('/invoice/{orderId}', [AuthController::class, 'invoice'])->name('invoice');

// Test route to verify database relationships
Route::get('/test-db', function () {
    try {
        $tables = DB::select('SHOW TABLES');
        $tableNames = array_map(function($table) {
            return array_values((array)$table)[0];
        }, $tables);
        
        return response()->json([
            'success' => true,
            'tables' => $tableNames,
            'order_table_exists' => in_array('order', $tableNames),
            'order_detail_table_exists' => in_array('order_detail', $tableNames)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Test route to test Telegram notification with a real order
Route::get('/test-telegram/{orderId}', function ($orderId) {
    try {
        $telegramController = new \App\Http\Controllers\TelegramController();
        $result = $telegramController->sendOrderNotification($orderId);
        return $result;
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});