@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/browse_schools.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== PAGE HEADER ==================== -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-title">Browse All Schools</h1>
                <p class="page-subtitle">Discover and compare educational institutions that match your needs</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="results-count">
                    Showing {{ $schools->firstItem() ?? 0 }}-{{ $schools->lastItem() ?? 0 }} of {{ $schools->total() }} schools
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FILTER SECTION ==================== -->
<section class="filter-section">
    <div class="container">
        <div class="filter-container">
            <div class="filter-group">
                <label class="filter-label">Search</label>
                <input type="text" class="filter-search" id="searchInput"
                    placeholder="Search by name, location, or curriculum..." value="{{ request('search', '') }}">
            </div>

            <div class="filter-group">
                <label class="filter-label">Location</label>
                <select class="filter-select" id="locationFilter">
                    <option value="">All Locations</option>
                    @foreach($cities as $city)
                    <option value="{{ $city }}" {{ request('location') == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">School Type</label>
                <select class="filter-select" id="typeFilter">
                    <option value="">All Types</option>
                    @foreach($schoolTypes as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Curriculum</label>
                <select class="filter-select" id="curriculumFilter">
                    <option value="">All Curriculums</option>
                    @foreach($curriculums as $curriculum)
                    <option value="{{ $curriculum->code }}" {{ request('curriculum') == $curriculum->code ? 'selected' : '' }}>{{ $curriculum->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <button class="clear-filters-btn" onclick="clearFilters()">
                    <i class="fas fa-redo"></i> Clear All
                </button>
            </div>
        </div>
    </div>
</section>
<!-- ==================== SCHOOLS GRID SECTION ==================== -->
<section class="schools-grid-section">
    <div class="container">
        <div class="row" id="schoolsContainer">
            @foreach($schools as $school)
            <div class="col-lg-4 col-md-6 school-card-col">
                <div class="school-card">
                    <div class="school-image">
                        @if($school->banner_image)
                        <img src="{{ asset('website/' . $school->banner_image) }}" alt="{{ $school->name }}">
                        @else
                        <i class="fas fa-school"></i>
                        @endif
                        @if($school->hasNewAnnouncements())
                            <div class="new-announcement-badge">
                                <span class="badge-pulse"></span>
                                <a href="{{ route('browseSchools.show', $school->uuid) }}#announcements" class="announcement-link">
                                    <i class="fas fa-bullhorn"></i>
                                    New Updates
                                </a>
                            </div>
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

        <!-- No Results -->
        @if($schools->count() == 0)
        <div class="no-results">
            <i class="fas fa-search"></i>
            <h4>No schools found</h4>
            <p>Try adjusting your search criteria or <a href="{{ route('browseSchools.index') }}" style="color: #4361ee; text-decoration: none;">browse all schools</a>.</p>
        </div>
        @endif

        <!-- Pagination -->
        @if($schools->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <nav aria-label="School pagination">
                    <ul class="pagination justify-content-center">
                        {{-- Previous Page Link --}}
                        @if ($schools->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $schools->previousPageUrl() }}" rel="prev">Previous</a>
                        </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($schools->getUrlRange(1, $schools->lastPage()) as $page => $url)
                        @if ($page == $schools->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                        @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($schools->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $schools->nextPageUrl() }}" rel="next">Next</a>
                        </li>
                        @else
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
// ==================== FILTER FUNCTIONS ==================== //
let filterTimeout;

function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const location = document.getElementById('locationFilter').value;
    const type = document.getElementById('typeFilter').value;
    const curriculum = document.getElementById('curriculumFilter').value;

    // Build query parameters
    const params = new URLSearchParams();
    
    if (search) params.append('search', search);
    if (location) params.append('location', location);
    if (type) params.append('type', type);
    if (curriculum) params.append('curriculum', curriculum);

    // Redirect to filtered page
    window.location.href = '{{ route("browseSchools.index") }}?' + params.toString();
}

function clearFilters() {
    window.location.href = '{{ route("browseSchools.index") }}';
}

// ==================== EVENT LISTENERS ==================== //
document.addEventListener('DOMContentLoaded', function() {
    // Search input with debounce
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(applyFilters, 800);
        });
    }

    // Filter select changes
    const locationFilter = document.getElementById('locationFilter');
    const typeFilter = document.getElementById('typeFilter');
    const curriculumFilter = document.getElementById('curriculumFilter');

    if (locationFilter) {
        locationFilter.addEventListener('change', applyFilters);
    }
    if (typeFilter) {
        typeFilter.addEventListener('change', applyFilters);
    }
    if (curriculumFilter) {
        curriculumFilter.addEventListener('change', applyFilters);
    }

    // Add animation to cards on scroll
    observeElements();
});

// ==================== SCROLL ANIMATIONS ==================== //
function observeElements() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Observe school cards
    document.querySelectorAll('.school-card').forEach(function(card) {
        observer.observe(card);
    });
}
</script>
@endpush

@endsection