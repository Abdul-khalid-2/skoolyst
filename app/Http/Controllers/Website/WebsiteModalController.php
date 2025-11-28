<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class WebsiteModalController extends Controller
{
    public function productModal($productId)
    {
        $product = Product::with(['category', 'shop', 'productAttributes'])
            ->where('uuid', $productId)
            ->orWhere('id', $productId)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }
}
