@php
    /** @var \App\Models\Order $order */
    /** @var string $recipientType */
    $symbol = config('shop.currency_symbol', 'Rs.');
    $isShop = $recipientType === 'shop';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $isShop ? 'New Order' : 'Order Confirmation' }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f4f4f5; padding:24px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:8px;overflow:hidden;">
    <tr>
        <td style="background:#0f4077;color:#ffffff;padding:16px 20px;">
            <h2 style="margin:0;font-size:20px;">
                {{ $isShop ? 'New Order Received' : 'Thank you for your order!' }}
            </h2>
        </td>
    </tr>
    <tr>
        <td style="padding:20px;color:#111827;font-size:14px;line-height:1.6;">
            @if($isShop)
                <p style="margin:0 0 12px 0;">
                    You have received a new order on <strong>{{ $order->shop->name ?? 'your shop' }}</strong>.
                </p>
            @else
                <p style="margin:0 0 12px 0;">
                    Hi {{ $order->shipping_first_name }}, your order has been placed successfully.
                    We'll let you know once it ships.
                </p>
            @endif

            <p style="margin:0 0 4px 0;"><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p style="margin:0 0 4px 0;"><strong>Status:</strong> {{ ucfirst($order->status?->value ?? $order->status) }}</p>
            <p style="margin:0 0 16px 0;"><strong>Payment:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method?->value ?? $order->payment_method)) }}</p>

            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-bottom:16px;">
                <thead>
                    <tr style="background:#f3f4f6;">
                        <th align="left" style="padding:8px;border-bottom:1px solid #e5e7eb;font-size:13px;">Item</th>
                        <th align="center" style="padding:8px;border-bottom:1px solid #e5e7eb;font-size:13px;">Qty</th>
                        <th align="right" style="padding:8px;border-bottom:1px solid #e5e7eb;font-size:13px;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td style="padding:8px;border-bottom:1px solid #f3f4f6;">{{ $item->product_name }}</td>
                            <td align="center" style="padding:8px;border-bottom:1px solid #f3f4f6;">{{ $item->quantity }}</td>
                            <td align="right" style="padding:8px;border-bottom:1px solid #f3f4f6;">{{ $symbol }} {{ number_format($item->total_price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <tr>
                    <td align="right" style="padding:2px 8px;">Subtotal:</td>
                    <td align="right" style="padding:2px 8px;width:120px;">{{ $symbol }} {{ number_format($order->subtotal) }}</td>
                </tr>
                <tr>
                    <td align="right" style="padding:2px 8px;">Shipping:</td>
                    <td align="right" style="padding:2px 8px;">{{ $symbol }} {{ number_format($order->shipping_cost) }}</td>
                </tr>
                <tr>
                    <td align="right" style="padding:2px 8px;">Tax:</td>
                    <td align="right" style="padding:2px 8px;">{{ $symbol }} {{ number_format($order->tax_amount) }}</td>
                </tr>
                @if($order->discount_amount > 0)
                <tr>
                    <td align="right" style="padding:2px 8px;">Discount:</td>
                    <td align="right" style="padding:2px 8px;">- {{ $symbol }} {{ number_format($order->discount_amount) }}</td>
                </tr>
                @endif
                <tr>
                    <td align="right" style="padding:6px 8px;font-weight:bold;border-top:1px solid #e5e7eb;">Total:</td>
                    <td align="right" style="padding:6px 8px;font-weight:bold;border-top:1px solid #e5e7eb;">{{ $symbol }} {{ number_format($order->total_amount) }}</td>
                </tr>
            </table>

            <p style="margin:18px 0 4px 0;"><strong>Shipping to:</strong></p>
            <p style="margin:0;color:#374151;">
                {{ $order->shipping_first_name }} {{ $order->shipping_last_name }}<br>
                {{ $order->shipping_address }},<br>
                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip_code }}<br>
                {{ $order->shipping_phone }}
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:16px 20px;border-top:1px solid #e5e7eb;font-size:12px;color:#6b7280;">
            This is an automatic notification from the SKOOLYST platform.
        </td>
    </tr>
</table>
</body>
</html>
