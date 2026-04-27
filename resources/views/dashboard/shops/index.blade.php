<x-app-layout>
    <main class="main-content">
        <section id="shops" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Shops</h2>
                    <p class="mb-0 text-muted">Manage your shops and associations</p>
                </x-slot>
                @role('super-admin')
                    <x-slot name="actions">
                        <x-button href="{{ route('shops.create') }}" variant="primary">
                            <i class="fas fa-plus me-2"></i> Create Shop
                        </x-button>
                    </x-slot>
                @endrole
            </x-page-header>

            @if (session('success'))
                <x-alert variant="success" class="mb-4">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if (session('error'))
                <x-alert variant="error" class="mb-4">
                    {{ session('error') }}
                </x-alert>
            @endif

            <x-data-table
                class="mb-0"
                :headers="[
                    ['label' => 'Shop Info', 'key' => 'name', 'sortable' => true],
                    ['label' => 'Type', 'key' => 'shop_type', 'sortable' => true],
                    ['label' => 'Location', 'key' => 'city', 'sortable' => true],
                    ['label' => 'Status', 'key' => 'is_active', 'sortable' => true],
                    ['label' => 'Associations', 'key' => 'associations', 'sortable' => true],
                    ['label' => 'Rating', 'key' => 'rating', 'sortable' => true],
                    ['label' => 'Created', 'key' => 'created_at', 'sortable' => true],
                    ['label' => 'Actions', 'key' => 'actions', 'sortable' => false],
                ]"
                :records="$shops"
                :sortBy="request('sort_by')"
                :sortDir="request('sort_dir', 'desc')"
                :searchValue="request('search')"
                emptyTitle="No shops found"
                emptyDescription="Create your first shop to get started."
                emptyIcon="fa-store"
            >
                @role('super-admin')
                    <x-slot name="emptyActions">
                        <x-button href="{{ route('shops.create') }}" variant="primary">
                            <i class="fas fa-plus me-2"></i>Create Shop
                        </x-button>
                    </x-slot>
                @endrole
                <x-slot name="rows">
                    @foreach ($shops as $shop)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($shop->logo_url)
                                        <img
                                            src="{{ asset('website/' . $shop->logo_url) }}"
                                            alt="{{ $shop->name }}"
                                            class="rounded me-3"
                                            width="40"
                                            height="40"
                                        >
                                    @else
                                        <div
                                            class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px;"
                                        >
                                            <i class="fas fa-store text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $shop->name }}</strong>
                                        @if ($shop->email)
                                            <br><small class="text-muted">{{ $shop->email }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <x-badge variant="info" class="text-capitalize">
                                    {{ $shop->shop_type_label }}
                                </x-badge>
                            </td>
                            <td>
                                @if ($shop->city)
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $shop->city }}, {{ $shop->state }}
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <x-badge :variant="$shop->is_active ? 'success' : 'secondary'" class="me-1">
                                    {{ $shop->is_active ? 'Active' : 'Inactive' }}
                                </x-badge>
                                @if ($shop->is_verified)
                                    <x-badge variant="primary">Verified</x-badge>
                                @endif
                            </td>
                            <td>
                                <x-badge variant="warning">
                                    {{ $shop->school_associations_count }} Schools
                                </x-badge>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span>{{ number_format($shop->rating, 1) }}</span>
                                    <small class="text-muted ms-1">({{ $shop->total_reviews }})</small>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">{{ $shop->created_at->format('M j, Y') }}</small>
                            </td>
                            <td class="text-end">
                                <x-table-action>
                                    <x-table-action-item href="{{ route('shops.show', $shop) }}" icon="fa-eye">
                                        View
                                    </x-table-action-item>
                                    <x-table-action-item href="{{ route('shops.edit', $shop) }}" icon="fa-edit">
                                        Edit
                                    </x-table-action-item>
                                    <x-table-action-item
                                        href="{{ route('shops.destroy', $shop) }}"
                                        icon="fa-trash"
                                        variant="danger"
                                        method="DELETE"
                                        onclick="return confirm('Are you sure you want to delete this shop?')"
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
