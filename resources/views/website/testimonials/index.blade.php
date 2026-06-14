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
        <h1 class="hero-title">Parent Testimonials</h1>
        <p class="hero-subtitle">Real experiences from parents who used SKOOLYST to find their perfect schools</p>
    </div>
</section>

<!-- ==================== TESTIMONIALS SECTION ==================== -->
<section class="testimonials-section" id="testimonials">
    <div class="container">
        <h2 class="section-headline">What Our Parents Say</h2>
        <p class="section-description">Read real feedback from families who discovered their ideal schools through SKOOLYST</p>

        <!-- Testimonials Grid -->
        @if($testimonials->count() > 0)
        <div class="row mt-5">
            @foreach($testimonials as $testimonial)
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @if($testimonial->avatar)
                            <img src="{{ asset($testimonial->avatar) }}" alt="{{ $testimonial->author_name }}" class="testimonial-avatar">
                            @else
                            <div class="testimonial-avatar">{{ $testimonial->initials }}</div>
                            @endif
                            <div>
                                <h5 class="testimonial-author-name mb-1">{{ $testimonial->author_name }}</h5>
                                <div class="testimonial-author-info">
                                    <span>{{ $testimonial->author_role }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $testimonial->author_location }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="testimonial-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $testimonial->rating)
                                    <i class="fas fa-star"></i>
                                    @else
                                    <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span class="ms-2">{{ $testimonial->rating }}.0</span>
                            </div>
                            @if($testimonial->featured)
                            <span class="testimonial-featured-badge">
                                <i class="fas fa-star me-1"></i> Featured
                            </span>
                            @endif
                        </div>
                    </div>

                    <p class="testimonial-message">
                        "{{ $testimonial->message }}"
                    </p>

                    @if($testimonial->platform_features_liked && count($testimonial->platform_features_liked) > 0)
                    <div class="testimonial-features mt-3">
                        <h6 class="testimonial-features-title">Liked Features:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($testimonial->platform_features_liked as $feature)
                            <span class="testimonial-feature-tag">{{ $feature }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="testimonial-footer mt-4 pt-3">
                        <small class="text-muted">
                            <i class="far fa-calendar me-1"></i>
                            {{ $testimonial->created_at->format('F d, Y') }}
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-5">
            <div class="col-12">
                <nav aria-label="Testimonials pagination">
                    {{ $testimonials->links() }}
                </nav>
            </div>
        </div>
        @else
        <!-- No Testimonials Found -->
        <div class="row">
            <div class="col-12">
                <div class="no-testimonials text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="far fa-comments fa-4x text-muted"></i>
                    </div>
                    <h3 class="mb-3">No Testimonials Yet</h3>
                    <p class="text-muted mb-4">Be the first to share your experience with SKOOLYST!</p>
                    <a href="{{ route('website.home') }}#footer" class="btn btn-primary">
                        <i class="fas fa-pen me-2"></i> Write a Testimonial
                    </a>
                </div>
            </div>
        </div>
        @endif

    </div>
</section>

<!-- ==================== CTA SECTION ==================== -->
<section class="final-cta" style="background: linear-gradient(135deg, #0f4077 0%, #1a5fa0 100%); padding: 4rem 2rem; text-align: center; color: white;">
    <div class="container">
        <h2 style="font-size: 2.2rem; font-weight: 700; margin-bottom: 1rem;">Share Your Experience</h2>
        <p style="font-size: 1.1rem; margin-bottom: 2rem; opacity: 0.95;">Have you used SKOOLYST? We'd love to hear your feedback!</p>
        <a href="{{ route('website.home') }}#footer" class="btn btn-light" style="padding: 12px 30px; font-weight: 600; border-radius: 8px;">
            <i class="fas fa-pen me-2"></i> Write a Testimonial
        </a>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Testimonials Section */
    .testimonials-section {
        padding: 5rem 0;
        background: #f8f9fa;
    }

    .section-headline {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a1a;
    }

    .section-description {
        text-align: center;
        font-size: 1.1rem;
        color: #6c757d;
        margin-bottom: 3rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Testimonial Card */
    .testimonial-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .testimonial-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    }

    .testimonial-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0f4077 0%, #1a5fa0 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        flex-shrink: 0;
        object-fit: cover;
    }

    .testimonial-author-name {
        font-weight: 700;
        color: #1a1a1a;
        font-size: 1rem;
    }

    .testimonial-author-info {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .testimonial-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 1rem;
    }

    .testimonial-rating i {
        color: #ffc107;
    }

    .testimonial-rating .far {
        color: #dee2e6;
    }

    .testimonial-featured-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .testimonial-message {
        color: #333;
        font-size: 1.05rem;
        line-height: 1.7;
        font-style: italic;
        margin: 1.5rem 0 0 0;
        flex-grow: 1;
    }

    .testimonial-features {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e9ecef;
    }

    .testimonial-features-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .testimonial-feature-tag {
        display: inline-block;
        background: #e9ecef;
        color: #495057;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .testimonial-feature-tag:hover {
        background: #0f4077;
        color: white;
    }

    .testimonial-footer {
        margin-top: auto;
    }

    .no-testimonials {
        padding: 5rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1.5rem;
    }

    .no-testimonials h3 {
        font-size: 1.8rem;
        color: #1a1a1a;
        margin-bottom: 1rem;
    }

    .no-testimonials p {
        color: #6c757d;
        font-size: 1rem;
        margin-bottom: 2rem;
    }

    /* Hero Title and Subtitle */
    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1rem;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 600px;
        margin: 0 auto;
    }

    @media (max-width: 768px) {
        .section-headline {
            font-size: 2rem;
        }

        .testimonial-card {
            padding: 1.5rem;
        }

        .hero-title {
            font-size: 1.8rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }
    }
</style>
@endpush