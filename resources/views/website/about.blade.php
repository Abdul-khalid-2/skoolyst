@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== COMMON STYLES ==================== */
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 3rem;
        color: #1a1a1a;
    }

    /* ==================== HERO SECTION ==================== */
    .hero-section {
        background: #0f4077;
        color: white;
        padding: 100px 0 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
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
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .hero-content img {
        max-width: 100%;
        height: auto;
        margin: 20px auto;
        display: block;
    }

    /* ==================== ABOUT SECTION ==================== */
    .about-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .section-description {
        font-size: 1.2rem;
        text-align: center;
        max-width: 800px;
        margin: 0 auto 3rem;
        color: #666;
        line-height: 1.6;
    }

    .feature-card {
        background: white;
        padding: 2.5rem 2rem;
        border-radius: 15px;
        text-align: center;
        height: 100%;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        border-color: #4361ee;
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        background: #0f4077;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 1.8rem;
    }

    .feature-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a1a;
    }

    .feature-text {
        font-size: 1rem;
        line-height: 1.6;
        color: #666;
    }

    /* ==================== MISSION VISION SECTION ==================== */
    .mission-vision-section {
        padding: 80px 0;
        background: white;
    }

    .mission-vision-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 3rem;
        margin-bottom: 4rem;
    }

    .mission-card,
    .vision-card {
        background: #f8f9fa;
        padding: 3rem 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease;
        border: 1px solid #e0e0e0;
    }

    .mission-card:hover,
    .vision-card:hover {
        transform: translateY(-10px);
    }

    .card-icon {
        width: 80px;
        height: 80px;
        background: #0f4077;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        color: white;
        font-size: 2rem;
    }

    .card-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1a1a1a;
    }

    .card-content {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #555;
    }

    /* ==================== HOW IT WORKS SECTION ==================== */
    .how-it-works-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .how-it-works-card {
        background: white;
        padding: 2.5rem 2rem;
        border-radius: 15px;
        text-align: center;
        height: 100%;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .how-it-works-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        border-color: #38b000;
    }

    /* ==================== PLATFORM FEATURES ==================== */
    .platform-features-section {
        padding: 80px 0;
        background: white;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .feature-card {
        background: white;
        padding: 2.5rem 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        border-color: #4361ee;
    }

    /* ==================== VALUE PROPOSITION ==================== */
    .value-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .value-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .value-card {
        background: white;
        text-align: center;
        padding: 2.5rem 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .value-card:hover {
        transform: translateY(-5px);
    }

    .value-number {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        color: #4361ee;
    }

    .value-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #1a1a1a;
    }

    .value-description {
        font-size: 1rem;
        color: #666;
        line-height: 1.6;
    }

    /* ==================== BLOG SECTION ==================== */
    .blog-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .blog-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
    }

    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .blog-image {
        height: 200px;
        background: #0f4077;
        background-size: cover;
        background-position: center;
    }

    .blog-content {
        padding: 2rem;
    }

    .blog-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a1a;
    }

    .blog-excerpt {
        font-size: 1rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .blog-link {
        color: #4361ee;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: color 0.3s ease;
    }

    .blog-link:hover {
        color: #38b000;
    }

    /* ==================== CTA SECTION ==================== */
    .cta-section {
        padding: 80px 0;
        background: #0f4077;
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

    /* ==================== FINAL CTA ==================== */
    .final-cta {
        padding: 80px 0;
        background: #1a1a1a;
        color: white;
        text-align: center;
    }

    .final-cta-headline {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 2rem;
    }

    .final-cta-button {
        display: inline-block;
        padding: 1rem 2.5rem;
        background: #38b000;
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        border: 2px solid #38b000;
    }

    .final-cta-button:hover {
        background: transparent;
        color: #38b000;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(56, 176, 0, 0.3);
    }

    /* ==================== RESPONSIVE DESIGN ==================== */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .mission-vision-grid,
        .features-grid,
        .value-grid {
            grid-template-columns: 1fr;
        }

        .mission-card,
        .vision-card,
        .feature-card,
        .how-it-works-card,
        .feature-card,
        .value-card {
            padding: 2rem 1.5rem;
        }

        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }

        .cta-btn {
            width: 100%;
            max-width: 300px;
        }

        .section-title {
            font-size: 2rem;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')
<!-- ==================== HERO SECTION ==================== -->
<section class="hero-section">
    <div class="container">
        <h3 class="hero-title">{{ __('about.hero_title') }}</h3>
        <p class="hero-subtitle">
            {{ __('about.hero_subtitle') }}
        </p>
        <div class="hero-content">
            <img src="{{ asset('assets/assets/hero1.png') }}" alt="SKOOLYST Platform Overview">
        </div>
    </div>
</section>

<!-- ==================== ABOUT SECTION ==================== -->
<section class="about-section" id="about">
    <div class="container">
        <h2 class="section-title">{{ __('about.why_choose_us') }}</h2>
        <p class="section-description">
            {{ __('about.about_description') }}
        </p>

        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <h3 class="feature-title">{{ __('about.school_profiles') }}</h3>
                    <p class="feature-text">
                        {{ __('about.school_profiles_text') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h3 class="feature-title">{{ __('about.smart_ads') }}</h3>
                    <p class="feature-text">
                        {{ __('about.smart_ads_text') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 class="feature-title">{{ __('about.achievement_hub') }}</h3>
                    <p class="feature-text">
                        {{ __('about.achievement_hub_text') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="feature-title">{{ __('about.parent_engagement') }}</h3>
                    <p class="feature-text">
                        {{ __('about.parent_engagement_text') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== MISSION & VISION SECTION ==================== -->
<section class="mission-vision-section">
    <div class="container">
        <h2 class="section-title">{{ __('about.purpose_vision') }}</h2>

        <div class="mission-vision-grid">
            <div class="mission-card">
                <div class="card-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="card-title">{{ __('about.our_mission') }}</h3>
                <p class="card-content">
                    {{ __('about.mission_text') }}
                </p>
            </div>

            <div class="vision-card">
                <div class="card-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="card-title">{{ __('about.our_vision') }}</h3>
                <p class="card-content">
                    {{ __('about.vision_text') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== HOW IT WORKS SECTION ==================== -->
<section class="how-it-works-section" id="how-it-works">
    <div class="container">
        <h2 class="section-title">{{ __('about.how_it_works') }}</h2>

        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="how-it-works-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3 class="feature-title">{{ __('about.create_profile') }}</h3>
                    <p class="feature-text">
                        {{ __('about.create_profile_text') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="how-it-works-card">
                    <div class="feature-icon">
                        <i class="fas fa-ad"></i>
                    </div>
                    <h3 class="feature-title">{{ __('about.post_announcements') }}</h3>
                    <p class="feature-text">
                        {{ __('about.post_announcements_text') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="how-it-works-card">
                    <div class="feature-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3 class="feature-title">{{ __('about.highlight_achievements') }}</h3>
                    <p class="feature-text">
                        {{ __('about.highlight_achievements_text') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="how-it-works-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">{{ __('about.engage_community') }}</h3>
                    <p class="feature-text">
                        {{ __('about.engage_community_text') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PLATFORM FEATURES ==================== -->
<section class="platform-features-section">
    <div class="container">
        <h2 class="section-title">{{ __('about.unique_features') }}</h2>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">{{ __('about.smart_discovery') }}</h3>
                <p class="feature-text">
                    {{ __('about.smart_discovery_text') }}
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="feature-title">{{ __('about.reviews_ratings') }}</h3>
                <p class="feature-text">
                    {{ __('about.reviews_ratings_text') }}
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h3 class="feature-title">{{ __('about.comparison') }}</h3>
                <p class="feature-text">
                    {{ __('about.comparison_text') }}
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="feature-title">{{ __('about.ecommerce') }}</h3>
                <p class="feature-text">
                    {{ __('about.ecommerce_text') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== VALUE PROPOSITION ==================== -->
<section class="value-section">
    <div class="container">
        <h2 class="section-title">{{ __('about.who_benefits') }}</h2>

        <div class="value-grid">
            <div class="value-card">
                <div class="value-number">{{ __('about.parents') }}</div>
                <h3 class="value-title">{{ __('about.parents_title') }}</h3>
                <p class="value-description">
                    {{ __('about.parents_description') }}
                </p>
            </div>

            <div class="value-card">
                <div class="value-number">{{ __('about.schools_benefit') }}</div>
                <h3 class="value-title">{{ __('about.schools_title') }}</h3>
                <p class="value-description">
                    {{ __('about.schools_description') }}
                </p>
            </div>

            <div class="value-card">
                <div class="value-number">{{ __('about.students') }}</div>
                <h3 class="value-title">{{ __('about.students_title') }}</h3>
                <p class="value-description">
                    {{ __('about.students_description') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== BLOG SECTION (Optional) ==================== -->
<section class="blog-section" id="blog">
    <div class="container">
        <h2 class="section-headline">{{ __('about.latest_insights') }}</h2>

        <div class="row mt-5">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="blog-card">
                    <div class="blog-image"></div>
                    <div class="blog-content">
                        <h3 class="blog-title">{{ __('about.digital_transformation') }}</h3>
                        <p class="blog-excerpt">
                            {{ __('about.digital_transformation_text') }}
                        </p>
                        <a href="#" class="blog-link">{{ __('about.read_more') }} <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="blog-card">
                    <div class="blog-image"></div>
                    <div class="blog-content">
                        <h3 class="blog-title">{{ __('about.school_communities') }}</h3>
                        <p class="blog-excerpt">
                            {{ __('about.school_communities_text') }}
                        </p>
                        <a href="#" class="blog-link">{{ __('about.read_more') }} <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="blog-card">
                    <div class="blog-image"></div>
                    <div class="blog-content">
                        <h3 class="blog-title">{{ __('about.marketing_school') }}</h3>
                        <p class="blog-excerpt">
                            {{ __('about.marketing_school_text') }}
                        </p>
                        <a href="#" class="blog-link">{{ __('about.read_more') }} <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CTA SECTION ==================== -->
<section class="cta-section">
    <div class="container">
        <h2 class="cta-title">{{ __('about.join_revolution') }}</h2>
        <p class="cta-subtitle">
            {{ __('about.cta_subtitle') }}
        </p>

        <div class="cta-buttons">
            <a href="{{ route('browseSchools.index') }}" class="cta-btn primary">
                <i class="fas fa-search me-2"></i> {{ __('about.browse_schools') }}
            </a>
            <a href="{{ route('register') }}" class="cta-btn secondary">
                <i class="fas fa-school me-2"></i> {{ __('about.register_school') }}
            </a>
        </div>
    </div>
</section>

<!-- ==================== FINAL CTA ==================== -->
<section class="final-cta">
    <div class="container">
        <h2 class="final-cta-headline">{{ __('about.ready_transform') }}</h2>
        <p class="section-description" style="color: #ccc; margin-bottom: 2rem;">
            {{ __('about.final_cta_text') }}
        </p>
        <a href="{{ route('register') }}" class="final-cta-button">{{ __('about.get_started_free') }}</a>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Add animation on scroll (optional)
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe all feature cards
    document.querySelectorAll('.feature-card, .how-it-works-card, .feature-card, .value-card').forEach(card => {
        observer.observe(card);
    });
</script>
@endpush