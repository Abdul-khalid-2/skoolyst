<x-app-layout>
    <main class="main-content">
        <section id="orders" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Order Management</h2>
                    <p class="mb-0 text-muted">Manage and track customer orders</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('dashboard.orders.export') }}" variant="outline-primary">
                        <i class="fas fa-download me-2"></i> Export
                    </x-button>
                </x-slot>
            </x-page-header>

            @if (session('warning'))
                <x-alert variant="warning" class="mb-4">{{ session('warning') }}</x-alert>
            @endif

            @if (session('success'))
                <x-alert variant="success" class="mb-4">{{ session('success') }}</x-alert>
            @endif

            <!-- Order Statistics -->
            <div class="row mb-4">
                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Orders</div>
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
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Revenue</div>
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
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pending Orders</div>
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
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Delivered</div>
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

            <x-card class="mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-2 g-md-3 align-items-end">
                        @if (request()->filled('sort_by'))
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                        @endif
                        @if (request()->filled('sort_dir'))
                            <input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">
                        @endif
                        @if (request()->filled('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Order Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="">All Payments</option>
                                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        @role('super-admin')
                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label">Shop</label>
                                <select name="shop_id" class="form-select">
                                    <option value="">All Shops</option>
                                    @foreach ($shops as $shop)
                                        <option value="{{ $shop->id }}" {{ (string) request('shop_id') === (string) $shop->id ? 'selected' : '' }}>
                                            {{ $shop->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endrole
                        <div class="col-12 col-md-auto d-flex flex-wrap gap-2 mt-2 mt-md-0">
                            <x-button type="submit" variant="primary">
                                <i class="fas fa-filter me-2"></i> Apply
                            </x-button>
                            <x-button href="{{ route('dashboard.orders.index') }}" variant="outline-secondary">
                                Reset
                            </x-button>
                        </div>
                    </form>
                </div>
            </x-card>

            <x-data-table
                class="mb-0"
                :headers="[
                    ['label' => 'Order', 'key' => 'order_number', 'sortable' => true],
                    ['label' => 'Customer', 'key' => 'shipping_last_name', 'sortable' => true],
                    ['label' => 'Shop', 'key' => 'shop', 'sortable' => true],
                    ['label' => 'Amount', 'key' => 'total_amount', 'sortable' => true],
                    ['label' => 'Status', 'key' => 'status', 'sortable' => true],
                    ['label' => 'Payment', 'key' => 'payment_status', 'sortable' => true],
                    ['label' => 'Date', 'key' => 'created_at', 'sortable' => true],
                    ['label' => 'Actions', 'key' => 'actions', 'sortable' => false],
                ]"
                :records="$orders"
                :sortBy="request('sort_by', 'created_at')"
                :sortDir="request('sort_dir', 'desc')"
                :searchValue="request('search')"
                emptyTitle="No orders found"
                emptyDescription="Try adjusting filters or search."
                emptyIcon="fa-shopping-cart"
            >
                <x-slot name="rows">
                    @foreach ($orders as $order)
                    <tr>
                        <td>
                            <div class="table-cell-content">
                                <strong class="d-block">{{ $order->order_number }}</strong>
                                <small class="text-muted">{{ $order->order_items_count }} items</small>
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
                                {{ $order->status instanceof \BackedEnum ? str_replace('_', ' ', $order->status->value) : $order->status }}
                            </span>
                            @if ($order->tracking_number ?? null)
                                <br>
                                <small class="text-muted d-block">Tracking: {{ $order->tracking_number }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->payment_status_color }} d-inline-block mb-1">
                                {{ $order->payment_status instanceof \BackedEnum ? ucfirst(str_replace('_', ' ', $order->payment_status->value)) : ucfirst($order->payment_status) }}
                            </span>
                            <br>
                            <small class="text-muted text-capitalize d-block">
                                @php $pm = $order->payment_method; @endphp
                                {{ $pm ? str_replace('_', ' ', $pm instanceof \BackedEnum ? $pm->value : $pm) : '—' }}
                            </small>
                        </td>
                        <td>
                            <small class="text-muted d-block">{{ $order->created_at->format('M j, Y') }}</small>
                            <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
                        </td>
                        <td class="text-end">
                            <x-table-action>
                                <x-table-action-item href="{{ route('dashboard.orders.show', $order) }}" icon="fa-eye">
                                    View
                                </x-table-action-item>
                                @role('super-admin')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><span class="dropdown-header small">Order status</span></li>
                                    <li>
                                        <a href="#" class="dropdown-item update-status" data-order-id="{{ $order->id }}" data-status="confirmed">
                                            <i class="fas fa-check me-2"></i>Confirm order
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item update-status" data-order-id="{{ $order->id }}" data-status="processing">
                                            <i class="fas fa-cog me-2"></i>Processing
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item update-status" data-order-id="{{ $order->id }}" data-status="shipped">
                                            <i class="fas fa-shipping-fast me-2"></i>Mark shipped
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item update-status" data-order-id="{{ $order->id }}" data-status="delivered">
                                            <i class="fas fa-check-circle me-2"></i>Mark delivered
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a href="#" class="dropdown-item update-payment-status" data-order-id="{{ $order->id }}" data-status="paid">
                                            <i class="fas fa-money-bill me-2"></i>Mark paid
                                        </a>
                                    </li>
                                @endrole
                            </x-table-action>
                        </td>
                    </tr>
                    @endforeach
                </x-slot>
            </x-data-table>
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
                            <textarea name="notes" class="form-control" rows="3" placeholder="Add any notes about this status change..."></textarea>
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

    @push('css')
        <style>
            .table-cell-content {
                max-width: 14rem;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        </style>
    @endpush

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ordersApiBase = @js(url('/dashboard/orders'));
                const statusModalEl = document.getElementById('statusModal');
                const statusModal = statusModalEl ? new bootstrap.Modal(statusModalEl) : null;
                const statusForm = document.getElementById('statusForm');
                const confirmBtn = document.getElementById('confirmStatusUpdate');

                document.querySelectorAll('.update-status').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        document.getElementById('orderId').value = this.getAttribute('data-order-id');
                        document.getElementById('orderStatus').value = this.getAttribute('data-status');
                        if (statusModal) statusModal.show();
                    });
                });

                if (confirmBtn && statusForm) {
                    confirmBtn.addEventListener('click', function() {
                        const formData = new FormData(statusForm);
                        const orderId = formData.get('order_id');
                        fetch(ordersApiBase + '/' + orderId + '/update-status', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: formData.get('status'),
                                notes: formData.get('notes')
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    if (statusModal) statusModal.hide();
                                    showToast('Order status updated successfully!', 'success');
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    showToast(data.message || 'Update failed', 'danger');
                                }
                            })
                            .catch(() => showToast('An error occurred while updating status', 'danger'));
                    });
                }

                document.querySelectorAll('.update-payment-status').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const orderId = this.getAttribute('data-order-id');
                        const status = this.getAttribute('data-status');
                        if (!confirm('Are you sure you want to update the payment status?')) return;
                        fetch(ordersApiBase + '/' + orderId + '/update-payment-status', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ payment_status: status })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showToast('Payment status updated successfully!', 'success');
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    showToast(data.message || 'Update failed', 'danger');
                                }
                            })
                            .catch(() => showToast('An error occurred while updating payment status', 'danger'));
                    });
                });

                function showToast(message, type) {
                    const toast = document.createElement('div');
                    const bsType = type === 'error' ? 'danger' : type;
                    toast.className = 'toast align-items-center text-bg-' + bsType + ' border-0 position-fixed bottom-0 end-0 m-3';
                    toast.setAttribute('role', 'alert');
                    toast.style.zIndex = '1060';
                    toast.innerHTML =
                        '<div class="d-flex">' +
                        '<div class="toast-body">' + message + '</div>' +
                        '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
                        '</div>';
                    document.body.appendChild(toast);
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.show();
                    toast.addEventListener('hidden.bs.toast', function() {
                        document.body.removeChild(toast);
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
