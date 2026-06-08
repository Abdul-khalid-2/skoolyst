<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlacedMail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WebsiteCheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private CouponService $couponService,
    ) {
    }

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
            'userData' => $userData,
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

            // Re-validate any applied coupon at submit time so a stale/invalid
            // session coupon can never grant a discount.
            [$coupon, $couponResult] = $this->resolveAppliedCoupon($cartItems);

            $appliedCoupon = $coupon
                ? ['discount_amount' => $couponResult['discount'], 'free_shipping' => $couponResult['free_shipping']]
                : null;

            $globalTotals = $this->cartService->totals($cartItems, $appliedCoupon);
            $totalSubtotal = $globalTotals['subtotal'];
            $totalCouponDiscount = $globalTotals['coupon_discount'];
            $freeShipping = $coupon ? $couponResult['free_shipping'] : false;

            // Group cart items by shop
            $itemsByShop = [];
            foreach ($cartItems as $item) {
                $shopId = $this->getShopIdForProduct($item['id']);
                if (! $shopId) {
                    continue;
                }
                $itemsByShop[$shopId][] = $item;
            }

            if (empty($itemsByShop)) {
                return response()->json(['success' => false, 'message' => 'Could not determine shops for cart items'], 400);
            }

            $checkoutSessionId = (string) Str::uuid();

            $createdOrders = [];
            foreach ($itemsByShop as $shopId => $shopItems) {
                // Prorate the coupon discount across shops by their subtotal share.
                $shopSubtotal = $this->couponService->cartSubtotal($shopItems);
                $share = $totalSubtotal > 0 ? $shopSubtotal / $totalSubtotal : 0;

                $shopCoupon = $coupon
                    ? [
                        'discount_amount' => round($totalCouponDiscount * $share, 2),
                        'free_shipping' => $freeShipping,
                    ]
                    : null;

                $shopTotals = $this->cartService->totals($shopItems, $shopCoupon);

                $createdOrders[] = $this->createSingleShopOrder(
                    $request,
                    $shopTotals,
                    $shopItems,
                    $shopId,
                    $checkoutSessionId,
                    $coupon?->id,
                );
            }

            // Record coupon usage once for the whole checkout (against the first order).
            if ($coupon && $totalCouponDiscount > 0) {
                $this->couponService->recordUsage(
                    $coupon,
                    $createdOrders[0],
                    $totalCouponDiscount,
                    auth()->id(),
                );
            }

            session()->forget('cart');
            session()->forget('applied_coupon');

            DB::commit();

            // Notifications are best-effort and must not break a successful order.
            foreach ($createdOrders as $createdOrder) {
                $this->sendOrderNotifications($createdOrder);
            }

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

            return response()->json(['success' => false, 'message' => 'Failed to process order: '.$e->getMessage()], 500);
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        try {
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            if (! $coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coupon code.',
                ], 400);
            }

            $cartItems = $this->getCartItems();

            $result = $this->couponService->evaluate($coupon, $cartItems, auth()->user());

            if (! $result['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 400);
            }

            // Store coupon in session so the cart/checkout totals reflect it.
            session()->put('applied_coupon', [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount_amount' => $result['discount'],
                'free_shipping' => $result['free_shipping'],
            ]);

            $cartData = $this->calculateCartTotals($cartItems);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'coupon' => [
                    'code' => $coupon->code,
                    'discount_amount' => $result['discount'],
                    'free_shipping' => $result['free_shipping'],
                ],
                'cart_data' => $cartData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply coupon: '.$e->getMessage(),
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
            'cart_data' => $cartData,
        ]);
    }

    // Helper Methods
    private function getCartItems()
    {
        return session()->get('cart', []);
    }

    private function calculateCartTotals($cartItems)
    {
        $appliedCoupon = session()->get('applied_coupon');

        return $this->cartService->totals($cartItems, $appliedCoupon);
    }

    /**
     * Re-validate the session coupon against the current cart.
     *
     * @return array{0: ?Coupon, 1: array}
     */
    private function resolveAppliedCoupon(array $cartItems): array
    {
        $applied = session()->get('applied_coupon');

        if (! $applied || empty($applied['id'])) {
            return [null, []];
        }

        $coupon = Coupon::find($applied['id']);

        if (! $coupon) {
            session()->forget('applied_coupon');

            return [null, []];
        }

        $result = $this->couponService->evaluate($coupon, $cartItems, auth()->user());

        if (! $result['valid']) {
            session()->forget('applied_coupon');

            return [null, []];
        }

        return [$coupon, $result];
    }

    private function createSingleShopOrder($request, $cartData, $shopItems, $shopId, $checkoutSessionId, ?int $couponId = null)
    {
        $order = Order::create([
            'uuid'                  => (string) Str::uuid(),
            'checkout_session_id'   => $checkoutSessionId,
            'user_id'               => auth()->id(),
            'shop_id'               => $shopId,
            'coupon_id'             => $couponId,
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
                'uuid'                => (string) Str::uuid(),
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

    private function sendOrderNotifications(Order $order): void
    {
        try {
            $order->loadMissing(['orderItems', 'shop.user']);

            // Customer confirmation
            if ($order->shipping_email) {
                Mail::to($order->shipping_email)->send(new OrderPlacedMail($order, 'customer'));
            }

            // Shop owner notification
            $shopEmail = $order->shop->email ?? $order->shop->user->email ?? null;
            if ($shopEmail) {
                Mail::to($shopEmail)->send(new OrderPlacedMail($order, 'shop'));
            }
        } catch (\Throwable $e) {
            Log::warning('Order notification failed for order '.$order->id.': '.$e->getMessage());
        }
    }

    private function getShopIdForProduct($productUuid)
    {
        try {
            $product = Product::with('shop')->where('uuid', $productUuid)->first();

            return $product ? $product->shop_id : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getCategoryIdForProduct($productUuid)
    {
        try {
            $product = Product::where('uuid', $productUuid)->first();

            return $product ? $product->category_id : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function generateOrderNumber()
    {
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(Str::random(6));

        return "ORD-{$timestamp}-{$random}";
    }
}
