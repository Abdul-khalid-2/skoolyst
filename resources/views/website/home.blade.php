@extends('website.layout.app')

@push('styles')

<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== HERO SECTION ==================== -->
<section class="hero-section" id="home">
    <div class="hero-content">
        <img class="hero-subheading" src="{{ asset('assets/assets/hero1.png') }}" alt="hero1.png">
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

<!-- ==================== FILTER SECTION ==================== -->
<section class="filter-section">
    <div class="container">
        <div class="filter-container">
            <div class="filter-group">
                <label class="filter-label">Location</label>
                <select class="filter-select" id="locationFilter" onchange="applyFilters()">
                    <option value="">All Locations</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Bangalore">Bangalore</option>
                    <option value="Pune">Pune</option>
                    <option value="Chennai">Chennai</option>
                    <option value="Hyderabad">Hyderabad</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">School Type</label>
                <select class="filter-select" id="typeFilter" onchange="applyFilters()">
                    <option value="">All Types</option>
                    <option value="Public">Public</option>
                    <option value="Private">Private</option>
                    <option value="International">International</option>
                    <option value="Charter">Charter</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Curriculum</label>
                <select class="filter-select" id="curriculumFilter" onchange="applyFilters()">
                    <option value="">All Curriculums</option>
                    <option value="CBSE">CBSE</option>
                    <option value="ICSE">ICSE</option>
                    <option value="IB">IB (International Baccalaureate)</option>
                    <option value="IGCSE">IGCSE</option>
                    <option value="State Board">State Board</option>
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
        <h2 class="section-title">Browse Schools</h2>
        <p class="section-subtitle">Explore educational institutions that match your needs</p>

        <div class="row" id="schoolsContainer">
            <!-- Schools will be dynamically loaded here -->
        </div>

        <div id="noResults" class="no-results" style="display: none;">
            <i class="fas fa-search" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
            <p>No schools found matching your criteria. Try adjusting your filters.</p>
        </div>
    </div>
</section>

<!-- ==================== HOW IT WORKS SECTION ==================== -->
<section class="how-it-works-section" id="how-it-works">
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
</section>

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
                            <div class="author-name">Priya Sharma</div>
                            <div class="author-role">Parent, Mumbai</div>
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
                            <div class="author-name">Rajesh Kumar</div>
                            <div class="author-role">Parent, Delhi</div>
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
                            <div class="author-name">Anjali Desai</div>
                            <div class="author-role">Parent, Bangalore</div>
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
        <a href="#directory" class="cta-button">Start Exploring</a>
    </div>
</section>

@push('scripts')

<script src="{{ asset('assets/js/home.js') }}"></script>
@endpush

@endsection