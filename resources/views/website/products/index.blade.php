@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== PRODUCTS HERO SECTION ==================== */
    .products-hero {
        background: linear-gradient(135deg, #4361ee 0%, #38b000 100%);
        color: white;
        padding: 80px 0 40px;
        position: relative;
        overflow: hidden;
    }

    .products-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        animation: float 20s linear infinite;
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    }

    .hero-subtitle {
        font-size: 1.1rem;
        opacity: 0.95;
        margin-bottom: 2rem;
    }

    /* ==================== SEARCH AND FILTER SECTION ==================== */
    .search-filter-section {
        background: white;
        padding: 2rem 0;
        border-bottom: 1px solid #e0e0e0;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .search-container {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .search-input {
        flex: 1;
        padding: 0.8rem 1.5rem;
        border: 2px solid #e0e0e0;
        border-radius: 50px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .search-btn {
        padding: 0.8rem 2rem;
        background: #4361ee;
        color: white;
        border: none;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        background: #3a56d4;
        transform: translateY(-2px);
    }

    .filter-container {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-select {
        padding: 0.6rem 1rem;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        min-width: 150px;
    }

    .filter-select:focus {
        outline: none;
        border-color: #4361ee;
    }

    .results-count {
        color: #666;
        font-size: 0.9rem;
        margin-left: auto;
    }

    .view-toggle {
        display: flex;
        gap: 0.5rem;
    }

    .view-btn {
        padding: 0.5rem;
        border: 1px solid #e0e0e0;
        background: white;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .view-btn.active {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    /* ==================== PRODUCTS GRID SECTION ==================== */
    .products-section {
        padding: 3rem 0;
        background: #f8f9fa;
        min-height: 60vh;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .products-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Product Card Styles */
    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
    }

    .product-card.list-view {
        display: flex;
        height: 200px;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .product-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: #38b000;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }

    .product-badge.sale {
        background: #ff6b35;
    }

    .product-badge.new {
        background: #4361ee;
    }

    .product-image {
        height: 200px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .product-card.list-view .product-image {
        width: 200px;
        height: 100%;
        flex-shrink: 0;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.1);
    }

    .product-content {
        padding: 1.5rem;
        flex: 1;
    }

    .product-category {
        color: #4361ee;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }

    .product-name {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1a1a1a;
        line-height: 1.4;
    }

    .product-card.list-view .product-name {
        font-size: 1.2rem;
    }

    .product-shop {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .product-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-attributes {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .attribute-tag {
        background: #f8f9fa;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        color: #666;
        border: 1px solid #e0e0e0;
    }

    .product-pricing {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .price-current {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .price-original {
        font-size: 1rem;
        color: #999;
        text-decoration: line-through;
        margin-left: 0.5rem;
    }

    .stock-status {
        font-size: 0.9rem;
        font-weight: 600;
    }

    .in-stock {
        color: #38b000;
    }

    .low-stock {
        color: #ff6b35;
    }

    .out-of-stock {
        color: #dc3545;
    }

    .product-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        padding: 0.6rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-align: center;
        font-size: 0.9rem;
    }

    .btn-success {
        background: #38b000;
        color: white;
        flex: 2;
    }

    .btn-secondary {
        background: #f8f9fa;
        color: #4361ee;
        border: 2px solid #4361ee;
    }

    .btn-outline {
        background: transparent;
        color: #666;
        border: 1px solid #e0e0e0;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-success:hover {
        background: #2f9a00;
    }

    .favorite-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .favorite-btn:hover {
        background: #fff5f5;
        border-color: #ff6b6b;
    }

    .favorite-btn.active {
        background: #ff6b6b;
        border-color: #ff6b6b;
        color: white;
    }

    /* ==================== PAGINATION ==================== */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 3rem;
    }

    .page-link {
        padding: 0.5rem 1rem;
        border: 1px solid #e0e0e0;
        background: white;
        color: #333;
        text-decoration: none;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    .page-link.active {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    /* ==================== MODAL STYLES ==================== */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 1rem;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 15px;
        max-width: 500px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        color: #333;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-product {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .modal-product-image {
        width: 120px;
        height: 120px;
        background: #f8f9fa;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .modal-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .modal-product-info {
        flex: 1;
    }

    .modal-product-name {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1a1a1a;
    }

    .modal-product-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: #4361ee;
        margin-bottom: 0.5rem;
    }

    .modal-product-shop {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .quantity-btn {
        width: 40px;
        height: 40px;
        border: 1px solid #e0e0e0;
        background: white;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .quantity-btn:hover {
        background: #f8f9fa;
        border-color: #4361ee;
    }

    .quantity-input {
        width: 60px;
        height: 40px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        text-align: center;
        font-size: 1rem;
        font-weight: 600;
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
    }

    .modal-actions .btn {
        flex: 1;
        padding: 1rem;
    }

    /* ==================== EMPTY STATE ==================== */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #666;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #e0e0e0;
        margin-bottom: 1rem;
    }

    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #333;
    }

    .empty-state-description {
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    /* ==================== RESPONSIVE DESIGN ==================== */
    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .search-container {
            flex-direction: column;
        }

        .filter-container {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-select {
            width: 100%;
        }

        .results-count {
            margin-left: 0;
            text-align: center;
        }

        .product-card.list-view {
            flex-direction: column;
            height: auto;
        }

        .product-card.list-view .product-image {
            width: 100%;
            height: 200px;
        }

        .modal-product {
            flex-direction: column;
            text-align: center;
        }

        .modal-product-image {
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
        }

        .modal-actions {
            flex-direction: column;
        }
    }

    @media (max-width: 480px) {
        .products-grid {
            grid-template-columns: 1fr;
        }

        .hero-title {
            font-size: 2rem;
        }

        .product-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')

<!-- ==================== HERO SECTION ==================== -->
<section class="products-hero">
    <div class="container">
        <h1 class="hero-title">Educational Products Marketplace</h1>
        <p class="hero-subtitle">
            Discover textbooks, stationery, uniforms, and all essential educational supplies from trusted shops
        </p>
    </div>
</section>

<!-- ==================== SEARCH AND FILTER SECTION ==================== -->
<section class="search-filter-section">
    <div class="container">
        <form action="{{ route('website.stationary.index') }}" method="GET" id="productsFilterForm">
            <div class="search-container">
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="Search for products, books, stationery..." 
                       value="{{ $search }}"
                       id="searchInput">
                <button type="submit" class="search-btn" id="searchButton">
                    <i class="fas fa-search me-2"></i> Search
                </button>
            </div>

            <div class="filter-container">
                <!-- Category Filter -->
                <select class="filter-select" name="category" id="categoryFilter">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ $category == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Shop Filter -->
                <select class="filter-select" name="shop" id="shopFilter">
                    <option value="">All Shops</option>
                    @foreach($shops as $shopItem)
                        <option value="{{ $shopItem->uuid }}" {{ $shop == $shopItem->uuid ? 'selected' : '' }}>
                            {{ $shopItem->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Product Type Filter -->
                <select class="filter-select" name="product_type" id="productTypeFilter">
                    <option value="">All Product Types</option>
                    @foreach($productTypes as $type)
                        <option value="{{ $type }}" {{ $productType == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>

                <!-- Education Board Filter -->
                <select class="filter-select" name="education_board" id="educationBoardFilter">
                    <option value="">All Education Boards</option>
                    @foreach($educationBoards as $board)
                        <option value="{{ $board }}" {{ $educationBoard == $board ? 'selected' : '' }}>
                            {{ ucfirst($board) }}
                        </option>
                    @endforeach
                </select>

                <!-- Class Level Filter -->
                <select class="filter-select" name="class_level" id="classLevelFilter">
                    <option value="">All Class Levels</option>
                    @foreach($classLevels as $level)
                        <option value="{{ $level }}" {{ $classLevel == $level ? 'selected' : '' }}>
                            {{ $level }}
                        </option>
                    @endforeach
                </select>

                <!-- Sort Filter -->
                <select class="filter-select" name="sort_by" id="sortFilter">
                    <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Newest First</option>
                    <option value="price_low" {{ $sortBy == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ $sortBy == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Name A-Z</option>
                </select>

                <!-- Price Range (Hidden inputs for now, can be enhanced with range sliders) -->
                <input type="hidden" name="min_price" value="{{ $minPrice }}">
                <input type="hidden" name="max_price" value="{{ $maxPrice }}">

                <div class="results-count" id="resultsCount">
                    {{ $products->total() }} products found
                </div>

                <div class="view-toggle">
                    <button type="button" class="view-btn active" data-view="grid">
                        <i class="fas fa-th"></i>
                    </button>
                    <button type="button" class="view-btn" data-view="list">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

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
                            <div class="product-category">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </div>

                            <!-- Product Name -->
                            <h3 class="product-name">{{ $product->name }}</h3>

                            <!-- Shop Name -->
                            <div class="product-shop">{{ $product->shop->name }}</div>

                            <!-- Description -->
                            <p class="product-description">
                                {{ $product->short_description ?? Str::limit($product->description, 120) }}
                            </p>

                            <!-- Product Attributes -->
                            <div class="product-attributes">
                                @if($product->attributes)
                                    @if($product->attributes->education_board)
                                        <span class="attribute-tag">{{ ucfirst($product->attributes->education_board) }}</span>
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
                            </div>

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
                                <div class="stock-status {{ $product->is_in_stock ? ($product->isLowStock() ? 'low-stock' : 'in-stock') : 'out-of-stock' }}">
                                    {{ $product->is_in_stock ? ($product->isLowStock() ? 'Low Stock' : 'In Stock') : 'Out of Stock' }}
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="product-actions">
                                <button class="btn btn-success add-to-cart-btn" 
                                        data-product-id="{{ $product->uuid }}"
                                        {{ !$product->is_in_stock ? 'disabled' : '' }}>
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    {{ $product->is_in_stock ? 'Add to Cart' : 'Out of Stock' }}
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

<!-- ==================== ADD TO CART MODAL ==================== -->
<div class="modal-overlay" id="addToCartModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Add to Cart</h3>
            <button class="modal-close" id="closeModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-product">
                <div class="modal-product-image">
                    <img id="modalProductImage" src="" alt="Product Image">
                </div>
                <div class="modal-product-info">
                    <h4 id="modalProductName" class="modal-product-name"></h4>
                    <div id="modalProductPrice" class="modal-product-price"></div>
                    <div id="modalProductShop" class="modal-product-shop"></div>
                    <div id="modalStockStatus" class="stock-status"></div>
                </div>
            </div>

            <div class="quantity-selector">
                <label for="quantity">Quantity:</label>
                <button type="button" class="quantity-btn" id="decreaseQuantity">-</button>
                <input type="number" id="quantity" name="quantity" value="1" min="1" max="10" class="quantity-input">
                <button type="button" class="quantity-btn" id="increaseQuantity">+</button>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" id="continueShopping">
                    Continue Shopping
                </button>
                <button type="button" class="btn btn-success" id="confirmAddToCart">
                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when filters change
        const filterSelects = document.querySelectorAll('.filter-select');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('productsFilterForm').submit();
            });
        });

        // View Toggle
        const viewButtons = document.querySelectorAll('.view-btn');
        const productsContainer = document.getElementById('productsContainer');
        
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const view = this.getAttribute('data-view');
                
                // Update active button
                viewButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Update view
                if (view === 'list') {
                    productsContainer.classList.remove('products-grid');
                    productsContainer.classList.add('products-list');
                    document.querySelectorAll('.product-card').forEach(card => {
                        card.classList.add('list-view');
                    });
                } else {
                    productsContainer.classList.remove('products-list');
                    productsContainer.classList.add('products-grid');
                    document.querySelectorAll('.product-card').forEach(card => {
                        card.classList.remove('list-view');
                    });
                }
            });
        });

        // Add to Cart Modal functionality
        const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
        const modal = document.getElementById('addToCartModal');
        const closeModal = document.getElementById('closeModal');
        const continueShopping = document.getElementById('continueShopping');
        const confirmAddToCart = document.getElementById('confirmAddToCart');
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.getElementById('decreaseQuantity');
        const increaseBtn = document.getElementById('increaseQuantity');

        let currentProduct = null;

        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (this.disabled) return;
                
                const productCard = this.closest('.product-card');
                
                // Get product details
                const productName = productCard.querySelector('.product-name').textContent;
                const productPrice = productCard.querySelector('.price-current').textContent;
                const productShop = productCard.querySelector('.product-shop').textContent;
                const productImage = productCard.querySelector('.product-image img').src;
                const stockStatus = productCard.querySelector('.stock-status').textContent;
                const isInStock = !this.disabled;

                // Populate modal
                document.getElementById('modalProductName').textContent = productName;
                document.getElementById('modalProductPrice').textContent = productPrice;
                document.getElementById('modalProductShop').textContent = productShop;
                document.getElementById('modalProductImage').src = productImage;
                document.getElementById('modalStockStatus').textContent = stockStatus;
                document.getElementById('modalStockStatus').className = 'stock-status ' + 
                    (isInStock ? (stockStatus.includes('Low') ? 'low-stock' : 'in-stock') : 'out-of-stock');

                // Reset quantity
                quantityInput.value = 1;

                // Store current product info
                currentProduct = {
                    name: productName,
                    price: productPrice,
                    shop: productShop,
                    image: productImage,
                    isInStock: isInStock
                };

                // Show modal
                modal.classList.add('active');
            });
        });

        // Close modal
        function closeModalFunc() {
            modal.classList.remove('active');
            currentProduct = null;
        }

        closeModal.addEventListener('click', closeModalFunc);
        continueShopping.addEventListener('click', closeModalFunc);

        // Quantity controls
        decreaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        increaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue < 10) {
                quantityInput.value = currentValue + 1;
            }
        });

        // Confirm add to cart
        confirmAddToCart.addEventListener('click', function() {
            if (!currentProduct) return;

            const quantity = parseInt(quantityInput.value);
            
            // Here you would typically make an AJAX call to add to cart
            // For now, just show success message
            showToast(`${currentProduct.name} added to cart successfully!`, 'success');

            // Close modal
            closeModalFunc();
        });

        // Favorite functionality
        const favoriteButtons = document.querySelectorAll('.favorite-btn');
        favoriteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productCard = this.closest('.product-card');
                const productName = productCard.querySelector('.product-name').textContent;
                const isActive = this.classList.contains('active');

                // Toggle favorite state
                this.classList.toggle('active');

                // Here you would typically make an AJAX call to update favorites
                if (!isActive) {
                    showToast(`${productName} added to favorites!`, 'success');
                } else {
                    showToast(`${productName} removed from favorites!`, 'info');
                }
            });
        });

        // Toast notification function
        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#38b000' : type === 'error' ? '#dc3545' : '#4361ee'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                z-index: 10000;
                transform: translateX(100%);
                transition: transform 0.3s ease;
            `;
            toast.textContent = message;

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);

            // Remove after 3 seconds
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }
    });
</script>
@endpush