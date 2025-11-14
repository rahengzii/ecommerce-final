<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Telegram\Bot\Api;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $botToken = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');

            $telegram = new Api($botToken);
            $response = $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $request->input('message', 'Hello World')
            ]);

            $messageId = $response->getMessageId();

            return response()->json([
                'success' => true,
                'message_id' => $messageId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sendOrderNotification($orderId)
    {
        try {
            // Get order details
            $order = DB::table('order')->where('order_id', $orderId)->first();
            
            if (!$order) {
                Log::error('Order not found: ' . $orderId);
                throw new \Exception('Order not found');
            }
            
            // Get order details with product information
            $order_details = DB::table('order_detail')
                ->join('product', 'order_detail.product_id', '=', 'product.id')
                ->where('order_detail.order_id', $order->id)
                ->select('order_detail.*', 'product.name as product_name')
                ->get();

            // Build customer info message
            $customer_info = "<b>📋 Order ID:</b> " . $order->order_id . "\n";
            $customer_info .= "<b>👤 Customer:</b> " . $order->fullname . "\n";
            $customer_info .= "<b>📧 Email:</b> " . $order->email . "\n";
            $customer_info .= "<b>📞 Phone:</b> " . ($order->phone ?: 'Not provided') . "\n";
            $customer_info .= "<b>📍 Address:</b> " . $order->address . ", " . $order->city . "\n";
            $customer_info .= "<b>💳 Payment Method:</b> " . strtoupper($order->payment_method) . "\n";
            $customer_info .= "<b>📊 Status:</b> " . ucfirst($order->status) . "\n\n";
            
            if ($order_details->count() > 0) {
                $customer_info .= "<b>📦 Order Items:</b>\n";
                foreach ($order_details as $item) {
                    $customer_info .= "• " . $item->product_name . " (Qty: " . $item->quantity . ") - $" . number_format($item->total, 2) . "\n";
                }
            }
            
            $customer_info .= "\n<b>💰 Order Summary:</b>\n";
            $customer_info .= "Subtotal: $" . number_format($order->subtotal, 2) . "\n";
            $customer_info .= "Shipping: $" . number_format($order->shipping, 2) . "\n";
            $customer_info .= "Tax: $" . number_format($order->tax, 2) . "\n";
            $customer_info .= "<b>💵 Total: $" . number_format($order->total, 2) . "</b>\n\n";
            $customer_info .= "🕒 <b>Order Time:</b> " . $order->created_at;

            // Get credentials from environment variables
            $token = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');

            if (!$token || !$chatId) {
                throw new \Exception('Telegram credentials not configured in .env file');
            }

            // Send notification using HTTP client
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'Sample App Agent',
                'Content-Type' => 'application/json',
            ])->post("https://api.telegram.org/bot{$token}/sendMessage", [
                "text" => "🔔 <b>New Order Received!</b>\n\n" . $customer_info,
                "parse_mode" => "HTML",
                "disable_web_page_preview" => false,
                "disable_notification" => false,
                "reply_to_message_id" => null,
                "chat_id" => $chatId
            ]);
            
            $data = $response->json();
            
            if ($response->successful()) {
                Log::info('Telegram notification sent successfully for order: ' . $orderId);
                return response()->json([
                    'success' => true,
                    'message' => 'Order notification sent successfully!',
                    'data' => $data
                ]);
            } else {
                Log::error('Telegram API request failed: ' . $response->body());
                throw new \Exception('Telegram API request failed: ' . ($data['description'] ?? 'Unknown error'));
            }

        } catch (\Exception $e) {
            Log::error('Telegram Order Notification Error for order ' . $orderId . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
    /**
     * Test method to verify HTTP-based Telegram notification
     */
    public function testHttpNotification(Request $request)
    {
        try {
            $token = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');

            if (!$token || !$chatId) {
                throw new \Exception('Telegram credentials not configured in .env file');
            }
            
            $testMessage = "🧪 <b>TEST NOTIFICATION</b> 🧪\n\n";
            $testMessage .= "✅ Your HTTP-based Telegram bot is working correctly!\n";
            $testMessage .= "🤖 <b>Bot:</b> Sample Store Bot\n";
            $testMessage .= "🕒 <b>Time:</b> " . now()->format('Y-m-d H:i:s') . "\n";
            $testMessage .= "🌐 <b>Environment:</b> " . env('APP_ENV');

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'Sample App Agent',
                'Content-Type' => 'application/json',
            ])->post("https://api.telegram.org/bot{$token}/sendMessage", [
                "text" => $testMessage,
                "parse_mode" => "HTML",
                "disable_web_page_preview" => false,
                "disable_notification" => false,
                "chat_id" => $chatId
            ]);

            $data = $response->json();

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test notification sent successfully!',
                    'data' => $data
                ]);
            } else {
                throw new \Exception('Telegram API request failed: ' . ($data['description'] ?? 'Unknown error'));
            }

        } catch (\Exception $e) {
            Log::error('Telegram test failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'troubleshooting' => [
                    'check_bot_token' => 'Make sure TELEGRAM_BOT_TOKEN is set in .env file',
                    'check_chat_id' => 'Make sure TELEGRAM_CHAT_ID is set in .env file',
                    'bot_permissions' => 'Make sure the bot has permission to send messages to this chat'
                ]
            ], 500);
        }
    }
}