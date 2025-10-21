@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/school-profile.css') }}">
@if($school->custom_style)
<link rel="stylesheet" href="{{ asset('assets/css/schools/' . $school->custom_style . '.css') }}">
@endif
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')
    <!-- School Header Section -->
    <section class="school-header" 
        @if($school->banner_image)
            style="background-image: url('{{ asset('website/' . $school->banner_image) }}'); background-size: cover; background-position: center center;"
        @endif
    >
        <div class="container">
            <div class="school-basic-info">
                <div class="school-logo">
                    @if($school->logo)
                        <img src="{{ asset('website/' . $school->logo) }}" alt="{{ $school->name }} Logo">
                    @else
                        <div class="school-logo-placeholder">
                            <i class="fas fa-school"></i>
                        </div>
                    @endif
                </div>
                <div class="school-info">
                    <h1 class="school-name">{{ $school->name }}</h1>
                    <div class="school-meta">
                        <span class="school-location">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $school->city }}, {{ $school->address }}
                        </span>
                        <span class="school-type">
                            <i class="fas fa-tag"></i>
                            {{ $school->school_type }}
                        </span>
                    </div>
                    <div class="school-rating">
                        @php
                            $averageRating = $school->reviews->avg('rating') ?? 0;
                            $reviewCount = $school->reviews->count();
                        @endphp
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($averageRating))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $averageRating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-value">{{ number_format($averageRating, 1) }}</span>
                        <span class="review-count">({{ $reviewCount }} reviews)</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Optional: Keep a semi-transparent overlay for better text contrast --}}
        @if($school->banner)
            <div class="school-header-overlay"></div>
        @endif
    </section>

    <!-- School Navigation -->
    <nav class="school-navigation">
        <div class="container">
            <ul class="nav-links">
                <li><a href="#overview" class="nav-link active" data-tab="overview">Overview</a></li>
                <li><a href="#gallery" class="nav-link" data-tab="gallery">Gallery</a></li>
                <li><a href="#curriculum" class="nav-link" data-tab="curriculum">Curriculum</a></li>
                <li><a href="#facilities" class="nav-link" data-tab="facilities">Facilities</a></li>
                <li><a href="#reviews" class="nav-link" data-tab="reviews">Reviews</a></li>
                <li><a href="#events" class="nav-link" data-tab="events">Events</a></li>
                <li><a href="#branches" class="nav-link" data-tab="branches">Branches</a></li>
                <li><a href="#contact" class="nav-link" data-tab="contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="school-main-content">
        <div class="container">
            <div class="content-grid">
                <!-- Left Column - Main Content -->
                <div class="main-column">
                    <!-- Overview Section -->
                    <section id="overview" class="content-section active">
                        <h2 class="section-title">About Our School</h2>
                        <div class="section-content">
                            <p class="school-description">
                                {{ $school->description ?? 'No description available for this school.' }}
                            </p>
                            <div class="quick-facts">
                                <h3>Quick Facts</h3>
                                <div class="facts-grid">
                                    <div class="fact-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>Established: {{ $school->established_year ?? 'N/A' }}</span>
                                    </div>
                                    <div class="fact-item">
                                        <i class="fas fa-users"></i>
                                        <span>Student Strength: {{ $school->student_strength ?? 'N/A' }}</span>
                                    </div>
                                    <div class="fact-item">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <span>Faculty: {{ $school->faculty_count ?? 'N/A' }} teachers</span>
                                    </div>
                                    <div class="fact-item">
                                        <i class="fas fa-building"></i>
                                        <span>Campus Size: {{ $school->campus_size ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Gallery Section -->
                    <section id="gallery" class="content-section">
                        <h2 class="section-title">School Gallery</h2>
                        <div class="section-content">
                            @if($school->images && $school->images->count() > 0)
                                <div class="gallery-grid">
                                    @foreach($school->images as $image)
                                        <div class="gallery-item">
                                            <img src="{{ asset('website/' . $image->image_path) }}" alt="{{ $image->title ?? 'School Image' }}">
                                            @if($image->title)
                                                <div class="image-caption">{{ $image->title }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="no-content">No gallery images available.</p>
                            @endif
                        </div>
                    </section>

                    <!-- Curriculum Section -->
                    <section id="curriculum" class="content-section">
                        <h2 class="section-title">Curriculum & Programs</h2>
                        <div class="section-content">
                            @if($school->curriculums && $school->curriculums->count() > 0)
                                <div class="curriculum-list">
                                    @foreach($school->curriculums as $curriculum)
                                        <div class="curriculum-item">
                                            <h4>{{ $curriculum->name }}</h4>
                                            <p>{{ $curriculum->description ?? 'No description available.' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="no-content">Curriculum information not available.</p>
                            @endif
                        </div>
                    </section>

                    <!-- Facilities Section -->
                    <section id="facilities" class="content-section">
                        <h2 class="section-title">Facilities & Features</h2>
                        <div class="section-content">
                            @if($school->features && $school->features->count() > 0)
                                <div class="facilities-grid">
                                    @foreach($school->features as $feature)
                                        <div class="facility-item">
                                            <i class="fas fa-{{ $feature->icon ?? 'check' }}"></i>
                                            <div>
                                                <span>{{ $feature->name }}</span>
                                                @if($feature->description)
                                                    <p class="facility-description">{{ $feature->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="no-content">Facilities information not available.</p>
                            @endif
                        </div>
                    </section>

                    <!-- Reviews Section -->
                    <section id="reviews" class="content-section">
                        <h2 class="section-title">Parent & Student Reviews</h2>
                        <div class="section-content">
                            @if($school->reviews && $school->reviews->count() > 0)
                                <div class="reviews-list">
                                    @foreach($school->reviews as $review)
                                        <div class="review-item">
                                            <div class="review-header">
                                                <div class="reviewer-info">
                                                    <span class="reviewer-name">{{ $review->reviewer_name }}</span>
                                                    <div class="review-rating">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <span class="review-date">{{ $review->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <p class="review-content">{{ $review->review }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="no-content">No reviews yet. Be the first to review!</p>
                            @endif
                        </div>
                    </section>

                    <!-- Events Section -->
                    <section id="events" class="content-section">
                        <h2 class="section-title">Upcoming Events</h2>
                        <div class="section-content">
                            @if($school->events && $school->events->count() > 0)
                                <div class="events-list">
                                    @foreach($school->events as $event)
                                        <div class="event-item">
                                            <div class="event-date">
                                                <span class="event-day">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
                                                <span class="event-month">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</span>
                                            </div>
                                            <div class="event-details">
                                                <h4 class="event-title">{{ $event->event_name }}</h4>
                                                <p class="event-description">{{ $event->event_description }}</p>
                                                <div class="event-meta">
                                                    <span class="event-location">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        {{ $event->event_location }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="no-content">No upcoming events scheduled.</p>
                            @endif
                        </div>
                    </section>

                    <!-- Branches Section -->
                    <section id="branches" class="content-section">
                        <h2 class="section-title">Our Branches</h2>
                        <div class="section-content">
                            @if($school->branches && $school->branches->count() > 0)
                                <div class="branches-list">
                                    @foreach($school->branches as $branch)
                                        <div class="branch-item">
                                            <h4 class="branch-name">{{ $branch->name }}</h4>
                                            <div class="branch-details">
                                                <p class="branch-address">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ $branch->address }}, {{ $branch->city }}
                                                </p>
                                                @if($branch->contact_number)
                                                    <p class="branch-phone">
                                                        <i class="fas fa-phone"></i>
                                                        {{ $branch->contact_number }}
                                                    </p>
                                                @endif
                                                @if($branch->branch_head_name)
                                                    <p class="branch-head">
                                                        <i class="fas fa-user-tie"></i>
                                                        {{ $branch->branch_head_name }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="no-content">No additional branches information available.</p>
                            @endif
                        </div>
                    </section>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="sidebar-column">
                    <!-- Contact Information -->
                    <section id="contact-sidebar" class="sidebar-section">
                        <h3 class="sidebar-title">Contact Information</h3>
                        <div class="contact-info">
                            @if($school->contact_number)
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span>{{ $school->contact_number }}</span>
                                </div>
                            @endif
                            @if($school->email)
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ $school->email }}</span>
                                </div>
                            @endif
                            @if($school->website)
                                <div class="contact-item">
                                    <i class="fas fa-globe"></i>
                                    <a href="{{ $school->website }}" target="_blank">Visit Website</a>
                                </div>
                            @endif
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $school->address }}, {{ $school->city }}</span>
                            </div>
                        </div>
                    </section>

                    <!-- Fee Structure -->
                    <section class="sidebar-section">
                        <h3 class="sidebar-title">Fee Structure</h3>
                        <div class="fee-info">
                            @if($school->regular_fees)
                                <div class="fee-item">
                                    <span class="fee-label">Regular Fees:</span>
                                    <span class="fee-amount">₹{{ number_format($school->regular_fees) }}/year</span>
                                </div>
                            @endif
                            @if($school->discounted_fees)
                                <div class="fee-item">
                                    <span class="fee-label">Discounted Fees:</span>
                                    <span class="fee-amount">₹{{ number_format($school->discounted_fees) }}/year</span>
                                </div>
                            @endif
                            @if($school->admission_fees)
                                <div class="fee-item">
                                    <span class="fee-label">Admission Fees:</span>
                                    <span class="fee-amount">₹{{ number_format($school->admission_fees) }}</span>
                                </div>
                            @endif
                            @if(!$school->regular_fees && !$school->discounted_fees && !$school->admission_fees)
                                <p class="no-content">Fee information not available.</p>
                            @endif
                        </div>
                    </section>

                    <!-- Quick Actions -->
                    <section class="sidebar-section">
                        <h3 class="sidebar-title">Quick Actions</h3>
                        <div class="action-buttons">
                            <button class="action-btn primary">
                                <i class="fas fa-download"></i>
                                Download Brochure
                            </button>
                            <button class="action-btn secondary">
                                <i class="fas fa-calendar-check"></i>
                                Schedule Visit
                            </button>
                            <button class="action-btn tertiary">
                                <i class="fas fa-edit"></i>
                                Write Review
                            </button>
                        </div>
                    </section>

                    <!-- Social Media -->
                    <section class="sidebar-section">
                        <h3 class="sidebar-title">Follow Us</h3>
                        <div class="social-links">
                            <a href="#" class="social-link">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/school-profile.js') }}"></script>
@endpush