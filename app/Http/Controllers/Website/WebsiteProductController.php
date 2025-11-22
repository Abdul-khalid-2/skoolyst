<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductCategory;

class WebsiteProductController extends Controller
{
    public function index(Request $request)
    {
        // $query = Product::with(['category', 'shop'])
        //     ->where('is_active', true)
        //     ->where('is_approved', true);

        // // Search by product name
        // if ($request->has('search') && $request->search) {
        //     $searchTerm = $request->search;
        //     $query->where(function ($q) use ($searchTerm) {
        //         $q->where('name', 'LIKE', "%{$searchTerm}%")
        //             ->orWhere('description', 'LIKE', "%{$searchTerm}%")
        //             ->orWhere('short_description', 'LIKE', "%{$searchTerm}%");
        //     });
        // }

        // // Filter by category
        // if ($request->has('category') && $request->category) {
        //     $query->where('category_id', $request->category);
        // }

        // // Filter by shop type
        // if ($request->has('shop_type') && $request->shop_type) {
        //     $query->whereHas('shop', function ($q) use ($request) {
        //         $q->where('shop_type', $request->shop_type);
        //     });
        // }

        // // Filter by product type
        // if ($request->has('product_type') && $request->product_type) {
        //     $query->where('product_type', $request->product_type);
        // }

        // // Sorting
        // switch ($request->get('sort', 'newest')) {
        //     case 'price_low':
        //         $query->orderBy('base_price', 'asc');
        //         break;
        //     case 'price_high':
        //         $query->orderBy('base_price', 'desc');
        //         break;
        //     case 'name':
        //         $query->orderBy('name', 'asc');
        //         break;
        //     case 'rating':
        //         $query->orderBy('rating', 'desc');
        //         break;
        //     case 'newest':
        //     default:
        //         $query->orderBy('created_at', 'desc');
        //         break;
        // }

        // $products = $query->paginate(12);
        // $categories = ProductCategory::where('is_active', true)->get();

        // // Get user favorites (you'll need to implement this based on your auth system)
        // $favorites = auth()->check() ? auth()->user()->favorites()->pluck('product_id')->toArray() : [];

        // return view('products.index', compact('products', 'categories', 'favorites'));
        return view('website.shops.products');
    }

    // public function show($id)
    // {
    //     $product = Product::with(['category', 'shop', 'attributes'])
    //         ->where('is_active', true)
    //         ->where('is_approved', true)
    //         ->findOrFail($id);

    //     $relatedProducts = Product::with(['category', 'shop'])
    //         ->where('category_id', $product->category_id)
    //         ->where('id', '!=', $product->id)
    //         ->where('is_active', true)
    //         ->where('is_approved', true)
    //         ->inRandomOrder()
    //         ->limit(4)
    //         ->get();

    //     return view('products.show', compact('product', 'relatedProducts'));
    // }
}
