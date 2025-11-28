<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteCheckoutController extends Controller
{
    public function index()
    {
        // For demo purposes - in real app, this would come from session/database
        $orderItems = [
            [
                'name' => 'Mathematics Textbook Grade 10',
                'shop_name' => 'ABC Book Store',
                'quantity' => 2,
                'price' => 1200
            ],
            [
                'name' => 'Geometry Box Set',
                'shop_name' => 'XYZ Stationery',
                'quantity' => 1,
                'price' => 350
            ]
        ];

        $subtotal = 2750;
        $shipping = 100;
        $tax = 275;
        $discount = 150;
        $total = $subtotal + $shipping + $tax - $discount;

        return view('website.checkout', compact('orderItems', 'subtotal', 'shipping', 'tax', 'discount', 'total'));
    }

    public function process(Request $request)
    {
        // Process the order
        // Validate request
        // Create order
        // Process payment
        // Send confirmation email

        return response()->json(['success' => true, 'order_id' => 12345]);
    }
}
