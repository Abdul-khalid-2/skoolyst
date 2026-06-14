<x-app-layout>
    <main class="main-content">
        <section id="coupons" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Coupons</h2>
                    <p class="mb-0 text-muted">Manage discount coupons</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('coupons.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i> Add Coupon
                    </x-button>
                </x-slot>
            </x-page-header>

            @if(session('success'))
                <x-alert variant="success" class="mb-4">
                    {{ session('success') }}
                </x-alert>
            @endif

            <x-filter-card :action="route('coupons.index')" method="GET" class="mb-3">
                <div class="row g-2 align-items-end">
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
                        <x-button type="submit" variant="outline-primary" class="w-100">
                            <i class="fas fa-search me-1"></i> Filter
                        </x-button>
                    </div>
                </div>
            </x-filter-card>

            <x-data-table
                class="mb-0"
                :searchable="false"
                :headers="[
                    ['label' => 'Code', 'key' => 'code', 'sortable' => false],
                    ['label' => 'Discount', 'key' => 'discount', 'sortable' => false],
                    ['label' => 'Scope', 'key' => 'scope', 'sortable' => false],
                    ['label' => 'Usage', 'key' => 'usage', 'sortable' => false],
                    ['label' => 'Validity', 'key' => 'validity', 'sortable' => false],
                    ['label' => 'Status', 'key' => 'is_active', 'sortable' => false],
                    ['label' => 'Actions', 'key' => 'actions', 'sortable' => false],
                ]"
                :records="$coupons"
                emptyTitle="No coupons yet"
                emptyDescription="Create your first coupon to get started."
                emptyIcon="fa-ticket-alt"
            >
                <x-slot name="emptyActions">
                    <x-button href="{{ route('coupons.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i>Create Coupon
                    </x-button>
                </x-slot>
                <x-slot name="rows">
                    @foreach($coupons as $coupon)
                        <tr>
                            <td>
                                <strong>{{ $coupon->code }}</strong>
                                <div><small class="text-muted">{{ $coupon->name }}</small></div>
                            </td>
                            <td>
                                @if($coupon->discount_type->value === 'percentage')
                                    {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%
                                @elseif($coupon->discount_type->value === 'free_shipping')
                                    <x-badge variant="info">Free Shipping</x-badge>
                                @else
                                    {{ config('shop.currency_symbol', 'Rs.') }} {{ number_format($coupon->discount_value) }}
                                @endif
                            </td>
                            <td><x-badge variant="secondary">{{ str_replace('_', ' ', $coupon->scope->value) }}</x-badge></td>
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
                                <x-badge :variant="$coupon->is_active ? 'success' : 'secondary'">
                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                </x-badge>
                            </td>
                            <td class="text-end">
                                <x-table-action>
                                    <x-table-action-item href="{{ route('coupons.show', $coupon) }}" icon="fa-eye">
                                        View
                                    </x-table-action-item>
                                    <x-table-action-item href="{{ route('coupons.edit', $coupon) }}" icon="fa-edit">
                                        Edit
                                    </x-table-action-item>
                                    <x-table-action-item
                                        href="{{ route('coupons.toggle-status', $coupon) }}"
                                        icon="fa-power-off"
                                        method="POST"
                                    >
                                        Toggle Status
                                    </x-table-action-item>
                                    <x-table-action-item
                                        href="{{ route('coupons.destroy', $coupon) }}"
                                        icon="fa-trash"
                                        variant="danger"
                                        method="DELETE"
                                        onclick="return confirm('Delete this coupon?')"
                                    >
                                        Delete
                                    </x-table-action-item>
                                </x-table-action>
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-data-table>
        </section>
    </main>
</x-app-layout>
