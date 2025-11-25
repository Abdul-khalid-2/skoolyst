<x-app-layout>
    <main class="main-content">
        <section id="shops" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Shops</h2>
                    <p class="mb-0 text-muted">Manage your shops and associations</p>
                </div>
                <a href="{{ route('shops.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Create Shop
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
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
                                            <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" 
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
                                        <span class="badge bg-info text-capitalize">
                                            {{ str_replace('_', ' ', $shop->shop_type) }}
                                        </span>
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
                                        <span class="badge bg-{{ $shop->is_active ? 'success' : 'secondary' }} me-1">
                                            {{ $shop->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($shop->is_verified)
                                        <span class="badge bg-primary">Verified</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $shop->schoolAssociations->count() }} Schools
                                        </span>
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
                                            <a href="{{ route('shops.show', $shop) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('shops.edit', $shop) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('shops.destroy', $shop) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('Are you sure you want to delete this shop?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-store fa-2x mb-3"></i>
                                            <p>No shops found. Create your first shop to get started.</p>
                                            <a href="{{ route('shops.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Create Shop
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $shops->links() }}
                </div>
            </div>
        </section>
    </main>
</x-app-layout>