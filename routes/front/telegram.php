<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

Route::post('/telegram/send-message', [TelegramController::class, 'sendMessage'])
    ->name('telegram.send_message');

// Route for testing order notifications
Route::post('/telegram/send-order-notification/{orderId}', [TelegramController::class, 'sendOrderNotification'])
    ->name('telegram.send_order_notification');

// Add test route for HTTP-based notification
Route::post('/telegram/test-http-notification', [TelegramController::class, 'testHttpNotification'])
    ->name('telegram.test_http_notification');

