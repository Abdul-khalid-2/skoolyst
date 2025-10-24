@extends('website.layout.app')

@push('styles')

<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/how_it_works.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')
<!-- ==================== HERO SECTION ==================== -->
<section class="hero-section" id="home">
    <div class="hero-content">
        <img class="hero-subheading" src="{{ asset('assets/assets/hero1.png') }}" alt="hero1.png">

    </div>
</section>
<!-- ==================== ABOUT SECTION ==================== -->
<section class="about-section" id="about">
    <div class="container">
        <h2 class="section-headline">Why SKOOLYST?</h2>
        <p class="section-description">
            Empower schools, academies, and institutes to build digital profiles, advertise admissions,
            showcase achievements, and connect with students – all in one place.
        </p>

        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <h3 class="feature-title">School Profiles</h3>
                    <p class="feature-text">
                        Create and customize your institution's comprehensive digital page with ease.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h3 class="feature-title">Smart Ads</h3>
                    <p class="feature-text">
                        Promote admissions, events, or achievements to reach the right audience effectively.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 class="feature-title">Achievement Hub</h3>
                    <p class="feature-text">
                        Highlight and celebrate student and staff successes on a dedicated platform.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="feature-title">Parent Engagement</h3>
                    <p class="feature-text">
                        Build trust with direct communication channels between schools and families.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FEATURES SECTION ==================== -->
<section class="features-section" id="features">
    <div class="container">
        <h2 class="section-headline">How It Works</h2>

        <div class="row mt-5">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="how-it-works-card">
                    <div class="card-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3 class="card-title">Create School Profile</h3>
                    <p class="card-description">
                        Sign up and build a complete profile showcasing your institution's unique identity, facilities, and values.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="how-it-works-card">
                    <div class="card-icon">
                        <i class="fas fa-ad"></i>
                    </div>
                    <h3 class="card-title">Post Advertisements</h3>
                    <p class="card-description">
                        Promote new admissions, events, workshops, and special programs to attract prospective students.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="how-it-works-card">
                    <div class="card-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3 class="card-title">Highlight Achievements</h3>
                    <p class="card-description">
                        Share academic excellence, sports victories, cultural events, and student accomplishments with pride.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="how-it-works-card">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="card-title">Engage with Parents & Students</h3>
                    <p class="card-description">
                        Foster meaningful connections through integrated messaging and community-building tools.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== BLOG SECTION ==================== -->
<section class="blog-section" id="blog">
    <div class="container">
        <h2 class="section-headline">Latest Insights</h2>

        <div class="row mt-5">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="blog-card">
                    <div class="blog-image"></div>
                    <div class="blog-content">
                        <h3 class="blog-title">Digital Transformation in Education</h3>
                        <p class="blog-excerpt">
                            Discover how educational institutions are leveraging technology to enhance learning experiences and streamline operations.
                        </p>
                        <a href="#" class="blog-link">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="blog-card">
                    <div class="blog-image"></div>
                    <div class="blog-content">
                        <h3 class="blog-title">Building Stronger School Communities</h3>
                        <p class="blog-excerpt">
                            Learn effective strategies for fostering parent engagement and creating collaborative educational environments.
                        </p>
                        <a href="#" class="blog-link">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="blog-card">
                    <div class="blog-image"></div>
                    <div class="blog-content">
                        <h3 class="blog-title">Marketing Your School Online</h3>
                        <p class="blog-excerpt">
                            Explore proven digital marketing techniques to attract prospective students and showcase your institution's strengths.
                        </p>
                        <a href="#" class="blog-link">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FINAL CTA SECTION ==================== -->
<section class="final-cta" id="cta">
    <div class="container">
        <h2 class="final-cta-headline">Bring Your School Online Today</h2>
        <a href="{{ route('register') }}" class="final-cta-button">Get Started</a>
    </div>
</section>


@push('scripts')

<script src="{{ asset('assets/js/how_it_works.js') }}"></script>
@endpush

@endsection