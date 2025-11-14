<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB as FacadeDB;
use App\Http\Controllers\TelegramController;

class KhqrController extends Controller
{
    public function generateQRCode(Request $request)
    {
        try {
            // Handle both route parameter and request input
            $orderId = $request->order_id ?? $request->route('orderId');
            $amount = $request->amount;

            // If called from route, get order details from database
            if (!$amount && $orderId) {
                $order = DB::table('order')->where('order_id', $orderId)->first();
                if ($order) {
                    $amount = $order->total;
                } else {
                    return response()->json([
                        'success' => false,
                        'error' => 'Order not found'
                    ], 404);
                }
            }

            if (!$amount || !$orderId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Amount and Order ID are required'
                ], 400);
            }

            // Convert USD to KHR if needed (1 USD = 4100 KHR approximately)
            $amountInKHR = $amount * 4000;

            // Try to use Bakong API with timeout handling
            try {
                $individualInfo = new IndividualInfo(
                    bakongAccountID: 'premprey_kim@aclb',
                    merchantName: 'Premprey Kim',
                    merchantCity: 'PHNOM PENH',
                    currency: KHQRData::CURRENCY_KHR,
                    amount: $amountInKHR
                );

                $qrResponse = BakongKHQR::generateIndividual($individualInfo);

                if ($qrResponse && isset($qrResponse->data)) {
                    $qrString = $qrResponse->data->qr; // The EMVCo string
                    $qrImage = $qrResponse->data->base64; // Base64 image
                    $md5Hash = $qrResponse->data->md5; // MD5 hash for tracking

                    // Store QR code data in database
                    DB::table('order')->where('order_id', $orderId)->update([
                        'qr_code_string' => $qrString,
                        'qr_code_md5' => $md5Hash,
                        'qr_code_image' => $qrImage,
                        'updated_at' => now()
                    ]);

                    Log::info('QR Code generated for order: ' . $orderId, [
                        'md5' => $md5Hash,
                        'qr_string' => $qrString
                    ]);

                    return response()->json([
                        'success' => true,
                        'qr_code' => [
                            'qr_code' => $qrImage, // Base64 image
                            'qr_string' => $qrString, // EMVCo string
                            'md5' => $md5Hash
                        ],
                        'order_id' => $orderId,
                        'amount' => $amount,
                        'amount_khr' => $amountInKHR
                    ]);
                } else {
                    throw new \Exception('Bakong API returned empty response');
                }
            } catch (\Exception $bakongError) {
                Log::warning('Bakong API Error (using fallback): ' . $bakongError->getMessage());
                // Continue to fallback
            }

            // Fallback: generate QR using Cambodia QR Government Service (qr.gov.kh)
            try {
                $defaultQRString = '00020101021229210017premprey_kim@aclb5204599953031165405120005802KH5912Kim Premprey6010PHNOM PENH9917001317631035624276304C460';
                
                // Use Cambodia government QR service: https://qr.gov.kh/en/
                $qrImageUrl = "https://qr.gov.kh/api/generate?text=" . urlencode($qrString) . "&size=300";
                
                $qrImageBase64 = null;
                
                // Try to fetch image from qr.gov.kh with 5-second timeout
                try {
                    $response = Http::timeout(5)->get($qrImageUrl);
                    if ($response->successful()) {
                        $imageData = $response->body();
                        if (!empty($imageData)) {
                            $qrImageBase64 = base64_encode($imageData);
                        }
                    }
                } catch (\Exception $httpEx) {
                    Log::warning('Failed to fetch QR image from qr.gov.kh: ' . $httpEx->getMessage());
                    // Fallback to alternative QR service
                    try {
                        $altQrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($defaultQRString) . "&format=png&margin=10&ecc=L";
                        $response = Http::timeout(5)->get($altQrImageUrl);
                        if ($response->successful()) {
                            $imageData = $response->body();
                            if (!empty($imageData)) {
                                $qrImageBase64 = base64_encode($imageData);
                                $qrImageUrl = $altQrImageUrl;
                            }
                        }
                    } catch (\Exception $altEx) {
                        Log::warning('Failed to fetch QR image from alternative service: ' . $altEx->getMessage());
                    }
                }

                // Generate MD5 for tracking
                $md5Hash = md5($defaultQRString . $orderId . microtime());

                // Store fallback QR in database
                if ($orderId) {
                    DB::table('order')->where('order_id', $orderId)->update([
                        'qr_code_string' => $defaultQRString,
                        'qr_code_md5' => $md5Hash,
                        'qr_code_image' => $qrImageBase64,
                        'updated_at' => now()
                    ]);
                }

                Log::info('QR code generated using qr.gov.kh for order: ' . $orderId);

                return response()->json([
                    'success' => true,
                    'qr_code' => [
                        'qr_code' => $qrImageBase64,
                        'qr_string' => $defaultQRString,
                        'qr_url' => $qrImageUrl,
                        'md5' => $md5Hash
                    ],
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'amount_khr' => $amountInKHR,
                    'note' => 'QR code generated from qr.gov.kh'
                ]);
            } catch (\Exception $fallbackError) {
                Log::error('QR Generation Error: ' . $fallbackError->getMessage());
                
                // Last resort: return minimal response with KHQR string
                $defaultQRString = '00020101021229210017premprey_kim@aclb5204599953031165405120005802KH5912Kim Premprey6010PHNOM PENH9917001317631035624276304C460';
                $md5Hash = md5($defaultQRString . $orderId . microtime());
                
                return response()->json([
                    'success' => true,
                    'qr_code' => [
                        'qr_code' => null,
                        'qr_string' => $defaultQRString,
                        'md5' => $md5Hash
                    ],
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'amount_khr' => $amountInKHR,
                    'note' => 'Using KHQR string (image generation failed)'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('QR Generation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // New simplified generateKHQR method (as requested)
    public function generateKHQR($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $total = floatval(str_replace(',', '', $order->total));

            $individualInfo = new IndividualInfo(
                bakongAccountID: 'premprey_kim@aclb',
                merchantName: 'Kim Premprey',
                merchantCity: 'PHNOM PENH',
                currency: KHQRData::CURRENCY_KHR,
                amount: $total
                // amount: 12000
            );

            $response = BakongKHQR::generateIndividual($individualInfo);
            // dd(BakongKHQR::generateIndividual($individualInfo));

            // Check if response has data with qr and md5
            if ($response && isset($response->data)) {
                $qrString = $response->data->qr;
                $md5Hash = $response->data->md5;
                
                // Store in database
                DB::table('order')->where('order_id', $orderId)->update([
                    'qr_code_string' => $qrString,
                    'qr_code_md5' => $md5Hash,
                    'updated_at' => now()
                ]);

                return response()->json([
                    'status' => (object)['code' => 0],
                    'data' => [
                        'qr' => $qrString,
                        'md5' => $md5Hash
                    ]
                ]);
            } else {
                // Fallback: Return static QR code
                $defaultQRString = '00020101021229210017premprey_kim@aclb520459995303116540450005802KH5912Premprey Kim6010PHNOM PENH99170013176302586965063041CEB';
                
                // Generate MD5 for tracking
                $md5Hash = md5($defaultQRString . $orderId . microtime());
                
                // Store fallback QR in database
                DB::table('order')->where('order_id', $orderId)->update([
                    'qr_code_string' => $defaultQRString,
                    'qr_code_md5' => $md5Hash,
                    'updated_at' => now()
                ]);

                return response()->json([
                    'status' => (object)['code' => 0],
                    'data' => [
                        'qr' => $defaultQRString,
                        'md5' => $md5Hash
                    ]
                ]);
            }
        } catch (\Exception $e) {
            Log::error('KHQR Generation Error: ' . $e->getMessage());
            
            // Return fallback QR code on error
            $defaultQRString = '00020101021229210017premprey_kim@aclb520459995303116540450005802KH5912Premprey Kim6010PHNOM PENH99170013176302586965063041CEB';
            $md5Hash = md5($defaultQRString . $orderId . microtime());
            
            try {
                DB::table('order')->where('order_id', $orderId)->update([
                    'qr_code_string' => $defaultQRString,
                    'qr_code_md5' => $md5Hash,
                    'updated_at' => now()
                ]);
            } catch (\Exception $dbError) {
                Log::warning('Failed to update order: ' . $dbError->getMessage());
            }

            return response()->json([
                'status' => (object)['code' => 0],
                'data' => [
                    'qr' => $defaultQRString,
                    'md5' => $md5Hash
                ]
            ]);
        }
    }

    // New payment check endpoint (as requested)
    public function checkKHQRPayment(Request $request)
    {
        $md5 = $request->md5;
        $orderId = $request->order_id;

        Log::info('KHQR Payment Check Started', ['md5' => $md5, 'order_id' => $orderId]);

        try {
            $apiToken = env('BAKONG_API_TOKEN');
            $bakongKhqr = new BakongKHQR($apiToken);
            $response = $bakongKhqr->checkTransactionByMD5($md5);

            // Log the ENTIRE response to see what Bakong is returning
            Log::info('FULL BAKONG API RESPONSE:', [
                'response' => is_scalar($response) ? $response : json_encode($response, JSON_PRETTY_PRINT),
                'md5' => $md5
            ]);

            $isPaid = false;

            // Normalize response if object
            $respArr = is_array($response) ? $response : (is_object($response) ? (array) $response : []);

            if (isset($respArr['data']['status'])) {
                Log::info('Found status in data:', ['status' => $respArr['data']['status']]);
                if (strtoupper($respArr['data']['status']) === 'PAID') {
                    $isPaid = true;
                }
            }

            if (isset($respArr['status'])) {
                Log::info('Found status at root:', ['status' => $respArr['status']]);
                if (strtoupper($respArr['status']) === 'PAID') {
                    $isPaid = true;
                }
            }

            if (isset($respArr['data']['responseCode'])) {
                Log::info('Found responseCode:', ['code' => $respArr['data']['responseCode']]);
                if ($respArr['data']['responseCode'] === '00') {
                    $isPaid = true;
                }
            }

            Log::info('Payment status determined:', ['isPaid' => $isPaid]);

            if ($isPaid) {
                $order = Order::findOrFail($orderId);

                // Try to update a transaction record if exists, otherwise update order only
                try {
                    $transaction = FacadeDB::table('transaction')->where('order_id', $orderId)->first();
                } catch (\Exception $ex) {
                    $transaction = null;
                }

                if ($transaction && ($transaction->status ?? null) !== 'approved') {
                    try {
                        FacadeDB::table('transaction')->where('order_id', $orderId)->update(['status' => 'approved']);
                    } catch (\Exception $e) {
                        Log::warning('Failed to update transaction table: ' . $e->getMessage());
                    }

                    // get default address
                    $address = Address::where('user_id', $order->user_id)->where('isdefault', 1)->first();

                    // Send Telegram using existing TelegramController (keeps behavior consistent)
                    try {
                        $telegramController = new TelegramController();
                        $telegramController->sendOrderNotification($orderId);
                        Log::info('Telegram notification sent for paid order: ' . $orderId);
                    } catch (\Exception $e) {
                        Log::error('Telegram notification failed for order ' . $orderId . ': ' . $e->getMessage());
                    }

                    // Try to clear cart/session, wrapped in try/catch to avoid hard dependency
                    try {
                        if (class_exists('\\Cart')) {
                            $cartClass = '\\Cart';
                            $cartClass::instance('cart')->destroy();
                        }
                        Session::forget('checkout');
                        Session::forget('coupon');
                        Session::forget('discount');
                        Session::forget('pending_order_id');
                        Session::put('order_id', $order->id);
                    } catch (\Exception $e) {
                        Log::warning('Failed to clear cart/session: ' . $e->getMessage());
                    }

                    return response()->json([
                        'success' => true,
                        'paid' => true,
                        'message' => 'Payment confirmed successfully'
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'paid' => true,
                    'message' => 'Payment already processed'
                ]);
            }

            return response()->json([
                'success' => true,
                'paid' => false,
                'message' => 'Payment pending'
            ]);
        } catch (\Exception $e) {
            Log::error('KHQR payment check failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'paid' => false,
                'message' => 'Failed to check payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkOrderStatus($orderId)
    {
        try {
            $order = DB::table('order')->where('order_id', $orderId)->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'error' => 'Order not found'
                ], 404);
            }

            // If order has MD5 hash, check payment status with Bakong
            if (isset($order->qr_code_md5) && $order->qr_code_md5 && $order->status === 'pending') {
                try {
                    $transactionStatus = $this->checkTransactionByMD5($order->qr_code_md5);

                    if ($transactionStatus && isset($transactionStatus['status'])) {
                        // Update order status if payment is completed
                        if ($transactionStatus['status'] === 'PAID' || $transactionStatus['status'] === 'SUCCESS') {
                            DB::table('order')->where('order_id', $orderId)->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                                'updated_at' => now()
                            ]);

                            // Send Telegram notification for successful payment
                            try {
                                $telegramController = new TelegramController();
                                $telegramController->sendOrderNotification($orderId);
                                Log::info('Telegram notification sent for paid order: ' . $orderId);
                            } catch (\Exception $e) {
                                Log::error('Telegram notification failed for order ' . $orderId . ': ' . $e->getMessage());
                            }

                            return response()->json([
                                'success' => true,
                                'status' => 'paid',
                                'order_id' => $orderId
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to check transaction status: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'status' => $order->status,
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            Log::error('Order Status Check Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function checkTransactionByMD5($md5)
    {
        try {
            // Initialize BakongKHQR with your API token
            $apiToken = env('BAKONG_API_TOKEN', '');

            if (empty($apiToken)) {
                Log::warning('Bakong API token not configured');
                return null;
            }

            $bakongKhqr = new BakongKHQR($apiToken);
            $response = $bakongKhqr->checkTransactionByMD5($md5);

            Log::info('Transaction check result for MD5 ' . $md5, ['response' => $response]);

            return $response;
        } catch (\Exception $e) {
            Log::error('Transaction check failed for MD5 ' . $md5 . ': ' . $e->getMessage());
            return null;
        }
    }

    public function paymentCallback(Request $request)
    {
        // Handle payment callback/webhook from Bakong KHQR
        Log::info('KHQR Payment Callback received:', $request->all());

        try {
            $orderId = $request->input('order_id');
            $status = $request->input('status');
            $transactionId = $request->input('transaction_id');
            $md5 = $request->input('md5');

            if (!$orderId && !$md5) {
                return response()->json([
                    'success' => false,
                    'error' => 'Order ID or MD5 is required'
                ], 400);
            }

            // Find order by order_id or md5
            $query = DB::table('order');
            if ($orderId) {
                $query->where('order_id', $orderId);
            } elseif ($md5) {
                $query->where('qr_code_md5', $md5);
            }

            $order = $query->first();

            if (!$order) {
                Log::warning('Order not found for callback', [
                    'order_id' => $orderId,
                    'md5' => $md5
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Order not found'
                ], 404);
            }

            // Update order status if payment is successful
            if (strtolower($status) === 'completed' || strtolower($status) === 'paid' || strtolower($status) === 'success') {
                DB::table('order')
                    ->where('order_id', $order->order_id)
                    ->update([
                        'status' => 'paid',
                        'transaction_id' => $transactionId,
                        'paid_at' => now(),
                        'updated_at' => now()
                    ]);

                Log::info('Order marked as paid: ' . $order->order_id);

                // Send Telegram notification
                try {
                    $telegramController = new TelegramController();
                    $telegramController->sendOrderNotification($order->order_id);
                    Log::info('Telegram notification sent for paid order: ' . $order->order_id);
                } catch (\Exception $e) {
                    Log::error('Telegram notification failed for order ' . $order->order_id . ': ' . $e->getMessage());
                    // Don't fail the callback if Telegram fails
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Payment confirmed',
                    'order_id' => $order->order_id
                ]);
            }

            // Handle failed payments
            if (strtolower($status) === 'failed' || strtolower($status) === 'cancelled') {
                DB::table('order')
                    ->where('order_id', $order->order_id)
                    ->update([
                        'status' => strtolower($status),
                        'updated_at' => now()
                    ]);

                Log::info('Order marked as ' . strtolower($status) . ': ' . $order->order_id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Callback processed'
            ]);
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Manual payment verification endpoint (for testing)
    public function verifyPayment($orderId)
    {
        try {
            $order = DB::table('order')->where('order_id', $orderId)->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'error' => 'Order not found'
                ], 404);
            }

            if (!isset($order->qr_code_md5) || !$order->qr_code_md5) {
                return response()->json([
                    'success' => false,
                    'error' => 'No QR code associated with this order'
                ], 400);
            }

            $transactionStatus = $this->checkTransactionByMD5($order->qr_code_md5);

            return response()->json([
                'success' => true,
                'order_id' => $orderId,
                'order_status' => $order->status,
                'transaction_status' => $transactionStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Payment verification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate QR code from a specific KHQR string
     */
    public function generateFromString(Request $request)
    {
        try {
            // Default KHQR string - your provided string
            $qrString = $request->input('qr_string', '00020101021229210017premprey_kim@aclb520459995303116540450005802KH5912Premprey Kim6010PHNOM PENH99170013176302586965063041CEB');

            // Use external QR code service as a simple alternative
            $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrString);

            // Get the image and convert to base64
            try {
                $response = Http::timeout(10)->get($qrImageUrl);
                if ($response->successful()) {
                    $imageData = $response->body();
                    $qrImageBase64 = base64_encode($imageData);
                } else {
                    $qrImageBase64 = null;
                }
            } catch (\Exception $e) {
                // Fallback: return the URL
                $qrImageBase64 = null;
            }

            return response()->json([
                'success' => true,
                'qr_code' => [
                    'qr_code' => $qrImageBase64, // Base64 image
                    'qr_string' => $qrString, // The KHQR string
                    'qr_url' => $qrImageUrl // Fallback URL
                ],
                'message' => 'QR code generated from KHQR string'
            ]);
        } catch (\Exception $e) {
            Log::error('QR Generation from String Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show QR payment page with custom KHQR string
     */
    public function showCustomQR(Request $request)
    {
        try {
            $qrString = $request->input('qr_string', '00020101021229210017premprey_kim@aclb520459995303116540450005802KH5912Premprey Kim6010PHNOM PENH99170013176302586965063041CEB');

            // Extract amount from QR string (look for "5404" followed by amount)
            $amount = 50.00; // Default amount
            if (preg_match('/5404(\d{4})/', $qrString, $matches)) {
                $amount = (float)$matches[1] / 100; // Convert from cents
            }

            // Use external QR service for image generation
            $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrString);

            // Get the image and convert to base64
            try {
                $response = Http::timeout(10)->get($qrImageUrl);
                if ($response->successful()) {
                    $imageData = $response->body();
                    $qrImageBase64 = base64_encode($imageData);
                } else {
                    $qrImageBase64 = null;
                }
            } catch (\Exception $e) {
                $qrImageBase64 = null;
            }

            // Create a mock order object for the view
            $order = (object) [
                'order_id' => 'DEMO-' . date('Ymd') . '-' . rand(1000, 9999),
                'total' => $amount,
                'created_at' => now(),
                'status' => 'pending'
            ];

            $qrCode = [
                'qr_code' => $qrImageBase64,
                'qr_string' => $qrString
            ];

            return view('qr', compact('order', 'qrCode'));
        } catch (\Exception $e) {
            Log::error('Custom QR Display Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate QR code: ' . $e->getMessage());
        }
    }



    /**
     * Generate static QR code for orders
     */
    public function generateStaticQR($orderId)
    {
        try {
            $order = DB::table('order')->where('order_id', $orderId)->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'error' => 'Order not found'
                ], 404);
            }

            // Use the default KHQR string or generate one
            $defaultQRString = '00020101021229210017premprey_kim@aclb520459995303116540450005802KH5912Premprey Kim6010PHNOM PENH99170013176302586965063041CEB';

            // Generate static QR image URL
            $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($defaultQRString) . "&format=png&margin=10&ecc=L";

            // Convert to base64 for consistency
            try {
                $response = Http::timeout(10)->get($qrImageUrl);
                if ($response->successful()) {
                    $imageData = $response->body();
                    $qrImageBase64 = base64_encode($imageData);
                } else {
                    $qrImageBase64 = null;
                }
            } catch (\Exception $e) {
                $qrImageBase64 = null;
            }

            // Update order with static QR data
            DB::table('order')->where('order_id', $orderId)->update([
                'qr_code_string' => $defaultQRString,
                'qr_code_image' => $qrImageBase64,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'qr_code' => [
                    'qr_code' => $qrImageBase64,
                    'qr_string' => $defaultQRString,
                    'qr_url' => $qrImageUrl
                ],
                'order_id' => $orderId,
                'message' => 'Static QR code generated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Static QR Generation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
