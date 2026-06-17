@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/products.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/browse_schools.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== PRODUCTS HERO SECTION (compact, unified) ==================== -->
<section class="products-hero-section" id="products-hero">
    <div class="products-hero-content">
        <h1 class="products-hero-title">Educational Products Marketplace</h1>
        <p class="products-hero-subheading">
            Discover textbooks, stationery, uniforms, and all essential educational supplies from trusted shops
        </p>
    </div>
</section>

<!-- ==================== SEARCH AND FILTER SECTION ==================== -->
<section class="filter-section" aria-label="Filter products">
    <div class="container">
        <header class="filter-section__header text-center">
            <p class="filter-section__eyebrow">Refine your search</p>
            <h2 class="filter-section__title h5 mb-0">Find what you need</h2>
        </header>

        <div class="filter-bar filter-bar--browse">
            <div class="filter-container"
                 id="productsFiltersContainer"
                 data-base-url="{{ route('website.stationary.index') }}"
                 style="grid-template-columns: 1.4fr repeat(5, 1fr) auto;">

                <div class="filter-group filter-group--search">
                    <label class="filter-label" for="productSearchInput">
                        <i class="fas fa-magnifying-glass" aria-hidden="true"></i>
                        Search
                    </label>
                    <div class="filter-input-wrap">
                        <input type="search" class="filter-search" id="productSearchInput" name="search"
                            placeholder="Search for products, books, stationery..."
                            value="{{ $search }}" autocomplete="off" inputmode="search">
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="categoryFilter">
                        <i class="fas fa-tag" aria-hidden="true"></i>
                        Category
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select js-select2" id="categoryFilter" name="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ $category == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="shopFilter">
                        <i class="fas fa-store" aria-hidden="true"></i>
                        Shop
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select js-select2" id="shopFilter" name="shop">
                            <option value="">All Shops</option>
                            @foreach($shops as $shopItem)
                                <option value="{{ $shopItem->uuid }}" {{ $shop == $shopItem->uuid ? 'selected' : '' }}>{{ $shopItem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="productTypeFilter">
                        <i class="fas fa-boxes-stacked" aria-hidden="true"></i>
                        Product type
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select js-select2" id="productTypeFilter" name="product_type">
                            <option value="">All Product Types</option>
                            @foreach($productTypes as $type)
                                <option value="{{ $type }}" {{ $productType == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="educationBoardFilter">
                        <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                        Board
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select js-select2" id="educationBoardFilter" name="education_board">
                            <option value="">All Education Boards</option>
                            @foreach($educationBoards as $board)
                                <option value="{{ $board }}" {{ $educationBoard == $board ? 'selected' : '' }}>{{ ucfirst($board) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="classLevelFilter">
                        <i class="fas fa-layer-group" aria-hidden="true"></i>
                        Class level
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select js-select2" id="classLevelFilter" name="class_level">
                            <option value="">All Class Levels</option>
                            @foreach($classLevels as $level)
                                <option value="{{ $level }}" {{ $classLevel == $level ? 'selected' : '' }}>{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group filter-group--action">
                    <span class="filter-label filter-label--spacer" aria-hidden="true">
                        <i class="fas fa-layer-group" aria-hidden="true"></i>
                        Class level
                    </span>
                    <button type="button" class="clear-filters-btn" id="productsClearFilters"
                        title="Reset all filters" aria-label="Reset all filters">
                        <i class="fas fa-arrow-rotate-left" aria-hidden="true"></i>
                        <span class="clear-filters-btn__text">Reset</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Sort / Count / View row -->
<div class="container py-2 border-bottom mb-1">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <span class="text-muted small fw-medium">{{ $products->total() }} products found</span>
        <div class="d-flex align-items-center gap-2">
            <select id="sortFilter" class="form-select form-select-sm" style="width:auto;min-width:160px;">
                <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Newest First</option>
                <option value="price_low"  {{ $sortBy == 'price_low'  ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_high" {{ $sortBy == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name"       {{ $sortBy == 'name'       ? 'selected' : '' }}>Name A-Z</option>
            </select>
            <div class="btn-group btn-group-sm" role="group" id="viewToggle" aria-label="View toggle">
                <button type="button" class="btn btn-outline-secondary active" data-view="grid" title="Grid view">
                    <i class="fas fa-th"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary" data-view="list" title="List view">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==================== PRODUCTS GRID SECTION ==================== -->
<section class="products-section">
    <div class="container">
        @if($products->count() > 0)
            <div id="productsContainer" class="products-grid">
                @foreach($products as $product)
                    <div class="product-card" data-product-id="{{ $product->uuid }}">
                        <!-- Product Badges -->
                        @if($product->sale_price && $product->sale_price < $product->base_price)
                            <div class="product-badge sale">Sale</div>
                        @elseif($product->is_featured)
                            <div class="product-badge new">Featured</div>
                        @endif

                        <!-- Product Image -->
                        <div class="product-image">
                            @if($product->main_image_url)
                                <img src="{{ asset('website/' . $product->main_image_url) }}" 
                                     alt="{{ $product->name }}"
                                     onerror="this.src='{{ asset('website/blog/featured-images/XW4x3h32SHkSbJoetKkWpG37K6hGjtYwMDfa4hxs.png') }}'">
                            @else
                                <img src="https://via.placeholder.com/300x200/4361ee/ffffff?text=Product+Image" 
                                     alt="{{ $product->name }}">
                            @endif
                        </div>

                        <div class="product-content">
                            <!-- Category -->
                            {{-- <div class="product-category">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </div> --}}

                            <!-- Product Name -->
                            <h3 class="product-name">{{ $product->name }}</h3>

                            <!-- Shop Name -->
                            {{-- <div class="product-shop">{{ $product->shop->name }}</div>

                            <!-- Description -->
                            <p class="product-description">
                                {{ $product->short_description ?? Str::limit($product->description, 120) }}
                            </p>

                            <!-- Product Attributes -->
                            <div class="product-attributes">
                                @if($product->attributes)
                                    @if($product->attributes->education_board)
                                        <span class="attribute-tag">{{ ucfirst($product->attributes->education_board instanceof \BackedEnum ? $product->attributes->education_board->value : $product->attributes->education_board) }}</span>
                                    @endif
                                    @if($product->attributes->class_level)
                                        <span class="attribute-tag">{{ $product->attributes->class_level }}</span>
                                    @endif
                                    @if($product->attributes->subject)
                                        <span class="attribute-tag">{{ $product->attributes->subject }}</span>
                                    @endif
                                @endif
                                @if($product->brand)
                                    <span class="attribute-tag">{{ $product->brand }}</span>
                                @endif
                            </div> --}}

                            <!-- Pricing -->
                            <div class="product-pricing">
                                <div>
                                    <span class="price-current">
                                        Rs. {{ number_format($product->sale_price ?? $product->base_price) }}
                                    </span>
                                    @if($product->sale_price && $product->sale_price < $product->base_price)
                                        <span class="price-original">
                                            Rs. {{ number_format($product->base_price) }}
                                        </span>
                                    @endif
                                </div>
                                <!-- <div class="stock-status {{ $product->is_in_stock ? ($product->isLowStock() ? 'low-stock' : 'in-stock') : 'out-of-stock' }}">
                                    {{ $product->is_in_stock ? ($product->isLowStock() ? 'Low Stock' : 'In Stock') : 'Out of Stock' }}
                                </div> -->
                            </div>

                            <!-- Actions -->
                            <div class="product-actions">
                                <button class="btn btn-success view-details-btn" 
                                        data-product-id="{{ $product->uuid }}"
                                        data-product-data='@json($product)'>
                                    <i class="fas fa-eye me-2"></i>
                                    View Details
                                </button>
                                <button class="favorite-btn" data-product-id="{{ $product->uuid }}">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="empty-state-title">No products found</h3>
                <p class="empty-state-description">
                    @if(request()->anyFilled(['search', 'category', 'shop', 'product_type', 'education_board', 'class_level']))
                        Try adjusting your search or filter criteria to find what you're looking for.
                    @else
                        No products are currently available. Please check back later.
                    @endif
                </p>
                @if(request()->anyFilled(['search', 'category', 'shop', 'product_type', 'education_board', 'class_level']))
                    <a href="{{ route('website.stationary.index') }}" class="btn btn-primary">
                        Clear All Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>


@endsection

@push('scripts')
@include('website.partials.select2-assets')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var fc      = document.getElementById('productsFiltersContainer');
        var baseUrl = fc ? fc.getAttribute('data-base-url') : window.location.pathname;

        function getProductParams() {
            var params   = new URLSearchParams();
            var current  = new URLSearchParams(window.location.search);

            var search = document.getElementById('productSearchInput');
            if (search && search.value.trim()) params.set('search', search.value.trim());

            var cat = document.getElementById('categoryFilter');
            if (cat && cat.value) params.set('category', cat.value);

            var shop = document.getElementById('shopFilter');
            if (shop && shop.value) params.set('shop', shop.value);

            var type = document.getElementById('productTypeFilter');
            if (type && type.value) params.set('product_type', type.value);

            var board = document.getElementById('educationBoardFilter');
            if (board && board.value) params.set('education_board', board.value);

            var level = document.getElementById('classLevelFilter');
            if (level && level.value) params.set('class_level', level.value);

            var sort = document.getElementById('sortFilter');
            if (sort && sort.value && sort.value !== 'created_at') params.set('sort_by', sort.value);

            // Preserve price range params if set in current URL
            if (current.has('min_price')) params.set('min_price', current.get('min_price'));
            if (current.has('max_price')) params.set('max_price', current.get('max_price'));

            return params;
        }

        function applyProductsFilters() {
            var params = getProductParams();
            window.location.href = baseUrl + (params.toString() ? '?' + params.toString() : '');
        }

        // Filter selects — navigate on change
        var filterIds = ['categoryFilter', 'shopFilter', 'productTypeFilter', 'educationBoardFilter', 'classLevelFilter'];
        if (window.SkoolystWebsiteSelect2) {
            window.SkoolystWebsiteSelect2.bindChanges(filterIds, applyProductsFilters);
        } else if (window.jQuery) {
            window.jQuery('#' + filterIds.join(', #')).on('change', applyProductsFilters);
        } else {
            filterIds.forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.addEventListener('change', applyProductsFilters);
            });
        }

        // Sort — navigate on change (includes active filter params)
        var sortFilter = document.getElementById('sortFilter');
        if (sortFilter) {
            if (window.SkoolystWebsiteSelect2) {
                window.SkoolystWebsiteSelect2.onChange(sortFilter, applyProductsFilters);
            } else if (window.jQuery) {
                window.jQuery(sortFilter).on('change', applyProductsFilters);
            } else {
                sortFilter.addEventListener('change', applyProductsFilters);
            }
        }

        // Search — debounced 500 ms, instant on Enter
        var searchInput = document.getElementById('productSearchInput');
        var searchTimer;
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(applyProductsFilters, 500);
            });
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { clearTimeout(searchTimer); applyProductsFilters(); }
            });
        }

        // Reset button
        var clearBtn = document.getElementById('productsClearFilters');
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                window.location.href = baseUrl;
            });
        }

        // View toggle — local class swap only, no navigation
        var viewBtns          = document.querySelectorAll('#viewToggle .btn');
        var productsContainer = document.getElementById('productsContainer');
        viewBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var view = this.getAttribute('data-view');
                viewBtns.forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');
                if (productsContainer) {
                    if (view === 'list') {
                        productsContainer.classList.replace('products-grid', 'products-list');
                        productsContainer.querySelectorAll('.product-card').forEach(function (c) { c.classList.add('list-view'); });
                    } else {
                        productsContainer.classList.replace('products-list', 'products-grid');
                        productsContainer.querySelectorAll('.product-card').forEach(function (c) { c.classList.remove('list-view'); });
                    }
                }
            });
        });
    });
</script>
<script src="{{ asset('assets/js/product-modal.js') }}"></script>
@endpush