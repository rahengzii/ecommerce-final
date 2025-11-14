<?php

use App\Http\Controllers\KhqrController;
use Illuminate\Support\Facades\Route;

// KHQR Generation and Payment Routes
Route::get('/generate-khqr/{orderId}', [KhqrController::class, 'generateKHQR'])->name('khqr.generate');
// Route::post('/khqr/generate', [KhqrController::class, 'generateQrCode'])->name('khqr.generate.legacy');
Route::get('/khqr/generate', [KhqrController::class, 'generateKHQR'])->name('khqr.generate.legacy');

// Payment checking and approval routes
Route::post('/check-khqr-payment', [KhqrController::class, 'checkKHQRPayment'])->name('khqr.check_payment');
Route::post('/approve-test-payment', [KhqrController::class, 'approveTestPayment'])->name('khqr.approve_test_payment');

// Legacy API routes
Route::post('/api/khqr/check-payment', [KhqrController::class, 'checkKHQRPayment'])->name('khqr.check_payment_api');
Route::post('/api/khqr/approve-test-payment', [KhqrController::class, 'approveTestPayment'])->name('khqr.approve_test_payment_api');
