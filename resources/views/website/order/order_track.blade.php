@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/order_track.css') }}">
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
                    @php $statusVal = $order->status instanceof \BackedEnum ? $order->status->value : $order->status; @endphp
                    <span class="order-status badge-custom badge-{{ $statusVal === 'delivered' ? 'success-custom' : ($statusVal === 'cancelled' ? 'danger-custom' : 'primary-custom') }}">
                        {{ ucfirst($statusVal) }}
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
                        @php $payStatusVal = $order->payment_status instanceof \BackedEnum ? $order->payment_status->value : $order->payment_status; @endphp
                        <span class="summary-value-custom badge-custom badge-{{ $payStatusVal === 'paid' ? 'success-custom' : 'warning-custom' }}">
                            {{ ucfirst($payStatusVal) }}
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

                        $currentStatusIndex = array_search($order->status instanceof \BackedEnum ? $order->status->value : $order->status, array_keys($statuses));
                        @endphp

                        @foreach($statuses as $status => $info)
                        @php
                        $isCompleted = array_search($status, array_keys($statuses)) <= $currentStatusIndex;
                            $isCurrent = ($order->status instanceof \BackedEnum ? $order->status->value : $order->status) === $status;
                            @endphp

                            <div class="timeline-item-custom {{ $isCompleted ? 'completed' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                <div class="timeline-icon-custom">
                                    <i class="{{ $info['icon'] }}"></i>
                                </div>
                                <div class="timeline-content-custom">
                                    <h5 class="timeline-title-custom">{{ ucfirst($status instanceof \BackedEnum ? $status->value : $status) }}</h5>
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