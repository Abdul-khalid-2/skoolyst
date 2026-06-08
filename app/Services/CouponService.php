<?php

namespace App\Services;

use App\Enums\CouponScope;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShopSchoolAssociation;
use App\Models\User;
use Illuminate\Support\Collection;

class CouponService
{
    /**
     * Validate a coupon against the given cart and compute the discount.
     *
     * @param  array  $cartItems  Session cart items (each: id, price, quantity, ...).
     * @return array{valid: bool, message: string, discount: float, free_shipping: bool, eligible_subtotal: float}
     */
    public function evaluate(Coupon $coupon, array $cartItems, ?User $user = null): array
    {
        $fail = static fn (string $message): array => [
            'valid' => false,
            'message' => $message,
            'discount' => 0.0,
            'free_shipping' => false,
            'eligible_subtotal' => 0.0,
        ];

        if (! $coupon->isValid()) {
            return $fail('This coupon is invalid or has expired.');
        }

        if ($user && ! $coupon->canBeUsedByUser($user)) {
            return $fail('You have already used this coupon the maximum number of times.');
        }

        $subtotal = $this->cartSubtotal($cartItems);

        if ($coupon->minimum_order_amount && $subtotal < (float) $coupon->minimum_order_amount) {
            $symbol = config('shop.currency_symbol', 'Rs.');

            return $fail("Minimum order amount of {$symbol} ".number_format((float) $coupon->minimum_order_amount).' is required for this coupon.');
        }

        $eligibleSubtotal = $this->eligibleSubtotal($coupon, $cartItems);

        if ($eligibleSubtotal <= 0) {
            return $fail('This coupon does not apply to the items in your cart.');
        }

        $freeShipping = $coupon->isFreeShipping();
        $discount = $freeShipping ? 0.0 : $coupon->calculateDiscount($eligibleSubtotal);

        if (! $freeShipping && $discount <= 0) {
            return $fail('This coupon does not apply to the items in your cart.');
        }

        return [
            'valid' => true,
            'message' => 'Coupon applied successfully.',
            'discount' => $discount,
            'free_shipping' => $freeShipping,
            'eligible_subtotal' => $eligibleSubtotal,
        ];
    }

    /**
     * Persist a coupon usage record and bump the usage counter.
     */
    public function recordUsage(Coupon $coupon, Order $order, float $discountAmount, ?int $userId): void
    {
        CouponUsage::create([
            'coupon_id' => $coupon->id,
            'user_id' => $userId,
            'order_id' => $order->id,
            'discount_amount' => round($discountAmount, 2),
        ]);

        $coupon->incrementUsage();
    }

    public function cartSubtotal(array $cartItems): float
    {
        $subtotal = 0.0;

        foreach ($cartItems as $item) {
            $subtotal += (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0);
        }

        return round($subtotal, 2);
    }

    /**
     * Sum of the cart lines that the coupon's scope actually applies to.
     */
    protected function eligibleSubtotal(Coupon $coupon, array $cartItems): float
    {
        $scope = $coupon->scope;

        if ($scope === CouponScope::GlobalScope) {
            return $this->cartSubtotal($cartItems);
        }

        $products = $this->resolveProducts($cartItems);

        $shopIds = $coupon->shops()->pluck('shops.id')->all();
        $schoolIds = $coupon->schools()->pluck('schools.id')->all();
        $productIds = $coupon->products()->pluck('products.id')->all();
        $categoryIds = $coupon->categories()->pluck('product_categories.id')->all();

        $eligible = 0.0;

        foreach ($cartItems as $item) {
            $product = $products->get($item['id'] ?? null);

            $lineTotal = (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0);
            $shopId = $product->shop_id ?? ($item['shop_id'] ?? null);
            $categoryId = $product->category_id ?? ($item['category_id'] ?? null);
            $productId = $product->id ?? ($item['product_id'] ?? null);
            $schoolId = $product->school_id ?? null;

            $matches = match ($scope) {
                CouponScope::ShopSpecific => in_array($shopId, $shopIds),
                CouponScope::ProductSpecific => in_array($productId, $productIds) || in_array($categoryId, $categoryIds),
                CouponScope::SchoolSpecific => $this->matchesSchool($schoolId, $shopId, $schoolIds),
                default => false,
            };

            if ($matches) {
                $eligible += $lineTotal;
            }
        }

        return round($eligible, 2);
    }

    protected function matchesSchool(?int $schoolId, ?int $shopId, array $schoolIds): bool
    {
        if (empty($schoolIds)) {
            return false;
        }

        if ($schoolId && in_array($schoolId, $schoolIds)) {
            return true;
        }

        if ($shopId) {
            return ShopSchoolAssociation::where('shop_id', $shopId)
                ->whereIn('school_id', $schoolIds)
                ->where('is_active', true)
                ->exists();
        }

        return false;
    }

    protected function resolveProducts(array $cartItems): Collection
    {
        $uuids = collect($cartItems)->pluck('id')->filter()->unique()->all();

        if (empty($uuids)) {
            return collect();
        }

        return Product::whereIn('uuid', $uuids)->get()->keyBy('uuid');
    }
}
