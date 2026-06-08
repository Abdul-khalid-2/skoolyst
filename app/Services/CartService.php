<?php

namespace App\Services;

/**
 * Single source of truth for cart/checkout money math.
 *
 * Both the cart page and the checkout page MUST use this service so the
 * subtotal, tax, shipping and totals always match. Rates live in config/shop.php
 * instead of being hardcoded in each controller.
 */
class CartService
{
    /**
     * Calculate the totals for a set of cart items.
     *
     * @param  array  $cartItems  Each item must contain 'price' and 'quantity'.
     * @param  array|null  $appliedCoupon  Optional ['discount_amount' => float, 'free_shipping' => bool].
     * @return array<string, mixed>
     */
    public function totals(array $cartItems, ?array $appliedCoupon = null): array
    {
        $subtotal = 0.0;
        $totalItems = 0;

        foreach ($cartItems as $item) {
            $price = (float) ($item['price'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);
            $subtotal += $price * $quantity;
            $totalItems += $quantity;
        }

        $subtotal = round($subtotal, 2);

        // Shipping (config driven)
        $flatRate = (float) config('shop.shipping.flat_rate', 0);
        $freeThreshold = config('shop.shipping.free_threshold');
        $shipping = ($freeThreshold !== null && $subtotal >= (float) $freeThreshold) ? 0.0 : $flatRate;

        // Tax (config driven)
        $taxRate = (float) config('shop.tax_rate', 0);
        $tax = round($subtotal * $taxRate, 2);

        // Automatic discount (config driven, disabled by default)
        $regularDiscount = 0.0;
        if (config('shop.auto_discount.enabled')) {
            $threshold = (float) config('shop.auto_discount.threshold', 0);
            if ($subtotal >= $threshold) {
                $regularDiscount = round($subtotal * (float) config('shop.auto_discount.rate', 0), 2);
            }
        }

        // Coupon discount + free shipping
        $couponDiscount = 0.0;
        if ($appliedCoupon) {
            $couponDiscount = round((float) ($appliedCoupon['discount_amount'] ?? 0), 2);
            if (! empty($appliedCoupon['free_shipping'])) {
                $shipping = 0.0;
            }
        }

        $totalDiscount = round($regularDiscount + $couponDiscount, 2);

        // Discount should never exceed the goods value.
        if ($totalDiscount > $subtotal) {
            $totalDiscount = $subtotal;
        }

        $total = round(max(0, $subtotal + $shipping + $tax - $totalDiscount), 2);

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'tax_rate' => $taxRate,
            'discount' => $totalDiscount,
            'regular_discount' => $regularDiscount,
            'coupon_discount' => $couponDiscount,
            'total' => $total,
            'total_items' => $totalItems,
        ];
    }
}
