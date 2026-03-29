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

/* New Announcement Badge */
.new-announcement-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 2px 10px rgba(255, 107, 107, 0.4);
    z-index: 10;
    animation: pulse 2s infinite;
}

.badge-pulse {
    width: 8px;
    height: 8px;
    background: white;
    border-radius: 50%;
    animation: blink 1.5s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 2px 10px rgba(255, 107, 107, 0.4);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.6);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 2px 10px rgba(255, 107, 107, 0.4);
    }
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

/* School Image Container */
.school-image {
    position: relative;
    overflow: hidden;
}

/* Recent Announcements Section */


.visitor-count {
    position: absolute;
    bottom: 10px;
    left: 20px;
    margin: 0;
    font-size: 0.85rem;
    color: #6c757d;
}

.visitor-count i {
    margin-right: 5px;
    color: #0f4077;
    font-size: 0.85rem;
}
</style>
@endpush
@push('meta')
    <title>Find & Compare the Best Schools in Pakistan | SKOOLYST</title>
    <meta name="description" content="Discover, explore, and compare schools in Pakistan with SKOOLYST. Search by country, city, curriculum, and type to find the perfect school for your child anywhere in the world.">
    <meta name="keywords" content="best schools in Pakistan, international schools, global school search, O Level schools, Montessori schools, A Level schools, IB schools, school admissions, compare schools">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph for social sharing -->
    <meta property="og:title" content="Find & Compare the Best Schools in Pakistan | SKOOLYST">
    <meta property="og:description" content="Discover and compare schools around the world by location, curriculum, and type with SKOOLYST.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('assets/assets/hero1.png') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Find & Compare the Best Schools in Pakistan | SKOOLYST">
    <meta name="twitter:description" content="Discover and compare schools around the world by location, curriculum, and type with SKOOLYST.">
    <meta name="twitter:image" content="{{ asset('assets/assets/hero1.png') }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    @php
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'Find Best Schools in Pakistan',
            'description' => 'Search and compare top schools in Pakistan by location, curriculum, and reviews.',
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'SKOOLYST',
                'url' => url('/'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('assets/assets/hero.png')
                ],
            ],
            'mainEntity' => [
                '@type' => 'ItemList',
                'itemListElement' => []
            ],
        ];
        
        // Add schools to the itemListElement if they exist
        if(isset($schools) && count($schools) > 0) {
            foreach ($schools as $index => $school) {
                $jsonLd['mainEntity']['itemListElement'][] = [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'url' => route('browseSchools.show', $school['id'] ?? $school->id),
                    'name' => $school['name'] ?? $school->name,
                    'image' => isset($school['banner_image']) ? asset('website/'.$school['banner_image']) : (isset($school->banner_image) ? asset('website/'.$school->banner_image) : asset('assets/images/default-school.jpg')),
                    'address' => $school['location'] ?? ($school->city ?? 'Location not specified'),
                    'aggregateRating' => [
                        '@type' => 'AggregateRating',
                        'ratingValue' => number_format($school['rating'] ?? ($school->reviews->avg('rating') ?? 0), 1),
                        'reviewCount' => $school['review_count'] ?? ($school->reviews->count() ?? 0),
                    ],
                ];
            }
        }
    @endphp
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')

<!-- ==================== HERO SECTION (compact) ==================== -->
<section class="hero-section" id="home">
    <div class="hero-content">
        <img class="hero-image" src="{{ asset('assets/assets/hero1.png') }}" alt="Hero Image">
        <p class="hero-subheading">Discover, compare, and connect with the best educational institutions</p>
    </div>
</section>

<!-- ==================== SEARCH BAR (below header) ==================== -->
<section class="search-section">
    <div class="container">
        <div class="search-container">
            <div class="search-box">
                <input type="text" class="search-input" id="mainSearch" placeholder="Search by school name, location, or curriculum...">
                <button class="search-btn" type="button" onclick="performSearch()">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ==================== VIDEO SHOWCASE SECTION ==================== -->
{{-- <section class="video-section" id="video-showcase">
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
</section> --}}

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
                    <option value="{{ $type }}">{{ $type === 'Separate' ? 'Girs And Boys Seprate Campuses' : $type }}</option>
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
                    <i class="fas fa-redo"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ==================== SCHOOL DIRECTORY SECTION ==================== -->
<section class="directory-section" id="directory">
    <div class="container">
        <h2 class="section-title"> SCHOOLS </h2>
        <p class="section-subtitle">Explore Educational Institutions Around The Globe That Match Your Needs</p>

        <div class="row" id="schoolsContainer">
            <!-- Schools will be dynamically loaded here -->
            @foreach($schools as $school)
                <div class="col-lg-4 col-md-6 school-card-col">
                    <article class="school-card" itemscope itemtype="https://schema.org/School">
                        <div class="school-image">
                            @if(isset($school['banner_image']) && $school['banner_image'])
                                <img src="{{ $school['banner_image'] }}" alt="{{ $school['name'] }} school campus image" itemprop="image" style="width: 100%; height: 200px; object-fit: cover;">
                            @elseif(isset($school->banner_image) && $school->banner_image)
                                <img src="{{ asset('website/' . $school->banner_image) }}" alt="{{ $school->name }} school campus image" itemprop="image" style="width: 100%; height: 200px; object-fit: cover;">
                            @else
                                <i class="fas fa-school" aria-hidden="true"></i>
                            @endif
                            <!-- New Announcement Badge - Commented out as it may not be available in array -->
                            @if(isset($school->hasNewAnnouncements) && $school->hasNewAnnouncements())
                                <div class="new-announcement-badge">
                                    <span class="badge-pulse"></span>
                                    <a href="{{ route('browseSchools.show', $school->uuid ?? $school['id']) }}" class="announcement-link">
                                        <i class="fas fa-bullhorn"></i>
                                        New Updates
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="school-content">
                            <div class="school-header">
                                <div>
                                    <h3 class="school-name" itemprop="name">{{ $school['name'] ?? $school->name }}</h3>
                                    <div class="school-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span itemprop="addressLocality">{{ $school['location'] ?? ($school->city ?? 'Location not specified') }}</span>
                                    </div>
                                </div>
                                <span class="school-type-badge">{{ $school['type'] ?? $school->school_type }}</span>
                            </div>
                            <div class="school-rating" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                                @php
                                    $averageRating = $school['rating'] ?? ($school->reviews->avg('rating') ?? 0);
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

                                <span>{{ number_format($averageRating, 1) }}</span>
                                <small>({{ $school['review_count'] ?? ($school->reviews->count() ?? 0) }} reviews)</small>
                                <meta itemprop="ratingValue" content="{{ number_format($averageRating, 1) }}">
                                <meta itemprop="reviewCount" content="{{ $school['review_count'] ?? ($school->reviews->count() ?? 0) }}">
                            </div>
                            <p class="school-description" itemprop="description">
                                {{ Str::limit($school['description'] ?? ($school->description ?? 'No description available'), 160) }}
                            </p>
                            <div class="school-features">
                                @if(isset($school['curriculum']) && $school['curriculum'])
                                    <span class="feature-tag"><i class="fas fa-book"></i> {{ $school['curriculum'] }}</span>
                                @elseif(isset($school->curriculums) && $school->curriculums->count() > 0)
                                    <span class="feature-tag"><i class="fas fa-book"></i> {{ $school->curriculums->first()->name }}</span>
                                @endif
                                
                                @if(isset($school['features']) && is_array($school['features']))
                                    @foreach(array_slice($school['features'], 0, 3) as $feature)
                                        <span class="feature-tag">{{ $feature }}</span>
                                    @endforeach
                                @elseif(isset($school->features))
                                    @foreach($school->features->take(3) as $feature)
                                        <span class="feature-tag">{{ $feature->name }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <a href="{{ route('browseSchools.show', $school['uuid']) }}" class="view-profile-btn" itemprop="url">
                                <i class="fas fa-eye"></i> View Full Profile
                            </a>
                            <p class="visitor-count">
                                @if(isset($school['visitor_count']) && $school['visitor_count'] > 0)
                                    <i class="fas fa-eye"></i> {{ $school['visitor_count'] }}
                                @endif
                            </p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div id="noResults" class="no-results" style="display: none;">
            <i class="fas fa-search fa-3x site-muted mb-3 d-block"></i>
            <p>No schools found matching your criteria. Try adjusting your filters.</p>
        </div>

        @if($schools->count() == 0)
        <div class="no-results">
            <i class="fas fa-school fa-3x site-muted mb-3 d-block"></i>
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
        <h2 class="section-title"> WHAT PARENTS SAY </h2>
        <p class="section-subtitle">Real Experiences From Families Who Found Their Perfect School</p>

        @if($testimonials->count() > 2)
            <div class="row mt-5">
                @foreach($testimonials as $testimonial)
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-rating mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="testimonial-text">
                            "{{ $testimonial->message }}"
                        </p>
                        <div class="testimonial-author">
                            @if($testimonial->avatar)
                                <img src="{{ asset($testimonial->avatar) }}" alt="{{ $testimonial->author_name }}" class="author-avatar">
                            @else
                                <div class="author-avatar">{{ $testimonial->initials ?? substr($testimonial->author_name, 0, 1) }}</div>
                            @endif
                            <div class="author-info">
                                <div class="author-name">{{ $testimonial->author_name }}</div>
                                <div class="author-role">{{ $testimonial->author_role ?? '' }}, {{ $testimonial->author_location ?? '' }}</div>
                                <div class="author-experience">
                                    <small class="text-muted">{{ $testimonial->experience_rating ?? '' }} {{ isset($testimonial->created_at) ? '• ' . $testimonial->created_at->format('M Y') : '' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Fallback to hardcoded testimonials if none in database -->
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
        @endif
        
        <!-- View All Testimonials Link -->
        <div class="text-center mt-4">
            <a href="{{ route('testimonials.index') }}" class="btn-site btn-site-outline">
                View All Testimonials <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- ==================== CTA SECTION ==================== -->
<section class="cta-section" id="about">
    <div class="container">
        <h2 class="cta-headline">Ready to Find Your School?</h2>
        <p class="cta-subheadline">Join Thousands Of Parents Who Have Found Their Perfect Educational Match</p>
        <a href="{{ route('browseSchools.index') }}" class="cta-button">Start Exploring</a>
    </div>
</section>

@push('testemonial')
                <!-- Testimonial Form Section - Compact two-column layout -->
            <div class="testimonial-form-section">
                <div class="testimonial-form-inner row g-4 align-items-start">
                    <!-- Left: About Skoolyst + request to fill form -->
                    <div class="testimonial-form-info col-lg-6 col-md-12">
                        <h3 class="form-title">Share Your Experience</h3>
                        <p class="form-subtitle">Help other parents by sharing your feedback about our platform.</p>
                        <div class="form-info-content">
                            <p><strong>About SKOOLYST</strong></p>
                            <p>SKOOLYST helps parents find and compare schools with detailed profiles, reviews, and ratings—all in one place.</p>
                            <p><strong>Why share your experience?</strong></p>
                            <ul>
                                <li>Your feedback helps other families make informed decisions</li>
                                <li>Schools value real parent experiences to improve</li>
                                <li>It only takes a minute and makes a difference</li>
                            </ul>
                            <p class="form-cta-text">Please take a moment to fill the form and tell us how SKOOLYST worked for you.</p>
                        </div>
                    </div>
                    <!-- Right: Form fields -->
                    <div class="testimonial-form-fields col-lg-6 col-md-12">
                        <form id="testimonialForm" class="testimonial-form">
                            @csrf

                            <!-- Row 1: Name | Email -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="author_name">Your Name <span style="color: red">*</span></label>
                                        <input
                                            type="text"
                                            id="author_name"
                                            name="author_name"
                                            class="form-control"
                                            placeholder="Enter your name"
                                            required
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="author_email">Email</label>
                                        <input
                                            type="email"
                                            id="author_email"
                                            name="author_email"
                                            class="form-control"
                                            placeholder="Enter your email (optional)"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Row 2: Location | Overall Experience -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="author_location">Your Location</label>
                                        <input
                                            type="text"
                                            id="author_location"
                                            name="author_location"
                                            class="form-control"
                                            placeholder="e.g., Karachi, Lahore"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="experience_rating">Overall Experience <span style="color: red">*</span></label>
                                        <select
                                            id="experience_rating"
                                            name="experience_rating"
                                            class="form-control"
                                            required
                                        >
                                            <option value="">Select your experience</option>
                                            <option value="excellent">Excellent</option>
                                            <option value="good">Good</option>
                                            <option value="average">Average</option>
                                            <option value="poor">Poor</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 3: What did you like most -->
                            <div class="form-group">
                                <label>What did you like most? (Select all that apply)</label>
                                <div class="platform-features">
                                    <div class="form-check">
                                        <input type="checkbox" name="platform_features[]" value="School Search" id="feature1">
                                        <label for="feature1">Find School</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="platform_features[]" value="School Comparison" id="feature2">
                                        <label for="feature2">Comparison</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="platform_features[]" value="Detailed Profiles" id="feature3">
                                        <label for="feature3">School Profile</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="platform_features[]" value="Reviews & Ratings" id="feature4">
                                        <label for="feature4">Reviews</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="platform_features[]" value="User Interface" id="feature5">
                                        <label for="feature5">App easy to use</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 4: Rate your experience | Description -->
                            <div class="row g-3 align-items-start">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Rate Your Experience <span style="color: red">*</span></label>
                                        <div class="star-rating">
                                            <input type="radio" id="star5" name="rating" value="5" required>
                                            <label for="star5" title="Excellent">☆</label>
                                            <input type="radio" id="star4" name="rating" value="4">
                                            <label for="star4" title="Good">☆</label>
                                            <input type="radio" id="star3" name="rating" value="3">
                                            <label for="star3" title="Average">☆</label>
                                            <input type="radio" id="star2" name="rating" value="2">
                                            <label for="star2" title="Poor">☆</label>
                                            <input type="radio" id="star1" name="rating" value="1">
                                            <label for="star1" title="Very Poor">☆</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="message">Description <span style="color: red">*</span></label>
                                        <textarea
                                            id="message"
                                            name="message"
                                            class="form-control"
                                            rows="3"
                                            placeholder="Share your experience with SKOOLYST platform..."
                                            minlength="20"
                                            maxlength="1000"
                                            required
                                        ></textarea>
                                        <small class="text-muted">Minimum 20 characters</small>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-submit">
                                <span class="submit-text">Submit Feedback</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>

                            <div class="alert mt-3 d-none" id="formMessage"></div>
                        </form>
                    </div>
                </div>
            </div>

@endpush

@push('scripts')
<script src="{{ asset('assets/js/home.js') }}"></script>
<script>
// Video Player Functionality
// document.addEventListener('DOMContentLoaded', function() {
//     const video = document.getElementById('mainVideo');
//     const playButton = document.getElementById('playButton');
//     const videoOverlay = document.getElementById('videoOverlay');
//     const videoContainer = document.getElementById('videoPlayerContainer');
//     const replayBtn = document.getElementById('replayBtn');
//     const fullscreenBtn = document.getElementById('fullscreenBtn');

//     // Play/Pause functionality
//     playButton.addEventListener('click', function() {
//         video.play();
//         videoContainer.classList.add('playing');
//         videoOverlay.style.opacity = '0';
//     });

//     // Replay functionality
//     replayBtn.addEventListener('click', function() {
//         video.currentTime = 0;
//         video.play();
//         videoContainer.classList.add('playing');
//     });

//     // Fullscreen functionality
//     fullscreenBtn.addEventListener('click', function() {
//         if (video.requestFullscreen) {
//             video.requestFullscreen();
//         } else if (video.webkitRequestFullscreen) {
//             video.webkitRequestFullscreen();
//         } else if (video.msRequestFullscreen) {
//             video.msRequestFullscreen();
//         }
//     });

//     // Video ended event
//     video.addEventListener('ended', function() {
//         videoContainer.classList.remove('playing');
//         setTimeout(() => {
//             videoOverlay.style.opacity = '1';
//         }, 500);
//     });

//     // Click on video container to play/pause
//     videoContainer.addEventListener('click', function(e) {
//         if (e.target === videoContainer || e.target.classList.contains('video-wrapper')) {
//             if (video.paused) {
//                 video.play();
//                 videoContainer.classList.add('playing');
//                 videoOverlay.style.opacity = '0';
//             } else {
//                 video.pause();
//                 videoContainer.classList.remove('playing');
//                 videoOverlay.style.opacity = '1';
//             }
//         }
//     });

//     // Keyboard controls
//     document.addEventListener('keydown', function(e) {
//         if (e.code === 'Space' && document.activeElement !== document.body) {
//             e.preventDefault();
//             if (video.paused) {
//                 video.play();
//                 videoContainer.classList.add('playing');
//                 videoOverlay.style.opacity = '0';
//             } else {
//                 video.pause();
//                 videoContainer.classList.remove('playing');
//                 videoOverlay.style.opacity = '1';
//             }
//         }
//     });
// });
</script>
<script>
    // Add this to your main JS file or create a new one
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('testimonialForm');
        const submitBtn = form.querySelector('.btn-submit');
        const submitText = form.querySelector('.submit-text');
        const spinner = form.querySelector('.spinner-border');
        const formMessage = document.getElementById('formMessage');
            
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Disable submit button and show spinner
                submitBtn.disabled = true;
                submitText.textContent = 'Submitting...';
                spinner.classList.remove('d-none');
                
                // Clear previous messages
                formMessage.classList.add('d-none');
                formMessage.textContent = '';
                
                try {
                    const response = await fetch('/testimonials', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: new FormData(form)
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        // Success
                        formMessage.classList.remove('d-none', 'alert-danger');
                        formMessage.classList.add('alert-success');
                        formMessage.textContent = data.message;
                        
                        // Reset form
                        form.reset();
                        
                        // Reset star rating
                        const starInputs = form.querySelectorAll('input[name="rating"]');
                        starInputs.forEach(input => input.checked = false);
                        
                        // Scroll to message
                        formMessage.scrollIntoView({ behavior: 'smooth' });
                        
                        // Hide message after 5 seconds
                        setTimeout(() => {
                            formMessage.classList.add('d-none');
                        }, 5000);
                    } else {
                        // Show validation errors
                        formMessage.classList.remove('d-none', 'alert-success');
                        formMessage.classList.add('alert-danger');
                        
                        if (data.errors) {
                            let errorHtml = '<ul class="mb-0">';
                            for (const field in data.errors) {
                                errorHtml += `<li>${data.errors[field][0]}</li>`;
                            }
                            errorHtml += '</ul>';
                            formMessage.innerHTML = errorHtml;
                        } else {
                            formMessage.textContent = data.message || 'An error occurred. Please try again.';
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    formMessage.classList.remove('d-none', 'alert-success');
                    formMessage.classList.add('alert-danger');
                    formMessage.textContent = 'An error occurred. Please try again.';
                } finally {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitText.textContent = 'Submit Feedback';
                    spinner.classList.add('d-none');
                }
            });
        }
    });
</script>
@endpush

@endsection