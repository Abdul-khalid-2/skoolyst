<x-app-layout>
    <main class="main-content">
        <section id="product-category-show" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Category Details</h2>
                    <p class="mb-0 text-muted">Complete information about the category</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.product-categories.edit', $productCategory) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Edit Category
                    </a>
                    <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Categories
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Category Header -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    @if($productCategory->image_url)
                                    <img src="{{ asset('storage/' . $productCategory->image_url) }}" alt="{{ $productCategory->name }}" 
                                         class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    @elseif($productCategory->icon)
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-{{ $productCategory->icon }} fa-2x text-primary"></i>
                                    </div>
                                    @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-folder fa-2x text-muted"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="col">
                                    <h3 class="h5 mb-1">{{ $productCategory->name }}</h3>
                                    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                        <span class="badge bg-{{ $productCategory->is_active ? 'success' : 'secondary' }}">
                                            {{ $productCategory->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($productCategory->parent)
                                        <span class="badge bg-info">
                                            Subcategory of: {{ $productCategory->parent->name }}
                                        </span>
                                        @else
                                        <span class="badge bg-primary">Main Category</span>
                                        @endif
                                    </div>
                                    @if($productCategory->description)
                                    <p class="text-muted mb-0">{{ $productCategory->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Information Tabs -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <ul class="nav nav-tabs card-header-tabs" id="categoryTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" 
                                            data-bs-target="#details" type="button" role="tab">
                                        <i class="fas fa-info-circle me-2"></i>Details
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="products-tab" data-bs-toggle="tab" 
                                            data-bs-target="#products" type="button" role="tab">
                                        <i class="fas fa-box me-2"></i>Products ({{ $productCategory->products_count }})
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="subcategories-tab" data-bs-toggle="tab" 
                                            data-bs-target="#subcategories" type="button" role="tab">
                                        <i class="fas fa-sitemap me-2"></i>Subcategories ({{ $productCategory->children->count() }})
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="categoryTabsContent">
                                <!-- Details Tab -->
                                <div class="tab-pane fade show active" id="details" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Category Name</label>
                                                <p class="mb-0">{{ $productCategory->name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Slug</label>
                                                <p class="mb-0"><code>{{ $productCategory->slug }}</code></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Parent Category</label>
                                                <p class="mb-0">
                                                    @if($productCategory->parent)
                                                    <a href="{{ route('admin.product-categories.show', $productCategory->parent) }}" 
                                                       class="text-decoration-none">
                                                        {{ $productCategory->parent->name }}
                                                    </a>
                                                    @else
                                                    <span class="text-muted">-</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Sort Order</label>
                                                <p class="mb-0">{{ $productCategory->sort_order }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($productCategory->icon)
                                    <div class="mb-3">
                                        <label class="form-label text-muted small mb-1">Icon</label>
                                        <p class="mb-0">
                                            <i class="fas fa-{{ $productCategory->icon }} text-primary me-2"></i>
                                            <code>fa-{{ $productCategory->icon }}</code>
                                        </p>
                                    </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Created Date</label>
                                                <p class="mb-0">{{ $productCategory->created_at->format('M j, Y \\a\\t g:i A') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small mb-1">Last Updated</label>
                                                <p class="mb-0">{{ $productCategory->updated_at->format('M j, Y \\a\\t g:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Products Tab -->
                                <div class="tab-pane fade" id="products" role="tabpanel">
                                    @if($productCategory->products->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Shop</th>
                                                        <th>Price</th>
                                                        <th>Stock</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($productCategory->products->take(10) as $product)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if($product->main_image_url)
                                                                <img src="{{ asset('storage/' . $product->main_image_url) }}" 
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
                                                            <small>{{ $product->shop->name }}</small>
                                                        </td>
                                                        <td>
                                                            <strong>Rs. {{ number_format($product->base_price) }}</strong>
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
                                        
                                        @if($productCategory->products->count() > 10)
                                        <div class="text-center mt-3">
                                            <a href="{{ route('admin.products.index', ['category_id' => $productCategory->id]) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                View All Products
                                            </a>
                                        </div>
                                        @endif
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-box fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">No products found in this category.</p>
                                            <a href="{{ route('admin.products.create', ['category_id' => $productCategory->id]) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus me-2"></i>Add Product
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <!-- Subcategories Tab -->
                                <div class="tab-pane fade" id="subcategories" role="tabpanel">
                                    @if($productCategory->children->count() > 0)
                                        <div class="row">
                                            @foreach($productCategory->children as $subcategory)
                                            <div class="col-md-6 mb-3">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            @if($subcategory->icon)
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="fas fa-{{ $subcategory->icon }} text-primary"></i>
                                                            </div>
                                                            @endif
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">{{ $subcategory->name }}</h6>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="badge bg-info me-2">
                                                                        {{ $subcategory->products_count }} products
                                                                    </span>
                                                                    <span class="badge bg-{{ $subcategory->is_active ? 'success' : 'secondary' }}">
                                                                        {{ $subcategory->is_active ? 'Active' : 'Inactive' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ route('admin.product-categories.show', $subcategory) }}" 
                                                                   class="btn btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('admin.product-categories.edit', $subcategory) }}" 
                                                                   class="btn btn-outline-secondary">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-sitemap fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">No subcategories found.</p>
                                            <a href="{{ route('admin.product-categories.create', ['parent_id' => $productCategory->id]) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus me-2"></i>Add Subcategory
                                            </a>
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
                                <i class="fas fa-chart-bar text-primary me-2"></i>Category Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-box fa-2x text-primary mb-2"></i>
                                        <h4 class="mb-1">{{ $productCategory->products_count }}</h4>
                                        <small class="text-muted">Total Products</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-sitemap fa-2x text-warning mb-2"></i>
                                        <h4 class="mb-1">{{ $productCategory->children->count() }}</h4>
                                        <small class="text-muted">Subcategories</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-sort-amount-down fa-2x text-info mb-2"></i>
                                        <h4 class="mb-1">{{ $productCategory->sort_order }}</h4>
                                        <small class="text-muted">Sort Order</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-calendar fa-2x text-success mb-2"></i>
                                        <h4 class="mb-1">{{ $productCategory->created_at->format('M Y') }}</h4>
                                        <small class="text-muted">Created</small>
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
                                <a href="{{ route('admin.products.create', ['category_id' => $productCategory->id]) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add Product
                                </a>
                                <a href="{{ route('admin.product-categories.create', ['parent_id' => $productCategory->id]) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-sitemap me-2"></i>Add Subcategory
                                </a>
                                <a href="{{ route('admin.products.index', ['category_id' => $productCategory->id]) }}" 
                                   class="btn btn-outline-info">
                                    <i class="fas fa-boxes me-2"></i>View All Products
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="confirmDelete()">
                                    <i class="fas fa-trash me-2"></i>Delete Category
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Delete Form -->
    <form id="deleteForm" action="{{ route('admin.product-categories.destroy', $productCategory) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        // Initialize tabs
        document.addEventListener('DOMContentLoaded', function() {
            const triggerTabList = [].slice.call(document.querySelectorAll('#categoryTabs button'))
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