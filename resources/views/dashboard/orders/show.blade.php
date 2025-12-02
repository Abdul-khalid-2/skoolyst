<x-app-layout>
    <main class="main-content">
        <section id="order-details" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Order Details</h2>
                    <p class="mb-0 text-muted">Order #{{ $order->order_number }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard.orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Orders
                    </a>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i> Print
                    </button>
                </div>
            </div>

            <div class="row">
                <!-- Order Information -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Order Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product_image)
                                                    <img src="{{ asset($item->product_image) }}" 
                                                         alt="{{ $item->product_name }}" 
                                                         class="rounded me-3" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                                        <small class="text-muted">SKU: {{ $item->product_sku ?? 'N/A' }}</small>
                                                        @if($item->category_name)
                                                        <br>
                                                        <small class="text-muted">Category: {{ $item->category_name }}</small>
                                                        @endif
                                                        <br>
                                                        <small class="text-muted">Shop: {{ $item->shop->name ?? 'Unknown Shop' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rs. {{ number_format($item->unit_price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td><strong>Rs. {{ number_format($item->total_price, 2) }}</strong></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Order Timeline</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item {{ $order->created_at ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Order Placed</h6>
                                        <p class="text-muted mb-0">
                                            @if($order->created_at)
                                                {{ $order->created_at->format('M j, Y g:i A') }}
                                            @else
                                                Pending
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="timeline-item {{ $order->confirmed_at ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Order Confirmed</h6>
                                        <p class="text-muted mb-0">
                                            @if($order->confirmed_at)
                                                {{ $order->confirmed_at->format('M j, Y g:i A') }}
                                            @else
                                                Pending
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="timeline-item {{ $order->processing_at ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Processing</h6>
                                        <p class="text-muted mb-0">
                                            @if($order->processing_at)
                                                {{ $order->processing_at->format('M j, Y g:i A') }}
                                            @else
                                                Pending
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="timeline-item {{ $order->shipped_at ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Shipped</h6>
                                        <p class="text-muted mb-0">
                                            @if($order->shipped_at)
                                                {{ $order->shipped_at->format('M j, Y g:i A') }}
                                            @else
                                                Pending
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="timeline-item {{ $order->delivered_at ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Delivered</h6>
                                        <p class="text-muted mb-0">
                                            @if($order->delivered_at)
                                                {{ $order->delivered_at->format('M j, Y g:i A') }}
                                            @else
                                                Pending
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Notes -->
                    @if($order->admin_notes)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Admin Notes</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $order->admin_notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <!-- Order Status Card -->
                    @role('shop-owner')
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Order Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <span class="badge bg-{{ $order->status_color }} fs-6 text-capitalize">
                                        {{ $order->status }}
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Payment Status:</strong>
                                    <span class="badge bg-{{ $order->payment_status_color }} ms-2 text-capitalize">
                                        {{ $order->payment_status }}
                                    </span>
                                </div>
                                
                                @if($order->paid_at)
                                <div class="mb-3">
                                    <strong>Paid At:</strong>
                                    <br>
                                    <span class="text-muted">{{ $order->paid_at->format('M j, Y g:i A') }}</span>
                                </div>
                                @endif

                                <!-- Status Update Form -->
                                <form id="statusUpdateForm" class="mb-3">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Update Status</label>
                                        <select name="status" class="form-select" id="statusSelect">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            {{-- 
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option> --}}
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                                </form>

                                <!-- Payment Status Update Form -->
                                <form id="paymentStatusForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Update Payment Status</label>
                                        <select name="payment_status" class="form-select" id="paymentStatusSelect">
                                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                            {{-- <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                            <option value="partially_refunded" {{ $order->payment_status == 'partially_refunded' ? 'selected' : '' }}>Partially Refunded</option> --}}
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary w-100">Update Payment</button>
                                </form>
                            </div>
                        </div>
                    @endrole

                    @role('super-admin')
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Order Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <span class="badge bg-{{ $order->status_color }} fs-6 text-capitalize">
                                        {{ $order->status }}
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Payment Status:</strong>
                                    <span class="badge bg-{{ $order->payment_status_color }} ms-2 text-capitalize">
                                        {{ $order->payment_status }}
                                    </span>
                                </div>
                                
                                @if($order->paid_at)
                                <div class="mb-3">
                                    <strong>Paid At:</strong>
                                    <br>
                                    <span class="text-muted">{{ $order->paid_at->format('M j, Y g:i A') }}</span>
                                </div>
                                @endif

                                <!-- Status Update Form -->
                                <form id="statusUpdateForm" class="mb-3">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Update Status</label>
                                        <select name="status" class="form-select" id="statusSelect">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                                </form>

                                <!-- Payment Status Update Form -->
                                <form id="paymentStatusForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Update Payment Status</label>
                                        <select name="payment_status" class="form-select" id="paymentStatusSelect">
                                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                            <option value="partially_refunded" {{ $order->payment_status == 'partially_refunded' ? 'selected' : '' }}>Partially Refunded</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary w-100">Update Payment</button>
                                </form>
                            </div>
                        </div>
                    @endrole

                    <!-- Customer Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Customer Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Name:</strong>
                                <p class="mb-1">{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Email:</strong>
                                <p class="mb-1">{{ $order->shipping_email }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Phone:</strong>
                                <p class="mb-1">{{ $order->shipping_phone }}</p>
                            </div>
                            
                            @if($order->user)
                            <div class="mb-3">
                                <strong>Account:</strong>
                                <p class="mb-1">
                                    <span class="badge bg-info">Registered User</span>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    @role('super-admin')
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Shipping Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Address:</strong>
                                    <p class="mb-1">{{ $order->shipping_address }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>City:</strong>
                                    <p class="mb-1">{{ $order->shipping_city }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>State:</strong>
                                    <p class="mb-1">{{ $order->shipping_state }}</p>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>ZIP Code:</strong>
                                    <p class="mb-1">{{ $order->shipping_zip_code }}</p>
                                </div>
                                
                                @if($order->delivery_instructions)
                                <div class="mb-3">
                                    <strong>Delivery Instructions:</strong>
                                    <p class="mb-1">{{ $order->delivery_instructions }}</p>
                                </div>
                                @endif

                                <!-- Shipping Update Form -->
                                <form id="shippingUpdateForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Tracking Number</label>
                                        <input type="text" name="tracking_number" class="form-control" 
                                            value="{{ $order->tracking_number }}" placeholder="Enter tracking number">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Shipping Carrier</label>
                                        <input type="text" name="shipping_carrier" class="form-control" 
                                            value="{{ $order->shipping_carrier }}" placeholder="e.g., DHL, FedEx">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Estimated Delivery</label>
                                        <input type="date" name="estimated_delivery_date" class="form-control" 
                                            value="{{ $order->estimated_delivery_date ? $order->estimated_delivery_date->format('Y-m-d') : '' }}">
                                    </div>
                                    <button type="submit" class="btn btn-outline-secondary w-100">Update Shipping</button>
                                </form>
                            </div>
                        </div>
                    @endrole
                    <!-- Order Summary -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>Rs. {{ number_format($order->shipping_cost, 2) }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>Rs. {{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            
                            @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span class="text-danger">- Rs. {{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            
                            @if($order->coupon)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Coupon:</span>
                                <span class="text-info">{{ $order->coupon->code }}</span>
                            </div>
                            @endif
                            
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Total:</strong>
                                <strong>Rs. {{ number_format($order->total_amount, 2) }}</strong>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Payment Method:</span>
                                <span class="text-capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <span>Order Date:</span>
                                <span>{{ $order->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status update
        document.getElementById('statusUpdateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            updateStatus();
        });

        // Payment status update
        document.getElementById('paymentStatusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            updatePaymentStatus();
        });

        // Shipping info update
        document.getElementById('shippingUpdateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            updateShippingInfo();
        });

        function updateStatus() {
            const formData = new FormData(document.getElementById('statusUpdateForm'));
            
            fetch('{{ route("dashboard.orders.update-status", $order) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: formData.get('status'),
                    notes: '' // You can add a notes field if needed
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Order status updated successfully!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while updating status', 'error');
            });
        }

        function updatePaymentStatus() {
            const formData = new FormData(document.getElementById('paymentStatusForm'));
            
            fetch('{{ route("dashboard.orders.update-payment-status", $order) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    payment_status: formData.get('payment_status')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Payment status updated successfully!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while updating payment status', 'error');
            });
        }

        function updateShippingInfo() {
            const formData = new FormData(document.getElementById('shippingUpdateForm'));
            
            fetch('{{ route("dashboard.orders.update-shipping", $order) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    tracking_number: formData.get('tracking_number'),
                    shipping_carrier: formData.get('shipping_carrier'),
                    estimated_delivery_date: formData.get('estimated_delivery_date')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Shipping information updated successfully!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while updating shipping information', 'error');
            });
        }

        function showToast(message, type = 'info') {
            // Implement your toast notification here
            alert(message); // Replace with your toast implementation
        }
    });
    </script>

    <style>
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -2rem;
        top: 0;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: #dee2e6;
        border: 3px solid white;
    }
    
    .timeline-item.completed .timeline-marker {
        background: #28a745;
    }
    
    .timeline-content h6 {
        margin-bottom: 0.25rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    </style>
</x-app-layout>