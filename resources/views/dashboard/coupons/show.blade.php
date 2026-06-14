<x-app-layout>
    <main class="main-content">
        <section id="show-coupon" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">{{ $coupon->code }}</h2>
                    <p class="mb-0 text-muted">{{ $coupon->name }}</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('coupons.edit', $coupon) }}" variant="primary"><i class="fas fa-edit me-2"></i>Edit</x-button>
                    <x-button href="{{ route('coupons.index') }}" variant="secondary"><i class="fas fa-arrow-left me-2"></i>Back</x-button>
                </x-slot>
            </x-page-header>

            @if(session('success'))
                <x-alert variant="success" class="mb-4">{{ session('success') }}</x-alert>
            @endif

            <div class="row">
                <div class="col-lg-7">
                    <x-card class="mb-4">
                        <x-slot name="cardHeader"><h5 class="mb-0">Details</h5></x-slot>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Discount</dt>
                            <dd class="col-sm-8">
                                @if($coupon->discount_type->value === 'percentage')
                                    {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%
                                    @if($coupon->maximum_discount_amount)
                                        (max {{ config('shop.currency_symbol', 'Rs.') }} {{ number_format($coupon->maximum_discount_amount) }})
                                    @endif
                                @elseif($coupon->discount_type->value === 'free_shipping')
                                    Free Shipping
                                @else
                                    {{ config('shop.currency_symbol', 'Rs.') }} {{ number_format($coupon->discount_value) }}
                                @endif
                            </dd>

                            <dt class="col-sm-4">Scope</dt>
                            <dd class="col-sm-8">{{ str_replace('_', ' ', $coupon->scope->value) }}</dd>

                            <dt class="col-sm-4">Minimum Order</dt>
                            <dd class="col-sm-8">{{ $coupon->minimum_order_amount ? config('shop.currency_symbol', 'Rs.').' '.number_format($coupon->minimum_order_amount) : '—' }}</dd>

                            <dt class="col-sm-4">Usage</dt>
                            <dd class="col-sm-8">{{ $coupon->usage_count }}{{ $coupon->usage_limit ? ' / '.$coupon->usage_limit : ' (unlimited)' }}</dd>

                            <dt class="col-sm-4">Per Customer</dt>
                            <dd class="col-sm-8">{{ $coupon->usage_per_customer ?? 'Unlimited' }}</dd>

                            <dt class="col-sm-4">Validity</dt>
                            <dd class="col-sm-8">
                                {{ $coupon->valid_from?->timezone(config('shop.timezone'))->format('d M Y H:i') ?? 'Now' }}
                                →
                                {{ $coupon->valid_until?->timezone(config('shop.timezone'))->format('d M Y H:i') ?? 'No expiry' }}
                                <span class="text-muted">({{ config('shop.timezone') }})</span>
                            </dd>

                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8">
                                <x-badge :variant="$coupon->is_active ? 'success' : 'secondary'">{{ $coupon->is_active ? 'Active' : 'Inactive' }}</x-badge>
                            </dd>

                            @if($coupon->description)
                                <dt class="col-sm-4">Description</dt>
                                <dd class="col-sm-8">{{ $coupon->description }}</dd>
                            @endif
                        </dl>
                    </x-card>

                    @if($coupon->applicables->isNotEmpty())
                        <x-card class="mb-4">
                            <x-slot name="cardHeader"><h5 class="mb-0">Targets</h5></x-slot>
                            @foreach($coupon->applicables as $applicable)
                                <x-badge variant="secondary" class="me-1 mb-1">
                                    {{ class_basename($applicable->applicable_type) }}:
                                    {{ $applicable->applicable->name ?? '#'.$applicable->applicable_id }}
                                </x-badge>
                            @endforeach
                        </x-card>
                    @endif
                </div>

                <div class="col-lg-5">
                    <x-card class="mb-4" noPad>
                        <x-slot name="cardHeader"><h5 class="mb-0">Recent Usage ({{ $coupon->coupon_usage_count }})</h5></x-slot>
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr><th>Order</th><th>User</th><th class="text-end">Discount</th></tr>
                            </thead>
                            <tbody>
                                @forelse($coupon->couponUsage->take(15) as $usage)
                                    <tr>
                                        <td>{{ $usage->order->order_number ?? '#'.$usage->order_id }}</td>
                                        <td>{{ $usage->user->name ?? 'Guest' }}</td>
                                        <td class="text-end">{{ config('shop.currency_symbol', 'Rs.') }} {{ number_format($usage->discount_amount) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">Not used yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </x-card>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>
