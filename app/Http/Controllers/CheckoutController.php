<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\TelegramController;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $customer_id = 1; // Or get from session

        // Get cart items for the customer
        $cart_items = DB::table('cart')
            ->join('product', 'cart.product_id', '=', 'product.id')
            ->select('cart.*', 'product.name', 'product.price', 'product.image')
            ->where('cart.customer_id', $customer_id)
            ->get();

        if ($cart_items->isEmpty()) {
            return redirect()->route('cart_index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $cart_items->sum(function ($item) {
            return $item->price * $item->qty;
        });
        $shipping = 0.00;
        $tax = $subtotal * 0.0;
        $total = $subtotal + $shipping + $tax;

        return view('checkout', [
            'cart_items' => $cart_items,
            'customer_id' => $customer_id,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
            'prefill' => [] // Empty prefill array for form
        ]);
    }

    public function process(Request $request)
    {
        // Basic validation
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string',
            'city' => 'required|string',
            'payment_method' => 'required|string',
            'customer_id' => 'required'
        ]);

        $customer_id = $request->customer_id;
        $payment_method = $request->payment_method;

        try {
            // Get cart items
            $cart_items = DB::table('cart')
                ->join('product', 'cart.product_id', '=', 'product.id')
                ->select('cart.*', 'product.name', 'product.price', 'product.image')
                ->where('cart.customer_id', $customer_id)
                ->get();

            if ($cart_items->isEmpty()) {
                return redirect()->route('cart_index')->with('error', 'Your cart is empty.');
            }

            // Calculate totals based on products
            $subtotal = $cart_items->sum(function ($item) {
                return $item->price * $item->qty;
            });
            $shipping = 0.00;
            $tax = $subtotal * 0.0;
            $total = $subtotal + $shipping + $tax;

            // Generate order ID
            $order_id = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);

            // Set initial status based on payment method
            $status = $payment_method === 'cod' ? 'confirmed' : 'pending';

            // Save order to database
            $order_data = [
                'order_id' => $order_id,
                'customer_id' => $customer_id,
                'fullname' => $request->fullname,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'phone' => $request->phone ?? 'Not provided',
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $payment_method,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Insert order and get the database ID
            $order_db_id = DB::table('order')->insertGetId($order_data);

            // Save order details with actual product prices
            foreach ($cart_items as $item) {
                DB::table('order_detail')->insert([
                    'order_id' => $order_db_id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->name,
                    'quantity' => $item->qty,
                    'price' => $item->price,
                    'total' => $item->price * $item->qty,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Clear cart after successful order
            DB::table('cart')->where('customer_id', $customer_id)->delete();

            // Send Telegram notification
            try {
                $telegramController = new TelegramController();
                $telegramResponse = $telegramController->sendOrderNotification($order_id);

                // Check if the response is successful
                if ($telegramResponse && $telegramResponse->getData() && $telegramResponse->getData()->success) {
                    Log::info('Telegram notification sent successfully for order: ' . $order_id);
                } else {
                    $errorMsg = $telegramResponse && $telegramResponse->getData() ? $telegramResponse->getData()->error : 'Unknown error';
                    Log::warning('Telegram notification failed for order ' . $order_id . ': ' . $errorMsg);
                }
            } catch (\Exception $telegramError) {
                Log::error('Failed to send Telegram notification for order ' . $order_id . ': ' . $telegramError->getMessage());
                // Don't stop the checkout process if Telegram fails
            }

            // Redirect based on payment method
            if ($payment_method === 'khqr') {
                // Redirect to QR payment page for KHQR
                return redirect()->route('qr_payment', ['orderId' => $order_id]);
            } else {
                // For COD, show order confirmation directly
                return view('order-confirmation', [
                    'order' => (object)['id' => $order_id],
                    'order_items' => $cart_items,
                    'subtotal' => $subtotal,
                    'shipping' => $shipping,
                    'tax' => $tax,
                    'total' => $total,
                    'payment_method' => $payment_method,
                    'shipping_info' => [
                        'fullname' => $request->fullname,
                        'email' => $request->email,
                        'address' => $request->address,
                        'city' => $request->city,
                        'phone' => $request->phone ?? 'Not provided'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'There was an error processing your order. Please try again.')
                ->withInput();
        }
    }

    public function showQRPayment($orderId = null)
    {
        try {
            // Ensure $orderId is available (fallback to route or request if not passed)
            if (empty($orderId)) {
                $orderId = request()->route('orderId') ?? request('orderId') ?? null;
            }

            // Get order details
            $order = DB::table('order')->where('order_id', $orderId)->first();

            if (!$order) {
                return redirect()->route('checkout_index')->with('error', 'Order not found.');
            }

            // Get order items to display product breakdown
            $orderItems = DB::table('order_detail')
                ->join('product', 'order_detail.product_id', '=', 'product.id')
                ->select('order_detail.*', 'product.name', 'product.image')
                ->where('order_detail.order_id', $order->id)
                ->get();

            // Calculate the actual total from order (includes products + shipping + tax)
            $totalAmount = $order->total;

            Log::info('Generating QR for order: ' . $orderId, [
                'subtotal' => $order->subtotal,
                'shipping' => $order->shipping,
                'tax' => $order->tax,
                'total' => $totalAmount,
                'products_count' => $orderItems->count()
            ]);

            // Check if QR code already exists in database
            if (!empty($order->qr_code_image) && !empty($order->qr_code_string)) {
                // Use existing QR code from database
                $qrCode = [
                    'qr_code' => $order->qr_code_image,
                    'qr_string' => $order->qr_code_string
                ];
            } else {
                // Generate new QR code with the actual total amount
                $khqrController = new KhqrController();
                $qrResponse = $khqrController->generateQRCode(new Request([
                    'amount' => $totalAmount, // Use total from order (products + shipping + tax)
                    'order_id' => $orderId
                ]));

                // Convert JSON response to array
                $responseData = $qrResponse->getData();

                if ($responseData && isset($responseData->success) && $responseData->success) {
                    // Extract QR code data from response
                    $qrCode = [
                        'qr_code' => $responseData->qr_code->qr_code ?? null,
                        'qr_string' => $responseData->qr_code->qr_string ?? null
                    ];

                    Log::info('QR code generated successfully for order: ' . $orderId, [
                        'amount_usd' => $totalAmount,
                        'amount_khr' => $responseData->amount_khr ?? null
                    ]);
                } else {
                    // Fallback: use a default or show error
                    $qrCode = [
                        'qr_code' => null,
                        'qr_string' => null
                    ];
                    Log::error('Failed to generate QR code for order: ' . $orderId);
                }
            }

            return view('qr', [
                'order' => $order,
                'qrCode' => $qrCode,
                'orderItems' => $orderItems
            ]);
        } catch (\Exception $e) {
            Log::error('QR Payment Error: ' . $e->getMessage());
            return redirect()->route('checkout_index')->with('error', 'Error loading QR payment page: ' . $e->getMessage());
        }
    }

    public function orderConfirmation($orderId = null)
    {
        try {
            // Ensure $orderId is available (fallback to route or request if not passed)
            if (empty($orderId)) {
                $orderId = request()->route('orderId') ?? request('orderId') ?? null;
            }

            // Get order details
            $order = DB::table('order')->where('order_id', $orderId)->first();

            if (!$order) {
                return redirect()->route('home')->with('error', 'Order not found.');
            }

            // Get order items
            $order_items = DB::table('order_detail')
                ->join('product', 'order_detail.product_id', '=', 'product.id')
                ->select('order_detail.*', 'product.name', 'product.image')
                ->where('order_detail.order_id', $order->id)
                ->get();

            return view('order-confirmation', [
                'order' => $order,
                'order_items' => $order_items,
                'subtotal' => $order->subtotal,
                'shipping' => $order->shipping,
                'tax' => $order->tax,
                'total' => $order->total,
                'payment_method' => $order->payment_method,
                'shipping_info' => [
                    'fullname' => $order->fullname,
                    'email' => $order->email,
                    'address' => $order->address,
                    'city' => $order->city,
                    'phone' => $order->phone
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Order Confirmation Error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Error loading order confirmation.');
        }
    }
}
