<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="{{ asset('css/dashboard/products/index.css') }}">
    @endpush
    <main class="main-content">
        <section id="products" class="page-section">
            <x-page-header>
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Products</h2>
                    <p class="mb-0 text-muted">Manage your products inventory</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('products.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i> Add Product
                    </x-button>
                </x-slot>
            </x-page-header>

            @if(session('success'))
                <x-alert variant="success">{{ session('success') }}</x-alert>
            @endif

            <x-card class="mb-4">
                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="shop_id" class="form-control">
                                    <option value="">All Shops</option>
                                    @php
                                        $userShops = \App\Models\Shop::where('user_id', Auth::id())->get();
                                    @endphp
                                    @foreach($userShops as $shop)
                                        <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                                            {{ $shop->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="category_id" class="form-control">
                                    <option value="">All Categories</option>
                                    @foreach($categories = \App\Models\ProductCategory::where('is_active', true)->get() as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <x-button type="submit" variant="primary" class="w-100">Filter</x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </x-card>

            <x-card>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Shop</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                            @if($product->main_image_url)
                                                <img src="{{ asset('website/'.$product->main_image_url) }}" alt="{{ $product->name }}"
                                                     class="img-thumbnail products-index-thumb">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded products-index-thumb-placeholder">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <strong>{{ $product->name }}</strong>
                                                @if($product->is_featured)
                                                    <x-badge variant="warning" class="mt-1 products-badge-sm">Featured</x-badge>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <code>{{ $product->sku }}</code>
                                        </td>
                                        <td>{{ $product->shop->name }}</td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <strong>Rs. {{ number_format($product->current_price, 2) }}</strong>
                                                @if($product->sale_price)
                                                    <small class="text-muted text-decoration-line-through">
                                                        Rs. {{ number_format($product->base_price, 2) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($product->manage_stock)
                                                <x-badge variant="{{ $product->is_in_stock ? 'success' : 'danger' }}">
                                                    {{ $product->stock_quantity }} in stock
                                                </x-badge>
                                                @if($product->isLowStock())
                                                    <x-badge variant="warning" class="d-inline-block mt-1">Low Stock</x-badge>
                                                @endif
                                            @else
                                                <x-badge variant="info">Not Tracked</x-badge>
                                            @endif
                                        </td>
                                        <td>
                                            <x-badge variant="{{ $product->is_active ? 'success' : 'secondary' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </x-badge>
                                            @if(!$product->is_approved)
                                                <x-badge variant="warning" class="d-inline-block mt-1">Pending</x-badge>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group products-table-actions">
                                                <x-button href="{{ route('products.show', $product) }}"
                                                   variant="outline-info" class="btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </x-button>
                                                <x-button href="{{ route('products.edit', $product) }}"
                                                   variant="outline-primary" class="btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </x-button>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                                      class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
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
                                        <td colspan="9" class="p-0 border-0">
                                            <x-empty-state title="No products found" icon="fa-box-open" class="py-4">
                                                <x-slot name="actions">
                                                    <x-button href="{{ route('products.create') }}" variant="primary">
                                                        <i class="fas fa-plus me-2"></i>Create Your First Product
                                                    </x-button>
                                                </x-slot>
                                            </x-empty-state>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $products->links() }}
                </div>
            </x-card>
        </section>
    </main>
</x-app-layout>
