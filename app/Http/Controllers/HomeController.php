<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        // Get all products
        $products = DB::table('product')
            ->select('*')
            ->get();

        // Get trending products (limit to 4)
        $trending_products = DB::table('product')
            ->select('*')
            ->limit(4)
            ->get();

        // Get best seller products (limit to 4)
        $best_seller_products = DB::table('product')
            ->select('*')
            ->limit(4)
            ->get();

        // Get only 4 featured products
        $featured_products = DB::table('product')
            ->select('*')
            ->limit(4)
            ->get();

        return view('home', [
            'products' => $products,
            'trending_products' => $trending_products,
            'best_seller_products' => $best_seller_products,
            'featured_products' => $featured_products
        ]);
    }

    public function getById(Request $request)
    {
        $products = DB::table('product')
            ->select('*')
            ->where('id', intval($request->pro_id))
            ->get();
        return view('home', ['products' => $products]);
    }

    // Add this method for viewing all products
    public function allProducts(Request $request)
    {
        $products = DB::table('product')
            ->select('*')
            ->paginate(12);

        return view('products.all', ['products' => $products]);
    }

    // Product detail method
    public function productDetail($id)
    {
        $product = DB::table('product')
            ->select('*')
            ->where('id', $id)
            ->first();

        if (!$product) {
            abort(404);
        }

        // Get related products (same category, excluding current product)
        $related_products = DB::table('product')
            ->select('*')
            ->where('category', $product->category)
            ->where('id', '!=', $id)
            ->limit(4)
            ->get();

        return view('product_detail', [
            'product' => $product,
            'related_products' => $related_products
        ]);
    }
}
