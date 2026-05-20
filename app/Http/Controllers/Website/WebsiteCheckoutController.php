<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

class WebsiteCheckoutController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();

        if (empty($cartItems)) {
            return redirect()->route('website.cart')->with('error', 'Your cart is empty');
        }

        $cartData = $this->calculateCartTotals($cartItems);

        // Get user info if logged in
        $user = auth()->user();
        $userData = [];

        if ($user) {
            $userData = [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
            ];
        }

        return view('website.checkout', array_merge($cartData, [
            'cartItems' => $cartItems,
            'userData' => $userData
        ]));
    }

    public function process(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'city'           => 'required|string|max:255',
            'state'          => 'required|string|max:255',
            'zip_code'       => 'required|string|max:20',
            'payment_method' => 'required|in:credit_card,cash_on_delivery,digital_wallet',
        ]);

        try {
            DB::beginTransaction();

            $cartItems = $this->getCartItems();

            if (empty($cartItems)) {
                return response()->json(['success' => false, 'message' => 'Your cart is empty'], 400);
            }

            // Group cart items by shop
            $itemsByShop = [];
            foreach ($cartItems as $item) {
                $shopId = $this->getShopIdForProduct($item['id']);
                if (!$shopId) continue;
                $itemsByShop[$shopId][] = $item;
            }

            if (empty($itemsByShop)) {
                return response()->json(['success' => false, 'message' => 'Could not determine shops for cart items'], 400);
            }

            $checkoutSessionId = \Illuminate\Support\Str::uuid()->toString();

            $createdOrders = [];
            foreach ($itemsByShop as $shopId => $shopItems) {
                $shopCartData  = $this->calculateCartTotals($shopItems);
                $createdOrders[] = $this->createSingleShopOrder($request, $shopCartData, $shopItems, $shopId, $checkoutSessionId);
            }

            session()->forget('cart');
            session()->forget('applied_coupon');

            DB::commit();

            $firstOrder = $createdOrders[0];

            return response()->json([
                'success'      => true,
                'order_id'     => $firstOrder->id,
                'order_number' => $firstOrder->order_number,
                'total_orders' => count($createdOrders),
                'redirect_url' => route('website.order.confirmation', $firstOrder->uuid),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to process order: ' . $e->getMessage()], 500);
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        try {
            $coupon = Coupon::where('code', $request->coupon_code)
                ->active()
                ->first();

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired coupon code'
                ], 400);
            }

            $cartItems = $this->getCartItems();
            $cartData = $this->calculateCartTotals($cartItems);

            // Check minimum order amount
            if ($coupon->minimum_order_amount && $cartData['subtotal'] < $coupon->minimum_order_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum order amount of Rs. ' . number_format($coupon->minimum_order_amount) . ' required'
                ], 400);
            }

            // Calculate discount
            $discountAmount = $coupon->calculateDiscount($cartData['subtotal']);

            // Update cart data with coupon discount
            $cartData['discount'] += $discountAmount;
            $cartData['total'] = $cartData['subtotal'] + $cartData['shipping'] + $cartData['tax'] - $cartData['discount'];

            // Store coupon in session
            session()->put('applied_coupon', [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount_amount' => $discountAmount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully',
                'coupon' => [
                    'code' => $coupon->code,
                    'discount_amount' => $discountAmount
                ],
                'cart_data' => $cartData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeCoupon()
    {
        session()->forget('applied_coupon');

        $cartItems = $this->getCartItems();
        $cartData = $this->calculateCartTotals($cartItems);

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully',
            'cart_data' => $cartData
        ]);
    }

    // Helper Methods
    private function getCartItems()
    {
        return session()->get('cart', []);
    }

    private function calculateCartTotals($cartItems)
    {
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalItems += $item['quantity'];
        }

        // Calculate shipping (free over 2000, otherwise 100)
        $shipping = $subtotal > 2000 ? 0 : 100;

        // Calculate tax (10% of subtotal)
        $tax = $subtotal * 0.10;

        // Get applied coupon discount
        $couponDiscount = 0;
        $appliedCoupon = session()->get('applied_coupon');
        if ($appliedCoupon) {
            $couponDiscount = $appliedCoupon['discount_amount'];
        }

        // Calculate regular discount (5% of subtotal if over 1000)
        $regularDiscount = $subtotal > 1000 ? $subtotal * 0.05 : 0;

        $totalDiscount = $regularDiscount + $couponDiscount;
        $total = $subtotal + $shipping + $tax - $totalDiscount;

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'discount' => $totalDiscount,
            'coupon_discount' => $couponDiscount,
            'regular_discount' => $regularDiscount,
            'total' => $total,
            'total_items' => $totalItems
        ];
    }

    private function createSingleShopOrder($request, $cartData, $shopItems, $shopId, $checkoutSessionId)
    {
        $order = \App\Models\Order::create([
            'uuid'                  => \Illuminate\Support\Str::uuid(),
            'checkout_session_id'   => $checkoutSessionId,
            'user_id'               => auth()->id(),
            'shop_id'               => $shopId,
            'order_number'          => $this->generateOrderNumber(),
            'status'                => 'pending',
            'subtotal'              => $cartData['subtotal'],
            'shipping_cost'         => $cartData['shipping'],
            'tax_amount'            => $cartData['tax'],
            'discount_amount'       => $cartData['discount'],
            'total_amount'          => $cartData['total'],
            'payment_method'        => $request->payment_method,
            'payment_status'        => 'pending',
            'shipping_first_name'   => $request->first_name,
            'shipping_last_name'    => $request->last_name,
            'shipping_email'        => $request->email,
            'shipping_phone'        => $request->phone,
            'shipping_address'      => $request->address,
            'shipping_city'         => $request->city,
            'shipping_state'        => $request->state,
            'shipping_zip_code'     => $request->zip_code,
            'shipping_country'      => 'Pakistan',
            'delivery_instructions' => $request->delivery_instructions,
        ]);

        foreach ($shopItems as $item) {
            $categoryId = $this->getCategoryIdForProduct($item['id']);

            $order->orderItems()->create([
                'uuid'                => \Illuminate\Support\Str::uuid(),
                'product_id'          => $item['product_id'],
                'shop_id'             => $shopId,
                'product_name'        => $item['name'],
                'product_description' => $item['description'] ?? null,
                'product_sku'         => $item['sku'] ?? null,
                'product_image'       => $item['image'],
                'unit_price'          => $item['price'],
                'quantity'            => $item['quantity'],
                'total_price'         => $item['price'] * $item['quantity'],
                'category_id'         => $categoryId,
                'category_name'       => $item['category'] ?? null,
            ]);
        }

        return $order;
    }

    // private function getOrCreateUser($request)
    // {
    //     // If user is authenticated, return their ID
    //     if (auth()->check()) {
    //         return auth()->id();
    //     }

    //     // Check if user already exists with this email
    //     $existingUser = \App\Models\User::where('email', $request->email)->first();
    //     if ($existingUser) {
    //         return $existingUser->id;
    //     }

    //     // Create new guest user
    //     try {
    //         $user = \App\Models\User::create([
    //             'uuid' => \Illuminate\Support\Str::uuid(),
    //             'name' => $request->first_name . ' ' . $request->last_name,
    //             'email' => $request->email,
    //             'phone' => $request->phone,
    //             'address' => $request->address,
    //             'password' => \Illuminate\Support\Str::random(32), // Random password for guest users
    //             'email_verified_at' => null, // Not verified for guest users
    //         ]);

    //         // You might want to assign a guest role or handle permissions
    //         // $user->assignRole('guest');

    //         return $user->id;
    //     } catch (\Exception $e) {
    //         throw new \Exception('Failed to create user account: ' . $e->getMessage());
    //     }
    // }

    // private function applyCouponToOrder($order, $userId)
    // {
    //     $appliedCoupon = session()->get('applied_coupon');

    //     if ($appliedCoupon) {
    //         $coupon = \App\Models\Coupon::find($appliedCoupon['id']);

    //         if ($coupon) {
    //             $order->update(['coupon_id' => $coupon->id]);

    //             // Record coupon usage
    //             \App\Models\CouponUsage::create([
    //                 'coupon_id' => $coupon->id,
    //                 'user_id' => $userId,
    //                 'order_id' => $order->id,
    //                 'discount_amount' => $appliedCoupon['discount_amount'],
    //             ]);

    //             $coupon->incrementUsage();
    //         }

    //         // Clear applied coupon from session
    //         session()->forget('applied_coupon');
    //     }
    // }

    private function getShopIdForProduct($productUuid)
    {
        try {
            $product = \App\Models\Product::with('shop')->where('uuid', $productUuid)->first();
            return $product ? $product->shop_id : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getCategoryIdForProduct($productUuid)
    {
        try {
            $product = \App\Models\Product::where('uuid', $productUuid)->first();
            return $product ? $product->category_id : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function generateOrderNumber()
    {
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(\Illuminate\Support\Str::random(6));
        return "ORD-{$timestamp}-{$random}";
    }
}
