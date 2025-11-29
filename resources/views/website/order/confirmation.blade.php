@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== ORDER CONFIRMATION SECTION ==================== */
    .order-confirmation-section-custom {
        padding: 4rem 0;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .confirmation-container-custom {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        padding: 3rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .confirmation-icon-custom {
        text-align: center;
        margin-bottom: 2rem;
    }

    .confirmation-icon-custom i {
        font-size: 4rem;
        color: #38b000;
    }

    .confirmation-message-custom {
        text-align: center;
        margin-bottom: 3rem;
    }

    .confirmation-title-custom {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .confirmation-subtitle-custom {
        font-size: 1.25rem;
        color: #718096;
        margin-bottom: 1rem;
    }

    .confirmation-text-custom {
        color: #4a5568;
        line-height: 1.6;
    }

    .order-summary-card-custom,
    .order-items-card-custom,
    .shipping-info-card-custom {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .summary-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .summary-header-custom h3 {
        margin: 0;
        color: #2d3748;
    }

    .card-title-custom {
        color: #2d3748;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .summary-row-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .summary-row-custom:last-child {
        border-bottom: none;
    }

    .summary-label-custom {
        color: #718096;
        font-weight: 500;
    }

    .summary-value-custom {
        color: #2d3748;
        font-weight: 600;
    }

    .badge-custom {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-success-custom {
        background: #d4edda;
        color: #155724;
    }

    .badge-warning-custom {
        background: #fff3cd;
        color: #856404;
    }

    .badge-primary-custom {
        background: #cfe2ff;
        color: #084298;
    }

    .order-item-custom {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .order-item-custom:last-child {
        border-bottom: none;
    }

    .item-image-custom {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        margin-right: 1rem;
    }

    .item-image-custom img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-details-custom {
        flex: 1;
    }

    .item-name-custom {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }

    .item-shop-custom {
        color: #718096;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .item-meta-custom {
        display: flex;
        gap: 1rem;
        font-size: 0.875rem;
        color: #4a5568;
    }

    .item-total-custom {
        font-weight: 600;
        color: #2d3748;
    }

    .shipping-details-custom p {
        margin-bottom: 0.5rem;
        color: #4a5568;
    }

    .delivery-instructions-custom {
        margin-top: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 6px;
        border-left: 4px solid #4361ee;
    }

    .confirmation-actions-custom {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin: 2rem 0;
        flex-wrap: wrap;
    }

    .help-section-custom {
        text-align: center;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-top: 2rem;
    }

    .help-section-custom h4 {
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .contact-info-custom {
        margin-top: 1rem;
    }

    .contact-info-custom p {
        margin-bottom: 0.5rem;
        color: #4a5568;
    }

    @media (max-width: 768px) {
        .order-confirmation-section-custom {
            padding: 2rem 0;
        }

        .confirmation-container-custom {
            padding: 2rem 1rem;
            margin: 1rem;
        }
        
        .confirmation-title-custom {
            font-size: 2rem;
        }
        
        .confirmation-actions-custom {
            flex-direction: column;
        }
        
        .confirmation-actions-custom .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .order-item-custom {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .item-image-custom {
            margin-bottom: 1rem;
            margin-right: 0;
        }
        
        .item-total-custom {
            align-self: flex-end;
            margin-top: 0.5rem;
        }

        .summary-header-custom {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
    }
</style>
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
                    <span class="order-status badge-custom badge-success-custom">{{ ucfirst($order->status) }}</span>
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
                        <span class="summary-value-custom">Rs. {{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="summary-row-custom">
                        <span class="summary-label-custom">Payment Method:</span>
                        <span class="summary-value-custom">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                    <div class="summary-row-custom">
                        <span class="summary-label-custom">Payment Status:</span>
                        <span class="summary-value-custom badge-custom badge-{{ $order->payment_status === 'paid' ? 'success-custom' : 'warning-custom' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="order-items-card-custom">
                <h3 class="card-title-custom">Order Items</h3>
                <div class="order-items-list">
                    @foreach($order->orderItems as $item)
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
                <a href="{{ route('website.order.show', $order->uuid) }}" class="btn btn-primary">
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
                    <p><i class="fas fa-envelope me-2"></i> support@schoolmart.com</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection