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

    /* ==================== FILTERS SECTION ==================== */
    .filters-section {
        background: white;
        padding: 2rem 0;
        border-bottom: 1px solid #e0e0e0;
        position: relative;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .filters-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
        font-size: 0.9rem;
    }

    .filter-select,
    .filter-input {
        padding: 0.75rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .filter-select:focus,
    .filter-input:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-filter {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
    }

    .btn-apply {
        background: #4361ee;
        color: white;
    }

    .btn-reset {
        background: #f8f9fa;
        color: #666;
        border: 2px solid #e0e0e0;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* ==================== SCHOOL ASSOCIATIONS SECTION ==================== */
    .school-associations {
        /* background: #f8f9fa;
        padding: 2rem 0; */
        margin-bottom: 2rem;
    }

    .school-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        /* justify-content: center; */
    }

    .school-tag {
        background: white;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        border: 2px solid #4361ee;
        color: #4361ee;
        font-weight: 600;
        font-size: 0.7rem;
        transition: all 0.3s ease;
    }

    .school-tag:hover {
        background: #4361ee;
        color: white;
        transform: translateY(-2px);
    }

    /* ==================== DYNAMIC CONTENT STYLES ==================== */
    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 15px;
        margin: 2rem 0;
    }

    .no-results-icon {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 1rem;
    }

    .no-results-title {
        font-size: 1.5rem;
        color: #666;
        margin-bottom: 1rem;
    }

    /* Update existing styles for dynamic content */
    .shop-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Association badge */
    .association-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: #38b000;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
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
        margin-bottom: 0.75rem;
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
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 3px solid #4361ee;
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
        .filters-container {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            flex-direction: column;
        }

        .school-tags {
            justify-content: flex-start;
        }

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
        <h1 class="hero-title">SKOOLYST EduMart</h1>
        <p class="hero-subtitle">
            Discover educational supplies, books, uniforms, and more from trusted school-affiliated shops. Everything you need for academic success in one place.
        </p>
    </div>
</section>

<!-- ==================== FILTERS SECTION ==================== -->
<section class="filters-section">
    <div class="container">
        <form action="{{ route('website.shop.index') }}" method="GET" id="shop-filters">
            <div class="filters-container">
                <!-- Search Filter -->
                <div class="filter-group">
                    <label class="filter-label">Search</label>
                    <input type="text"
                        name="search"
                        class="filter-input"
                        placeholder="Search shops or products..."
                        value="{{ $search }}">
                </div>

                <!-- School Type Filter -->
                <div class="filter-group">
                    <label class="filter-label">School Type</label>
                    <select name="school_type" class="filter-select">
                        <option value="">All School Types</option>
                        @foreach($schoolTypes as $type)
                        <option value="{{ $type }}" {{ $schoolType == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- City Filter -->
                <div class="filter-group">
                    <label class="filter-label">City</label>
                    <select name="city" class="filter-select">
                        <option value="">All Cities</option>
                        @foreach($cities as $cityItem)
                        <option value="{{ $cityItem }}" {{ $city == $cityItem ? 'selected' : '' }}>
                            {{ $cityItem }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Shop Type Filter -->
                <div class="filter-group">
                    <label class="filter-label">Shop Type</label>
                    <select name="shop_type" class="filter-select">
                        <option value="">All Shop Types</option>
                        <option value="stationery" {{ $shopType == 'stationery' ? 'selected' : '' }}>Stationery</option>
                        <option value="book_store" {{ $shopType == 'book_store' ? 'selected' : '' }}>Book Store</option>
                        <option value="school_affiliated" {{ $shopType == 'school_affiliated' ? 'selected' : '' }}>School Affiliated</option>
                        <option value="mixed" {{ $shopType == 'mixed' ? 'selected' : '' }}>Mixed</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="filter-actions">
                    <button type="submit" class="btn-filter btn-apply">Filter</button>
                    <a href="{{ route('website.shop.index') }}" class="btn-filter btn-reset">Reset</a>
                </div>
            </div>
        </form>
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
                            background: linear-gradient(135deg, #4361ee, #38b000);
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
                    <span class="shop-type">{{ ucfirst(str_replace('_', ' ', $shop->shop_type)) }}</span>

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
    // Auto-submit form when filter values change
    document.addEventListener('DOMContentLoaded', function() {
        const filterSelects = document.querySelectorAll('.filter-select');

        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('shop-filters').submit();
            });
        });

        // Add loading state to form submission
        const form = document.getElementById('shop-filters');
        form.addEventListener('submit', function() {
            const applyBtn = form.querySelector('.btn-apply');
            applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            applyBtn.disabled = true;
        });

    });
</script>
<script src="{{ asset('assets/js/product-modal.js') }}"></script>
@endpush