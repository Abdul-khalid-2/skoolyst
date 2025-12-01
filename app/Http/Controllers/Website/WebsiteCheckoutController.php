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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'payment_method' => 'required|in:credit_card,cash_on_delivery,digital_wallet',
        ]);

        try {
            DB::beginTransaction();

            $cartItems = $this->getCartItems();

            if (empty($cartItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty'
                ], 400);
            }

            $cartData = $this->calculateCartTotals($cartItems);

            // Create order
            $order = $this->createOrder($request, $cartData, $cartItems);

            // Clear cart
            session()->forget('cart');

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'redirect_url' => route('website.order.confirmation', $order->uuid)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to process order: ' . $e->getMessage()
            ], 500);
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

    private function createOrder($request, $cartData, $cartItems)
    {
        try {
            // Handle user authentication/creation
            $userId = $this->getOrCreateUser($request);

            // Get primary shop ID using smart algorithm
            $primaryShopId = $this->getPrimaryShopId($cartItems, $request->city);

            // If no primary shop found, fallback to first shop
            if (!$primaryShopId) {
                $firstItem = reset($cartItems);
                $primaryShopId = $this->getShopIdForProduct($firstItem['id']);
            }

            // Create order
            $order = \App\Models\Order::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'user_id' => $userId,
                'shop_id' => $primaryShopId,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $cartData['subtotal'],
                'shipping_cost' => $cartData['shipping'],
                'tax_amount' => $cartData['tax'],
                'discount_amount' => $cartData['discount'],
                'total_amount' => $cartData['total'],
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_first_name' => $request->first_name,
                'shipping_last_name' => $request->last_name,
                'shipping_email' => $request->email,
                'shipping_phone' => $request->phone,
                'shipping_address' => $request->address,
                'shipping_city' => $request->city,
                'shipping_state' => $request->state,
                'shipping_zip_code' => $request->zip_code,
                'shipping_country' => 'Pakistan',
                'delivery_instructions' => $request->delivery_instructions,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                $shopId = $this->getShopIdForProduct($item['id']);
                $categoryId = $this->getCategoryIdForProduct($item['id']);

                $order->orderItems()->create([
                    'uuid' => \Illuminate\Support\Str::uuid(),
                    'product_id' => $item['product_id'],
                    'shop_id' => $shopId,
                    'product_name' => $item['name'],
                    'product_description' => $item['description'] ?? null,
                    'product_sku' => $item['sku'] ?? null,
                    'product_image' => $item['image'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'category_id' => $categoryId,
                    'category_name' => $item['category'],
                ]);
            }

            // Apply coupon if exists
            $this->applyCouponToOrder($order, $userId);

            return $order;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create order: ' . $e->getMessage());
        }
    }

    private function getOrCreateUser($request)
    {
        // If user is authenticated, return their ID
        if (auth()->check()) {
            return auth()->id();
        }

        // Check if user already exists with this email
        $existingUser = \App\Models\User::where('email', $request->email)->first();
        if ($existingUser) {
            return $existingUser->id;
        }

        // Create new guest user
        try {
            $user = \App\Models\User::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => \Illuminate\Support\Str::random(32), // Random password for guest users
                'email_verified_at' => null, // Not verified for guest users
            ]);

            // You might want to assign a guest role or handle permissions
            // $user->assignRole('guest');

            return $user->id;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create user account: ' . $e->getMessage());
        }
    }

    private function applyCouponToOrder($order, $userId)
    {
        $appliedCoupon = session()->get('applied_coupon');

        if ($appliedCoupon) {
            $coupon = \App\Models\Coupon::find($appliedCoupon['id']);

            if ($coupon) {
                $order->update(['coupon_id' => $coupon->id]);

                // Record coupon usage
                \App\Models\CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $userId,
                    'order_id' => $order->id,
                    'discount_amount' => $appliedCoupon['discount_amount'],
                ]);

                $coupon->incrementUsage();
            }

            // Clear applied coupon from session
            session()->forget('applied_coupon');
        }
    }

    private function getPrimaryShopId($cartItems, $customerCity = null)
    {
        if (empty($cartItems)) {
            return null;
        }

        // Get all unique shops from cart items
        $shopIds = [];
        $shopProducts = [];
        foreach ($cartItems as $item) {
            $shopId = $this->getShopIdForProduct($item['id']);

            if ($shopId) {
                $shopIds[] = $shopId;
                if (!isset($shopProducts[$shopId])) {
                    $shopProducts[$shopId] = [];
                }
                $shopProducts[$shopId][] = $item;
            }
        }

        $uniqueShopIds = array_unique($shopIds);

        if (empty($uniqueShopIds)) {
            return null;
        }

        // Get shops with their details
        $shops = \App\Models\Shop::withCount(['orders as total_orders'])
            ->withSum(['orders as total_revenue' => function ($query) {
                $query->where('payment_status', 'paid');
            }], 'total_amount')
            ->whereIn('id', $uniqueShopIds)
            ->get()
            ->keyBy('id');

        // Algorithm to determine primary shop
        return $this->determinePrimaryShop($shops, $shopProducts, $customerCity);
    }

    private function determinePrimaryShop($shops, $shopProducts, $customerCity)
    {
        $eligibleShops = [];

        foreach ($shops as $shopId => $shop) {
            $score = 0;

            // 1. City Match (Highest Priority - 100 points)
            if ($customerCity && $shop->city && strtolower($shop->city) === strtolower($customerCity)) {
                $score += 100;
            }

            // 2. Admin Set Priority (High Priority - 50 points per priority level)
            if ($shop->priority) {
                $score += ($shop->priority * 50);
            }

            // 3. Total Revenue (Medium Priority - 1 point per 1000 Rs)
            if ($shop->total_revenue) {
                $score += ($shop->total_revenue / 1000);
            }

            // 4. Total Orders (Low Priority - 1 point per 10 orders)
            if ($shop->total_orders) {
                $score += ($shop->total_orders / 10);
            }

            // 5. Number of products in current cart (Bonus points)
            $cartProductCount = count($shopProducts[$shopId] ?? []);
            $score += ($cartProductCount * 5);

            $eligibleShops[$shopId] = [
                'shop' => $shop,
                'score' => $score,
                'city_match' => $customerCity && $shop->city && strtolower($shop->city) === strtolower($customerCity),
                'priority' => $shop->priority ?? 0,
                'revenue' => $shop->total_revenue ?? 0,
                'orders' => $shop->total_orders ?? 0,
                'cart_items' => $cartProductCount
            ];
        }

        // Sort by score descending
        uasort($eligibleShops, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Return the shop with highest score
        return !empty($eligibleShops) ? array_key_first($eligibleShops) : null;
    }

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
