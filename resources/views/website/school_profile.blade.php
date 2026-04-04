@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/school-profile.css') }}">
@if($school->custom_style)
<link rel="stylesheet" href="{{ asset('assets/css/schools/' . $school->custom_style . '.css') }}">
@endif


@endpush

@section('content')
    <!-- School Header Section -->
    <section class="school-header {{ $school->banner_image ? 'has-banner-image' : '' }}"
        @if($school->banner_image)
            style="background-image: url('{{ asset('website/' . $school->banner_image) }}')"
        @endif
    >
        <div class="school-header-overlay"></div>

        @if($school->banner_title)
            <div class="container">
                <div class="school-hero-content">
                    <div class="school-logo-wrapper me-3">
                        @if($school->profile->logo)
                            <img src="{{ asset('website/'. $school->profile->logo) }}" alt="{{ $school->name }} Logo" class="school-logo-img rounded" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid white;">
                        @else
                            <div class="school-logo-placeholder rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: rgba(255,255,255,0.2);">
                                <i class="fas fa-school text-white"></i>
                            </div>
                        @endif
                    </div>

                    <div class="school-text-content">
                        <h1 class="school-title">{{ $school->banner_title ?? $school->name }}</h1>
                        @if($school->banner_tagline)
                            <p class="school-tagline">{{ $school->banner_tagline ?? "" }}</p>
                        @endif
                        <p class="school-name-sub">{{ $school->name }}</p>
                    </div>
                </div>
            </div>
        @endif
        
    </section>

    <!-- School Navigation -->
    <nav class="school-navigation">
        <div class="container">
            <div class="nav-links-wrapper">
                <button class="nav-scroll-btn nav-scroll-left" aria-label="Scroll left">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <ul class="nav-links" id="schoolNavLinks">
                    <li><a href="#overview" class="nav-link active" data-tab="overview">Overview</a></li>
                    <li><a href="#gallery" class="nav-link" data-tab="gallery">Gallery</a></li>
                    <li><a href="#curriculum" class="nav-link" data-tab="curriculum">Curriculum</a></li>
                    <li><a href="#facilities" class="nav-link" data-tab="facilities">Facilities</a></li>
                    <li><a href="#mission-vision" class="nav-link" data-tab="mission-vision">Mission & Vision</a></li>
                    <li><a href="#reviews" class="nav-link" data-tab="reviews">Reviews</a></li>
                    <li><a href="#announcements" class="nav-link" data-tab="announcements">Announcement 
                            @if($school->hasNewAnnouncements())
                                    <i class="fas fa-bullhorn" style="color: #03ec3a"></i>
                            @endif
                        </a>
                    </li>
                    <li><a href="#branches" class="nav-link" data-tab="branches">Branches</a></li>
                    <li><a href="#contact" class="nav-link" data-tab="contact">Contact</a></li>
                </ul>
                <button class="nav-scroll-btn nav-scroll-right" aria-label="Scroll right">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
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
                            
                            <!-- Quick Facts -->
                            <div class="quick-facts">
                                <h3>Quick Facts</h3>
                                <div class="facts-grid">
                                    <!-- Basic Facts -->
                                    @if($school->profile->established_year)
                                        <div class="fact-item">
                                            <i class="fas fa-calendar"></i>
                                            <span>Established: {{ $school->profile->established_year ?? 'N/A' }}</span>
                                        </div>
                                    @endif
                                    @if($school->profile->student_strength)
                                    <div class="fact-item">
                                        <i class="fas fa-users"></i>
                                        <span>Student Strength: {{ $school->profile->student_strength ?? 'N/A' }}</span>
                                    </div>
                                    @endif
                                    @if($school->profile->faculty_count)
                                    <div class="fact-item">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <span>Faculty: {{ $school->profile->faculty_count ?? 'N/A' }} teachers</span>
                                    </div>
                                    @endif
                                    @if($school->profile->campus_size)
                                    <div class="fact-item">
                                        <i class="fas fa-building"></i>
                                        <span>Campus Size: {{ $school->profile->campus_size ?? 'N/A' }}</span>
                                    </div>
                                    @endif

                                    <!-- Additional Quick Facts from JSON -->
                                    @if($school->profile && $school->profile->quick_facts)
                                        @php
                                            $quickFacts = json_decode($school->profile->quick_facts, true);
                                        @endphp

                                        @foreach($quickFacts as $key => $value)
                                            @if(!in_array($key, ['established_year', 'student_strength', 'faculty_count', 'campus_size']))
                                                <div class="fact-item">
                                                    <i class="fas fa-check-circle"></i>
                                                    <span>{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif

                                </div>
                            </div>

                            <!-- School Statistics -->
                            <!-- @if($school->profile && ($school->profile->visitor_count > 0 || $school->profile->total_time_spent > 0))
                            <div class="school-statistics">
                                <h3>School Statistics</h3>
                                <div class="stats-grid">
                                    @if($school->profile->visitor_count > 0)
                                    <div class="stat-item">
                                        <div class="stat-number">{{ $school->profile->visitor_count }}</div>
                                        <div class="stat-label">Profile Visitors</div>
                                    </div>
                                    @endif
                                    @if($school->profile->total_time_spent > 0)
                                    <div class="stat-item">
                                        <div class="stat-number">{{ round($school->profile->total_time_spent / 60) }}</div>
                                        <div class="stat-label">Minutes Spent by Visitors</div>
                                    </div>
                                    @endif
                                    @if($school->profile->last_visited_at)
                                    <div class="stat-item">
                                        <div class="stat-number">{{ $school->profile->last_visited_at->format('M d') }}</div>
                                        <div class="stat-label">Last Visited</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif -->
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
                                                @if($feature->pivot->description)
                                                    <p class="facility-description">{{ $feature->pivot->description }}</p>
                                                @elseif($feature->description)
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
                        @if($school->description)
                            <p>{{ $school->description??"" }}</p>
                        @endif
                    </section>

                    <!-- Mission & Vision Section -->
                    <section id="mission-vision" class="content-section">
                        <h2 class="section-title">Mission & Vision</h2>
                        <div class="section-content">
                            <div class="mission-vision-grid">
                                @if($school->profile && $school->profile->mission)
                                <div class="mission-vision-item">
                                    <div class="mv-icon">
                                        <i class="fas fa-bullseye"></i>
                                    </div>
                                    <h3>Our Mission</h3>
                                    <p>{{ $school->profile->mission }}</p>
                                </div>
                                @endif
                                
                                @if($school->profile && $school->profile->vision)
                                <div class="mission-vision-item">
                                    <div class="mv-icon">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                    <h3>Our Vision</h3>
                                    <p>{{ $school->profile->vision }}</p>
                                </div>
                                @endif
                                
                                @if($school->profile && $school->profile->school_motto)
                                <div class="mission-vision-item">
                                    <div class="mv-icon">
                                        <i class="fas fa-quote-left"></i>
                                    </div>
                                    <h3>Our Motto</h3>
                                    <p>"{{ $school->profile->school_motto }}"</p>
                                </div>
                                @endif
                            </div>
                            
                            @if(!$school->profile || (!$school->profile->mission && !$school->profile->vision && !$school->profile->school_motto))
                                <p class="no-content">Mission and vision information not available.</p>
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
                                                    <span class="reviewer-name">
                                                        {{ $review->user_id ? $review->user->name : $review->reviewer_name }}
                                                        @if($review->user_id)
                                                            <span class="verified-badge">✓ Verified</span>
                                                        @endif
                                                    </span>
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

                    {{-- <section class="sidebar-section">
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
                            <button class="action-btn tertiary" id="writeReviewBtn">
                                <i class="fas fa-edit"></i>
                                Write Review
                            </button>
                        </div>
                    </section> --}}

                    <!-- Review Modal -->
                    <div id="reviewModal" class="review-modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Write a Review</h3>
                                <button type="button" class="close-modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                @auth
                                    <form id="reviewForm" action="{{ route('reviews.store', $school->id) }}" method="POST">
                                        @csrf
                                        <div class="star-rating-container">
                                            <label class="star-rating-label">Your Rating *</label>
                                            <div class="star-rating">
                                                <input type="radio" id="star5" name="rating" value="5">
                                                <label for="star5" class="star-label"><i class="fas fa-star"></i></label>
                                                <input type="radio" id="star4" name="rating" value="4">
                                                <label for="star4" class="star-label"><i class="fas fa-star"></i></label>
                                                <input type="radio" id="star3" name="rating" value="3">
                                                <label for="star3" class="star-label"><i class="fas fa-star"></i></label>
                                                <input type="radio" id="star2" name="rating" value="2">
                                                <label for="star2" class="star-label"><i class="fas fa-star"></i></label>
                                                <input type="radio" id="star1" name="rating" value="1">
                                                <label for="star1" class="star-label"><i class="fas fa-star"></i></label>
                                            </div>
                                            <div class="rating-text">
                                                <span id="ratingText">Select your rating</span>
                                            </div>
                                        </div>
                                        
                                        <div class="review-form-group">
                                            <label for="review">Your Review *</label>
                                            <textarea name="review" id="review" rows="5" placeholder="Share your experience with this school..." required></textarea>
                                        </div>
                                        
                                        <div class="modal-form-actions">
                                            <button type="button" class="btn-cancel-review" id="cancelReview">Cancel</button>
                                            <button type="submit" class="btn-submit-review">Submit Review</button>
                                        </div>
                                    </form>
                                @else
                                    <div class="login-required">
                                        <div class="login-icon">
                                            <i class="fas fa-user-lock"></i>
                                        </div>
                                        <h4>Login Required</h4>
                                        <p>Please login to submit your review and help other parents make informed decisions.</p>
                                        <div class="auth-buttons">
                                            <a href="{{ route('login') }}" class="btn-login-modal">Login</a>
                                            <a href="{{ route('register') }}" class="btn-register-modal">Register</a>
                                        </div>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>



                    <!-- Add the modal for login required -->
                    <div id="contactInquiryForm" class="modal" style="display: none;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Submit Query</h3>
                                <span class="close">&times;</span>
                            </div>
                            <div class="modal-body">                               
                                <div class="login-required">
                                    <div class="login-icon">
                                        <i class="fas fa-user-lock"></i>
                                    </div>
                                    <h4>Login Required</h4>
                                    <p>Please login to submit your query and help other parents make informed decisions.</p>
                                    <div class="auth-buttons">
                                        <a href="{{ route('login') }}" class="btn-login">Login</a>
                                        <a href="{{ route('register') }}" class="btn-register">Register</a>
                                    </div>
                                </div>                                    
                            </div>
                        </div>
                    </div>


                    <!-- Announcements Section -->
                    <section id="announcements" class="content-section">
                        <h2 class="section-title">Latest Announcements</h2>
                        <div class="section-content">
                            @if($school->announcements && $school->announcements->where('status', 'published')->count() > 0)
                                @php
                                    $publishedAnnouncements = $school->announcements->where('status', 'published')
                                        ->filter(function($announcement) {
                                            // Filter announcements that are published and within date range
                                            return $announcement->isPublished();
                                        })
                                        ->sortByDesc('created_at');
                                @endphp
                                
                                @if($publishedAnnouncements->count() > 0)
                                    <div class="announcements-list">
                                        @foreach($publishedAnnouncements->take(5) as $announcement)
                                            <div class="announcement-item card mb-3">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @if($announcement->feature_image)
                                                        <div class="col-md-3">
                                                            <img src="{{ $announcement->feature_image_url }}" 
                                                                alt="{{ $announcement->title }}" 
                                                                class="announcement-image img-fluid rounded">
                                                        </div>
                                                        @endif
                                                        <div class="{{ $announcement->feature_image ? 'col-md-9' : 'col-12' }}">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <h4 class="announcement-title mb-0">
                                                                    <a href="{{ route('website.announcements.show', $announcement->uuid) }}" 
                                                                    class="text-decoration-none">
                                                                        {{ $announcement->title }}
                                                                    </a>
                                                                </h4>
                                                                @if($announcement->created_at->gt(now()->subDays(7)))
                                                                    <span class="badge bg-danger ms-2">New</span>
                                                                @endif
                                                            </div>
                                                            
                                                            <div class="announcement-meta mb-2">
                                                                <small class="text-muted">
                                                                    <i class="fas fa-calendar-alt"></i>
                                                                    Posted {{ $announcement->created_at->diffForHumans() }}
                                                                    @if($announcement->branch)
                                                                        • <i class="fas fa-building"></i>
                                                                        {{ $announcement->branch->name }}
                                                                    @endif
                                                                    • <i class="fas fa-eye"></i>
                                                                    {{ $announcement->view_count }} views
                                                                    • <i class="fas fa-comments"></i>
                                                                    {{ $announcement->comments->count() }} comments
                                                                </small>
                                                            </div>
                                                            
                                                            <p class="announcement-description mb-3">
                                                                {{ Str::limit(strip_tags($announcement->content), 200) }}
                                                            </p>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <a href="{{ route('website.announcements.show', $announcement->uuid) }}" 
                                                                class="btn btn-sm btn-outline-primary">
                                                                    Read More <i class="fas fa-arrow-right ms-1"></i>
                                                                </a>
                                                                
                                                                @if($announcement->publish_at || $announcement->expire_at)
                                                                <div class="announcement-dates">
                                                                    @if($announcement->publish_at)
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-clock"></i>
                                                                            Published: {{ $announcement->publish_at->format('M d, Y') }}
                                                                        </small>
                                                                    @endif
                                                                    @if($announcement->expire_at)
                                                                        <br>
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-calendar-times"></i>
                                                                            Expires: {{ $announcement->expire_at->format('M d, Y') }}
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if($publishedAnnouncements->count() > 5)
                                        <div class="text-center mt-4">
                                            <a href="{{ route('schools.announcements', $school->uuid) }}" 
                                            class="btn btn-primary">
                                                View All Announcements ({{ $publishedAnnouncements->count() }})
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                                        <p class="no-content">No active announcements at the moment.</p>
                                        <p class="text-muted">Check back later for updates from this school.</p>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                                    <p class="no-content">No announcements available.</p>
                                    <p class="text-muted">This school hasn't posted any announcements yet.</p>
                                </div>
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

                    <!-- Contact Section -->
                    <section id="contact" class="content-section">
                        <h2 class="section-title">Contact Us</h2>
                        <div class="section-content">
                            <!-- Contact Form -->
                            <div class="contact-form-section">
                                <form id="contactForm" class="contact-form" action="{{ route('contact.inquiry.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="school_id" value="{{ $school->id }}">
                                    
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="name">Full Name *</label>
                                            <input type="text" id="name" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email Address *</label>
                                            <input type="email" id="email" name="email" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <input type="tel" id="phone" name="phone">
                                        </div>
                                        <div class="form-group">
                                            <label for="subject">Subject *</label>
                                            <select id="subject" name="subject" required>
                                                <option value="">Select a subject</option>
                                                <option value="admission">Admission Inquiry</option>
                                                <option value="tour">Schedule a Tour</option>
                                                <option value="general">General Information</option>
                                                <option value="feedback">Feedback</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="message">Message *</label>
                                        <textarea id="message" name="message" rows="5" placeholder="Please enter your message here..." required></textarea>
                                    </div>
                                    
                                    <div class="form-actions">
                                        <button type="submit" class="btn-submit">
                                            <i class="fas fa-paper-plane"></i>
                                            Send Message
                                        </button>
                                    </div>
                                </form>
                            </div>
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

                    <!-- School Profile Info -->
                    <!-- <section class="sidebar-section">
                        <h3 class="sidebar-title">School Profile</h3>
                        <div class="profile-info">
                            <div class="profile-item">
                                <i class="fas fa-school"></i>
                                <div>
                                    <span class="profile-label">Established</span>
                                    <span class="profile-value">{{ $school->profile->established_year ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="profile-item">
                                <i class="fas fa-users"></i>
                                <div>
                                    <span class="profile-label">Students</span>
                                    <span class="profile-value">{{ $school->profile->student_strength ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="profile-item">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <div>
                                    <span class="profile-label">Faculty</span>
                                    <span class="profile-value">{{ $school->profile->faculty_count ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="profile-item">
                                <i class="fas fa-expand-arrows-alt"></i>
                                <div>
                                    <span class="profile-label">Campus</span>
                                    <span class="profile-value">{{ $school->profile->campus_size ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </section> -->

                    <!-- Fee Structure -->
                    <section class="sidebar-section">
                        <h3 class="sidebar-title">Fee Structure</h3>
                        <div class="fee-info">

                            {{-- ✅ FIXED STRUCTURE --}}
                            @if($school->fee_structure_type === 'fixed')

                                @if($school->regular_fees)
                                    <div class="fee-item">
                                        <span class="fee-label">Regular Fees:</span>
                                        <span class="fee-amount">Rs {{ number_format($school->regular_fees) }}</span>
                                    </div>
                                @endif

                                @if($school->discounted_fees)
                                    <div class="fee-item">
                                        <span class="fee-label">Discounted Fees:</span>
                                        <span class="fee-amount">Rs {{ number_format($school->discounted_fees) }}</span>
                                    </div>
                                @endif

                                @if($school->admission_fees)
                                    <div class="fee-item">
                                        <span class="fee-label">Admission Fees:</span>
                                        <span class="fee-amount">Rs {{ number_format($school->admission_fees) }}</span>
                                    </div>
                                @endif

                                @if(!$school->regular_fees && !$school->discounted_fees && !$school->admission_fees)
                                    <p class="no-content">Fee information not available.</p>
                                @endif


                            {{-- ✅ CLASS-WISE STRUCTURE --}}
                            @elseif($school->fee_structure_type === 'class_wise')

                                @php
                                    $classFees = is_array($school->class_wise_fees)
                                        ? $school->class_wise_fees
                                        : json_decode($school->class_wise_fees, true);
                                @endphp

                                @if(!empty($classFees) && is_array($classFees))

                                    @foreach($classFees as $range => $amount)
                                        <div class="fee-item">
                                            <span class="fee-label">{{ $range }}</span>
                                            <span class="fee-amount">Rs {{ $amount }}</span>
                                        </div>
                                    @endforeach

                                @else
                                    <p class="no-content">Class-wise fee information not available.</p>
                                @endif

                                {{-- Admission Fees --}}
                                @if($school->admission_fees)
                                    <div class="fee-item">
                                        <span class="fee-label">Admission Fees:</span>
                                        <span class="fee-amount">Rs {{ number_format($school->admission_fees) }}</span>
                                    </div>
                                @endif
                            @else
                                <p class="no-content">Fee structure not defined.</p>
                            @endif
                        </div>
                    </section>

                    <!-- Quick Actions -->
                      <section class="sidebar-section">
                        <h3 class="sidebar-title">Quick Actions</h3>
                        <div class="action-buttons">
                            <!-- <button class="action-btn primary">
                                <i class="fas fa-download"></i>
                                Download Brochure
                            </button>
                            <button class="action-btn secondary">
                                <i class="fas fa-calendar-check"></i>
                                Schedule Visit
                            </button> -->
                            <button class="action-btn tertiary" id="writeReviewBtn">
                                <i class="fas fa-edit"></i>
                                Write Review
                            </button>
                        </div>
                    </section>

                    <!-- Social Media -->
                    <section class="sidebar-section">
                        <h3 class="sidebar-title">Follow Us</h3>
                        <div class="social-links">
                            @if($school->profile && $school->profile->social_media)
                                @php
                                    $socialMedia = json_decode($school->profile->social_media, true);
                                @endphp

                                @foreach($socialMedia as $platform => $url)
                                    @if($url)
                                        <a href="{{ $url }}" target="_blank" class="social-link" title="{{ ucfirst($platform) }}">
                                            <i class="fab fa-{{ $platform }}"></i>
                                        </a>
                                    @endif
                                @endforeach
                            @else
                                <!-- Fallback social links -->
                                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                            @endif

                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/school-profile.js') }}"></script>
<script src="{{ asset('assets/js/contact-form.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navLinks = document.getElementById('schoolNavLinks');
        const scrollLeftBtn = document.querySelector('.nav-scroll-left');
        const scrollRightBtn = document.querySelector('.nav-scroll-right');

        if (!navLinks || !scrollLeftBtn || !scrollRightBtn) return;

        // Scroll amount (in pixels)
        const scrollAmount = 150;

        // Update button visibility based on scroll position
        function updateScrollButtons() {
            const atStart = navLinks.scrollLeft <= 10;
            const atEnd = navLinks.scrollLeft + navLinks.clientWidth >= navLinks.scrollWidth - 10;

            scrollLeftBtn.disabled = atStart;
            scrollRightBtn.disabled = atEnd;
        }

        // Initial check
        updateScrollButtons();

        // Scroll left
        scrollLeftBtn.addEventListener('click', () => {
            navLinks.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });

        // Scroll right
        scrollRightBtn.addEventListener('click', () => {
            navLinks.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        // Update buttons when user scrolls manually
        navLinks.addEventListener('scroll', updateScrollButtons);
    });


    // Review Modal Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('reviewModal');
            const writeReviewBtn = document.getElementById('writeReviewBtn');
            const closeBtn = document.querySelector('.close-modal');
            const cancelBtn = document.getElementById('cancelReview');
            const starInputs = document.querySelectorAll('.star-rating input');
            const ratingText = document.getElementById('ratingText');
            
            // Rating descriptions
            const ratingDescriptions = {
                1: 'Poor - Very dissatisfied',
                2: 'Fair - Could be better',
                3: 'Good - Met expectations',
                4: 'Very Good - Exceeded expectations',
                5: 'Excellent - Far beyond expectations'
            };
            
            // Open modal
            if (writeReviewBtn) {
                writeReviewBtn.addEventListener('click', function() {
                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            }
            
            // Close modal function
            function closeModal() {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            // Close button
            if (closeBtn) {
                closeBtn.addEventListener('click', closeModal);
            }
            
            // Cancel button
            if (cancelBtn) {
                cancelBtn.addEventListener('click', closeModal);
            }
            
            // Close when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
            
            // Star rating interaction
            if (starInputs.length > 0) {
                starInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        const rating = this.value;
                        if (ratingText) {
                            ratingText.textContent = ratingDescriptions[rating] || 'Select your rating';
                        }
                    });
                });
            }
            
            // Form submission handling
            const reviewForm = document.getElementById('reviewForm');
            if (reviewForm) {
                reviewForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const rating = document.querySelector('input[name="rating"]:checked');
                    const reviewText = document.getElementById('review').value.trim();
                    
                    // Validation
                    if (!rating) {
                        alert('Please select a rating');
                        return false;
                    }
                    
                    if (!reviewText) {
                        alert('Please write your review');
                        return false;
                    }
                    
                    // Add loading state
                    const submitBtn = this.querySelector('.btn-submit-review');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');
                    
                    // Submit via fetch
                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Review submitted successfully!');
                            closeModal();
                            location.reload();
                        } else {
                            alert('Error: ' + (data.message || 'Failed to submit review'));
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('loading');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while submitting your review');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('loading');
                    });
                });
            }
        });
</script>
@endpush