@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== ORDER TRACKING SECTION ==================== */
    .order-tracking-section-custom {
        padding: 4rem 0;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .tracking-header-custom {
        text-align: center;
        margin-bottom: 3rem;
    }

    .tracking-title-custom {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .tracking-subtitle-custom {
        font-size: 1.125rem;
        color: #718096;
    }

    .tracking-container-custom {
        max-width: 1000px;
        margin: 0 auto;
    }

    .tracking-form-card-custom {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .form-title-custom {
        color: #2d3748;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .form-group-custom {
        margin-bottom: 1.5rem;
    }

    .form-label-custom {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #374151;
    }

    .form-input-custom {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-input-custom:focus {
        outline: none;
        border-color: #4361ee;
    }

    .form-help-custom {
        display: block;
        margin-top: 0.25rem;
        color: #6b7280;
        font-size: 0.875rem;
    }

    .track-order-btn-custom {
        width: 100%;
        padding: 1rem 2rem;
        background: #4361ee;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .track-order-btn-custom:hover {
        background: #3a56d4;
    }

    .order-results-card-custom {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .results-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .results-header-custom h3 {
        margin: 0;
        color: #2d3748;
    }

    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .badge-primary-custom {
        background: #4361ee;
        color: white;
    }

    .badge-success-custom {
        background: #10b981;
        color: white;
    }

    .badge-warning-custom {
        background: #f59e0b;
        color: white;
    }

    .badge-danger-custom {
        background: #ef4444;
        color: white;
    }

    .badge-secondary-custom {
        background: #6b7280;
        color: white;
    }

    .badge-info-custom {
        background: #06b6d4;
        color: white;
    }

    .order-summary-custom {
        margin-bottom: 2rem;
    }

    .summary-row-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .summary-row-custom:last-child {
        border-bottom: none;
    }

    .summary-label-custom {
        color: #64748b;
        font-weight: 500;
    }

    .summary-value-custom {
        color: #1e293b;
        font-weight: 600;
    }

    .tracking-timeline-custom {
        margin-bottom: 2rem;
    }

    .tracking-timeline-custom h4 {
        color: #2d3748;
        margin-bottom: 1.5rem;
    }

    .timeline-custom {
        position: relative;
    }

    .timeline-custom::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }

    .timeline-item-custom {
        display: flex;
        align-items: flex-start;
        margin-bottom: 2rem;
        position: relative;
    }

    .timeline-item-custom.completed .timeline-icon-custom {
        background: #10b981;
        color: white;
    }

    .timeline-item-custom.current .timeline-icon-custom {
        background: #4361ee;
        color: white;
        transform: scale(1.1);
    }

    .timeline-item-custom:not(.completed):not(.current) .timeline-icon-custom {
        background: #e2e8f0;
        color: #64748b;
    }

    .timeline-icon-custom {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .timeline-content-custom {
        flex: 1;
    }

    .timeline-title-custom {
        margin: 0 0 0.25rem 0;
        color: #2d3748;
        font-weight: 600;
    }

    .timeline-date-custom {
        margin: 0;
        color: #64748b;
        font-size: 0.875rem;
    }

    .order-items-preview-custom {
        margin-bottom: 2rem;
    }

    .order-items-preview-custom h4 {
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .order-item-custom {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }

    .item-image-custom {
        width: 50px;
        height: 50px;
        border-radius: 6px;
        overflow: hidden;
        margin-right: 1rem;
    }

    .item-image-custom img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-info-custom {
        flex: 1;
    }

    .item-name-custom {
        margin: 0 0 0.25rem 0;
        font-weight: 600;
        color: #2d3748;
    }

    .item-shop-custom {
        margin: 0 0 0.5rem 0;
        color: #64748b;
        font-size: 0.875rem;
    }

    .item-meta-custom {
        display: flex;
        gap: 1rem;
        font-size: 0.875rem;
        color: #64748b;
    }

    .item-total-custom {
        font-weight: 600;
        color: #2d3748;
    }

    .action-buttons-custom {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .no-order-found-custom {
        text-align: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .no-order-icon-custom {
        font-size: 4rem;
        color: #9ca3af;
        margin-bottom: 1.5rem;
    }

    .no-order-found-custom h3 {
        color: #374151;
        margin-bottom: 1rem;
    }

    .no-order-found-custom p {
        color: #6b7280;
        margin-bottom: 2rem;
    }

    .suggestions-custom {
        text-align: left;
        max-width: 400px;
        margin: 0 auto;
    }

    .suggestions-custom h5 {
        color: #374151;
        margin-bottom: 1rem;
    }

    .suggestions-custom ul {
        color: #6b7280;
        padding-left: 1.5rem;
    }

    .suggestions-custom li {
        margin-bottom: 0.5rem;
    }

    .help-section-custom {
        text-align: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .help-section-custom h3 {
        color: #2d3748;
        margin-bottom: 2rem;
    }

    .help-options-custom {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .help-option-custom {
        text-align: center;
        padding: 1.5rem;
    }

    .help-option-custom i {
        font-size: 2.5rem;
        color: #4361ee;
        margin-bottom: 1rem;
    }

    .help-option-custom h4 {
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .help-option-custom p {
        color: #6b7280;
        margin: 0;
    }

    @media (max-width: 768px) {
        .order-tracking-section-custom {
            padding: 2rem 0;
        }

        .tracking-title-custom {
            font-size: 2rem;
        }

        .tracking-form-card-custom,
        .order-results-card-custom {
            padding: 1.5rem;
            margin: 1rem;
        }

        .results-header-custom {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .action-buttons-custom {
            flex-direction: column;
        }

        .order-item-custom {
            flex-direction: column;
            align-items: flex-start;
        }

        .item-image-custom {
            margin-bottom: 1rem;
        }

        .item-total-custom {
            align-self: flex-end;
            margin-top: 0.5rem;
        }

        .help-options-custom {
            grid-template-columns: 1fr;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== ORDER TRACKING SECTION ==================== -->
<section class="order-tracking-section-custom">
    <div class="container">
        <div class="tracking-header-custom">
            <h1 class="tracking-title-custom">Track Your Order</h1>
            <p class="tracking-subtitle-custom">Enter your order details to check the current status</p>
        </div>

        <div class="tracking-container-custom">
            <!-- Tracking Form -->
            <div class="tracking-form-card-custom">
                <h3 class="form-title-custom">Find Your Order</h3>
                <form action="{{ route('website.order.track') }}" method="GET" id="trackingForm">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Order Number *</label>
                        <input type="text" class="form-input-custom" name="order_number"
                            value="{{ request('order_number') }}"
                            placeholder="e.g., ORD-20231201-ABC123" required>
                        <small class="form-help-custom">You can find your order number in the confirmation email</small>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Email Address *</label>
                        <input type="email" class="form-input-custom" name="email"
                            value="{{ request('email') }}"
                            placeholder="Enter the email used for ordering" required>
                    </div>

                    <button type="submit" class="track-order-btn-custom">
                        <i class="fas fa-search me-2"></i>
                        Track Order
                    </button>
                </form>
            </div>

            <!-- Order Results -->
            @if(isset($order) && $order)
            <div class="order-results-card-custom">
                <div class="results-header-custom">
                    <h3>Order Found</h3>
                    <span class="order-status badge-custom badge-{{ $order->status === 'delivered' ? 'success-custom' : ($order->status === 'cancelled' ? 'danger-custom' : 'primary-custom') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <!-- Order Summary -->
                <div class="order-summary-custom">
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
                        <span class="summary-label-custom">Payment Status:</span>
                        <span class="summary-value-custom badge-custom badge-{{ $order->payment_status === 'paid' ? 'success-custom' : 'warning-custom' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                <!-- Tracking Timeline -->
                <div class="tracking-timeline-custom">
                    <h4>Order Status Timeline</h4>
                    <div class="timeline-custom">
                        @php
                        $statuses = [
                        'pending' => ['icon' => 'fas fa-clock', 'color' => 'secondary-custom'],
                        'confirmed' => ['icon' => 'fas fa-check', 'color' => 'primary-custom'],
                        'processing' => ['icon' => 'fas fa-cog', 'color' => 'info-custom'],
                        'shipped' => ['icon' => 'fas fa-shipping-fast', 'color' => 'warning-custom'],
                        'delivered' => ['icon' => 'fas fa-box-open', 'color' => 'success-custom'],
                        'cancelled' => ['icon' => 'fas fa-times', 'color' => 'danger-custom']
                        ];

                        $currentStatusIndex = array_search($order->status, array_keys($statuses));
                        @endphp

                        @foreach($statuses as $status => $info)
                        @php
                        $isCompleted = array_search($status, array_keys($statuses)) <= $currentStatusIndex;
                            $isCurrent=$order->status === $status;
                            @endphp

                            <div class="timeline-item-custom {{ $isCompleted ? 'completed' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                <div class="timeline-icon-custom">
                                    <i class="{{ $info['icon'] }}"></i>
                                </div>
                                <div class="timeline-content-custom">
                                    <h5 class="timeline-title-custom">{{ ucfirst($status) }}</h5>
                                    @if($isCompleted && $order->{"{$status}_at"})
                                    <p class="timeline-date-custom">
                                        {{ $order->{"{$status}_at"}->format('M d, Y h:i A') }}
                                    </p>
                                    @elseif($isCompleted)
                                    <p class="timeline-date-custom">Completed</p>
                                    @else
                                    <p class="timeline-date-custom">Pending</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                    </div>
                </div>

                <!-- Order Items Preview -->
                <div class="order-items-preview-custom">
                    <h4>Order Items</h4>
                    <div class="items-list">
                        @foreach($order->orderItems as $item)
                        <div class="order-item-custom">
                            <div class="item-image-custom">
                                <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}">
                            </div>
                            <div class="item-info-custom">
                                <h5 class="item-name-custom">{{ $item->product_name }}</h5>
                                <p class="item-shop-custom">{{ $item->shop->name ?? 'Unknown Shop' }}</p>
                                <div class="item-meta-custom">
                                    <span class="item-quantity">Qty: {{ $item->quantity }}</span>
                                    <span class="item-price">Rs. {{ number_format($item->unit_price, 2) }}</span>
                                </div>
                            </div>
                            <div class="item-total-custom">
                                Rs. {{ number_format($item->total_price, 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons-custom">
                    <a href="{{ route('website.order.show', $order->uuid) }}" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i>
                        View Full Order Details
                    </a>
                    <a href="{{ route('website.home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>
                        Back to Home
                    </a>
                </div>
            </div>
            @elseif(request()->has('order_number') && request()->has('email'))
            <!-- No Order Found -->
            <div class="no-order-found-custom">
                <div class="no-order-icon-custom">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Order Not Found</h3>
                <p>We couldn't find an order matching the provided details. Please check your order number and email address.</p>
                <div class="suggestions-custom">
                    <h5>Suggestions:</h5>
                    <ul>
                        <li>Double-check your order number</li>
                        <li>Ensure you're using the correct email address</li>
                        <li>Check your spam folder for the order confirmation email</li>
                        <li>Contact customer support if you need assistance</li>
                    </ul>
                </div>
            </div>
            @endif
        </div>

    </div>
</section>

@endsection