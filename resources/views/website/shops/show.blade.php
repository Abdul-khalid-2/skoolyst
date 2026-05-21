@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/shops_show.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== SHOP HERO SECTION (compact, unified) ==================== -->
<!-- ==================== SHOP HERO SECTION (compact, unified with right-side banner) ==================== -->
<section class="shop-hero-section" id="shop-hero">
    <div class="shop-hero-wrapper">
        <div class="shop-hero-info">
            <!-- <div class="shop-hero-logo">
                @if($shop->logo_url)
                    <img src="{{ asset('website/' . $shop->logo_url) }}" alt="{{ $shop->name }}">
                @else
                    <img src="https://via.placeholder.com/120" alt="{{ $shop->name }}">
                @endif
            </div> -->
            <h1 class="shop-hero-title">{{ $shop->name }}</h1>
            <span class="shop-hero-type">{{ ucfirst($shop->shop_type_label) }}</span>
            
            <div class="shop-hero-rating">
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($shop->rating))
                            <i class="fas fa-star"></i>
                        @elseif($i == ceil($shop->rating) && $shop->rating != floor($shop->rating))
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                <span class="value">{{ number_format($shop->rating, 1) }}</span>
                <span class="count">({{ $shop->total_reviews }} reviews)</span>
            </div>

            <div class="shop-hero-location">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $shop->city }}, {{ $shop->state }}, {{ $shop->country }}</span>
            </div>
        </div>

        <div class="shop-hero-banner">
            @if($shop->banner_url)
                <img src="{{ asset('website/' . $shop->banner_url) }}" alt="{{ $shop->name }} Banner">
            @else
                <div class="banner-placeholder">
                    <i class="fas fa-store-alt"></i>
                    <span>No Banner</span>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- ==================== SHOP SUB NAVIGATION ==================== -->
<nav class="shop-sub-nav">
    <div class="container">
        <div class="shop-sub-nav-container">
            <a href="#about" class="shop-nav-item active sub-nav-item">About</a>
            <a href="#products" class="shop-nav-item sub-nav-item">Products ({{ $shop->products->count() }})</a>
            <a href="#schools" class="shop-nav-item sub-nav-item">Associated Schools ({{ $shop->schoolAssociations->count() }})</a>
            <a href="#reviews" class="shop-nav-item sub-nav-item">Reviews</a>
        </div>
    </div>
</nav>

<!-- ==================== ABOUT SECTION ==================== -->
<section id="about" class="shop-content-section about-section">
    <div class="container">
        <h2 class="section-title">About {{ $shop->name }}</h2>
        <div class="about-content">
            <div class="about-text">
                @if($shop->description)
                    <p>{{ $shop->description }}</p>
                @else
                    <p>No description available for this shop.</p>
                @endif
                
                @if($shop->schoolAssociations->count() > 0)
                    <div class="mt-4">
                        <h4>School Partnerships</h4>
                        <p>This shop is officially associated with {{ $shop->schoolAssociations->count() }} educational institutions, providing specialized products and services tailored to their needs.</p>
                    </div>
                @endif
            </div>
            
            <div class="shop-details-card">
                <h4>Shop Details</h4>
                <div class="detail-item">
                    <span class="detail-label">Shop Type:</span>
                    <span class="detail-value">{{ ucfirst($shop->shop_type_label) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value">{{ $shop->city }}, {{ $shop->state }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Address:</span>
                    <span class="detail-value">{{ $shop->address ?? 'Not specified' }}</span>
                </div>
                {{-- @if($shop->email)
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $shop->email }}</span>
                </div>
                @endif
                @if($shop->phone)
                <div class="detail-item">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $shop->phone }}</span>
                </div>
                @endif --}}
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        @if($shop->is_verified)
                            <span class="text-success">✓ Verified Shop</span>
                        @else
                            <span class="text-warning">Unverified</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PRODUCTS SECTION ==================== -->
<!-- ==================== FEATURED PRODUCTS SECTION ==================== -->
<section class="products-section">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <p class="section-subtitle">
            Discover popular and essential educational products from our trusted shops
        </p>

        @if($shop->products->count() > 0)
            <div class="products-grid">
                @foreach($shop->products as $product)
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


<!-- ==================== ASSOCIATED SCHOOLS SECTION ==================== -->
@if($shop->schoolAssociations->count() > 0)
<section id="schools" class="shop-content-section schools-section">
    <div class="container">
        <h2 class="section-title">Associated Schools</h2>
        <p class="section-subtitle" style="text-align: center; color: #666; margin-bottom: 3rem;">
            This shop is officially associated with the following educational institutions
        </p>

        <div class="schools-grid">
            @foreach($shop->schoolAssociations as $association)
                @if($association->school)
                    <div class="school-card">
                        <h3 class="school-name">{{ $association->school->name }}</h3>
                        <span class="school-type">{{ $association->school->school_gender_type?->label() }} · {{ $association->school->school_ownership_type?->label() }}</span>
                        <div class="school-location">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $association->school->city }}, {{ $association->school->state ?? '' }}
                        </div>
                        <div class="association-type">
                            {{ ucfirst($association->association_type instanceof \BackedEnum ? $association->association_type->value : $association->association_type) }} Partner
                        </div>
                        @if($association->discount_percentage > 0)
                            <div class="discount-badge" style="margin-top: 1rem; background: #ff6b35; color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem;">
                                {{ $association->discount_percentage }}% Discount
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ==================== REVIEWS SECTION ==================== -->
<section id="reviews" class="shop-content-section" style="background: #f8f9fa;">
    <div class="container">
        <h2 class="section-title">Customer Reviews</h2>
        
        @if($shop->total_reviews > 0)
            <div style="text-align: center; margin-bottom: 3rem;">
                <div class="shop-rating-large" style="justify-content: center;">
                    <div class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($shop->rating))
                                <i class="fas fa-star"></i>
                            @elseif($i == ceil($shop->rating) && $shop->rating != floor($shop->rating))
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="rating-value">{{ number_format($shop->rating, 1) }}</span>
                    <span class="rating-count">based on {{ $shop->total_reviews }} reviews</span>
                </div>
            </div>
            
            <!-- Reviews content would go here -->
            <div style="text-align: center; padding: 2rem;">
                <p>Reviews feature coming soon!</p>
            </div>
        @else
            <div class="no-results">
                <div class="no-results-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 class="no-results-title">No Reviews Yet</h3>
                <p>Be the first to review this shop!</p>
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Smooth scrolling for navigation
    document.addEventListener('DOMContentLoaded', function() {
        const navItems = document.querySelectorAll('.sub-nav-item');
        const sections = document.querySelectorAll('.shop-content-section');
        
        // Update active nav item on scroll
        function updateActiveNav() {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                if (window.scrollY >= sectionTop) {
                    current = section.getAttribute('id');
                }
            });

            navItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === `#${current}`) {
                    item.classList.add('active');
                }
            });
        }

        // Smooth scroll to section
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                if (targetSection) {
                    window.scrollTo({
                        top: targetSection.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        window.addEventListener('scroll', updateActiveNav);
        updateActiveNav(); // Initialize on load
    });
</script>
<script src="{{ asset('assets/js/product-modal.js') }}"></script>
@endpush