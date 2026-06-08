<x-app-layout>
    <main class="main-content">
        <section id="coupons" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Coupons</h2>
                    <p class="mb-0 text-muted">Manage discount coupons</p>
                </div>
                <a href="{{ route('coupons.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Add Coupon
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" class="row g-2 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Code or name">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1">Status</label>
                            <select name="status" class="form-control">
                                <option value="">All</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" style="overflow-x:auto;">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Discount</th>
                                    <th>Scope</th>
                                    <th>Usage</th>
                                    <th>Validity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td>
                                            <strong>{{ $coupon->code }}</strong>
                                            <div><small class="text-muted">{{ $coupon->name }}</small></div>
                                        </td>
                                        <td>
                                            @if($coupon->discount_type->value === 'percentage')
                                                {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%
                                            @elseif($coupon->discount_type->value === 'free_shipping')
                                                <span class="badge bg-info">Free Shipping</span>
                                            @else
                                                {{ config('shop.currency_symbol', 'Rs.') }} {{ number_format($coupon->discount_value) }}
                                            @endif
                                        </td>
                                        <td><span class="badge bg-light text-dark">{{ str_replace('_', ' ', $coupon->scope->value) }}</span></td>
                                        <td>
                                            {{ $coupon->usage_count }}{{ $coupon->usage_limit ? ' / '.$coupon->usage_limit : '' }}
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $coupon->valid_from?->timezone(config('shop.timezone'))->format('d M Y') ?? '—' }}
                                                →
                                                {{ $coupon->valid_until?->timezone(config('shop.timezone'))->format('d M Y') ?? '∞' }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('coupons.show', $coupon) }}" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('coupons.toggle-status', $coupon) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Toggle status">
                                                        <i class="fas fa-power-off"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Delete this coupon?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-ticket-alt fa-2x mb-3"></i>
                                                <p>No coupons yet. Create your first coupon.</p>
                                                <a href="{{ route('coupons.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Create Coupon</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($coupons->hasPages())
                        <div class="mt-4">{{ $coupons->links() }}</div>
                    @endif
                </div>
            </div>
        </section>
    </main>
</x-app-layout>
