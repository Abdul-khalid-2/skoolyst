@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<style>
    /* ==================== INSIGHTS HERO SECTION (compact, unified) ==================== */
    .insights-hero-section {
    position: relative;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-primary);
    overflow: hidden;
    padding: var(--space-8) var(--space-4);
    }

    .insights-hero-section::before,
    .insights-hero-section::after {
    content: '';
    position: absolute;
    width: 80px;
    height: 80px;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-opacity='0.08' stroke-width='2'%3E%3Cpath d='M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z'/%3E%3Cpath d='M12 22V12'/%3E%3Cpath d='M9 10.5l3-1.5 3 1.5'/%3E%3C/svg%3E") no-repeat center;
    background-size: contain;
    animation: insights-hero-float 6s ease-in-out infinite;
    opacity: 0.9;
    }

    .insights-hero-section::before {
    top: 10%;
    left: 5%;
    }

    .insights-hero-section::after {
    bottom: 15%;
    right: 8%;
    animation-delay: 2s;
    }

    @keyframes insights-hero-float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-12px);
    }
    }

    .insights-hero-content {
    text-align: center;
    color: var(--color-white);
    z-index: 1;
    padding: var(--space-4);
    width: 100%;
    max-width: 800px;
    display: flex;
    flex-direction: column;
    align-items: center;
    }

    .insights-hero-title {
    font-size: var(--font-size-4xl);
    font-weight: var(--font-weight-extrabold);
    margin-bottom: var(--space-7);
    color: var(--color-white);
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    }

    .insights-hero-subheading {
    font-size: var(--font-size-base);
    margin: 0;
    opacity: 0.95;
    max-width: 600px;
    line-height: var(--line-height-relaxed);
    color: var(--color-white);
    }

    @media (min-width: 768px) {
    .insights-hero-section {
        min-height: 220px;
    }
    .insights-hero-subheading {
        font-size: var(--font-size-lg);
    }
    }

    @media (max-width: 576px) {
    .insights-hero-section {
        min-height: 180px;
        padding: var(--space-6) var(--space-4);
    }
    .insights-hero-title {
        font-size: var(--font-size-2xl);
    }
    .insights-hero-subheading {
        font-size: var(--font-size-sm);
    }
    }
    .insights-content { padding: 60px 0; }
    .insights-content h2 { margin-top: 2rem; color: #1f2937; }
    .insights-content p { color: #4b5563; line-height: 1.75; }
    .read-more-btn { display: inline-flex; align-items: center; gap: .5rem; margin-top: 1.5rem; padding: .65rem 1.2rem; border-radius: 999px; border: 2px solid #0e4c92; color: #0e4c92; background: #fff; font-weight: 700; text-decoration: none; }
    .read-more-btn:hover { background: #0e4c92; color: #fff; }
</style>
@endpush

@section('content')
<!-- ==================== INSIGHTS HERO SECTION (compact, unified) ==================== -->
<section class="insights-hero-section" id="insights-hero">
    <div class="insights-hero-content">
        <h1 class="insights-hero-title">Digital Transformation in Education with Skoolyst</h1>
        <p class="insights-hero-subheading">
            Skoolyst is helping schools, parents, and students move towards a smarter, more connected, and transparent education system.
        </p>
    </div>
</section>

<section class="container insights-content">
    <p>Education is rapidly shifting towards digital platforms where access to information, communication, and learning tools is essential. Skoolyst plays a key role in this transformation by bringing all educational stakeholders onto one platform.</p>

    <h2>1. Smart School Discovery</h2>
    <p>Parents can search and explore schools based on location, facilities, and categories. Detailed school profiles make it easier to compare and choose the right institution.</p>

    <h2>2. Transparent Information & Reviews</h2>
    <p>With reviews and ratings, Skoolyst builds trust by allowing real users to share their experiences. This transparency improves decision-making and encourages schools to maintain quality.</p>

    <h2>3. Digital Learning Support</h2>
    <p>The MCQs system provides students with a simple and effective way to practice and test their knowledge, supporting their academic growth beyond the classroom.</p>

    <h2>4. Easy Communication & Updates</h2>
    <p>Schools can share announcements, updates, and achievements directly on the platform, ensuring parents and students stay informed without communication gaps.</p>

    <h2>5. Future-Ready Education Platform</h2>
    <p>Skoolyst is evolving into a complete digital ecosystem, where users will also be able to access and purchase educational products, making everything related to education available in one place.</p>

    <a class="read-more-btn" href="{{ route('about') }}">← Back to Latest Insights</a>
</section>
@endsection