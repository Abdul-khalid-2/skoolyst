@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">

<style>
/* ==================== PAGE HEADER ==================== */
.page-header {
    background: linear-gradient(135deg, #4361ee, #38b000, #ff9e00);
    color: white;
    padding: 60px 0 40px;
    margin-bottom: 0;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 10%;
    left: 5%;
    width: 100px;
    height: 100px;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-opacity='0.1' stroke-width='2'%3E%3Cpath d='M4 19.5A2.5 2.5 0 0 1 6.5 17H20'/%3E%3Cpath d='M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z'/%3E%3C/svg%3E") no-repeat center;
    background-size: contain;
    animation: float 6s ease-in-out infinite;
}

.page-header::after {
    content: '';
    position: absolute;
    bottom: 15%;
    right: 8%;
    width: 120px;
    height: 120px;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-opacity='0.1' stroke-width='2'%3E%3Cpath d='M22 10v6M2 10l10-5 10 5-10 5z'/%3E%3Cpath d='M6 12v5c3 3 9 3 12 0v-5'/%3E%3C/svg%3E") no-repeat center;
    background-size: contain;
    animation: float 8s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
}

.results-count {
    background: rgba(255, 255, 255, 0.2);
    padding: 12px 24px;
    border-radius: 25px;
    font-weight: 600;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

/* ==================== FILTER SECTION ==================== */
.filter-section {
    background: #f8f9fa;
    padding: 2.5rem 0;
    border-bottom: 1px solid #e0e0e0;
}

.filter-container {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    align-items: end;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: block;
    font-size: 0.9rem;
    color: #555;
}

.filter-search,
.filter-select {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    font-family: inherit;
}

.filter-search:focus,
.filter-select:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.clear-filters-btn {
    padding: 0.8rem 1.8rem;
    background: #ff9e00;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: inherit;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}

.clear-filters-btn:hover {
    background: #e68a00;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 158, 0, 0.3);
}

/* ==================== SCHOOLS GRID SECTION ==================== */
.schools-grid-section {
    padding: 3rem 0 4rem;
    background: white;
}

.school-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.4s ease;
    height: 100%;
    margin-bottom: 2rem;
    border: 1px solid #f0f0f0;
    opacity: 0;
    transform: translateY(30px);
}

.school-card.visible {
    opacity: 1;
    transform: translateY(0);
}

.school-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    border-color: #4361ee;
}

.school-image {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, #4361ee, #38b000);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
    position: relative;
    overflow: hidden;
}

.school-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.school-card:hover .school-image img {
    transform: scale(1.05);
}

.school-content {
    padding: 1.75rem;
}

.school-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    gap: 1rem;
}

.school-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0.4rem;
    line-height: 1.3;
    flex: 1;
}

.school-type-badge {
    display: inline-block;
    padding: 0.4rem 1rem;
    background: linear-gradient(135deg, #4361ee, #38b000);
    color: white;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
    flex-shrink: 0;
}

.school-location {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.school-rating {
    color: #ff9e00;
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.3rem;
}

.school-description {
    font-size: 0.95rem;
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.school-features {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.feature-tag {
    padding: 0.4rem 0.8rem;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    font-size: 0.8rem;
    color: #555;
    transition: all 0.2s ease;
}

.feature-tag:hover {
    background: #e9ecef;
    transform: translateY(-1px);
}

.view-profile-btn {
    display: block;
    width: 100%;
    padding: 0.9rem;
    background: linear-gradient(135deg, #4361ee, #38b000);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
    font-family: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.view-profile-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
    color: white;
}

/* ==================== NO RESULTS & PAGINATION ==================== */
.no-results {
    text-align: center;
    padding: 4rem 2rem;
    color: #666;
    font-size: 1.1rem;
    background: #f8f9fa;
    border-radius: 16px;
    margin: 2rem 0;
}

.no-results i {
    font-size: 3rem;
    color: #ccc;
    margin-bottom: 1.5rem;
    display: block;
}

.pagination {
    margin-top: 3rem;
}

.page-link {
    padding: 0.75rem 1.25rem;
    border: 1px solid #e0e0e0;
    color: #4361ee;
    font-weight: 500;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: #4361ee;
    color: white;
    border-color: #4361ee;
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #4361ee, #38b000);
    border-color: #4361ee;
    color: white;
}

.page-item.disabled .page-link {
    color: #6c757d;
    background: #f8f9fa;
    border-color: #e0e0e0;
}

/* ==================== LOADING STATES ==================== */
.loading-spinner {
    text-align: center;
    padding: 3rem;
    color: #666;
}

.fa-spinner {
    color: #4361ee;
    margin-bottom: 1rem;
}

/* ==================== RESPONSIVE DESIGN ==================== */
@media (max-width: 1200px) {
    .filter-container {
        gap: 1rem;
    }
    
    .filter-group {
        min-width: 180px;
    }
}

@media (max-width: 768px) {
    .page-header {
        padding: 40px 0 30px;
        text-align: center;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .results-count {
        margin-top: 1rem;
        display: inline-block;
    }
    
    .filter-section {
        padding: 2rem 0;
    }
    
    .filter-container {
        flex-direction: column;
        gap: 1rem;
    }
    
    .filter-group {
        width: 100%;
        min-width: auto;
    }
    
    .clear-filters-btn {
        margin-top: 0.5rem;
        width: 100%;
        justify-content: center;
    }
    
    .schools-grid-section {
        padding: 2rem 0 3rem;
    }
    
    .school-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .school-type-badge {
        align-self: flex-start;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 1.8rem;
    }
    
    .page-subtitle {
        font-size: 1rem;
    }
    
    .school-content {
        padding: 1.25rem;
    }
    
    .school-name {
        font-size: 1.2rem;
    }
    
    .pagination {
        flex-wrap: wrap;
    }
    
    .page-link {
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
    }
}

/* ==================== ANIMATION DELAYS ==================== */
.school-card-col:nth-child(1) .school-card { transition-delay: 0.1s; }
.school-card-col:nth-child(2) .school-card { transition-delay: 0.2s; }
.school-card-col:nth-child(3) .school-card { transition-delay: 0.3s; }
.school-card-col:nth-child(4) .school-card { transition-delay: 0.1s; }
.school-card-col:nth-child(5) .school-card { transition-delay: 0.2s; }
.school-card-col:nth-child(6) .school-card { transition-delay: 0.3s; }
.school-card-col:nth-child(7) .school-card { transition-delay: 0.1s; }
.school-card-col:nth-child(8) .school-card { transition-delay: 0.2s; }
.school-card-col:nth-child(9) .school-card { transition-delay: 0.3s; }

/* ==================== ACCESSIBILITY ==================== */
@media (prefers-reduced-motion: reduce) {
    .school-card,
    .page-header::before,
    .page-header::after,
    .view-profile-btn,
    .clear-filters-btn {
        animation: none;
        transition: none;
    }
    
    .school-card {
        opacity: 1;
        transform: none;
    }
}

/* ==================== DARK MODE SUPPORT ==================== */
@media (prefers-color-scheme: dark) {
    .filter-section {
        background: #1a1a1a;
        border-bottom-color: #333;
    }
    
    .filter-search,
    .filter-select {
        background: #2d2d2d;
        border-color: #444;
        color: #fff;
    }
    
    .school-card {
        background: #2d2d2d;
        border-color: #444;
    }
    
    .school-name {
        color: #fff;
    }
    
    .school-description {
        color: #ccc;
    }
    
    .feature-tag {
        background: #3d3d3d;
        border-color: #555;
        color: #ccc;
    }
}
</style>
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
                        <img src="{{ asset('website/' . $school->banner_image) }}" alt="{{ $school->name }}"
                            onerror="this.style.display='none';">
                        @endif
                        <i class="fas fa-school"></i>
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