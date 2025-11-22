<x-app-layout>
    <main class="main-content">
        <section id="product-show" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Product Details</h2>
                    <p class="mb-0 text-muted">Complete information about the product</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Edit Product
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Products
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Product Header -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    @if($product->main_image_url)
                                    <img src="{{ asset('storage/' . $product->main_image_url) }}" alt="{{ $product->name }}" 
                                         class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 100px; height: 100px;">
                                        <i class="fas fa-box fa-2x text-muted"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="col">
                                    <h3 class="h5 mb-1">{{ $product->name }}</h3>
                                    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                        <span class="badge bg-primary">{{ Str::title($product->product_type) }}</span>
                                        <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <span class="badge bg-{{ $product->is_approved ? 'info' : 'warning' }}">
                                            {{ $product->is_approved ? 'Approved' : 'Pending' }}
                                        </span>
                                        @if($product->is_featured)
                                        <span class="badge bg-warning">Featured</span>
                                        @endif
                                        <span class="badge bg-{{ $product->is_in_stock ? 'success' : 'danger' }}">
                                            {{ $product->is_in_stock ? 'In Stock' : 'Out of Stock' }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fas fa-store me-1"></i>
                                        <span class="me-3">{{ $product->shop->name }}</span>
                                        <i class="fas fa-tag me-1"></i>
                                        <span>{{ $product->category->name }}</span>
                                    </div>
                                </div>
                                <div class="col-auto text-end">
                                    <div class="h4 text-primary mb-1">Rs. {{ number_format($product->base_price) }}</div>
                                    @if($product->sale_price)
                                    <div class="text-muted text-decoration-line-through">
                                        Rs. {{ number_format($product->sale_price) }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Information Tabs -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" 
                                            data-bs-target="#details" type="button" role="tab">
                                        <i class="fas fa-info-circle me-2"></i>Details
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" 
                                            data-bs-target="#pricing" type="button" role="tab">
                                        <i class="fas fa-tag me-2"></i>Pricing & Inventory
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="media-tab" data-bs-toggle="tab" 
                                            data-bs-target="#media" type="button" role="tab">
                                        <i class="fas fa-images me-2"></i>Media
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="seo-tab" data-bs-toggle="tab" 
                                            data-bs-target="#seo" type="button" role="tab">
                                        <i class="fas fa-search me-2"></i>SEO
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="productTabsContent">
                                <!-- Details Tab -->
                                <div class="tab-pane fade show active" id="details" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Product Type</label>
                                                <p class="mb-0">{{ Str::title($product->product_type) }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">SKU</label>
                                                <p class="mb-0"><code>{{ $product->sku }}</code></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($product->short_description)
                                    <div class="mb-3">
                                        <label class="form-label text-muted small mb-1">Short Description</label>
                                        <p class="mb-0">{{ $product->short_description }}</p>
                                    </div>
                                    @endif

                                    @if($product->description)
                                    <div class="mb-3">
                                        <label class="form-label text-muted small mb-1">Full Description</label>
                                        <div class="border rounded p-3 bg-light">
                                            {!! nl2br(e($product->description)) !!}
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Product Attributes -->
                                    @if($product->brand || $product->color || $product->size || $product->material)
                                    <div class="mb-3">
                                        <label class="form-label text-muted small mb-2">Product Attributes</label>
                                        <div class="row">
                                            @if($product->brand)
                                            <div class="col-md-3 mb-2">
                                                <strong>Brand:</strong>
                                                <span class="text-muted">{{ $product->brand }}</span>
                                            </div>
                                            @endif
                                            @if($product->color)
                                            <div class="col-md-3 mb-2">
                                                <strong>Color:</strong>
                                                <span class="text-muted">{{ $product->color }}</span>
                                            </div>
                                            @endif
                                            @if($product->size)
                                            <div class="col-md-3 mb-2">
                                                <strong>Size:</strong>
                                                <span class="text-muted">{{ $product->size }}</span>
                                            </div>
                                            @endif
                                            @if($product->material)
                                            <div class="col-md-3 mb-2">
                                                <strong>Material:</strong>
                                                <span class="text-muted">{{ $product->material }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Created Date</label>
                                                <p class="mb-0">{{ $product->created_at->format('M j, Y \\a\\t g:i A') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Last Updated</label>
                                                <p class="mb-0">{{ $product->updated_at->format('M j, Y \\a\\t g:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing & Inventory Tab -->
                                <div class="tab-pane fade" id="pricing" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="text-center p-3 border rounded bg-light mb-3">
                                                <label class="form-label text-muted small mb-1">Base Price</label>
                                                <h4 class="text-primary mb-0">Rs. {{ number_format($product->base_price) }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 border rounded bg-light mb-3">
                                                <label class="form-label text-muted small mb-1">Sale Price</label>
                                                <h4 class="{{ $product->sale_price ? 'text-success' : 'text-muted' }} mb-0">
                                                    {{ $product->sale_price ? 'Rs. ' . number_format($product->sale_price) : 'Not Set' }}
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 border rounded bg-light mb-3">
                                                <label class="form-label text-muted small mb-1">Cost Price</label>
                                                <h4 class="{{ $product->cost_price ? 'text-info' : 'text-muted' }} mb-0">
                                                    {{ $product->cost_price ? 'Rs. ' . number_format($product->cost_price) : 'Not Set' }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Stock Quantity</label>
                                                <div class="d-flex align-items-center">
                                                    <h3 class="{{ $product->is_in_stock ? 'text-success' : 'text-danger' }} mb-0 me-3">
                                                        {{ $product->stock_quantity }}
                                                    </h3>
                                                    <div>
                                                        @if($product->is_in_stock)
                                                            @if($product->stock_quantity <= $product->low_stock_threshold)
                                                            <span class="badge bg-warning">Low Stock</span>
                                                            @else
                                                            <span class="badge bg-success">In Stock</span>
                                                            @endif
                                                        @else
                                                        <span class="badge bg-danger">Out of Stock</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Low Stock Threshold</label>
                                                <p class="mb-0 h5">{{ $product->low_stock_threshold }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Manage Stock</label>
                                                <p class="mb-0">
                                                    <span class="badge bg-{{ $product->manage_stock ? 'info' : 'secondary' }}">
                                                        {{ $product->manage_stock ? 'Yes' : 'No' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Profit Margin</label>
                                                <p class="mb-0 h5">
                                                    @if($product->cost_price && $product->base_price)
                                                        {{ number_format((($product->base_price - $product->cost_price) / $product->cost_price) * 100, 2) }}%
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Media Tab -->
                                <div class="tab-pane fade" id="media" role="tabpanel">
                                    <!-- Main Image -->
                                    <div class="mb-4">
                                        <label class="form-label text-muted small mb-2">Main Image</label>
                                        @if($product->main_image_url)
                                        <div class="border rounded p-3 bg-light text-center">
                                            <img src="{{ asset('storage/' . $product->main_image_url) }}" 
                                                 alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 300px;">
                                        </div>
                                        @else
                                        <div class="border rounded p-5 bg-light text-center text-muted">
                                            <i class="fas fa-image fa-3x mb-3"></i>
                                            <p>No main image uploaded</p>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Gallery Images -->
                                    <div>
                                        <label class="form-label text-muted small mb-2">Gallery Images</label>
                                        @if($product->image_gallery && count(json_decode($product->image_gallery)) > 0)
                                        <div class="row g-3">
                                            @foreach(json_decode($product->image_gallery) as $index => $galleryImage)
                                            <div class="col-md-4 col-6">
                                                <div class="border rounded p-2 bg-light text-center">
                                                    <img src="{{ asset('storage/' . $galleryImage) }}" 
                                                         alt="Gallery Image {{ $index + 1 }}" 
                                                         class="img-fluid rounded" style="height: 150px; object-fit: cover;">
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <div class="border rounded p-4 bg-light text-center text-muted">
                                            <i class="fas fa-images fa-2x mb-2"></i>
                                            <p>No gallery images uploaded</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- SEO Tab -->
                                <div class="tab-pane fade" id="seo" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small mb-1">Meta Title</label>
                                        <p class="mb-0">
                                            {{ $product->meta_title ?: '<span class="text-muted">Not set</span>' }}
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted small mb-1">Meta Description</label>
                                        <p class="mb-0">
                                            {{ $product->meta_description ?: '<span class="text-muted">Not set</span>' }}
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted small mb-1">Meta Keywords</label>
                                        <p class="mb-0">
                                            {{ $product->meta_keywords ?: '<span class="text-muted">Not set</span>' }}
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted small mb-1">Product URL</label>
                                        <p class="mb-0">
                                            <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="text-decoration-none">
                                                {{ route('products.show', $product->slug) }}
                                            </a>
                                        </p>
                                    </div>

                                    <div class="mt-4 p-3 bg-light rounded">
                                        <h6 class="text-muted mb-2">SEO Preview</h6>
                                        <div class="border-start border-3 border-primary ps-3">
                                            <div class="text-primary mb-1" style="font-size: 1.1rem;">
                                                {{ $product->meta_title ?: $product->name }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ $product->meta_description ?: Str::limit($product->short_description ?: $product->description, 160) }}
                                            </div>
                                        </div>
                                    </div>
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
                                <i class="fas fa-chart-bar text-primary me-2"></i>Product Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-eye fa-2x text-info mb-2"></i>
                                        <h4 class="mb-1">-</h4>
                                        <small class="text-muted">Views</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-shopping-cart fa-2x text-success mb-2"></i>
                                        <h4 class="mb-1">-</h4>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                                        <h4 class="mb-1">-</h4>
                                        <small class="text-muted">Favorites</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-star fa-2x text-warning mb-2"></i>
                                        <h4 class="mb-1">-</h4>
                                        <small class="text-muted">Reviews</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shop Information -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-store text-primary me-2"></i>Shop Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @if($product->shop->logo_url)
                                <img src="{{ asset('storage/' . $product->shop->logo_url) }}" alt="{{ $product->shop->name }}" 
                                     class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-store text-muted"></i>
                                </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $product->shop->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $product->shop->shop_type }}</p>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between small text-muted mb-2">
                                <span>Rating:</span>
                                <span>
                                    <i class="fas fa-star text-warning"></i>
                                    {{ number_format($product->shop->rating, 1) }} ({{ $product->shop->total_reviews }})
                                </span>
                            </div>
                            
                            <div class="d-flex justify-content-between small text-muted mb-2">
                                <span>Status:</span>
                                <span>
                                    <span class="badge bg-{{ $product->shop->is_active ? 'success' : 'secondary' }}">
                                        {{ $product->shop->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </span>
                            </div>
                            
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Verified:</span>
                                <span>
                                    <span class="badge bg-{{ $product->shop->is_verified ? 'info' : 'warning' }}">
                                        {{ $product->shop->is_verified ? 'Yes' : 'No' }}
                                    </span>
                                </span>
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
                                <a href="{{ route('products.show', $product->slug) }}" target="_blank" 
                                   class="btn btn-outline-info">
                                    <i class="fas fa-external-link-alt me-2"></i>View Public Page
                                </a>
                                <a href="{{ route('admin.products.create') }}?shop_id={{ $product->shop_id }}&category_id={{ $product->category_id }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-plus me-2"></i>Add Similar Product
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="confirmDelete()">
                                    <i class="fas fa-trash me-2"></i>Delete Product
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Delete Form -->
    <form id="deleteForm" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        // Initialize tabs
        document.addEventListener('DOMContentLoaded', function() {
            const triggerTabList = [].slice.call(document.querySelectorAll('#productTabs button'))
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