@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== ECOMMERCE HERO SECTION ==================== */
    .ecommerce-hero {
        background: linear-gradient(135deg, #ff6b35 0%, #4361ee 50%, #38b000 100%);
        color: white;
        padding: 100px 0 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .ecommerce-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        animation: float 20s linear infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(0px) translateX(0px);
        }
        100% {
            transform: translateY(-100px) translateX(-100px);
        }
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    }

    .hero-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ==================== FEATURED SHOPS SECTION ==================== */
    .shops-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 3rem;
        color: #1a1a1a;
    }

    .section-subtitle {
        font-size: 1.2rem;
        text-align: center;
        margin-bottom: 4rem;
        color: #666;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .shops-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    .shop-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid #e0e0e0;
    }

    .shop-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .shop-banner {
        height: 150px;
        background: linear-gradient(135deg, #4361ee, #38b000);
        position: relative;
    }

    .shop-logo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 4px solid white;
        position: absolute;
        bottom: -40px;
        left: 2rem;
        background: white;
        overflow: hidden;
    }

    .shop-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .shop-content {
        padding: 3rem 2rem 2rem;
    }

    .shop-name {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1a1a1a;
    }

    .shop-type {
        display: inline-block;
        background: #4361ee;
        color: white;
        padding: 0.3rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .shop-location {
        display: flex;
        align-items: center;
        color: #666;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .shop-location i {
        margin-right: 0.5rem;
        color: #4361ee;
    }

    .shop-rating {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .rating-stars {
        color: #ffc107;
        margin-right: 0.5rem;
    }

    .rating-value {
        font-weight: 600;
        color: #1a1a1a;
    }

    .shop-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .shop-actions {
        display: flex;
        gap: 1rem;
    }

    .btn {
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        flex: 1;
        text-align: center;
    }

    .btn-primary {
        background: #4361ee;
        color: white;
    }

    .btn-secondary {
        background: #f8f9fa;
        color: #4361ee;
        border: 2px solid #4361ee;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* ==================== CATEGORIES SECTION ==================== */
    .categories-section {
        padding: 80px 0;
        background: white;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
    }

    .category-card {
        background: white;
        padding: 2.5rem 2rem;
        border-radius: 15px;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid #f0f0f0;
        position: relative;
        overflow: hidden;
    }

    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #4361ee, #38b000);
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-color: #4361ee;
    }

    .category-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #4361ee, #38b000);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 2rem;
    }

    .category-name {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a1a;
    }

    .category-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .category-count {
        background: #f8f9fa;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        color: #4361ee;
        font-weight: 600;
    }

    /* ==================== FEATURED PRODUCTS SECTION ==================== */
    .products-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1a1a1a;
        line-height: 1.4;
    }

    .product-shop {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
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

    .product-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
    }

    .btn-success {
        background: #38b000;
        color: white;
        flex: 2;
    }

    /* ==================== HOW IT WORKS SECTION ==================== */
    .how-it-works {
        padding: 80px 0;
        background: white;
    }

    .steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        counter-reset: step-counter;
    }

    .step-card {
        text-align: center;
        padding: 2rem;
        position: relative;
    }

    .step-card::before {
        counter-increment: step-counter;
        content: counter(step-counter);
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #4361ee, #38b000);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .step-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a1a;
    }

    .step-description {
        color: #666;
        line-height: 1.6;
    }

    /* ==================== CTA SECTION ==================== */
    .ecommerce-cta {
        padding: 80px 0;
        background: linear-gradient(135deg, #ff6b35, #4361ee);
        color: white;
        text-align: center;
    }

    .cta-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .cta-subtitle {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .cta-btn {
        padding: 1rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid white;
    }

    .cta-btn.primary {
        background: white;
        color: #4361ee;
    }

    .cta-btn.secondary {
        background: transparent;
        color: white;
    }

    .cta-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    /* ==================== RESPONSIVE DESIGN ==================== */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .shops-grid {
            grid-template-columns: 1fr;
        }

        .categories-grid {
            grid-template-columns: 1fr;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }

        .steps-grid {
            grid-template-columns: 1fr;
        }

        .shop-actions {
            flex-direction: column;
        }

        .product-actions {
            flex-direction: column;
        }

        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }

        .cta-btn {
            width: 100%;
            max-width: 300px;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== HERO SECTION ==================== -->
<section class="ecommerce-hero">
    <div class="container">
        <h1 class="hero-title">SKOOLYST E-Commerce Marketplace</h1>
        <p class="hero-subtitle">
            Discover educational supplies, books, uniforms, and more from trusted school-affiliated shops. Everything you need for academic success in one place.
        </p>
    </div>
</section>

<!-- ==================== FEATURED SHOPS SECTION ==================== -->
<section class="shops-section">
    <div class="container">
        <h2 class="section-title">Featured Educational Shops</h2>
        <p class="section-subtitle">
            Browse through verified shops specializing in educational materials, school supplies, and academic resources
        </p>

        <div class="shops-grid">
            <!-- Shop Card 1 -->
            <div class="shop-card">
                <div class="shop-banner">
                    <div class="shop-logo">
                        <img src="https://via.placeholder.com/80" alt="Book Haven">
                    </div>
                </div>
                <div class="shop-content">
                    <h3 class="shop-name">Book Haven</h3>
                    <span class="shop-type">Book Store</span>
                    <div class="shop-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Lahore, Punjab</span>
                    </div>
                    <div class="shop-rating">
                        <div class="rating-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="rating-value">4.5 (128 reviews)</span>
                    </div>
                    <p class="shop-description">
                        Your one-stop destination for textbooks, reference books, and educational materials for all classes and boards.
                    </p>
                    <div class="shop-actions">
                        <a href="#" class="btn btn-primary">Visit Shop</a>
                        <a href="#" class="btn btn-secondary">View Products</a>
                    </div>
                </div>
            </div>

            <!-- Shop Card 2 -->
            <div class="shop-card">
                <div class="shop-banner">
                    <div class="shop-logo">
                        <img src="https://via.placeholder.com/80" alt="Scholar's Stationery">
                    </div>
                </div>
                <div class="shop-content">
                    <h3 class="shop-name">Scholar's Stationery</h3>
                    <span class="shop-type">Stationery</span>
                    <div class="shop-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Karachi, Sindh</span>
                    </div>
                    <div class="shop-rating">
                        <div class="rating-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span class="rating-value">4.0 (95 reviews)</span>
                    </div>
                    <p class="shop-description">
                        Premium quality stationery items including pens, notebooks, art supplies, and school essentials.
                    </p>
                    <div class="shop-actions">
                        <a href="#" class="btn btn-primary">Visit Shop</a>
                        <a href="#" class="btn btn-secondary">View Products</a>
                    </div>
                </div>
            </div>

            <!-- Shop Card 3 -->
            <div class="shop-card">
                <div class="shop-banner">
                    <div class="shop-logo">
                        <img src="https://via.placeholder.com/80" alt="Uniform Center">
                    </div>
                </div>
                <div class="shop-content">
                    <h3 class="shop-name">Uniform Center</h3>
                    <span class="shop-type">School Affiliated</span>
                    <div class="shop-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Islamabad</span>
                    </div>
                    <div class="shop-rating">
                        <div class="rating-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="rating-value">5.0 (67 reviews)</span>
                    </div>
                    <p class="shop-description">
                        Official school uniforms, sports kits, and accessories for various educational institutions.
                    </p>
                    <div class="shop-actions">
                        <a href="#" class="btn btn-primary">Visit Shop</a>
                        <a href="#" class="btn btn-secondary">View Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CATEGORIES SECTION ==================== -->
<section class="categories-section">
    <div class="container">
        <h2 class="section-title">Product Categories</h2>
        <p class="section-subtitle">
            Explore our wide range of educational products organized by categories
        </p>

        <div class="categories-grid">
            <!-- Category Card 1 -->
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="category-name">Textbooks & Books</h3>
                <p class="category-description">
                    Curriculum books, reference materials, story books, and educational publications for all levels.
                </p>
                <div class="category-count">245 Products</div>
            </div>

            <!-- Category Card 2 -->
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-pencil-alt"></i>
                </div>
                <h3 class="category-name">Stationery</h3>
                <p class="category-description">
                    Pens, pencils, notebooks, art supplies, and all essential writing materials.
                </p>
                <div class="category-count">189 Products</div>
            </div>

            <!-- Category Card 3 -->
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-tshirt"></i>
                </div>
                <h3 class="category-name">School Uniforms</h3>
                <p class="category-description">
                    Complete uniform sets, shirts, trousers, skirts, and accessories for various schools.
                </p>
                <div class="category-count">78 Products</div>
            </div>

            <!-- Category Card 4 -->
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3 class="category-name">Bags & Backpacks</h3>
                <p class="category-description">
                    School bags, backpacks, lunch bags, and carrying solutions for students.
                </p>
                <div class="category-count">45 Products</div>
            </div>

            <!-- Category Card 5 -->
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-copy"></i>
                </div>
                <h3 class="category-name">Copies & Notebooks</h3>
                <p class="category-description">
                    Various types of copies, registers, notebooks, and writing pads for different needs.
                </p>
                <div class="category-count">156 Products</div>
            </div>

            <!-- Category Card 6 -->
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-futbol"></i>
                </div>
                <h3 class="category-name">Sports Equipment</h3>
                <p class="category-description">
                    Sports gear, games equipment, and physical education necessities.
                </p>
                <div class="category-count">67 Products</div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FEATURED PRODUCTS SECTION ==================== -->
<section class="products-section">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <p class="section-subtitle">
            Discover popular and essential educational products from our trusted shops
        </p>

        <div class="products-grid">
            <!-- Product Card 1 -->
            <div class="product-card">
                <div class="product-badge">Bestseller</div>
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x200" alt="Mathematics Textbook">
                </div>
                <div class="product-content">
                    <div class="product-category">Textbook</div>
                    <h3 class="product-name">Mathematics for Class 9 - Federal Board</h3>
                    <div class="product-shop">Book Haven</div>
                    <div class="product-attributes">
                        <span class="attribute-tag">Federal Board</span>
                        <span class="attribute-tag">Class 9</span>
                        <span class="attribute-tag">2024 Edition</span>
                    </div>
                    <div class="product-pricing">
                        <div>
                            <span class="price-current">Rs. 850</span>
                            <span class="price-original">Rs. 950</span>
                        </div>
                        <div class="stock-status in-stock">In Stock</div>
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-success btn-sm">Add to Cart</button>
                        <button class="btn btn-secondary btn-sm">Quick View</button>
                    </div>
                </div>
            </div>

            <!-- Product Card 2 -->
            <div class="product-card">
                <div class="product-badge">New</div>
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x200" alt="School Bag">
                </div>
                <div class="product-content">
                    <div class="product-category">School Bag</div>
                    <h3 class="product-name">Premium Waterproof School Backpack</h3>
                    <div class="product-shop">Scholar's Stationery</div>
                    <div class="product-attributes">
                        <span class="attribute-tag">Waterproof</span>
                        <span class="attribute-tag">3 Compartments</span>
                        <span class="attribute-tag">Blue Color</span>
                    </div>
                    <div class="product-pricing">
                        <div>
                            <span class="price-current">Rs. 2,500</span>
                        </div>
                        <div class="stock-status in-stock">In Stock</div>
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-success btn-sm">Add to Cart</button>
                        <button class="btn btn-secondary btn-sm">Quick View</button>
                    </div>
                </div>
            </div>

            <!-- Product Card 3 -->
            <div class="product-card">
                <div class="product-badge">Sale</div>
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x200" alt="School Uniform">
                </div>
                <div class="product-content">
                    <div class="product-category">Uniform</div>
                    <h3 class="product-name">Complete School Uniform Set - Beaconhouse</h3>
                    <div class="product-shop">Uniform Center</div>
                    <div class="product-attributes">
                        <span class="attribute-tag">Beaconhouse</span>
                        <span class="attribute-tag">Size: Medium</span>
                        <span class="attribute-tag">Summer Edition</span>
                    </div>
                    <div class="product-pricing">
                        <div>
                            <span class="price-current">Rs. 3,200</span>
                            <span class="price-original">Rs. 3,800</span>
                        </div>
                        <div class="stock-status low-stock">Low Stock</div>
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-success btn-sm">Add to Cart</button>
                        <button class="btn btn-secondary btn-sm">Quick View</button>
                    </div>
                </div>
            </div>
        </div>
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
            <a href="#" class="cta-btn primary">
                <i class="fas fa-store me-2"></i> Explore All Shops
            </a>
            <a href="{{ route('products.index') }}" class="cta-btn secondary">
                <i class="fas fa-shopping-bag me-2"></i> Browse Products
            </a>
        </div>
    </div>
</section>

@endsection