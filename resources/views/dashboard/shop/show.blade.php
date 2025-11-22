<x-app-layout>
    <style>
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #000000 !important;
            transform: translateX(5px);
        }
    </style>
    <main class="main-content">
        <section id="shop-show" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Shop Details</h2>
                    <p class="mb-0 text-muted">Complete information about the shop</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.shops.edit', $shop) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Edit Shop
                    </a>
                    <a href="{{ route('admin.shops.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Shops
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Shop Header -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    @if($shop->logo_url)
                                    <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" 
                                         class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-store fa-2x text-muted"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="col">
                                    <h3 class="h5 mb-1">{{ $shop->name }}</h3>
                                    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                        <span class="badge bg-primary">{{ Str::title(str_replace('_', ' ', $shop->shop_type)) }}</span>
                                        <span class="badge bg-{{ $shop->is_active ? 'success' : 'secondary' }}">
                                            {{ $shop->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <span class="badge bg-{{ $shop->is_verified ? 'info' : 'warning' }}">
                                            {{ $shop->is_verified ? 'Verified' : 'Unverified' }}
                                        </span>
                                        @if($shop->school)
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-school me-1"></i>{{ $shop->school->name }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fas fa-star text-warning me-1"></i>
                                        <span class="me-3">{{ number_format($shop->rating, 1) }} ({{ $shop->total_reviews }} reviews)</span>
                                        <i class="fas fa-box me-1"></i>
                                        <span>{{ $shop->products_count ?? 0 }} products</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shop Information Tabs -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <ul class="nav nav-tabs card-header-tabs" id="shopTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" 
                                            data-bs-target="#details" type="button" role="tab">
                                        <i class="fas fa-info-circle me-2"></i>Details
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="products-tab" data-bs-toggle="tab" 
                                            data-bs-target="#products" type="button" role="tab">
                                        <i class="fas fa-box me-2"></i>Products ({{ $shop->products->count() }})
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" 
                                            data-bs-target="#contact" type="button" role="tab">
                                        <i class="fas fa-address-book me-2"></i>Contact & Location
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="shopTabsContent">
                                <!-- Details Tab -->
                                <div class="tab-pane fade show active" id="details" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Shop Type</label>
                                                <p class="mb-0">{{ Str::title(str_replace('_', ' ', $shop->shop_type)) }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Created Date</label>
                                                <p class="mb-0">{{ $shop->created_at->format('M j, Y \\a\\t g:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($shop->description)
                                    <div class="mb-3">
                                        <label class="form-label text-muted small mb-1">Description</label>
                                        <p class="mb-0">{{ $shop->description }}</p>
                                    </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Owner</label>
                                                <p class="mb-0">
                                                    {{ $shop->user->name }}
                                                    <small class="text-muted">({{ $shop->user->email }})</small>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Last Updated</label>
                                                <p class="mb-0">{{ $shop->updated_at->format('M j, Y \\a\\t g:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Shop Banner -->
                                    @if($shop->banner_url)
                                    <div class="mt-4">
                                        <label class="form-label text-muted small mb-2">Shop Banner</label>
                                        <div class="border rounded p-3 bg-light">
                                            <img src="{{  $shop->banner_url }}" 
                                                 alt="Shop Banner" class="img-fluid rounded">
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Products Tab -->
                                <div class="tab-pane fade" id="products" role="tabpanel">
                                    @if($shop->products->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Category</th>
                                                        <th>Price</th>
                                                        <th>Stock</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($shop->products->take(10) as $product)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if($product->main_image_url)
                                                                <img src="{{ $product->main_image_url }}" 
                                                                     alt="{{ $product->name }}" class="rounded me-2" 
                                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                                @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-2" 
                                                                     style="width: 40px; height: 40px;">
                                                                    <i class="fas fa-box text-muted"></i>
                                                                </div>
                                                                @endif
                                                                <div>
                                                                    <strong class="d-block">{{ Str::limit($product->name, 30) }}</strong>
                                                                    <small class="text-muted">{{ $product->sku }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-dark">
                                                                {{ $product->category->name }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <strong>Rs. {{ number_format($product->base_price) }}</strong>
                                                            @if($product->sale_price)
                                                            <br>
                                                            <small class="text-muted text-decoration-line-through">
                                                                Rs. {{ number_format($product->sale_price) }}
                                                            </small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-{{ $product->is_in_stock ? 'success' : 'secondary' }}">
                                                                {{ $product->stock_quantity }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column gap-1">
                                                                <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                                </span>
                                                                <span class="badge bg-{{ $product->is_approved ? 'info' : 'warning' }}">
                                                                    {{ $product->is_approved ? 'Approved' : 'Pending' }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ route('admin.products.edit', $product) }}" 
                                                                   class="btn btn-outline-secondary">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="{{ route('products.show', $product->slug) }}" 
                                                                   target="_blank" class="btn btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        @if($shop->products->count() > 10)
                                        <div class="text-center mt-3">
                                            <a href="{{ route('admin.products.index', ['shop_id' => $shop->id]) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                View All Products
                                            </a>
                                        </div>
                                        @endif
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-box fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">No products found for this shop.</p>
                                            <a href="{{ route('admin.products.create', ['shop_id' => $shop->id]) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus me-2"></i>Add First Product
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <!-- Contact & Location Tab -->
                                <div class="tab-pane fade" id="contact" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <h6 class="text-muted mb-3">
                                                    <i class="fas fa-address-book me-2"></i>Contact Information
                                                </h6>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small mb-1">Email</label>
                                                    <p class="mb-0">
                                                        @if($shop->email)
                                                        <a href="mailto:{{ $shop->email }}" class="text-decoration-none">
                                                            {{ $shop->email }}
                                                        </a>
                                                        @else
                                                        <span class="text-muted">Not provided</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small mb-1">Phone</label>
                                                    <p class="mb-0">
                                                        @if($shop->phone)
                                                        <a href="tel:{{ $shop->phone }}" class="text-decoration-none">
                                                            {{ $shop->phone }}
                                                        </a>
                                                        @else
                                                        <span class="text-muted">Not provided</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <h6 class="text-muted mb-3">
                                                    <i class="fas fa-map-marker-alt me-2"></i>Location
                                                </h6>
                                                @if($shop->address)
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small mb-1">Address</label>
                                                    <p class="mb-0">{{ $shop->address }}</p>
                                                </div>
                                                @endif
                                                
                                                <div class="row">
                                                    @if($shop->city)
                                                    <div class="col-6">
                                                        <label class="form-label text-muted small mb-1">City</label>
                                                        <p class="mb-0">{{ $shop->city }}</p>
                                                    </div>
                                                    @endif
                                                    @if($shop->state)
                                                    <div class="col-6">
                                                        <label class="form-label text-muted small mb-1">State</label>
                                                        <p class="mb-0">{{ $shop->state }}</p>
                                                    </div>
                                                    @endif
                                                </div>
                                                
                                                @if($shop->country)
                                                <div class="mt-3">
                                                    <label class="form-label text-muted small mb-1">Country</label>
                                                    <p class="mb-0">{{ $shop->country }}</p>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Map Placeholder -->
                                    @if($shop->latitude && $shop->longitude)
                                    <div class="mt-4">
                                        <h6 class="text-muted mb-3">
                                            <i class="fas fa-map me-2"></i>Location Map
                                        </h6>
                                        <div class="border rounded bg-light" style="height: 200px;">
                                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                <div class="text-center">
                                                    <i class="fas fa-map-marked-alt fa-2x mb-2"></i>
                                                    <p>Map would be displayed here</p>
                                                    <small>Coordinates: {{ $shop->latitude }}, {{ $shop->longitude }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quick Stats -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar text-primary me-2"></i>Shop Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-box fa-2x text-primary mb-2"></i>
                                        <h4 class="mb-1">{{ $shop->products_count ?? 0 }}</h4>
                                        <small class="text-muted">Total Products</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-star fa-2x text-warning mb-2"></i>
                                        <h4 class="mb-1">{{ number_format($shop->rating, 1) }}</h4>
                                        <small class="text-muted">Average Rating</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-comment fa-2x text-info mb-2"></i>
                                        <h4 class="mb-1">{{ $shop->total_reviews }}</h4>
                                        <small class="text-muted">Total Reviews</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-eye fa-2x text-success mb-2"></i>
                                        <h4 class="mb-1">-</h4>
                                        <small class="text-muted">Views</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.products.create', ['shop_id' => $shop->id]) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add New Product
                                </a>
                                <a href="{{ route('admin.products.index', ['shop_id' => $shop->id]) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-boxes me-2"></i>Manage Products
                                </a>
                                <a href="{{ route('admin.shops.show', $shop->slug) }}" target="_blank" 
                                   class="btn btn-outline-info">
                                    <i class="fas fa-external-link-alt me-2"></i>View Public Page
                                </a>
                                @if(auth()->user()->hasRole('super_admin'))
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="confirmDelete()">
                                    <i class="fas fa-trash me-2"></i>Delete Shop
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Shop Owner Info -->
                    <div class="card mt-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user text-primary me-2"></i>Shop Owner
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $shop->user->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $shop->user->email }}</p>
                                    <small class="text-muted">
                                        Member since {{ $shop->user->created_at->format('M Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Delete Form -->
    @if(auth()->user()->hasRole('super_admin'))
    <form id="deleteForm" action="{{ route('admin.shops.destroy', $shop) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
    @endif

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this shop? This will also delete all associated products and cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        // Initialize tabs
        document.addEventListener('DOMContentLoaded', function() {
            const triggerTabList = [].slice.call(document.querySelectorAll('#shopTabs button'))
            triggerTabList.forEach(function (triggerEl) {
                const tabTrigger = new bootstrap.Tab(triggerEl)
                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            })
        });
    </script>
</x-app-layout>