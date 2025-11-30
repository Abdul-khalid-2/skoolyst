<x-app-layout>
    <main class="main-content">
        <section id="orders" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Order Management</h2>
                    <p class="mb-0 text-muted">Manage and track customer orders</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard.orders.export') }}" class="btn btn-outline-primary">
                        <i class="fas fa-download me-2"></i> Export
                    </a>
                </div>
            </div>

            <!-- Order Statistics -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Orders</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['total'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Revenue</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($orderStats['revenue']) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Pending Orders</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['pending'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Delivered</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['delivered'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Order Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="">All Payments</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        @role('super-admin')
                        <div class="col-md-3">
                            <label class="form-label">Shop</label>
                            <select name="shop_id" class="form-select">
                                <option value="">All Shops</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                                        {{ $shop->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endrole
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Order #, Email, Phone..." value="{{ request('search') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i> Apply Filters
                            </button>
                            <a href="{{ route('dashboard.orders.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order Info</th>
                                    <th>Customer</th>
                                    <th>Shop</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <div>
                                            <strong class="d-block">{{ $order->order_number }}</strong>
                                            <small class="text-muted">{{ $order->orderItems->count() }} items</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $order->shipping_email }}</small>
                                            <br>
                                            <small class="text-muted">{{ $order->shipping_phone }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $order->shop->name }}</span>
                                    </td>
                                    <td>
                                        <strong>Rs. {{ number_format($order->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->status_color }} text-capitalize">
                                            {{ $order->status }}
                                        </span>
                                        @if($order->tracking_number)
                                        <br>
                                        <small class="text-muted">Tracking: {{ $order->tracking_number }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status_color }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                        <br>
                                        <small class="text-muted text-capitalize">
                                            {{ str_replace('_', ' ', $order->payment_method) }}
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $order->created_at->format('M j, Y') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('dashboard.orders.show', $order) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @role('super-admin')
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item update-status" href="#" 
                                                        data-order-id="{{ $order->id }}" data-status="confirmed">
                                                        <i class="fas fa-check me-2"></i>Confirm Order
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item update-status" href="#" 
                                                        data-order-id="{{ $order->id }}" data-status="processing">
                                                        <i class="fas fa-cog me-2"></i>Processing
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item update-status" href="#" 
                                                        data-order-id="{{ $order->id }}" data-status="shipped">
                                                        <i class="fas fa-shipping-fast me-2"></i>Mark Shipped
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item update-status" href="#" 
                                                        data-order-id="{{ $order->id }}" data-status="delivered">
                                                        <i class="fas fa-check-circle me-2"></i>Mark Delivered
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item update-payment-status" href="#" 
                                                        data-order-id="{{ $order->id }}" data-status="paid">
                                                        <i class="fas fa-money-bill me-2"></i>Mark Paid
                                                    </a>
                                                </li>
                                            </ul>
                                            @endrole
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-shopping-cart fa-2x mb-3"></i>
                                            <p>No orders found.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $orders->links() }}
                </div>
            </div>
        </section>
    </main>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                        @csrf
                        <input type="hidden" name="order_id" id="orderId">
                        <input type="hidden" name="status" id="orderStatus">
                        
                        <div class="mb-3">
                            <label class="form-label">Status Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="Add any notes about this status change..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Update Status</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status update functionality
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        const statusForm = document.getElementById('statusForm');
        const confirmBtn = document.getElementById('confirmStatusUpdate');
    
        document.querySelectorAll('.update-status').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const orderId = this.getAttribute('data-order-id');
                const status = this.getAttribute('data-status');
                
                document.getElementById('orderId').value = orderId;
                document.getElementById('orderStatus').value = status;
                
                statusModal.show();
            });
        });
    
        confirmBtn.addEventListener('click', function() {
            const formData = new FormData(statusForm);
            const orderId = formData.get('order_id');
            
            fetch(`/dashboard/orders/${orderId}/update-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: formData.get('status'),
                    notes: formData.get('notes')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusModal.hide();
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
        });
    
        // Payment status update
        document.querySelectorAll('.update-payment-status').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const orderId = this.getAttribute('data-order-id');
                const status = this.getAttribute('data-status');
                
                if (confirm('Are you sure you want to update the payment status?')) {
                    fetch(`/dashboard/orders/${orderId}/update-payment-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            payment_status: status
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
            });
        });
    
        function showToast(message, type = 'info') {
            // Implement your toast notification here
            alert(message); // Replace with your toast implementation
        }
    });
    </script>
</x-app-layout>
