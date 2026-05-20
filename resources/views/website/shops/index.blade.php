@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/shops.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/browse_schools.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== HERO SECTION ==================== -->
<section class="ecommerce-hero-section" id="ecommerce-hero">
    <div class="ecommerce-hero-content">
        <h1 class="ecommerce-hero-title">SKOOLYST EduMart</h1>
        <p class="ecommerce-hero-subheading">
            Discover educational supplies, books, uniforms, and more from trusted school-affiliated shops. 
            Everything you need for academic success in one place.
        </p>
    </div>
</section>

<!-- ==================== FILTERS SECTION ==================== -->
<section class="filter-section" aria-label="Filter shops">
    <div class="container">
        <header class="filter-section__header text-center">
            <p class="filter-section__eyebrow">Refine your search</p>
            <h2 class="filter-section__title h5 mb-0">Find the right shop</h2>
        </header>

        <div class="filter-bar filter-bar--browse">
            <div class="filter-container" id="shopFiltersContainer" data-base-url="{{ route('website.shop.index') }}">

                <div class="filter-group filter-group--search">
                    <label class="filter-label" for="shopSearchInput">
                        <i class="fas fa-magnifying-glass" aria-hidden="true"></i>
                        Search
                    </label>
                    <div class="filter-input-wrap">
                        <input type="search" class="filter-search" id="shopSearchInput" name="search"
                            placeholder="Search shops or products..."
                            value="{{ $search }}" autocomplete="off" inputmode="search">
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="genderTypeFilter">
                        <i class="fas fa-venus-mars" aria-hidden="true"></i>
                        Gender type
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select" id="genderTypeFilter" name="school_gender_type">
                            <option value="">All gender types</option>
                            @foreach($schoolGenderTypes as $value => $label)
                            <option value="{{ $value }}" {{ ($schoolGenderType ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="ownershipTypeFilter">
                        <i class="fas fa-building-columns" aria-hidden="true"></i>
                        Ownership
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select" id="ownershipTypeFilter" name="school_ownership_type">
                            <option value="">All ownership types</option>
                            @foreach($schoolOwnershipTypes as $value => $label)
                            <option value="{{ $value }}" {{ ($schoolOwnershipType ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="cityFilter">
                        <i class="fas fa-location-dot" aria-hidden="true"></i>
                        City
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select" id="cityFilter" name="city">
                            <option value="">All Cities</option>
                            @foreach($cities as $cityItem)
                            <option value="{{ $cityItem }}" {{ $city == $cityItem ? 'selected' : '' }}>{{ $cityItem }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="shopTypeFilter">
                        <i class="fas fa-store" aria-hidden="true"></i>
                        Shop type
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select" id="shopTypeFilter" name="shop_type">
                            <option value="">All Shop Types</option>
                            <option value="stationery" {{ $shopType == 'stationery' ? 'selected' : '' }}>Stationery</option>
                            <option value="book_store" {{ $shopType == 'book_store' ? 'selected' : '' }}>Book Store</option>
                            <option value="school_affiliated" {{ $shopType == 'school_affiliated' ? 'selected' : '' }}>School Affiliated</option>
                            <option value="mixed" {{ $shopType == 'mixed' ? 'selected' : '' }}>Mixed</option>
                        </select>
                    </div>
                </div>

                <div class="filter-group filter-group--action">
                    <span class="filter-label filter-label--spacer" aria-hidden="true">
                        <i class="fas fa-store" aria-hidden="true"></i>
                        Shop type
                    </span>
                    <button type="button" class="clear-filters-btn" id="shopClearFilters"
                        title="Reset all filters" aria-label="Reset all filters">
                        <i class="fas fa-arrow-rotate-left" aria-hidden="true"></i>
                        <span class="clear-filters-btn__text">Reset</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- ==================== FEATURED SHOPS SECTION ==================== -->
<section class="shops-section">
    <div class="container">
        <h2 class="section-title">Featured Educational Shops</h2>
        <p class="section-subtitle">
            Browse through verified shops specializing in educational materials, school supplies, and academic resources
        </p>

        @if($shops->count() > 0)
        <div class="shops-grid">
            @foreach($shops as $shop)
            <div class="shop-card">
                <div class="shop-banner" style="
                        @if($shop->banner_url)
                            background-image: url('{{ asset('website/' . $shop->banner_url) }}');
                        @else
                            background: #0f4077;
                        @endif
                        background-size: cover; 
                        background-position: center; 
                        background-repeat: no-repeat;">
                    <div class="shop-logo">
                        @if($shop->logo_url)
                        <img src="{{ asset('website/' . $shop->logo_url) }}" alt="{{ $shop->name }}">
                        @else
                        <img src="https://via.placeholder.com/80" alt="{{ $shop->name }}">
                        @endif
                    </div>
                </div>
                <div class="shop-content">
                    <h3 class="shop-name">{{ $shop->name }}</h3>
                    <span class="shop-type">{{ ucfirst($shop->shop_type_label) }}</span>

                    <div class="shop-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $shop->city }}, {{ $shop->state }}</span>
                    </div>

                    <div class="shop-rating">
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <=floor($shop->rating))
                                <i class="fas fa-star"></i>
                                @elseif($i == ceil($shop->rating) && $shop->rating != floor($shop->rating))
                                <i class="fas fa-star-half-alt"></i>
                                @else
                                <i class="far fa-star"></i>
                                @endif
                                @endfor
                        </div>
                        <span class="rating-value">{{ number_format($shop->rating, 1) }} ({{ $shop->total_reviews }} reviews)</span>
                    </div>

                    <p class="shop-description">
                        {{ Str::limit($shop->description, 120) }}
                    </p>

                    <!-- School Associations -->
                    @if($shop->schoolAssociations->count() > 0)
                    <div class="school-associations">
                        <div class="school-tags">
                            @foreach($shop->schoolAssociations->take(3) as $association)
                            <span class="school-tag">{{ $association->school->name }}</span>
                            @endforeach
                            @if($shop->schoolAssociations->count() > 3)
                            <span class="school-tag">+{{ $shop->schoolAssociations->count() - 3 }} more</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="shop-actions">
                        <a href="{{ route('website.shop.show', $shop->uuid) }}" class="btn btn-primary">Visit Shop</a>
                        <a href="{{ route('website.stationary.index') }}?shop={{ $shop->uuid }}" class="btn btn-secondary">View Products</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($shops->hasPages())
        <div class="pagination-container">
            {{ $shops->appends(request()->query())->links() }}
        </div>
        @endif
        @else
        <div class="no-results">
            <div class="no-results-icon">
                <i class="fas fa-store-slash"></i>
            </div>
            <h3 class="no-results-title">No shops found</h3>
            <p>Try adjusting your filters or search terms to find what you're looking for.</p>
            <a href="{{ route('website.shop.index') }}" class="btn btn-primary mt-3">Clear Filters</a>
        </div>
        @endif
    </div>
</section>

<!-- ==================== CATEGORIES SECTION ==================== -->
<section class="categories-section">
    <div class="container">
        <h2 class="section-title">Product Categories</h2>
        <p class="section-subtitle">
            Explore our wide range of educational products organized by categories
        </p>

        @if($categories->count() > 0)
        <div class="categories-grid">
            @foreach($categories as $category)
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-{{ $category->icon ?? 'shopping-bag' }}"></i>
                </div>
                <h3 class="category-name">{{ $category->name }}</h3>
                <p class="category-description">
                    {{ Str::limit($category->description ?? 'Various products in this category', 100) }}
                </p>
                <div class="category-count">{{ $category->products_count }} Products</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

<!-- ==================== FEATURED PRODUCTS SECTION ==================== -->
<section class="products-section">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <p class="section-subtitle">
            Discover popular and essential educational products from our trusted shops
        </p>

        @if($featuredProducts->count() > 0)
        <div class="products-grid">
            @foreach($featuredProducts as $product)
            <div class="product-card">
                @if($product->sale_price && $product->sale_price < $product->base_price)
                    <div class="product-badge">Sale</div>
                    @elseif($product->is_featured)
                    <div class="product-badge">Featured</div>
                    @endif

                    <div class="product-image">
                        @if($product->main_image_url)
                        <img src="{{ asset('website/' . $product->main_image_url) }}" alt="{{ $product->name }}">
                        @else
                        <img src="https://via.placeholder.com/300x200" alt="{{ $product->name }}">
                        @endif
                    </div>

                    <div class="product-content">
                        <h3 class="product-name">{{ $product->name }}</h3>

                        <div class="product-pricing">
                            <div>
                                <span class="price-current">Rs. {{ number_format($product->sale_price ?? $product->base_price) }}</span>
                                @if($product->sale_price && $product->sale_price < $product->base_price)
                                    <span class="price-original">Rs. {{ number_format($product->base_price) }}</span>
                                    @endif
                            </div>
                            <div class="stock-status {{ $product->is_in_stock ? 'in-stock' : 'low-stock' }}">
                                {{ $product->is_in_stock ? 'In Stock' : 'Low Stock' }}
                            </div>
                        </div>

                        <div class="product-actions">
                            <button class="btn btn-success quick-view-btn"
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
        @endif
    </div>
</section>



<!-- ==================== HOW IT WORKS SECTION ==================== -->
<section class="how-it-works">
    <div class="container">
        <h2 class="section-title">How It Works</h2>
        <p class="section-subtitle">
            Simple steps to get your educational supplies through SKOOLYST Marketplace
        </p>

        <div class="steps-grid">
            <div class="step-card">
                <h3 class="step-title">Browse Shops & Products</h3>
                <p class="step-description">
                    Explore verified educational shops and discover products tailored to your academic needs.
                </p>
            </div>

            <div class="step-card">
                <h3 class="step-title">Compare & Select</h3>
                <p class="step-description">
                    Compare prices, read reviews, and choose the best products from trusted sellers.
                </p>
            </div>

            <div class="step-card">
                <h3 class="step-title">Place Your Order</h3>
                <p class="step-description">
                    Add items to cart and complete your purchase with secure payment options.
                </p>
            </div>

            <div class="step-card">
                <h3 class="step-title">Receive & Enjoy</h3>
                <p class="step-description">
                    Get delivery at your doorstep or pick up from nearby locations.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CTA SECTION ==================== -->
<section class="ecommerce-cta">
    <div class="container">
        <h2 class="cta-title">Ready to Shop Smart?</h2>
        <p class="cta-subtitle">
            Join thousands of students, parents, and educators who trust SKOOLYST for their educational needs
        </p>

        <div class="cta-buttons">
            <a href="{{ route('website.shop.index') }}" class="cta-btn primary">
                <i class="fas fa-store me-2"></i> Explore All Shops
            </a>
            <a href="{{ route('website.stationary.index') }}" class="cta-btn secondary">
                <i class="fas fa-shopping-bag me-2"></i> Browse Products
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var fc      = document.getElementById('shopFiltersContainer');
        var baseUrl = fc ? fc.getAttribute('data-base-url') : window.location.pathname;

        function applyShopFilters() {
            var params = new URLSearchParams();

            var search = document.getElementById('shopSearchInput');
            if (search && search.value.trim()) params.set('search', search.value.trim());

            var gender = document.getElementById('genderTypeFilter');
            if (gender && gender.value) params.set('school_gender_type', gender.value);

            var ownership = document.getElementById('ownershipTypeFilter');
            if (ownership && ownership.value) params.set('school_ownership_type', ownership.value);

            var city = document.getElementById('cityFilter');
            if (city && city.value) params.set('city', city.value);

            var shopType = document.getElementById('shopTypeFilter');
            if (shopType && shopType.value) params.set('shop_type', shopType.value);

            window.location.href = baseUrl + (params.toString() ? '?' + params.toString() : '');
        }

        ['genderTypeFilter', 'ownershipTypeFilter', 'cityFilter', 'shopTypeFilter'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('change', applyShopFilters);
        });

        var searchInput = document.getElementById('shopSearchInput');
        var searchTimer;
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(applyShopFilters, 500);
            });
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { clearTimeout(searchTimer); applyShopFilters(); }
            });
        }

        var clearBtn = document.getElementById('shopClearFilters');
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                window.location.href = baseUrl;
            });
        }
    });
</script>
<script src="{{ asset('assets/js/product-modal.js') }}"></script>
@endpush