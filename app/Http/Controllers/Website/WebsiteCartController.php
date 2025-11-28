<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteCartController extends Controller
{
    public function index()
    {
        // For demo purposes - in real app, this would come from session/database
        $cartItems = [
            [
                'id' => 1,
                'name' => 'Mathematics Textbook Grade 10',
                'shop_name' => 'ABC Book Store',
                'category' => 'Textbooks',
                'price' => 1200,
                'quantity' => 2,
                'image' => 'https://via.placeholder.com/100'
            ],
            [
                'id' => 2,
                'name' => 'Geometry Box Set',
                'shop_name' => 'XYZ Stationery',
                'category' => 'Stationery',
                'price' => 350,
                'quantity' => 1,
                'image' => 'https://via.placeholder.com/100'
            ]
        ];

        $subtotal = 2750;
        $shipping = 100;
        $tax = 275;
        $discount = 150;
        $total = $subtotal + $shipping + $tax - $discount;

        return view('website.cart', compact('cartItems', 'subtotal', 'shipping', 'tax', 'discount', 'total'));
    }
}
