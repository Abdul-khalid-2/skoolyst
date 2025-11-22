<x-app-layout>
    <main class="main-content">
        <section id="shops" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Shops Management</h2>
                    <p class="mb-0 text-muted">Manage your educational shops and stores</p>
                </div>
                <a href="{{ route('admin.shops.create') }}" class="btn btn-primary">
                    <i class="fas fa-store me-2"></i> Create Shop
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <select class="form-select" id="shopTypeFilter">
                                <option value="">All Shop Types</option>
                                <option value="stationery">Stationery</option>
                                <option value="book_store">Book Store</option>
                                <option value="mixed">Mixed</option>
                                <option value="school_affiliated">School Affiliated</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="verified">Verified</option>
                                <option value="unverified">Unverified</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search shops..." id="searchInput">
                                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Shop Info</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Rating</th>
                                    <th>Products</th>
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
                                            <img src="{{ asset($shop->logo_url) }}" alt="{{ $shop->name }}" 
                                                 class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-store text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <strong>{{ $shop->name }}</strong>
                                                @if($shop->school)
                                                <br>
                                                <small class="text-muted">Affiliated with {{ $shop->school->name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ Str::title(str_replace('_', ' ', $shop->shop_type)) }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $shop->city }}, {{ $shop->state }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-{{ $shop->is_active ? 'success' : 'secondary' }}">
                                                {{ $shop->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <span class="badge bg-{{ $shop->is_verified ? 'info' : 'warning' }}">
                                                {{ $shop->is_verified ? 'Verified' : 'Unverified' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star text-warning me-1"></i>
                                            <span>{{ number_format($shop->rating, 1) }}</span>
                                            <small class="text-muted ms-1">({{ $shop->total_reviews }})</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $shop->products_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $shop->created_at->format('M j, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.shops.show', $shop) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.shops.edit', $shop) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(auth()->user()->hasRole('super_admin'))
                                            <form action="{{ route('admin.shops.destroy', $shop) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('Are you sure you want to delete this shop? This will also delete all associated products.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-store fa-2x mb-3"></i>
                                            <p>No shops found. Create your first shop to get started.</p>
                                            <a href="{{ route('admin.shops.create') }}" class="btn btn-primary">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            const shopTypeFilter = document.getElementById('shopTypeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');

            function applyFilters() {
                const shopType = shopTypeFilter.value;
                const status = statusFilter.value;
                const search = searchInput.value;
                
                let url = new URL(window.location.href);
                if (shopType) url.searchParams.set('type', shopType);
                else url.searchParams.delete('type');
                
                if (status) url.searchParams.set('status', status);
                else url.searchParams.delete('status');
                
                if (search) url.searchParams.set('search', search);
                else url.searchParams.delete('search');
                
                window.location.href = url.toString();
            }

            shopTypeFilter.addEventListener('change', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
            searchButton.addEventListener('click', applyFilters);
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') applyFilters();
            });

            // Set current filter values
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('type')) shopTypeFilter.value = urlParams.get('type');
            if (urlParams.get('status')) statusFilter.value = urlParams.get('status');
            if (urlParams.get('search')) searchInput.value = urlParams.get('search');
        });
    </script>
</x-app-layout>