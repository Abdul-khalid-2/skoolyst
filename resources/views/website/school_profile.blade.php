@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/school-profile.css') }}">
@if($school->custom_style)
<link rel="stylesheet" href="{{ asset('assets/css/schools/' . $school->custom_style . '.css') }}">
@endif

<style>
    /* Review Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    display: flex;
    justify-content: between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #eaeaea;
}

.modal-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.5rem;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    line-height: 1;
}

.close:hover {
    color: #e74c3c;
}

.modal-body {
    padding: 24px;
}
/* Star Rating - Fixed */
/* Star Rating - Fixed Properly */
.star-rating {
    display: flex;
    gap: 8px;
    margin: 10px 0;
    /* Remove flex-direction: row-reverse */
}

.star-rating input {
    display: none;
}

.star-label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s ease;
}

/* Highlight selected stars and stars to the left */
.star-rating input:checked ~ .star-label {
    color: #ffc107;
}

/* Highlight on hover */
.star-label:hover,
.star-label:hover ~ .star-label {
    color: #ffc107;
}

/* Keep selected state when not hovering */
.star-rating:not(:hover) input:checked ~ .star-label {
    color: #ffc107;
}

/* Reset stars after the hovered one */
.star-rating:hover .star-label:hover ~ .star-label {
    color: #ddd;
}

.rating-text {
    margin-top: 8px;
    color: #666;
    font-style: italic;
}

/* Form Styles */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #eaeaea;
    border-radius: 8px;
    resize: vertical;
    font-family: inherit;
    transition: border-color 0.3s ease;
}

.form-group textarea:focus {
    outline: none;
    border-color: #3498db;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
}

.btn-cancel, .btn-submit {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-cancel {
    background-color: #95a5a6;
    color: white;
}

.btn-cancel:hover {
    background-color: #7f8c8d;
}

.btn-submit {
    background-color: #27ae60;
    color: white;
}

.btn-submit:hover {
    background-color: #219a52;
}

.btn-submit:disabled {
    background-color: #bdc3c7;
    cursor: not-allowed;
}

/* Login Required */
.login-required {
    text-align: center;
    padding: 20px 0;
}

.login-icon {
    font-size: 4rem;
    color: #3498db;
    margin-bottom: 20px;
}

.login-required h4 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.login-required p {
    color: #666;
    margin-bottom: 24px;
}

.auth-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.btn-login, .btn-register {
    padding: 12px 24px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-login {
    background-color: #3498db;
    color: white;
}

.btn-login:hover {
    background-color: #2980b9;
}

.btn-register {
    background-color: #2c3e50;
    color: white;
}

.btn-register:hover {
    background-color: #1a252f;
}

/* Verified Badge */
.verified-badge {
    background-color: #27ae60;
    color: white;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 10px;
    margin-left: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .modal-content {
        margin: 10% auto;
        width: 95%;
    }
    
    .form-actions, .auth-buttons {
        flex-direction: column;
    }
}

/* Star Rating Styles */
.review-rating {
    display: flex;
    gap: 2px;
    align-items: center;
}

.review-rating i {
    font-size: 14px;
    transition: all 0.2s ease;
}

.review-rating .fas.fa-star {
    color: #ffc107; /* Gold color for filled stars */
    text-shadow: 0 1px 2px rgba(255, 193, 7, 0.3);
}

.review-rating .far.fa-star {
    color: #ffc107; /* Same color but lighter */
    opacity: 0.3;
}

/* Gradient Star Rating */
.review-rating.gradient {
    display: flex;
    gap: 1px;
}

.review-rating.gradient .fas.fa-star {
    background: linear-gradient(135deg, #ffc107, #ff6b00);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 1px 2px rgba(255, 107, 0, 0.2);
}

.review-rating.gradient .far.fa-star {
    background: linear-gradient(135deg, #e0e0e0, #b0b0b0);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}



/* Contact Section Styles */
/* .contact-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-top: 20px;
} */

.contact-info-section h3,
.contact-form-section h3 {
    color: #2c3e50;
    margin-bottom: 20px;
    font-size: 1.4rem;
}

.contact-details {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.contact-detail-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.contact-detail-item i {
    color: #3498db;
    font-size: 1.2rem;
    margin-top: 2px;
    width: 20px;
}

.contact-detail-item div {
    display: flex;
    flex-direction: column;
}

.contact-detail-item strong {
    color: #2c3e50;
    font-size: 0.9rem;
    margin-bottom: 4px;
}

.contact-detail-item span,
.contact-detail-item a {
    color: #555;
    text-decoration: none;
}

.contact-detail-item a:hover {
    color: #3498db;
}

/* Contact Form Styles */
.contact-form {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #eaeaea;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.contact-form .form-group {
    margin-bottom: 20px;
}

.contact-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.contact-form input,
.contact-form select,
.contact-form textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #eaeaea;
    border-radius: 8px;
    font-family: inherit;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.contact-form input:focus,
.contact-form select:focus,
.contact-form textarea:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.contact-form textarea {
    resize: vertical;
    min-height: 120px;
}

.contact-form .form-actions {
    margin-top: 30px;
    text-align: center;
}

.contact-form .btn-submit {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.contact-form .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
}

.contact-form .btn-submit:active {
    transform: translateY(0);
}

/* Form Validation Styles */
.contact-form input:invalid:not(:focus):not(:placeholder-shown),
.contact-form select:invalid:not(:focus),
.contact-form textarea:invalid:not(:focus):not(:placeholder-shown) {
    border-color: #e74c3c;
}

.contact-form input:valid:not(:focus):not(:placeholder-shown),
.contact-form select:valid:not(:focus),
.contact-form textarea:valid:not(:focus):not(:placeholder-shown) {
    border-color: #27ae60;
}

/* Success Message */
.form-success {
    background: #d4edda;
    color: #155724;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #c3e6cb;
    margin-bottom: 20px;
    text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    /* .contact-container {
        grid-template-columns: 1fr;
        gap: 30px;
    } */
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .contact-form {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .contact-detail-item {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .contact-detail-item i {
        align-self: center;
    }
}
</style>
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
                    <li><a href="#events" class="nav-link" data-tab="events">Events</a></li>
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
                                    <div class="fact-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>Established: {{ $school->profile->established_year ?? 'N/A' }}</span>
                                    </div>
                                    <div class="fact-item">
                                        <i class="fas fa-users"></i>
                                        <span>Student Strength: {{ $school->profile->student_strength ?? 'N/A' }}</span>
                                    </div>
                                    <div class="fact-item">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <span>Faculty: {{ $school->profile->faculty_count ?? 'N/A' }} teachers</span>
                                    </div>
                                    <div class="fact-item">
                                        <i class="fas fa-building"></i>
                                        <span>Campus Size: {{ $school->profile->campus_size ?? 'N/A' }}</span>
                                    </div>
                                    
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
                    <div id="reviewModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Write a Review</h3>
                                <span class="close">&times;</span>
                            </div>
                            <div class="modal-body">
                                @auth
                                    <form id="reviewForm" action="{{ route('reviews.store', $school->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="rating">Your Rating *</label>
                                            <div class="star-rating">
                                                <input type="radio" id="star1" name="rating" value="1" required>
                                                <label for="star1" class="star-label">
                                                    <i class="far fa-star"></i>
                                                </label>
                                                <input type="radio" id="star2" name="rating" value="2" required>
                                                <label for="star2" class="star-label">
                                                    <i class="far fa-star"></i>
                                                </label>
                                                <input type="radio" id="star3" name="rating" value="3" required>
                                                <label for="star3" class="star-label">
                                                    <i class="far fa-star"></i>
                                                </label>
                                                <input type="radio" id="star4" name="rating" value="4" required>
                                                <label for="star4" class="star-label">
                                                    <i class="far fa-star"></i>
                                                </label>
                                                <input type="radio" id="star5" name="rating" value="5" required>
                                                <label for="star5" class="star-label">
                                                    <i class="far fa-star"></i>
                                                </label>
                                            </div>
                                            <div class="rating-text">
                                                <span id="ratingText">Select your rating</span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="review">Your Review *</label>
                                            <textarea name="review" id="review" rows="5" placeholder="Share your experience with this school..." required></textarea>
                                        </div>
                                        
                                        <div class="form-actions">
                                            <button type="button" class="btn-cancel" id="cancelReview">Cancel</button>
                                            <button type="submit" class="btn-submit">Submit Review</button>
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
                                            <a href="{{ route('login') }}" class="btn-login">Login</a>
                                            <a href="{{ route('register') }}" class="btn-register">Register</a>
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
                                                <a href="{{ route('advertisement_pages.index', $event->id) }}">
                                                    <h4 class="event-title">{{ $event->event_name }}</h4>
                                                </a>
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
                    <section class="sidebar-section">
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
                    </section>

                    <!-- Fee Structure -->
                    <section class="sidebar-section">
                        <h3 class="sidebar-title">Fee Structure</h3>
                        <div class="fee-info">
                            @if($school->regular_fees)
                                <div class="fee-item">
                                    <span class="fee-label">Regular Fees:</span>
                                    <span class="fee-amount">Rs{{ number_format($school->regular_fees) }}/month</span>
                                </div>
                            @endif
                            @if($school->discounted_fees)
                                <div class="fee-item">
                                    <span class="fee-label">Discounted Fees:</span>
                                    <span class="fee-amount">Rs{{ number_format($school->discounted_fees) }}/month</span>
                                </div>
                            @endif
                            @if($school->admission_fees)
                                <div class="fee-item">
                                    <span class="fee-label">Admission Fees:</span>
                                    <span class="fee-amount">Rs{{ number_format($school->admission_fees) }}</span>
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
        const closeBtn = document.querySelector('.close');
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
        writeReviewBtn.addEventListener('click', function() {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
        
        // Close modal
        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);
        
        // Close when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
        
        // Star rating interaction
        starInputs.forEach(input => {
            const label = input.nextElementSibling;
            
            input.addEventListener('change', function() {
                const rating = this.value;
                ratingText.textContent = ratingDescriptions[rating] || 'Select your rating';
                
                // Update all stars based on selection
                starInputs.forEach(star => {
                    const starLabel = star.nextElementSibling;
                    if (star.value <= rating) {
                        starLabel.style.color = '#ffc107';
                    } else {
                        starLabel.style.color = '#ddd';
                    }
                });
            });
            
            // Add hover effects
            label.addEventListener('mouseenter', function() {
                const currentRating = this.previousElementSibling.value;
                ratingText.textContent = ratingDescriptions[currentRating] || 'Select your rating';
            });
        });

        // Reset stars when mouse leaves the rating area
        document.querySelector('.star-rating').addEventListener('mouseleave', function() {
            const checkedInput = document.querySelector('.star-rating input:checked');
            if (checkedInput) {
                const rating = checkedInput.value;
                starInputs.forEach(star => {
                    const starLabel = star.nextElementSibling;
                    if (star.value <= rating) {
                        starLabel.style.color = '#ffc107';
                    } else {
                        starLabel.style.color = '#ddd';
                    }
                });
            } else {
                // No rating selected, reset all to default
                document.querySelectorAll('.star-label').forEach(label => {
                    label.style.color = '#ddd';
                });
            }
        });
        
        // Form submission handling
        const reviewForm = document.getElementById('reviewForm');
        // Update your form submission handler
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default first for debugging
                
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
                const submitBtn = this.querySelector('.btn-submit');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                submitBtn.disabled = true;
                
                // Debug: Log form data
                const formData = new FormData(this);
                console.log('Form data:', Object.fromEntries(formData));
                
                // Submit via fetch to see the actual response
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        alert('Review submitted successfully!');
                        closeModal();
                        location.reload(); // Reload to show new review
                    } else {
                        alert('Error: ' + (data.message || 'Failed to submit review'));
                        submitBtn.innerHTML = 'Submit Review';
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting your review');
                    submitBtn.innerHTML = 'Submit Review';
                    submitBtn.disabled = false;
                });
            });
        }
    });
</script>
@endpush