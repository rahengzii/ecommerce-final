<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $customer_id = 1;
        $user_cart = DB::table('cart')
            ->join('product', 'cart.product_id', '=', 'product.id')
            ->select('cart.*', 'product.name', 'product.price', 'product.image')
            ->where('cart.customer_id', $customer_id)
            ->get();

        // Calculate totals
        $subtotal = $user_cart->sum(function ($item) {
            return $item->price * $item->qty;
        });
        $shipping = 0.00; // Example shipping
        $tax = $subtotal * 0.0; // Example tax 0%
        $total = $subtotal + $shipping + $tax;

        return view('cart', [
            'user_cart' => $user_cart,
            'customer_id' => $customer_id,
            'cartCount' => $user_cart->count(),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total
        ]);
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1); // Default to 1 if not provided
        $customer_id = 1;

        $cart = DB::table('cart')
            ->where('customer_id', $customer_id)
            ->where('product_id', $productId)
            ->first();

        if ($cart) {
            DB::table('cart')
                ->where('customer_id', $customer_id)
                ->where('product_id', $productId)
                ->increment('qty', $quantity);
        } else {
            DB::table('cart')->insert([
                'customer_id' => $customer_id,
                'product_id' => $productId,
                'qty' => $quantity,
            ]);
        }

        $last_cart = DB::table('cart')
            ->where('customer_id', $customer_id)
            ->get();

        return response()->json([
            'message' => 'Product added to cart',
            'last_cart' => $last_cart
        ]);
    }

    public function removeCart(Request $request)
    {
        $cart_id = $request->input('cart_id');
        DB::table('cart')
            ->where('id', $cart_id)
            ->delete();
        return response()->json([
            'message' => 'Product removed from cart'
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'qty' => 'required|integer|min:1|max:100'
        ]);

        $cart_id = $request->input('cart_id');
        DB::table('cart')
            ->where('id', $cart_id)
            ->update([
                'qty' => $request->input('qty')
            ]);
        return redirect('/cart')->with('success', 'Cart updated successfully.');;
    }

    /**
     * Increase quantity of a product in cart
     */
    public function increase(Request $request)
    {
        $cart_id = $request->input('cart_id');
        $customer_id = 1; // In real app, get from auth

        $cart = DB::table('cart')
            ->where('id', $cart_id)
            ->where('customer_id', $customer_id)
            ->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        // Get product stock to validate
        $product = DB::table('product')
            ->where('id', $cart->product_id)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Check if we can increase quantity
        if ($cart->qty >= $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum stock reached',
                'max_stock' => $product->stock
            ], 400);
        }

        // Increase quantity
        DB::table('cart')
            ->where('id', $cart_id)
            ->where('customer_id', $customer_id)
            ->increment('qty');

        // Get updated cart item
        $updated_cart = DB::table('cart')
            ->join('product', 'cart.product_id', '=', 'product.id')
            ->select('cart.*', 'product.name', 'product.price', 'product.image', 'product.stock')
            ->where('cart.id', $cart_id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Quantity increased',
            'cart_item' => $updated_cart,
            'new_quantity' => $updated_cart->qty
        ]);
    }

    /**
     * Decrease quantity of a product in cart
     */
    public function decrease(Request $request)
    {
        $cart_id = $request->input('cart_id');
        $customer_id = 1; // In real app, get from auth

        $cart = DB::table('cart')
            ->where('id', $cart_id)
            ->where('customer_id', $customer_id)
            ->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        // If quantity is 1, remove the item instead of decreasing
        if ($cart->qty <= 1) {
            DB::table('cart')
                ->where('id', $cart_id)
                ->where('customer_id', $customer_id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'removed' => true
            ]);
        }

        // Decrease quantity
        DB::table('cart')
            ->where('id', $cart_id)
            ->where('customer_id', $customer_id)
            ->decrement('qty');

        // Get updated cart item
        $updated_cart = DB::table('cart')
            ->join('product', 'cart.product_id', '=', 'product.id')
            ->select('cart.*', 'product.name', 'product.price', 'product.image', 'product.stock')
            ->where('cart.id', $cart_id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Quantity decreased',
            'cart_item' => $updated_cart,
            'new_quantity' => $updated_cart->qty,
            'removed' => false
        ]);
    }

    /**
     * Update cart quantity (for direct input)
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        $cart_id = $request->input('cart_id');
        $quantity = $request->input('quantity');
        $customer_id = 1;

        // Check product stock
        $cart = DB::table('cart')
            ->where('id', $cart_id)
            ->where('customer_id', $customer_id)
            ->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        $product = DB::table('product')
            ->where('id', $cart->product_id)
            ->first();

        if ($quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Quantity exceeds available stock',
                'max_stock' => $product->stock
            ], 400);
        }

        // Update quantity
        DB::table('cart')
            ->where('id', $cart_id)
            ->where('customer_id', $customer_id)
            ->update(['qty' => $quantity]);

        // Get updated cart item
        $updated_cart = DB::table('cart')
            ->join('product', 'cart.product_id', '=', 'product.id')
            ->select('cart.*', 'product.name', 'product.price', 'product.image', 'product.stock')
            ->where('cart.id', $cart_id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated',
            'cart_item' => $updated_cart
        ]);
    }
}