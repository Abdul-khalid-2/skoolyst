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
                <div class="col-6 col-md-3 mb-3">
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

                <div class="col-6 col-md-3 mb-3">
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

                <div class="col-6 col-md-3 mb-3">
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

                <div class="col-6 col-md-3 mb-3">
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
                    <form method="GET" class="row g-2 g-md-3">
                        <div class="col-12 col-md-6 col-lg-3">
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
                        <div class="col-12 col-md-6 col-lg-3">
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
                        <div class="col-12 col-md-6 col-lg-3">
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
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Order #, Email, Phone..." value="{{ request('search') }}">
                        </div>
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-2"></i> Apply Filters
                                </button>
                                <a href="{{ route('dashboard.orders.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="table-wrapper">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="min-width-150">Order Info</th>
                                    <th class="min-width-150">Customer</th>
                                    <th class="min-width-100">Shop</th>
                                    <th class="min-width-100">Amount</th>
                                    <th class="min-width-120">Status</th>
                                    <th class="min-width-120">Payment</th>
                                    <th class="min-width-120">Date</th>
                                    <th class="min-width-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <div class="table-cell-content">
                                            <strong class="d-block">{{ $order->order_number }}</strong>
                                            <small class="text-muted">{{ $order->orderItems->count() }} items</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-cell-content">
                                            <strong class="d-block">{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong>
                                            <small class="text-muted d-block">{{ $order->shipping_email }}</small>
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
                                        <span class="badge bg-{{ $order->status_color }} text-capitalize d-inline-block mb-1">
                                            {{ $order->status }}
                                        </span>
                                        @if($order->tracking_number)
                                        <br>
                                        <small class="text-muted d-block">Tracking: {{ $order->tracking_number }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status_color }} d-inline-block mb-1">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                        <br>
                                        <small class="text-muted text-capitalize d-block">
                                            {{ str_replace('_', ' ', $order->payment_method) }}
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted d-block">{{ $order->created_at->format('M j, Y') }}</small>
                                        <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('dashboard.orders.show', $order) }}"
                                                class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @role('super-admin')
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-cog"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
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
                                                </div>
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
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
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

    <style>
        /* Mobile-first responsive table styles */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
        }

        /* Table wrapper for better scrolling */
        .table-wrapper {
            min-width: 768px;
            /* Minimum width before scrolling kicks in */
        }

        /* Table styles */
        .table {
            width: 100%;
            margin-bottom: 0;
            font-size: 0.875rem;
        }

        /* Column width classes */
        .min-width-100 {
            min-width: 100px;
        }

        .min-width-120 {
            min-width: 120px;
        }

        .min-width-150 {
            min-width: 150px;
        }

        /* Table cell content */
        .table-cell-content {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Responsive table adjustments */
        @media (max-width: 768px) {

            /* Make dropdowns full width on mobile */
            .dropdown-menu {
                position: fixed !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                width: 90% !important;
                max-width: 300px !important;
            }

            /* Adjust table font size on mobile */
            .table {
                font-size: 0.8125rem;
            }

            /* Make badges smaller */
            .badge {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }

            /* Adjust button sizes */
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.8125rem;
            }

            /* Scroll indicator for mobile */
            .table-responsive::after {
                content: '← Scroll →';
                display: block;
                text-align: center;
                font-size: 0.75rem;
                color: #6c757d;
                padding: 0.5rem;
                background: linear-gradient(to right, transparent, #f8f9fa, transparent);
            }
        }

        @media (max-width: 576px) {

            /* Hide less important columns on very small screens */
            .table thead th:nth-child(3),
            /* Shop column */
            .table tbody td:nth-child(3) {
                display: none;
            }

            /* Adjust column widths */
            .min-width-150 {
                min-width: 120px;
            }

            .min-width-120 {
                min-width: 100px;
            }

            .min-width-100 {
                min-width: 80px;
            }
        }

        /* Table row hover effect */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, .02);
        }

        /* Action buttons styling */
        .btn-group {
            display: flex;
            flex-wrap: nowrap;
        }

        /* Pagination responsive */
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        /* Filters responsive spacing */
        .g-2.g-md-3 {
            row-gap: 0.5rem;
        }

        @media (min-width: 768px) {
            .g-2.g-md-3 {
                row-gap: 1rem;
            }
        }
    </style>

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
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.style.zIndex = '1060';

            toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

            document.body.appendChild(toast);

            // Initialize and show Bootstrap toast
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            // Remove toast after it hides
            toast.addEventListener('hidden.bs.toast', function() {
                document.body.removeChild(toast);
            });
        }

        // Add touch scrolling enhancement for mobile
        const tableContainer = document.querySelector('.table-responsive');
        if (tableContainer) {
            let isDown = false;
            let startX;
            let scrollLeft;

            tableContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                tableContainer.classList.add('active');
                startX = e.pageX - tableContainer.offsetLeft;
                scrollLeft = tableContainer.scrollLeft;
            });

            tableContainer.addEventListener('mouseleave', () => {
                isDown = false;
                tableContainer.classList.remove('active');
            });

            tableContainer.addEventListener('mouseup', () => {
                isDown = false;
                tableContainer.classList.remove('active');
            });

            tableContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - tableContainer.offsetLeft;
                const walk = (x - startX) * 2;
                tableContainer.scrollLeft = scrollLeft - walk;
            });
        }
    });
    </script>
</x-app-layout>
