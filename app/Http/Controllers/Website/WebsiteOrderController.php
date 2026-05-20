<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class WebsiteOrderController extends Controller
{
    public function confirmation($orderUuid)
    {
        try {
            $order = Order::with(['orderItems.shop', 'shop', 'user'])
                ->where('uuid', $orderUuid)
                ->firstOrFail();

            $allOrders = collect([$order]);

            if ($order->checkout_session_id) {
                $siblings = Order::with(['orderItems.shop', 'shop'])
                    ->where('checkout_session_id', $order->checkout_session_id)
                    ->where('uuid', '!=', $orderUuid)
                    ->get();
                $allOrders = $allOrders->merge($siblings);
            }

            return view('website.order.confirmation', compact('order', 'allOrders'));

        } catch (\Exception $e) {
            return redirect()->route('website.cart')
                ->with('error', 'Order not found.');
        }
    }

    public function show($orderUuid)
    {
        try {
            // For authenticated users, show their specific order
            if (auth()->check()) {
                $order = Order::with(['orderItems', 'shop', 'user', 'coupon'])
                    ->where('uuid', $orderUuid)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();
            } else {
                // For guests, allow viewing by order number and email
                $order = Order::with(['orderItems', 'shop', 'user', 'coupon'])
                    ->where('uuid', $orderUuid)
                    ->firstOrFail();
            }

            return view('website.order-details', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('website.order.track')
                ->with('error', 'Order not found or you do not have permission to view this order.');
        }
    }

    public function track(Request $request)
    {
        $order = null;

        if ($request->has('order_number') && $request->has('email')) {
            $request->validate([
                'order_number' => 'required|string',
                'email' => 'required|email'
            ]);

            $order = Order::with(['orderItems', 'shop'])
                ->where('order_number', $request->order_number)
                ->where('shipping_email', $request->email)
                ->first();
        }

        return view('website.order.order_track', compact('order'));
    }
}
