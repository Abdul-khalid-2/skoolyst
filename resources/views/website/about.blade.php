@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
@php
    $rtlText = app()->getLocale() === 'ur' ? 'text-rtl' : '';
@endphp
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

    .hero-section::before,
    .hero-section::after {
        content: '';
        position: absolute;
        width: 90px;
        height: 90px;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-opacity='0.12' stroke-width='2'%3E%3Cpath d='M12 2l9 7-9 7-9-7 9-7z'/%3E%3Cpath d='M12 22V9'/%3E%3Cpath d='M12 9L4.5 16.5'/%3E%3Cpath d='M12 9l7.5 7.5'/%3E%3C/svg%3E") no-repeat center;
        background-size: contain;
        animation: float 8s ease-in-out infinite;
        opacity: 0.9;
    }

    .hero-section::before {
        top: 10%;
        left: 6%;
    }

    .hero-section::after {
        bottom: 14%;
        right: 8%;
        animation-delay: 3s;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-12px);
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
        <h3 class="hero-title {{ $rtlText }}">{{ __('about.about_us') }}</h3>
        <p class="hero-subtitle {{ $rtlText }}">
            {{ __('about.hero_subtitle') }}
        </p>
    </div>
</section>

<!-- ==================== ABOUT SECTION ==================== -->
<section class="about-section" id="about">
    <div class="container">
        <h2 class="section-title {{ $rtlText }}">{{ __('about.why_choose_us') }}</h2>
        <p class="section-description {{ $rtlText }}">
            {{ __('about.about_description') }}
        </p>

        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <h3 class="feature-title {{ $rtlText }}">{{ __('about.school_profiles') }}</h3>
                    <p class="feature-text {{ $rtlText }}">
                        {{ __('about.school_profiles_text') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h3 class="feature-title {{ $rtlText }}">{{ __('about.smart_ads') }}</h3>
                    <p class="feature-text {{ $rtlText }}">
                        {{ __('about.smart_ads_text') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 class="feature-title {{ $rtlText }}">{{ __('about.achievement_hub') }}</h3>
                    <p class="feature-text {{ $rtlText }}">
                        {{ __('about.achievement_hub_text') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="feature-title {{ $rtlText }}">{{ __('about.parent_engagement') }}</h3>
                    <p class="feature-text {{ $rtlText }}">
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
        <h2 class="section-title {{ $rtlText }}">{{ __('about.purpose_vision') }}</h2>

        <div class="mission-vision-grid">
            <div class="mission-card">
                <div class="card-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="card-title {{ $rtlText }}">{{ __('about.our_mission') }}</h3>
                <p class="card-content {{ $rtlText }}">
                    {{ __('about.mission_text') }}
                </p>
            </div>

            <div class="vision-card">
                <div class="card-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="card-title {{ $rtlText }}">{{ __('about.our_vision') }}</h3>
                <p class="card-content {{ $rtlText }}">
                    {{ __('about.vision_text') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== HOW IT WORKS SECTION ==================== -->
<section class="how-it-works-section" id="how-it-works">
    <div class="container">
        <h2 class="section-title {{ $rtlText }}">{{ __('about.how_it_works') }}</h2>

        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="how-it-works-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3 class="feature-title {{ $rtlText }}">{{ __('about.create_profile') }}</h3>
                    <p class="feature-text {{ $rtlText }}">
                        {{ __('about.create_profile_text') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="how-it-works-card">
                    <div class="feature-icon">
                        <i class="fas fa-ad"></i>
                    </div>
                    <h3 class="feature-title {{ $rtlText }}">{{ __('about.post_announcements') }}</h3>
                    <p class="feature-text {{ $rtlText }}">
                        {{ __('about.post_announcements_text') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="how-it-works-card">
                    <div class="feature-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3 class="feature-title {{ $rtlText }}">{{ __('about.highlight_achievements') }}</h3>
                    <p class="feature-text {{ $rtlText }}">
                        {{ __('about.highlight_achievements_text') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="how-it-works-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title {{ $rtlText }}">{{ __('about.engage_community') }}</h3>
                    <p class="feature-text {{ $rtlText }}">
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
        <h2 class="section-title {{ $rtlText }}">{{ __('about.unique_features') }}</h2>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title {{ $rtlText }}">{{ __('about.smart_discovery') }}</h3>
                <p class="feature-text {{ $rtlText }}">
                    {{ __('about.smart_discovery_text') }}
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="feature-title {{ $rtlText }}">{{ __('about.reviews_ratings') }}</h3>
                <p class="feature-text {{ $rtlText }}">
                    {{ __('about.reviews_ratings_text') }}
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h3 class="feature-title {{ $rtlText }}">{{ __('about.comparison') }}</h3>
                <p class="feature-text {{ $rtlText }}">
                    {{ __('about.comparison_text') }}
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="feature-title {{ $rtlText }}">{{ __('about.ecommerce') }}</h3>
                <p class="feature-text {{ $rtlText }}">
                    {{ __('about.ecommerce_text') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== VALUE PROPOSITION ==================== -->
<section class="value-section">
    <div class="container">
        <h2 class="section-title {{ $rtlText }}">{{ __('about.who_benefits') }}</h2>

        <div class="value-grid">
            <div class="value-card">
                <div class="value-number {{ $rtlText }}">{{ __('about.parents') }}</div>
                <h3 class="value-title {{ $rtlText }}">{{ __('about.parents_title') }}</h3>
                <p class="value-description">
                    {{ __('about.parents_description') }}
                </p>
            </div>

            <div class="value-card">
                <div class="value-number {{ $rtlText }}">{{ __('about.schools_benefit') }}</div>
                <h3 class="value-title {{ $rtlText }}">{{ __('about.schools_title') }}</h3>
                <p class="value-description">
                    {{ __('about.schools_description') }}
                </p>
            </div>

            <div class="value-card">
                <div class="value-number {{ $rtlText }}">{{ __('about.students') }}</div>
                <h3 class="value-title {{ $rtlText }}">{{ __('about.students_title') }}</h3>
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
        <h2 class="section-headline {{ $rtlText }}">{{ __('about.latest_insights') }}</h2>

        <div class="row mt-5">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="blog-card">
                    <div class="blog-image"></div>
                    <div class="blog-content">
                        <h3 class="blog-title {{ $rtlText }}">{{ __('about.digital_transformation') }}</h3>
                        <p class="blog-excerpt">
                            {{ __('about.digital_transformation_text') }}
                        </p>
                        <a href="{{ route('insights.digital_transformation') }}" class="blog-link {{ $rtlText }}">{{ __('about.read_more') }} <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="blog-card">
                    <div class="blog-image"></div>
                    <div class="blog-content">
                        <h3 class="blog-title {{ $rtlText }}">{{ __('about.school_communities') }}</h3>
                        <p class="blog-excerpt">
                            {{ __('about.school_communities_text') }}
                        </p>
                        <a href="{{ route('insights.school_community') }}" class="blog-link {{ $rtlText }}">{{ __('about.read_more') }} <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="blog-card">
                    <div class="blog-image"></div>
                    <div class="blog-content">
                        <h3 class="blog-title {{ $rtlText }}">{{ __('about.marketing_school') }}</h3>
                        <p class="blog-excerpt">
                            {{ __('about.marketing_school_text') }}
                        </p>
                        <a href="{{ route('insights.school_marketing') }}" class="blog-link {{ $rtlText }}">{{ __('about.read_more') }} <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CTA SECTION ==================== -->
<section class="cta-section">
    <div class="container">
        <h2 class="cta-title {{ $rtlText }}">{{ __('about.join_revolution') }}</h2>
        <p class="cta-subtitle {{ $rtlText }}">
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
        <h2 class="final-cta-headline {{ $rtlText }}">{{ __('about.ready_transform') }}</h2>
        <p class="section-description {{ $rtlText }}" style="color: #ccc; margin-bottom: 2rem;">
            {{ __('about.final_cta_text') }}
        </p>
        <a href="{{ route('register') }}" class="final-cta-button {{ $rtlText }}">{{ __('about.get_started_free') }}</a>
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