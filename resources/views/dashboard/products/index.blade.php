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

            @if (session('success'))
                <x-alert variant="success" class="mb-4">{{ session('success') }}</x-alert>
            @endif

            <x-card class="mb-4">
                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET" class="row g-3 align-items-end">
                        @if (request()->filled('sort_by'))
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                        @endif
                        @if (request()->filled('sort_dir'))
                            <input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">
                        @endif
                        @if (request()->filled('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Shop</label>
                            <select name="shop_id" class="form-select">
                                <option value="">All Shops</option>
                                @foreach ($shops as $shop)
                                    <option value="{{ $shop->id }}" {{ (string) request('shop_id') === (string) $shop->id ? 'selected' : '' }}>
                                        {{ $shop->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-auto d-flex flex-wrap gap-2">
                            <x-button type="submit" variant="primary">
                                <i class="fas fa-filter me-1"></i> Apply
                            </x-button>
                            <x-button href="{{ route('products.index') }}" variant="outline-secondary">
                                Reset
                            </x-button>
                        </div>
                    </form>
                </div>
            </x-card>

            <x-data-table
                class="mb-0"
                :headers="[
                    ['label' => 'Image', 'key' => 'image', 'sortable' => false],
                    ['label' => 'Name', 'key' => 'name', 'sortable' => true],
                    ['label' => 'SKU', 'key' => 'sku', 'sortable' => true],
                    ['label' => 'Shop', 'key' => 'shop', 'sortable' => true],
                    ['label' => 'Category', 'key' => 'category', 'sortable' => true],
                    ['label' => 'Price', 'key' => 'base_price', 'sortable' => true],
                    ['label' => 'Stock', 'key' => 'stock_quantity', 'sortable' => true],
                    ['label' => 'Status', 'key' => 'is_active', 'sortable' => true],
                    ['label' => 'Actions', 'key' => 'actions', 'sortable' => false],
                ]"
                :records="$products"
                :sortBy="request('sort_by', 'created_at')"
                :sortDir="request('sort_dir', 'desc')"
                :searchValue="request('search')"
                emptyTitle="No products found"
                emptyDescription="Try adjusting filters or add your first product."
                emptyIcon="fa-box-open"
            >
                <x-slot name="emptyActions">
                    <x-button href="{{ route('products.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i>Create your first product
                    </x-button>
                </x-slot>
                <x-slot name="rows">
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                @if ($product->main_image_url)
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
                                    @if ($product->is_featured)
                                        <x-badge variant="warning" class="mt-1 products-badge-sm">Featured</x-badge>
                                    @endif
                                </div>
                            </td>
                            <td><code>{{ $product->sku }}</code></td>
                            <td>{{ $product->shop->name ?? '—' }}</td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong>Rs. {{ number_format($product->current_price, 2) }}</strong>
                                    @if ($product->sale_price)
                                        <small class="text-muted text-decoration-line-through">
                                            Rs. {{ number_format($product->base_price, 2) }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if ($product->manage_stock)
                                    <x-badge variant="{{ $product->is_in_stock ? 'success' : 'danger' }}">
                                        {{ $product->stock_quantity }} in stock
                                    </x-badge>
                                    @if ($product->isLowStock())
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
                                @if (! $product->is_approved)
                                    <x-badge variant="warning" class="d-inline-block mt-1">Pending</x-badge>
                                @endif
                            </td>
                            <td class="text-end">
                                <x-table-action>
                                    <x-table-action-item href="{{ route('products.show', $product) }}" icon="fa-eye">
                                        View
                                    </x-table-action-item>
                                    <x-table-action-item href="{{ route('products.edit', $product) }}" icon="fa-edit">
                                        Edit
                                    </x-table-action-item>
                                    <x-table-action-item
                                        href="{{ route('products.destroy', $product) }}"
                                        icon="fa-trash"
                                        variant="danger"
                                        method="DELETE"
                                        onclick="return confirm('Are you sure you want to delete this product?')"
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
