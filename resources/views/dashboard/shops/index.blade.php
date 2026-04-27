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

            @if(session('success'))
                <x-alert variant="success" class="mb-4">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if(session('error'))
                <x-alert variant="error" class="mb-4">
                    {{ session('error') }}
                </x-alert>
            @endif

            <x-card>
                <div class="card-body">
                   <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead>
                                <tr>
                                    <th>Shop Info</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Associations</th>
                                    <th>Rating</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shops as $shop)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($shop->logo_url)
                                            <img src="{{ asset('website/'. $shop->logo_url) }}" alt="{{ $shop->name }}"
                                                 class="rounded me-3" width="40" height="40">
                                            @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-store text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <strong>{{ $shop->name }}</strong>
                                                @if($shop->email)
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
                                        @if($shop->city)
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
                                        @if($shop->is_verified)
                                        <x-badge variant="primary">Verified</x-badge>
                                        @endif
                                    </td>
                                    <td>
                                        <x-badge variant="warning">
                                            {{ $shop->schoolAssociations->count() }} Schools
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
                                    <td>
                                        <div class="btn-group">
                                            <x-button href="{{ route('shops.show', $shop) }}"
                                               variant="outline-primary" class="btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </x-button>
                                            <x-button href="{{ route('shops.edit', $shop) }}"
                                               variant="outline-secondary" class="btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </x-button>
                                            <form action="{{ route('shops.destroy', $shop) }}" method="POST"
                                                  class="d-inline" onsubmit="return confirm('Are you sure you want to delete this shop?')">
                                                @csrf
                                                @method('DELETE')
                                                <x-button type="submit" variant="outline-danger" class="btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </x-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <x-empty-state
                                            title="No shops found"
                                            description="Create your first shop to get started."
                                            icon="fa-store"
                                            class="py-4"
                                        >
                                            <x-slot name="actions">
                                                <x-button href="{{ route('shops.create') }}" variant="primary">
                                                    <i class="fas fa-plus me-2"></i>Create Shop
                                                </x-button>
                                            </x-slot>
                                        </x-empty-state>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $shops->links() }}
                </div>
            </x-card>
        </section>
    </main>
</x-app-layout>
