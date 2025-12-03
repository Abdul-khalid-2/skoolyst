@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== SHOP HERO SECTION ==================== */
    .shop-hero {
        background: linear-gradient(135deg, #4361ee 0%, #38b000 100%);
        color: white;
        padding: 80px 0 40px;
        position: relative;
        overflow: hidden;
    }

    .shop-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
    }

    .shop-hero-content {
        position: relative;
        z-index: 2;
    }

    .shop-header {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .shop-logo-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        background: white;
        overflow: hidden;
        flex-shrink: 0;
    }

    .shop-logo-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .shop-info {
        flex: 1;
    }

    .shop-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .shop-type-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
    }

    .shop-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .meta-item i {
        color: #38b000;
    }

    .shop-rating-large {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .rating-stars {
        color: #ffc107;
    }

    .rating-value {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .rating-count {
        opacity: 0.9;
        font-size: 0.9rem;
    }

    /* ==================== SHOP NAVIGATION ==================== */
    .shop-sub-nav {
        background: white;
        border-bottom: 1px solid #e0e0e0;
        position: sticky;
        top: 70px; /* Adjust based on your main header height */
        z-index: 99; /* Lower than main header */
        margin-top: 0;
    }

    .shop-sub-nav-container {
        display: flex;
        gap: 2rem;
        overflow-x: auto;
        padding: 0;
    }

    .shop-nav-item {
        padding: 1rem 0;
        color: #666;
        text-decoration: none;
        font-weight: 600;
        white-space: nowrap;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .shop-nav-item:hover,
    .shop-nav-item.active {
        color: #4361ee;
        border-bottom-color: #4361ee;
    }

    /* ==================== SHOP CONTENT SECTIONS ==================== */
    .shop-content-section {
        padding: 4rem 0;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 2rem;
        color: #1a1a1a;
    }

    /* ==================== ABOUT SECTION ==================== */
    .about-section {
        background: #f8f9fa;
    }

    .about-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 3rem;
    }

    .about-text {
        line-height: 1.8;
        color: #555;
    }

    .shop-details-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .detail-item {
        display: flex;
        justify-content: between;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-label {
        font-weight: 600;
        color: #333;
        min-width: 120px;
    }

    .detail-value {
        color: #666;
        flex: 1;
    }

    /* ==================== ASSOCIATED SCHOOLS SECTION ==================== */
    .schools-section {
        background: white;
    }

    .schools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .school-card {
        background: white;
        border: 2px solid #f0f0f0;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .school-card:hover {
        border-color: #4361ee;
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .school-name {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a1a;
    }

    .school-type {
        display: inline-block;
        background: #4361ee;
        color: white;
        padding: 0.3rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .school-location {
        color: #666;
        margin-bottom: 1rem;
    }

    .association-type {
        background: #38b000;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* ==================== PRODUCTS SECTION ==================== */
    .products-section {
        background: #f8f9fa;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }

    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
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

    .product-image {
        height: 200px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
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
        font-size: 1.2rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .price-original {
        font-size: 0.9rem;
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

    .product-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* .btn {
        padding: 0.6rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-align: center;
        font-size: 0.9rem;
    } */

    .btn-success {
        background: #38b000;
        color: white;
        flex: 2;
    }

    .btn-secondary {
        background: #f8f9fa;
        color: #4361ee;
        border: 2px solid #4361ee;
        flex: 1;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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
    /* ==================== NO RESULTS STYLES ==================== */
    .no-results {
        text-align: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: 15px;
        margin: 2rem 0;
    }

    .no-results-icon {
        font-size: 3rem;
        color: #ccc;
        margin-bottom: 1rem;
    }

    .no-results-title {
        font-size: 1.3rem;
        color: #666;
        margin-bottom: 1rem;
    }

    /* ==================== MODAL STYLES ==================== */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
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
        border-radius: 12px;
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
        border-radius: 12px 12px 0 0;
    }

    .modal-title {
        font-size: 1.25rem;
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
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .modal-close:hover {
        background: #e9ecef;
        color: #333;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-product {
        display: grid;
        grid-template-columns: 150px 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .modal-product-image {
        width: 150px;
        height: 150px;
        background: #f8f9fa;
        border-radius: 8px;
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
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1a1a1a;
        line-height: 1.3;
    }

    .modal-product-category {
        color: #4361ee;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }

    .modal-product-shop {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-product-price {
        font-size: 1.4rem;
        font-weight: 700;
        color: #4361ee;
        margin-bottom: 0.03rem;
    }

    .modal-stock-status {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .modal-stock-status.in-stock {
        background: #e8f5e8;
        color: #38b000;
    }

    .modal-stock-status.low-stock {
        background: #fff3e8;
        color: #ff6b35;
    }

    .modal-stock-status.out-of-stock {
        background: #ffe8e8;
        color: #dc3545;
    }

    .modal-attributes {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .modal-attribute-tag {
        background: #f8f9fa;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        color: #666;
        border: 1px solid #e0e0e0;
    }

    .modal-description {
        /* background: #f8f9fa; */
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        /* border-left: 3px solid #4361ee; */
    }

    .modal-description h5 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .modal-description p {
        color: #666;
        line-height: 1.5;
        font-size: 0.9rem;
        margin: 0;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        /* padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px; */
    }

    .quantity-label {
        font-weight: 600;
        color: #333;
        min-width: 80px;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .quantity-btn {
        width: 36px;
        height: 36px;
        border: 1px solid #e0e0e0;
        background: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.1rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .quantity-btn:hover {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    .quantity-input {
        width: 50px;
        height: 36px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        text-align: center;
        font-size: 1rem;
        font-weight: 600;
    }

    .quantity-input:focus {
        outline: none;
        border-color: #4361ee;
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
    }

    .modal-actions .btn {
        flex: 1;
        padding: 0.5rem;
        font-size: 0.78rem;
    }

    /* Responsive Design for Modal */
    @media (max-width: 768px) {
        .modal-content {
            max-width: 95%;
            margin: 1rem;
        }
        
        .modal-product {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 1rem;
        }
        
        .modal-product-image {
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
        }
        
        .modal-product-name {
            font-size: 1.2rem;
        }
        
        .modal-product-price {
            font-size: 1.3rem;
        }
        
        .quantity-selector {
            flex-direction: inherit;
            align-items: anchor-center;
            gap: 0.75rem;
        }
        
        .quantity-controls {
            justify-content: center;
        }
        
        .modal-actions {
            flex-direction: column;
        }
    }

    @media (max-width: 480px) {
        .modal-body {
            padding: 1.25rem;
        }
        
        .modal-header {
            padding: 1rem 1.25rem;
        }
        
        .modal-title {
            font-size: 1.1rem;
        }
    }

    /* ==================== RESPONSIVE DESIGN ==================== */
    @media (max-width: 768px) {
        .shop-header {
            flex-direction: column;
            text-align: center;
        }

        .shop-logo-large {
            width: 100px;
            height: 100px;
        }

        .shop-title {
            font-size: 2rem;
        }

        .about-content {
            grid-template-columns: 1fr;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }

        .schools-grid {
            grid-template-columns: 1fr;
        }

        .shop-meta {
            justify-content: center;
        }

        .product-actions {
            flex-direction: column;
        }
    }
    /* Alternative Banner Style */
    .shop-banner-right {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .shop-banner-image {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 400px;
    }

    .shop-banner-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(67, 97, 238, 0.7), rgba(56, 176, 0, 0.7));
        z-index: 1;
        mix-blend-mode: multiply;
    }

    .banner-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: relative;
        z-index: 0;
    }

    /* Banner with shop name overlay */
    .banner-content-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        color: white;
        z-index: 2;
    }


</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== SHOP HERO SECTION ==================== -->
<section class="shop-hero">
    <div class="container">
        <div class="shop-hero-content">
            <div class="shop-hero-grid">
                <!-- Left Side: Shop Info -->
                <div class="shop-info-left">
                    <div class="shop-header">
                        <div class="shop-logo-large">
                            @if($shop->logo_url)
                                <img src="{{ asset('website/' . $shop->logo_url) }}" alt="{{ $shop->name }}">
                            @else
                                <img src="https://via.placeholder.com/120" alt="{{ $shop->name }}">
                            @endif
                        </div>
                        <div class="shop-info">
                            <h1 class="shop-title">{{ $shop->name }}</h1>
                            <span class="shop-type-badge">{{ ucfirst(str_replace('_', ' ', $shop->shop_type)) }}</span>
                            
                            <div class="shop-rating-large">
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
                                <span class="rating-count">({{ $shop->total_reviews }} reviews)</span>
                            </div>

                            <div class="shop-meta">
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $shop->city }}, {{ $shop->state }}, {{ $shop->country }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Banner Image -->
                <div class="shop-banner-right">
                    @if($shop->banner_url)
                        <div class="shop-banner-image">
                            <img src="{{ asset('website/' . $shop->banner_url) }}" 
                                 alt="{{ $shop->name }} Banner"
                                 class="banner-img">
                        </div>
                    @else
                        <div class="shop-banner-image default-banner">
                            <div class="banner-placeholder">
                                <i class="fas fa-store-alt"></i>
                                <span>No Banner Available</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
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
                    <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $shop->shop_type)) }}</span>
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
                        <span class="school-type">{{ $association->school->school_type }}</span>
                        <div class="school-location">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $association->school->city }}, {{ $association->school->state ?? '' }}
                        </div>
                        <div class="association-type">
                            {{ ucfirst($association->association_type) }} Partner
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