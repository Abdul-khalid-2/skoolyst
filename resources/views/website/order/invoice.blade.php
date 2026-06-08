@php
    /** @var \App\Models\Order $order */
    $symbol = config('shop.currency_symbol', 'Rs.');
    $orders = ($allOrders ?? collect())->isNotEmpty() ? $allOrders : collect([$order]);

    $grandSubtotal = $orders->sum('subtotal');
    $grandShipping = $orders->sum('shipping_cost');
    $grandTax = $orders->sum('tax_amount');
    $grandDiscount = $orders->sum('discount_amount');
    $grandTotal = $orders->sum('total_amount');
    $totalQty = $orders->sum(fn ($o) => $o->orderItems->sum('quantity'));

    $payMethod = $order->payment_method instanceof \BackedEnum ? $order->payment_method->value : $order->payment_method;
    $payStatus = $order->payment_status instanceof \BackedEnum ? $order->payment_status->value : $order->payment_status;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill — {{ $order->order_number }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #e5e7eb;
            font-family: 'Courier New', Courier, monospace;
            color: #111;
            padding: 20px 10px;
            font-size: 13px;
        }
        .toolbar {
            max-width: 320px;
            margin: 0 auto 14px;
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 6px;
            border: none;
            font-size: 13px;
            font-weight: 700;
            font-family: 'Segoe UI', Arial, sans-serif;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-primary { background: #0f4077; color: #fff; }
        .btn-secondary { background: #fff; color: #374151; border: 1px solid #d1d5db; }

        .receipt {
            background: #fff;
            max-width: 320px;
            margin: 0 auto;
            padding: 18px 16px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.12);
        }
        .center { text-align: center; }
        .shop-name { font-size: 18px; font-weight: 700; letter-spacing: 1px; }
        .muted { color: #555; font-size: 12px; }
        .divider {
            border: none;
            border-top: 1px dashed #999;
            margin: 10px 0;
        }
        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin: 2px 0;
        }
        .section-title {
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            margin: 4px 0;
        }
        table { width: 100%; border-collapse: collapse; }
        th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 1px dashed #999;
            padding: 4px 0;
        }
        th.r, td.r { text-align: right; }
        th.c, td.c { text-align: center; }
        td { padding: 5px 0; vertical-align: top; font-size: 12px; }
        .item-name { font-weight: 700; }
        .item-sub { color: #666; font-size: 11px; }
        .totals .row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            padding: 2px 0;
        }
        .totals .grand {
            font-size: 15px;
            font-weight: 700;
            border-top: 1px dashed #999;
            margin-top: 5px;
            padding-top: 6px;
        }
        .badge {
            display: inline-block;
            padding: 1px 7px;
            border: 1px solid #111;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 700;
        }
        .thanks { font-size: 12px; margin-top: 4px; }
        @media print {
            body { background: #fff; padding: 0; }
            .toolbar { display: none !important; }
            .receipt { box-shadow: none; max-width: 100%; width: 80mm; padding: 6px; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()" class="btn btn-primary">🖨 Print Bill</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">← Back</a>
    </div>

    <div class="receipt">
        <div class="center">
            <div class="shop-name">SKOOLYST</div>
            <div class="muted">School Marketplace</div>
            <div class="muted">skoolyst@gmail.com</div>
            <div class="muted">+92-334-0673401</div>
        </div>

        <hr class="divider">

        <div class="center" style="font-weight:700; letter-spacing:2px; font-size:13px;">CASH MEMO / BILL</div>

        <hr class="divider">

        <div class="meta-row"><span>Bill No:</span><span>{{ $order->order_number }}</span></div>
        <div class="meta-row"><span>Date:</span><span>{{ $order->created_at->format('d-m-Y h:i A') }}</span></div>
        <div class="meta-row"><span>Customer:</span><span>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</span></div>
        <div class="meta-row"><span>Phone:</span><span>{{ $order->shipping_phone }}</span></div>
        <div class="meta-row">
            <span>Payment:</span>
            <span>{{ strtoupper(str_replace('_', ' ', $payMethod)) }} ({{ ucfirst($payStatus) }})</span>
        </div>

        <hr class="divider">

        @foreach($orders as $shopOrder)
            @if($orders->count() > 1)
                <div class="section-title center">— {{ $shopOrder->shop->name ?? 'Shop' }} —</div>
            @endif
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="c">Qty</th>
                        <th class="r">Rate</th>
                        <th class="r">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shopOrder->orderItems as $item)
                        <tr>
                            <td>
                                <div class="item-name">{{ $item->product_name }}</div>
                            </td>
                            <td class="c">{{ $item->quantity }}</td>
                            <td class="r">{{ number_format($item->unit_price, 0) }}</td>
                            <td class="r">{{ number_format($item->total_price, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if(! $loop->last)<hr class="divider">@endif
        @endforeach

        <hr class="divider">

        <div class="totals">
            <div class="row"><span>Items / Qty</span><span>{{ $orders->sum(fn($o) => $o->orderItems->count()) }} / {{ $totalQty }}</span></div>
            <div class="row"><span>Subtotal</span><span>{{ $symbol }} {{ number_format($grandSubtotal, 0) }}</span></div>
            <div class="row"><span>Shipping</span><span>{{ $symbol }} {{ number_format($grandShipping, 0) }}</span></div>
            @if($grandTax > 0)
                <div class="row"><span>Tax</span><span>{{ $symbol }} {{ number_format($grandTax, 0) }}</span></div>
            @endif
            @if($grandDiscount > 0)
                <div class="row"><span>Discount</span><span>- {{ $symbol }} {{ number_format($grandDiscount, 0) }}</span></div>
            @endif
            <div class="row grand"><span>TOTAL</span><span>{{ $symbol }} {{ number_format($grandTotal, 0) }}</span></div>
        </div>

        <hr class="divider">

        <div class="center">
            <div class="thanks">*** THANK YOU ***</div>
            <div class="muted">Please come again!</div>
            @if($orders->count() > 1)
                <div class="muted" style="margin-top:6px;">Note: {{ $orders->count() }} shops — delivered separately.</div>
            @endif
        </div>
    </div>

    <script>
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            window.addEventListener('load', function () { window.print(); });
        }
    </script>
</body>
</html>
