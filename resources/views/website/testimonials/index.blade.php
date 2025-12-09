@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')
<!-- ==================== BREADCRUMB SECTION ==================== -->
<section class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('website.home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Testimonials</li>
            </ol>
        </nav>
        <h1 class="page-title">Parent Testimonials</h1>
        <p class="page-subtitle">Real experiences from parents who used SKOOLYST to find their perfect schools</p>
    </div>
</section>

<!-- ==================== TESTIMONIALS LIST SECTION ==================== -->
<section class="testimonials-list-section py-5">
    <div class="container">


        <!-- Filter Section -->
        <!-- <div class="row mb-4">
            <div class="col-12">
                <div class="filter-section bg-white p-4 rounded-3 shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">Filter Testimonials</h5>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('testimonials.index') }}" method="GET" class="d-flex gap-2">
                                <select name="rating" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Ratings</option>
                                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>★★★★★ (5 Stars)</option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>★★★★☆ (4 Stars)</option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>★★★☆☆ (3 Stars)</option>
                                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>★★☆☆☆ (2 Stars)</option>
                                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>★☆☆☆☆ (1 Star)</option>
                                </select>
                                <select name="experience" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Experiences</option>
                                    <option value="excellent" {{ request('experience') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                    <option value="good" {{ request('experience') == 'good' ? 'selected' : '' }}>Good</option>
                                    <option value="average" {{ request('experience') == 'average' ? 'selected' : '' }}>Average</option>
                                    <option value="poor" {{ request('experience') == 'poor' ? 'selected' : '' }}>Poor</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Testimonials Grid -->
        @if($testimonials->count() > 0)
        <div class="row">
            @foreach($testimonials as $testimonial)
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="testimonial-card-full h-100">
                    @if($testimonial->featured)
                    <div class="featured-badge">
                        <i class="fas fa-star me-1"></i> Featured
                    </div>
                    @endif

                    <div class="testimonial-header">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @if($testimonial->avatar)
                            <img src="{{ asset($testimonial->avatar) }}" alt="{{ $testimonial->author_name }}"
                                class="author-avatar-large">
                            @else
                            <div class="author-avatar-large">{{ $testimonial->initials }}</div>
                            @endif
                            <div>
                                <h5 class="author-name mb-1">{{ $testimonial->author_name }}</h5>
                                <div class="author-info">
                                    <span class="author-role">{{ $testimonial->author_role }}</span>
                                    <span class="mx-2">•</span>
                                    <span class="author-location">{{ $testimonial->author_location }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="testimonial-meta d-flex justify-content-between align-items-center mb-3">
                            <div class="rating-display">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=$testimonial->rating)
                                    <i class="fas fa-star text-warning"></i>
                                    @else
                                    <i class="far fa-star text-muted"></i>
                                    @endif
                                    @endfor
                                    <span class="rating-text ms-2">{{ $testimonial->rating }}.0</span>
                            </div>
                            <div class="experience-badge badge bg-{{ $testimonial->experience_rating == 'excellent' ? 'success' : ($testimonial->experience_rating == 'good' ? 'info' : ($testimonial->experience_rating == 'average' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst($testimonial->experience_rating) }}
                            </div>
                        </div>
                    </div>

                    <div class="testimonial-body">
                        <p class="testimonial-text">
                            "{{ $testimonial->message }}"
                        </p>

                        @if($testimonial->platform_features_liked && count($testimonial->platform_features_liked) > 0)
                        <div class="features-liked mt-3">
                            <h6 class="features-title">Liked Features:</h6>
                            <div class="features-tags">
                                @foreach($testimonial->platform_features_liked as $feature)
                                <span class="feature-tag">{{ $feature }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="testimonial-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">
                                <i class="far fa-calendar me-1"></i>
                                {{ $testimonial->created_at->format('F d, Y') }}
                            </span>
                            <div class="testimonial-actions">
                                <button class="btn btn-sm btn-outline-primary share-btn"
                                    data-text="'{{ Str::limit($testimonial->message, 100) }}' - {{ $testimonial->author_name }}"
                                    data-url="{{ url()->current() }}">
                                    <i class="fas fa-share-alt"></i> Share
                                </button>
                            </div>
                        </div>
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
@endsection

@push('styles')
<style>
    /* Testimonials Page Styles */
    .breadcrumb-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 3rem;
    }

    .breadcrumb-section .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 1rem;
    }

    .breadcrumb-section .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
    }

    .breadcrumb-section .breadcrumb-item.active {
        color: white;
    }

    .breadcrumb-section .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .breadcrumb-section .page-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    /* Stats Section */
    .testimonial-stats {
        border: 2px solid #e9ecef;
    }

    .stat-item {
        padding: 1rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Testimonial Card Full */
    .testimonial-card-full {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #eee;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }

    .testimonial-card-full:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
    }

    .featured-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .author-avatar-large {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .author-avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .author-name {
        font-weight: 700;
        color: #333;
    }

    .author-info {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .rating-display {
        font-size: 1.1rem;
    }

    .rating-text {
        font-weight: 600;
        color: #333;
    }

    .experience-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .testimonial-text {
        color: #333;
        font-size: 1.05rem;
        line-height: 1.7;
        font-style: italic;
        margin: 0;
    }

    .features-liked {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px dashed #dee2e6;
    }

    .features-title {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6c757d;
        margin-bottom: 0.75rem;
    }

    .features-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .feature-tag {
        background: #e9ecef;
        color: #495057;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .feature-tag:hover {
        background: #667eea;
        color: white;
    }

    .testimonial-footer {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eee;
    }

    /* Filter Section */
    .filter-section {
        border-left: 4px solid #667eea;
    }

    .filter-section .form-select {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 0.95rem;
    }

    .filter-section .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .cta-title {
        font-size: 2rem;
        font-weight: 700;
    }

    .cta-text {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .cta-section .btn-light {
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .cta-section .btn-light:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Empty State */
    .no-testimonials {
        background: #f8f9fa;
        border-radius: 15px;
        border: 2px dashed #dee2e6;
    }

    .empty-state-icon {
        color: #adb5bd;
    }

    /* Pagination Customization */
    .pagination .page-item.active .page-link {
        background: #667eea;
        border-color: #667eea;
    }

    .pagination .page-link {
        color: #667eea;
        border: 1px solid #dee2e6;
        padding: 8px 16px;
        margin: 0 2px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    /* Share Button */
    .share-btn {
        border-radius: 20px;
        padding: 5px 15px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .share-btn:hover {
        background: #667eea;
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .breadcrumb-section .page-title {
            font-size: 2rem;
        }

        .author-avatar-large {
            width: 60px;
            height: 60px;
            font-size: 1.2rem;
        }

        .testimonial-card-full {
            padding: 1.5rem;
        }

        .stat-item {
            padding: 0.5rem;
        }

        .stat-number {
            font-size: 2rem;
        }

        .cta-section {
            padding: 2rem 1rem !important;
        }

        .cta-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Share functionality
        const shareButtons = document.querySelectorAll('.share-btn');

        shareButtons.forEach(button => {
            button.addEventListener('click', function() {
                const text = this.getAttribute('data-text');
                const url = this.getAttribute('data-url');

                if (navigator.share) {
                    // Use Web Share API if available
                    navigator.share({
                        title: 'Parent Testimonial - SKOOLYST',
                        text: text,
                        url: url
                    }).then(() => {
                        console.log('Thanks for sharing!');
                    }).catch(console.error);
                } else {
                    // Fallback: Copy to clipboard
                    const textToCopy = `${text}\n\nRead more: ${url}`;

                    navigator.clipboard.writeText(textToCopy).then(() => {
                        // Show tooltip or alert
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
                        this.classList.remove('btn-outline-primary');
                        this.classList.add('btn-success');

                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.classList.remove('btn-success');
                            this.classList.add('btn-outline-primary');
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy: ', err);
                        alert('Failed to copy to clipboard. Please copy the URL manually.');
                    });
                }
            });
        });
    });
</script>
@endpush