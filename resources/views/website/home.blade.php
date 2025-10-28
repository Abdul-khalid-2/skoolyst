@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<style>
/* ==================== VIDEO SECTION STYLES ==================== */
.video-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.video-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    z-index: 1;
}

.video-container {
    position: relative;
    z-index: 2;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.video-header {
    text-align: center;
    margin-bottom: 50px;
}

.video-title {
    font-size: 3rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.video-subtitle {
    font-size: 1.3rem;
    color: rgba(255, 255, 255, 0.9);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.video-player-container {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    transform: scale(0.95);
    transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    cursor: pointer;
}

.video-player-container.playing {
    transform: scale(1);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
}

.video-wrapper {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    background: #000;
}

.video-element {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    border: none;
}

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 1;
    transition: all 0.3s ease;
}

.video-player-container.playing .video-overlay {
    opacity: 0;
    pointer-events: none;
}

.play-button {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #2563EB, #10B981);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(37, 99, 235, 0.4);
}

.play-button:hover {
    transform: scale(1.1);
    box-shadow: 0 15px 40px rgba(37, 99, 235, 0.6);
}

.overlay-text {
    color: white;
    font-size: 1.2rem;
    margin-top: 20px;
    text-align: center;
    font-weight: 500;
}

.video-controls {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 15px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.video-player-container:hover .video-controls {
    opacity: 1;
}

.control-btn {
    background: rgba(255, 255, 255, 0.9);
    border: none;
    padding: 10px 20px;
    border-radius: 25px;
    color: #2563EB;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.control-btn:hover {
    background: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.video-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 60px;
}

.feature-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.feature-icon {
    font-size: 2.5rem;
    color: #10B981;
    margin-bottom: 15px;
}

.feature-title {
    color: white;
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.feature-description {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.5;
}

/* Responsive Design */
@media (max-width: 768px) {
    .video-section {
        padding: 60px 0;
    }
    
    .video-title {
        font-size: 2.2rem;
    }
    
    .video-subtitle {
        font-size: 1.1rem;
        padding: 0 15px;
    }
    
    .video-player-container {
        margin: 0 15px;
        border-radius: 15px;
    }
    
    .play-button {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .video-features {
        grid-template-columns: 1fr;
        gap: 20px;
        margin-top: 40px;
        padding: 0 15px;
    }
    
    .feature-card {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .video-title {
        font-size: 1.8rem;
    }
    
    .video-subtitle {
        font-size: 1rem;
    }
    
    .control-btn {
        padding: 8px 15px;
        font-size: 0.9rem;
    }
}
</style>
@endpush
@push('meta')
    <title>Find & Compare the Best Schools in Pakistan | SKOOLYST</title>
    <meta name="description" content="Explore and compare the best schools in Pakistan with SKOOLYST. Search by city, curriculum, and type to find the perfect school in Karachi, Lahore, Islamabad, and beyond.">
    <meta name="keywords" content="best schools in Pakistan, Karachi schools, Lahore schools, Islamabad schools, O Level schools, Montessori schools, A Level schools, school admissions Pakistan, compare schools Pakistan">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Find Best Schools in Pakistan',
        'description' => 'Search and compare top schools across Pakistan by location, curriculum, and reviews.',
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'SKOOLYST Pakistan',
            'url' => url('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('assets/assets/hero.png')
            ],
        ],
        'mainEntity' => [
            '@type' => 'ItemList',
            'itemListElement' => $schools->map(function($school, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'url' => route('browseSchools.show', $school->uuid),
                    'name' => $school->name,
                    'image' => $school->banner_image ? asset('website/' . $school->banner_image) : asset('assets/images/default-school.jpg'),
                    'address' => $school->city,
                    'aggregateRating' => [
                        '@type' => 'AggregateRating',
                        'ratingValue' => number_format($school->reviews->avg('rating') ?? 0, 1),
                        'reviewCount' => $school->reviews->count(),
                    ],
                ];
            }),
        ],
    ], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')

<!-- ==================== HERO SECTION ==================== -->
<section class="hero-section" id="home">
    <div class="hero-content">
        <img class="hero-image" src="{{ asset('assets/assets/hero1.png') }}" alt="Hero Image">
        <div class="search-container">
            <div class="search-box">
                <input type="text" class="search-input" id="mainSearch" placeholder="Search by school name, location, or curriculum...">
                <button class="search-btn" onclick="performSearch()">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>
        <p class="hero-subheading">Discover, compare, and connect with the best educational institutions</p>
    </div>
</section>

<!-- ==================== VIDEO SHOWCASE SECTION ==================== -->
<section class="video-section" id="video-showcase">
    <div class="video-container">
        <div class="video-header">
            <h2 class="video-title">See SKOOLYST in Action</h2>
            <p class="video-subtitle">Watch how we're transforming educational discovery with our innovative platform</p>
        </div>
        
        <div class="video-player-container" id="videoPlayerContainer">
            <div class="video-wrapper">
                <video class="video-element" id="mainVideo" poster="{{ asset('assets/videos/video-poster.jpg') }}">
                    <source src="{{ asset('assets/videos/skoolyst-promo.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                
                <div class="video-overlay" id="videoOverlay">
                    <button class="play-button" id="playButton">
                        <i class="fas fa-play"></i>
                    </button>
                    <div class="overlay-text">Watch Our Platform Tour</div>
                </div>
                
                <div class="video-controls">
                    <button class="control-btn" id="replayBtn">
                        <i class="fas fa-redo"></i> Replay
                    </button>
                    <button class="control-btn" id="fullscreenBtn">
                        <i class="fas fa-expand"></i> Fullscreen
                    </button>
                </div>
            </div>
        </div>
        
        <div class="video-features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <h3 class="feature-title">For Parents & Students</h3>
                <p class="feature-description">Find the perfect school match with advanced search and comparison tools</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-school"></i>
                </div>
                <h3 class="feature-title">For Schools</h3>
                <p class="feature-description">Showcase your institution and connect with prospective families</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">Smart Analytics</h3>
                <p class="feature-description">Track engagement and make data-driven decisions for your institution</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FILTER SECTION ==================== -->
<section class="filter-section">
    <div class="container">
        <div class="filter-container">
            <div class="filter-group">
                <label class="filter-label">Location</label>
                <select class="filter-select" id="locationFilter" onchange="applyFilters()">
                    <option value="">All Locations</option>
                    @foreach($cities as $city)
                    <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">School Type</label>
                <select class="filter-select" id="typeFilter" onchange="applyFilters()">
                    <option value="">All Types</option>
                    @foreach($schoolTypes as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Curriculum</label>
                <select class="filter-select" id="curriculumFilter" onchange="applyFilters()">
                    <option value="">All Curriculums</option>
                    @foreach($curriculums as $curriculum)
                    <option value="{{ $curriculum->code }}">{{ $curriculum->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <button class="clear-filters-btn" onclick="clearFilters()">
                    <i class="fas fa-redo"></i> Clear Filters
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ==================== SCHOOL DIRECTORY SECTION ==================== -->
<section class="directory-section" id="directory">
    <div class="container">
        <h2 class="section-title">Schools</h2>
        <p class="section-subtitle">Explore educational institutions that match your needs</p>

        <div class="row" id="schoolsContainer">
            <!-- Schools will be dynamically loaded here -->
            @foreach($schools as $school)
            <div class="col-lg-4 col-md-6 school-card-col">
                <div class="school-card">
                    <div class="school-image">
                        @if($school->banner_image)
                        <img src="{{ asset('website/' . $school->banner_image) }}" alt="{{ $school->name }}" style="width: 100%; height: 200px; object-fit: cover;">
                        @else
                        <i class="fas fa-school"></i>
                        @endif
                    </div>
                    <div class="school-content">
                        <div class="school-header">
                            <div>
                                <h3 class="school-name">{{ $school->name }}</h3>
                                <div class="school-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $school->city ?? 'Location not specified' }}</span>
                                </div>
                            </div>
                            <span class="school-type-badge">{{ $school->school_type }}</span>
                        </div>
                        <div class="school-rating">
                            @php
                            $averageRating = $school->reviews->avg('rating') ?? 0;
                            $fullStars = floor($averageRating);
                            $hasHalfStar = $averageRating - $fullStars >= 0.5;
                            $emptyStars = 5 - ceil($averageRating);
                            @endphp

                            @for($i = 0; $i < $fullStars; $i++)
                                <i class="fas fa-star"></i>
                                @endfor

                                @if($hasHalfStar)
                                <i class="fas fa-star-half-alt"></i>
                                @endif

                                @for($i = 0; $i < $emptyStars; $i++)
                                    <i class="far fa-star"></i>
                                    @endfor

                                    <span style="color: #666; margin-left: 0.5rem;">{{ number_format($averageRating, 1) }}</span>
                                    <small style="color: #888; margin-left: 0.5rem;">({{ $school->reviews->count() }} reviews)</small>
                        </div>
                        <p class="school-description">
                            {{ Str::limit($school->description, 120) ?: 'No description available.' }}
                        </p>
                        <div class="school-features">
                            @if($school->curriculums->count() > 0)
                            <span class="feature-tag"><i class="fas fa-book"></i> {{ $school->curriculums->first()->name }}</span>
                            @endif
                            @foreach($school->features->take(3) as $feature)
                            <span class="feature-tag">{{ $feature->name }}</span>
                            @endforeach
                        </div>
                        <a href="{{ route('browseSchools.show', $school->uuid) }}" class="view-profile-btn">
                            <i class="fas fa-eye"></i> View Full Profile
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div id="noResults" class="no-results" style="display: none;">
            <i class="fas fa-search" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
            <p>No schools found matching your criteria. Try adjusting your filters.</p>
        </div>

        @if($schools->count() == 0)
        <div class="no-results">
            <i class="fas fa-school" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
            <p>No schools available at the moment. Please check back later.</p>
        </div>
        @endif
    </div>
</section>

<!-- ==================== HOW IT WORKS SECTION ==================== -->
{{-- <section class="how-it-works-section" id="how-it-works">
    <div class="container">
        <h2 class="section-title">How It Works</h2>
        <p class="section-subtitle">Find your ideal school in four simple steps</p>

        <div class="row mt-5">
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="step-title">Search & Filter</h3>
                    <p class="step-description">
                        Use our advanced search and filters to find schools by location, type, curriculum, and more.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <h3 class="step-title">Browse Profiles</h3>
                    <p class="step-description">
                        Explore detailed school profiles with facilities, achievements, and ratings from parents.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h3 class="step-title">Compare Options</h3>
                    <p class="step-description">
                        Compare multiple schools side-by-side to make an informed decision for your child's future.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="step-title">Connect</h3>
                    <p class="step-description">
                        Reach out directly to schools for inquiries, admission details, and campus visits.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section> --}}

<!-- ==================== TESTIMONIALS SECTION ==================== -->
<section class="testimonials-section" id="testimonials">
    <div class="container">
        <h2 class="section-title">What Parents Say</h2>
        <p class="section-subtitle">Real experiences from families who found their perfect school</p>

        <div class="row mt-5">
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <p class="testimonial-text">
                        "SKOOLYST made finding the right school for my daughter so easy! The detailed profiles and honest reviews helped us make the perfect choice."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">P</div>
                        <div class="author-info">
                            <div class="author-name">Fatima Malik</div>
                            <div class="author-role">Parent, Karachi</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <p class="testimonial-text">
                        "The comparison feature is brilliant! We could evaluate multiple schools based on our priorities and found an excellent match within days."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">R</div>
                        <div class="author-info">
                            <div class="author-name">Umer Khan</div>
                            <div class="author-role">Parent, Hyderabad</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <p class="testimonial-text">
                        "As a working parent, I appreciated how quickly I could research schools online. The platform is user-friendly and comprehensive."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">A</div>
                        <div class="author-info">
                            <div class="author-name">Ayesha Ahmed</div>
                            <div class="author-role">Parent, Karachi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CTA SECTION ==================== -->
<section class="cta-section" id="about">
    <div class="container">
        <h2 class="cta-headline">Ready to Find Your School?</h2>
        <p class="cta-subheadline">Join thousands of parents who have found their perfect educational match</p>
        <a href="{{ route('browseSchools.index') }}" class="cta-button">Start Exploring</a>
    </div>
</section>

@push('scripts')
<script src="{{ asset('assets/js/home.js') }}"></script>
<script>
// Video Player Functionality
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('mainVideo');
    const playButton = document.getElementById('playButton');
    const videoOverlay = document.getElementById('videoOverlay');
    const videoContainer = document.getElementById('videoPlayerContainer');
    const replayBtn = document.getElementById('replayBtn');
    const fullscreenBtn = document.getElementById('fullscreenBtn');

    // Play/Pause functionality
    playButton.addEventListener('click', function() {
        video.play();
        videoContainer.classList.add('playing');
        videoOverlay.style.opacity = '0';
    });

    // Replay functionality
    replayBtn.addEventListener('click', function() {
        video.currentTime = 0;
        video.play();
        videoContainer.classList.add('playing');
    });

    // Fullscreen functionality
    fullscreenBtn.addEventListener('click', function() {
        if (video.requestFullscreen) {
            video.requestFullscreen();
        } else if (video.webkitRequestFullscreen) {
            video.webkitRequestFullscreen();
        } else if (video.msRequestFullscreen) {
            video.msRequestFullscreen();
        }
    });

    // Video ended event
    video.addEventListener('ended', function() {
        videoContainer.classList.remove('playing');
        setTimeout(() => {
            videoOverlay.style.opacity = '1';
        }, 500);
    });

    // Click on video container to play/pause
    videoContainer.addEventListener('click', function(e) {
        if (e.target === videoContainer || e.target.classList.contains('video-wrapper')) {
            if (video.paused) {
                video.play();
                videoContainer.classList.add('playing');
                videoOverlay.style.opacity = '0';
            } else {
                video.pause();
                videoContainer.classList.remove('playing');
                videoOverlay.style.opacity = '1';
            }
        }
    });

    // Keyboard controls
    document.addEventListener('keydown', function(e) {
        if (e.code === 'Space' && document.activeElement !== document.body) {
            e.preventDefault();
            if (video.paused) {
                video.play();
                videoContainer.classList.add('playing');
                videoOverlay.style.opacity = '0';
            } else {
                video.pause();
                videoContainer.classList.remove('playing');
                videoOverlay.style.opacity = '1';
            }
        }
    });
});
</script>
@endpush

@endsection