<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        // Get trending products
        $trending_products = DB::table('product')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->select('product.*', 'category.name as category_name')
            ->orderBy('product.id', 'desc')
            ->limit(8)
            ->get();
            
        // Get best seller products
        $best_seller_products = DB::table('product')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->select('product.*', 'category.name as category_name')
            ->where('product.stock', '>', 0)
            ->limit(8)
            ->get();
            
        // Get featured products
        $featured_products = DB::table('product')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->select('product.*', 'category.name as category_name')
            ->orderBy('product.price', 'desc')
            ->limit(8)
            ->get();
        
        return view('home', compact('trending_products', 'best_seller_products', 'featured_products'));
    }

    public function food()
    {
        // Get only food products where category_id = 1
        $food_products = DB::table('product')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->select('product.*', 'category.name as category_name')
            ->where('product.category_id', 1) // Only category_id = 1 (food)
            ->get();

        return view('food', compact('food_products'));
    }

    public function drink()
    {
        // Get only drink products where category_id = 2 (Drink category)
        $drink_products = DB::table('product')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->select('product.*', 'category.name as category_name')
            ->where('product.category_id', 2) // category_id = 2 (drinks)
            ->get();

        return view('drink', compact('drink_products'));
    }

    public function show($id)
    {
        $product = DB::table('product')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->select('product.*', 'category.name as category_name')
            ->where('product.id', $id)
            ->first();
            
        if (!$product) {
            abort(404);
        }
        
        $related_products = DB::table('product')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->select('product.*', 'category.name as category_name')
            ->where('product.category_id', $product->category_id)
            ->where('product.id', '!=', $id)
            ->limit(4)
            ->get();
            
        // Get cart count for the navigation bar
        $customer_id = 1; // This should be dynamic based on logged-in user
        $cartCount = DB::table('cart')
            ->where('customer_id', $customer_id)
            ->sum('qty');
        
        return view('product_detail', compact('product', 'related_products', 'cartCount'));
    }
}