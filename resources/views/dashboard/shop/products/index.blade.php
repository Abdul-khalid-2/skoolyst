<x-app-layout>
    <main class="main-content">
        <section id="products" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Products Management</h2>
                    <p class="mb-0 text-muted">Manage your shop products</p>
                </div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Create Product
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
                            <select class="form-select" id="shopFilter">
                                <option value="">All Shops</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                                        {{ $shop->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="categoryFilter">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="stockFilter">
                                <option value="">All Stock</option>
                                <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                <option value="low_stock" {{ request('stock') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search products..." id="searchInput" value="{{ request('search') }}">
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
                                    <th>Product</th>
                                    <th>Shop</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->main_image_url)
                                            <img src="{{  $product->main_image_url }}" alt="{{ $product->name }}" 
                                                 class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <strong class="d-block">{{ Str::limit($product->name, 30) }}</strong>
                                                <small class="text-muted">{{ $product->sku }}</small>
                                                @if($product->is_featured)
                                                <span class="badge bg-warning ms-1">Featured</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->shop->logo_url)
                                            <img src="{{ $product->shop->logo_url }}" alt="{{ $product->shop->name }}" 
                                                 class="rounded me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                            @endif
                                            <span>{{ $product->shop->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $product->category->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Rs. {{ number_format($product->base_price) }}</strong>
                                            @if($product->sale_price)
                                            <br>
                                            <small class="text-success">
                                                Sale: Rs. {{ number_format($product->sale_price) }}
                                            </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="badge bg-{{ $product->is_in_stock ? 'success' : 'secondary' }}">
                                                {{ $product->stock_quantity }}
                                            </span>
                                            @if($product->is_in_stock && $product->stock_quantity <= $product->low_stock_threshold)
                                            <small class="text-warning">Low Stock</small>
                                            @endif
                                        </div>
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
                                        <small class="text-muted">{{ $product->created_at->format('M j, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.products.show', $product) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
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
                                            <i class="fas fa-box fa-2x mb-3"></i>
                                            <p>No products found.</p>
                                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Create Product
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $products->links() }}
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            const shopFilter = document.getElementById('shopFilter');
            const categoryFilter = document.getElementById('categoryFilter');
            const statusFilter = document.getElementById('statusFilter');
            const stockFilter = document.getElementById('stockFilter');
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');

            function applyFilters() {
                const shopId = shopFilter.value;
                const categoryId = categoryFilter.value;
                const status = statusFilter.value;
                const stock = stockFilter.value;
                const search = searchInput.value;
                
                let url = new URL(window.location.href);
                
                if (shopId) url.searchParams.set('shop_id', shopId);
                else url.searchParams.delete('shop_id');
                
                if (categoryId) url.searchParams.set('category_id', categoryId);
                else url.searchParams.delete('category_id');
                
                if (status) url.searchParams.set('status', status);
                else url.searchParams.delete('status');
                
                if (stock) url.searchParams.set('stock', stock);
                else url.searchParams.delete('stock');
                
                if (search) url.searchParams.set('search', search);
                else url.searchParams.delete('search');
                
                window.location.href = url.toString();
            }

            shopFilter.addEventListener('change', applyFilters);
            categoryFilter.addEventListener('change', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
            stockFilter.addEventListener('change', applyFilters);
            searchButton.addEventListener('click', applyFilters);
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') applyFilters();
            });

            // Set current filter values from URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('shop_id')) shopFilter.value = urlParams.get('shop_id');
            if (urlParams.get('category_id')) categoryFilter.value = urlParams.get('category_id');
            if (urlParams.get('status')) statusFilter.value = urlParams.get('status');
            if (urlParams.get('stock')) stockFilter.value = urlParams.get('stock');
            if (urlParams.get('search')) searchInput.value = urlParams.get('search');
        });
    </script>
</x-app-layout>