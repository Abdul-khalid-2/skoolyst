<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    */
    'currency' => env('SHOP_CURRENCY', 'PKR'),
    'currency_symbol' => env('SHOP_CURRENCY_SYMBOL', 'Rs.'),

    /*
    |--------------------------------------------------------------------------
    | Business timezone
    |--------------------------------------------------------------------------
    | The app stores timestamps in UTC (config/app.php). Admin-entered dates
    | such as coupon validity are interpreted in this business timezone and
    | converted to/from UTC for storage and display.
    */
    'timezone' => env('SHOP_TIMEZONE', 'Asia/Karachi'),

    /*
    |--------------------------------------------------------------------------
    | Tax
    |--------------------------------------------------------------------------
    | Tax is applied to the cart subtotal. Use a fraction, e.g. 0.05 = 5%.
    | Default is 0 (no tax) so totals stay predictable until configured.
    */
    'tax_rate' => (float) env('SHOP_TAX_RATE', 0),

    /*
    |--------------------------------------------------------------------------
    | Shipping
    |--------------------------------------------------------------------------
    | A flat shipping fee is charged per order. If the subtotal is greater than
    | or equal to "free_threshold", shipping becomes free. Set free_threshold to
    | null to disable free shipping.
    */
    'shipping' => [
        'flat_rate' => (float) env('SHOP_SHIPPING_FLAT_RATE', 100),
        'free_threshold' => env('SHOP_SHIPPING_FREE_THRESHOLD', 2000) !== null
            ? (float) env('SHOP_SHIPPING_FREE_THRESHOLD', 2000)
            : null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Automatic order discount
    |--------------------------------------------------------------------------
    | An optional automatic discount applied when the subtotal reaches a
    | threshold. Disabled by default; enable via env if you want it.
    */
    'auto_discount' => [
        'enabled' => filter_var(env('SHOP_AUTO_DISCOUNT_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'rate' => (float) env('SHOP_AUTO_DISCOUNT_RATE', 0),
        'threshold' => (float) env('SHOP_AUTO_DISCOUNT_THRESHOLD', 1000),
    ],

];
