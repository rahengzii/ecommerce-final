<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Process login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        try {
            // Find customer by email
            $customer = DB::table('customer')
                        ->where('email', $request->email)
                        ->first();

            if (!$customer) {
                return back()->with('error', 'Invalid credentials.')->withInput();
            }

            // Check password (plain text for now - you should hash passwords in real applications)
            if ($customer->password !== $request->password) {
                return back()->with('error', 'Invalid credentials.')->withInput();
            }

            // Store customer in session
            session([
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'logged_in' => true
            ]);

            return redirect('/')->with('success', 'Welcome back, ' . $customer->name . '!');

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Process registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customer,email',
            'password' => 'required|min:6|confirmed',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20'
        ]);

        try {
            // Create new customer
            $customer_id = DB::table('customer')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password, // In real app, use: Hash::make($request->password)
                'address' => $request->address,
                'phone' => $request->phone,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Auto-login after registration
            session([
                'customer_id' => $customer_id,
                'customer_name' => $request->name,
                'customer_email' => $request->email,
                'logged_in' => true
            ]);

            return redirect('/')->with('success', 'Account created successfully! Welcome to Sample Store.');

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return back()->with('error', 'Registration failed. Please try again.')->withInput();
        }
    }

    // Logout
    public function logout()
    {
        session()->flush();
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }

    public function profile()
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login')->with('error', 'Please login to view your profile.');
        }

        $customer = DB::table('customer')
            ->where('id', Session::get('customer_id'))
            ->first();

        // Get customer orders
        $orders = DB::table('order')
            ->where('customer_id', Session::get('customer_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('auth.profile', compact('customer', 'orders'));
    }

    public function updateProfile(Request $request)
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customer,email,' . Session::get('customer_id'),
            'phone' => 'nullable|string',
            'address' => 'nullable|string'
        ]);

        DB::table('customer')
            ->where('id', Session::get('customer_id'))
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'updated_at' => now()
            ]);

        // Update session
        Session::put('customer_name', $request->name);
        Session::put('customer_email', $request->email);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Helper method to validate order ownership
     */
    private function validateOrderOwnership($orderId, $customerId)
    {
        return DB::table('order')
            ->where('order_id', $orderId)
            ->where('customer_id', $customerId)
            ->exists();
    }

    public function invoice($orderId)
    {
        // Check authentication
        if (!Session::get('logged_in')) {
            return redirect()->route('login')->with('error', 'Please login to view invoice.');
        }

        // Validate order ID format
        if (empty($orderId)) {
            return redirect()->route('profile')->with('error', 'Invalid order ID.');
        }

        try {
            $customerId = Session::get('customer_id');
            
            // Validate order ownership first
            if (!$this->validateOrderOwnership($orderId, $customerId)) {
                Log::warning("Unauthorized invoice access attempt", [
                    'order_id' => $orderId,
                    'customer_id' => $customerId
                ]);
                return redirect()->route('profile')->with('error', 'Order not found or you do not have permission to view this invoice.');
            }

            // Get order details
            $order = DB::table('order')
                ->where('order_id', $orderId)
                ->where('customer_id', $customerId)
                ->first();

            if (!$order) {
                return redirect()->route('profile')->with('error', 'Order not found.');
            }

            // Get order items with product information
            $orderItems = DB::table('order_detail')
                ->leftJoin('product', 'order_detail.product_id', '=', 'product.id')
                ->where('order_detail.order_id', $order->id)
                ->select(
                    'order_detail.*',
                    'product.name as product_name_from_product',
                    'product.image as product_image'
                )
                ->get();

            // Ensure we have product names (use from order_detail if product no longer exists)
            $orderItems = $orderItems->map(function ($item) {
                if (empty($item->product_name_from_product)) {
                    // Use the stored product name if the product was deleted
                    $item->display_product_name = $item->product_name;
                } else {
                    // Use current product name
                    $item->display_product_name = $item->product_name_from_product;
                }
                return $item;
            });

            // Validate that order has items
            if ($orderItems->isEmpty()) {
                Log::warning("Order {$orderId} has no items", ['order_id' => $order->id]);
                return redirect()->route('profile')->with('error', 'This order has no items to display.');
            }

            // Log successful invoice access
            Log::info("Invoice generated successfully", [
                'order_id' => $orderId,
                'customer_id' => $customerId,
                'item_count' => $orderItems->count()
            ]);

            return view('auth.invoice', compact('order', 'orderItems'));

        } catch (\Exception $e) {
            Log::error('Invoice generation error: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'customer_id' => Session::get('customer_id'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('profile')->with('error', 'Unable to generate invoice. Please try again later.');
        }
    }
}