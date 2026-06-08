@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/order_confirmation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== ORDER CONFIRMATION SECTION ==================== -->
<section class="order-confirmation-section-custom">
    <div class="container">
        <div class="confirmation-container-custom">
            <!-- Success Icon -->
            <div class="confirmation-icon-custom">
                <i class="fas fa-check-circle"></i>
            </div>

            <!-- Confirmation Message -->
            <div class="confirmation-message-custom">
                <h1 class="confirmation-title-custom">Order Confirmed!</h1>
                <p class="confirmation-subtitle-custom">Thank you for your purchase</p>
                <p class="confirmation-text-custom">
                    Your order has been successfully placed and is being processed. 
                    We've sent a confirmation email to <strong>{{ $order->shipping_email }}</strong>.
                </p>
            </div>

            <!-- Order Summary -->
            <div class="order-summary-card-custom">
                <div class="summary-header-custom">
                    <h3>Order Details</h3>
                    <span class="order-status badge-custom badge-success-custom">{{ ucfirst($order->status instanceof \BackedEnum ? $order->status->value : $order->status) }}</span>
                </div>
                
                <div class="summary-body">
                    <div class="summary-row-custom">
                        <span class="summary-label-custom">Order Number:</span>
                        <span class="summary-value-custom">{{ $order->order_number }}</span>
                    </div>
                    <div class="summary-row-custom">
                        <span class="summary-label-custom">Order Date:</span>
                        <span class="summary-value-custom">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="summary-row-custom">
                        <span class="summary-label-custom">Total Amount:</span>
                        <span class="summary-value-custom">Rs. {{ number_format($allOrders->sum('total_amount'), 2) }}</span>
                    </div>
                    <div class="summary-row-custom">
                        <span class="summary-label-custom">Payment Method:</span>
                        <span class="summary-value-custom">{{ ucfirst(str_replace('_', ' ', $order->payment_method instanceof \BackedEnum ? $order->payment_method->value : $order->payment_method)) }}</span>
                    </div>
                    <div class="summary-row-custom">
                        <span class="summary-label-custom">Payment Status:</span>
                        <span class="summary-value-custom badge-custom badge-{{ ($order->payment_status instanceof \BackedEnum ? $order->payment_status->value : $order->payment_status) === 'paid' ? 'success-custom' : 'warning-custom' }}">
                            {{ ucfirst($order->payment_status instanceof \BackedEnum ? $order->payment_status->value : $order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="order-items-card-custom">
                <h3 class="card-title-custom">Order Items</h3>

                @foreach($allOrders as $shopOrder)
                    @if($allOrders->count() > 1)
                        <p style="font-weight:600; margin:12px 0 6px; font-size:14px; color:#374151;">
                            🏪 {{ $shopOrder->shop->name ?? 'Shop' }}
                            <span style="font-weight:400; font-size:12px; color:#6b7280;">
                                ({{ $shopOrder->order_number }})
                            </span>
                        </p>
                    @endif

                    <div class="order-items-list">
                        @foreach($shopOrder->orderItems as $item)
                            <div class="order-item-custom">
                                <div class="item-image-custom">
                                    <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}">
                                </div>
                                <div class="item-details-custom">
                                    <h4 class="item-name-custom">{{ $item->product_name }}</h4>
                                    <p class="item-shop-custom">{{ $item->shop->name ?? 'Unknown Shop' }}</p>
                                    <div class="item-meta-custom">
                                        <span class="item-quantity">Qty: {{ $item->quantity }}</span>
                                        <span class="item-price">Rs. {{ number_format($item->unit_price, 2) }} each</span>
                                    </div>
                                </div>
                                <div class="item-total-custom">
                                    Rs. {{ number_format($item->total_price, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

                @if($allOrders->count() > 1)
                    <div style="margin-top:12px; padding:10px 14px; background:#eff6ff; border-radius:8px; font-size:13px; color:#1d4ed8;">
                        ℹ️ Items from {{ $allOrders->count() }} shops — each shop will fulfill and deliver their order separately.
                    </div>
                @endif
            </div>

            <!-- Shipping Information -->
            <div class="shipping-info-card-custom">
                <h3 class="card-title-custom">Shipping Information</h3>
                <div class="shipping-details-custom">
                    <p><strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong></p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip_code }}</p>
                    <p>{{ $order->shipping_country }}</p>
                    <p><i class="fas fa-phone me-2"></i>{{ $order->shipping_phone }}</p>
                    <p><i class="fas fa-envelope me-2"></i>{{ $order->shipping_email }}</p>
                    @if($order->delivery_instructions)
                        <div class="delivery-instructions-custom">
                            <strong>Delivery Instructions:</strong>
                            <p>{{ $order->delivery_instructions }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="confirmation-actions-custom">
                <a href="{{ route('website.order.invoice', $order->uuid) }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-file-invoice me-2"></i>
                    Generate Bill
                </a>
                <a href="{{ route('website.order.show', $order->uuid) }}" class="btn btn-outline-primary">
                    <i class="fas fa-eye me-2"></i>
                    View Order Details
                </a>
                <a href="{{ route('website.stationary.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-shopping-bag me-2"></i>
                    Continue Shopping
                </a>
                <a href="{{ route('website.home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-2"></i>
                    Back to Home
                </a>
            </div>

            <!-- Help Section -->
            <div class="help-section-custom">
                <h4>Need Help?</h4>
                <p>If you have any questions about your order, please contact our customer support.</p>
                <div class="contact-info-custom">
                    <p><i class="fas fa-phone me-2"></i> +92-334-0673401</p>
                    <p><i class="fas fa-envelope me-2"></i> skoolyst@gmail.com</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection