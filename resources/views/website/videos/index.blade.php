@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== VIDEOS HEADER SECTION ==================== */
    .videos-header {
        background: linear-gradient(135deg, #4361ee 0%, #38b000 50%, #ff9e00 100%);
        color: white;
        padding: 100px 0 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .videos-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        animation: float 20s linear infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(0px) translateX(0px);
        }
        100% {
            transform: translateY(-100px) translateX(-100px);
        }
    }

    .videos-hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    }

    .videos-hero-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ==================== VIDEO STATS ==================== */
    .video-stats {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-top: -40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 10;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #4361ee;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 5px;
    }

    /* ==================== VIDEO CONTENT STYLES ==================== */
    .videos-search-form .form-control {
        border: none;
        border-radius: 50px;
        padding: 15px 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .videos-search-form .btn {
        border: none;
        border-radius: 50px;
        padding: 15px 30px;
        margin-left: 10px;
    }

    .video-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        height: 100%;
    }

    .video-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .video-card .card-img-top {
        height: 200px;
        object-fit: cover;
        position: relative;
    }

    .video-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .video-play-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .video-card:hover .video-play-overlay {
        opacity: 1;
    }

    .play-icon {
        width: 60px;
        height: 60px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #4361ee;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .video-card .card-title a {
        color: #1a1a1a;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .video-card .card-title a:hover {
        color: #4361ee !important;
    }

    .video-category-badge {
        background: linear-gradient(135deg, #4361ee, #38b000);
        border: none;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* ==================== FILTERS STYLES ==================== */
    .filters-container {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .filter-group {
        margin-bottom: 15px;
    }

    .filter-group label {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 8px;
        color: #333;
    }

    .filter-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 10px 15px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    }

    .quick-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .quick-filter-btn {
        padding: 8px 15px;
        border-radius: 25px;
        border: 2px solid #e9ecef;
        background: white;
        color: #666;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .quick-filter-btn:hover,
    .quick-filter-btn.active {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    /* ==================== SIDEBAR STYLES ==================== */
    .sidebar-widget {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .sidebar-widget .card-header {
        background: linear-gradient(135deg, #4361ee, #38b000);
        color: white;
        border: none;
        font-weight: 700;
        padding: 15px 20px;
    }

    .sidebar-widget .list-group-item {
        border: none;
        padding: 12px 0;
        background: transparent;
        border-bottom: 1px solid #f0f0f0;
    }

    .sidebar-widget .list-group-item:last-child {
        border-bottom: none;
    }

    .sidebar-widget .list-group-item:hover {
        background: transparent;
        color: #4361ee;
    }

    /* ==================== FEATURED VIDEOS ==================== */
    .featured-video-item {
        display: flex;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .featured-video-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .featured-video-thumb {
        width: 80px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .featured-video-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .featured-video-content {
        flex-grow: 1;
    }

    .featured-video-title {
        font-size: 0.9rem;
        margin-bottom: 5px;
        line-height: 1.3;
    }

    .featured-video-title a {
        color: #333;
        text-decoration: none;
    }

    .featured-video-title a:hover {
        color: #4361ee;
    }

    .featured-video-meta {
        font-size: 0.8rem;
        color: #666;
    }

    /* ==================== PAGINATION STYLES ==================== */
    .pagination .page-link {
        border-radius: 10px;
        margin: 0 3px;
        border: none;
        color: #4361ee;
        font-weight: 600;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #4361ee, #38b000);
        border-color: #4361ee;
    }

    /* ==================== EMPTY STATE ==================== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }

    /* ==================== RESPONSIVE DESIGN ==================== */
    @media (max-width: 768px) {
        .videos-hero-title {
            font-size: 2.5rem;
        }

        .videos-hero-subtitle {
            font-size: 1.1rem;
        }

        .videos-search-form .btn {
            width: 100%;
            margin-left: 0;
            margin-top: 10px;
        }

        .video-stats {
            margin-top: -20px;
            padding: 20px;
        }

        .stat-number {
            font-size: 2rem;
        }

        .quick-filters {
            justify-content: center;
        }

        .filter-select {
            font-size: 0.8rem;
            padding: 8px 12px;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== VIDEOS HERO SECTION ==================== -->
<section class="videos-header">
    <div class="container">
        <h1 class="videos-hero-title">SKOOLYST EduVideos</h1>
        <p class="videos-hero-subtitle">
            Explore our collection of educational videos from schools and shops. 
            Learn, discover, and get inspired with quality content.
        </p>

        <!-- Search Form -->
        <form action="{{ route('website.videos.index') }}" method="GET" class="videos-search-form mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search videos by title or description..." value="{{ request('search') }}">
                        <button class="btn btn-light" type="submit">
                            <i class="fas fa-search me-2"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- ==================== VIDEO STATS SECTION ==================== -->
{{-- <div class="container">
    <div class="row video-stats">
        <div class="col-md-3 col-6">
            <div class="stat-item">
                <div class="stat-number">{{ $totalVideos }}</div>
                <div class="stat-label">Total Videos</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-item">
                <div class="stat-number">{{ number_format($totalViews) }}</div>
                <div class="stat-label">Total Views</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-item">
                <div class="stat-number">{{ $featuredVideos->count() }}</div>
                <div class="stat-label">Featured</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-item">
                <div class="stat-number">{{ $categories->count() }}</div>
                <div class="stat-label">Categories</div>
            </div>
        </div>
    </div>
</div> --}}

<!-- ==================== VIDEOS CONTENT SECTION ==================== -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Filters -->
                <div class="filters-container">
                    <form action="{{ route('website.videos.index') }}" method="GET" id="video-filters-form">
                        <div class="row">
                            <div class="col-md-4 filter-group">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control filter-select">
                                    <option value="all">All Categories</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 filter-group">
                                <label for="school">School</label>
                                <select name="school" id="school" class="form-control filter-select">
                                    <option value="all">All Schools</option>
                                    @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ request('school') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 filter-group">
                                <label for="shop">Shop</label>
                                <select name="shop" id="shop" class="form-control filter-select">
                                    <option value="all">All Shops</option>
                                    @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}" {{ request('shop') == $shop->id ? 'selected' : '' }}>
                                        {{ $shop->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Quick Filters -->
                        <div class="mt-4">
                            <label class="d-block mb-2">Quick Filters:</label>
                            <div class="quick-filters">
                                <button type="button" class="quick-filter-btn {{ !request('filter') || request('filter') == 'all' ? 'active' : '' }}"
                                        data-filter="all">
                                    All Videos
                                </button>
                                <button type="button" class="quick-filter-btn {{ request('filter') == 'featured' ? 'active' : '' }}"
                                        data-filter="featured">
                                    <i class="fas fa-star me-1"></i> Featured
                                </button>
                                <button type="button" class="quick-filter-btn {{ request('filter') == 'popular' ? 'active' : '' }}"
                                        data-filter="popular">
                                    <i class="fas fa-fire me-1"></i> Popular
                                </button>
                                <button type="button" class="quick-filter-btn {{ request('filter') == 'recent' ? 'active' : '' }}"
                                        data-filter="recent">
                                    <i class="fas fa-clock me-1"></i> Recent
                                </button>
                            </div>
                            <input type="hidden" name="filter" id="filter" value="{{ request('filter', 'all') }}">
                        </div>

                        <!-- Clear Filters -->
                        @if(request()->hasAny(['category', 'school', 'shop', 'filter', 'search']))
                        <div class="mt-3 text-end">
                            <a href="{{ route('website.videos.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear Filters
                            </a>
                        </div>
                        @endif
                    </form>
                </div>

                <!-- Videos Grid -->
                @if($videos->count() > 0)
                <div class="row">
                    @foreach($videos as $video)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <article class="video-card card h-100 shadow-sm">
                            <!-- Video Thumbnail -->
                            <div class="position-relative card-img-top">
                                @if($video->video_id)
                                <img src="https://img.youtube.com/vi/{{ $video->video_id }}/hqdefault.jpg" 
                                     class="w-100 h-100 object-fit-cover" 
                                     alt="{{ $video->title }}">
                                @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-video fa-3x text-muted"></i>
                                </div>
                                @endif
                                
                                <!-- Play Overlay -->
                                <div class="video-play-overlay">
                                    <div class="play-icon">
                                        <i class="fas fa-play"></i>
                                    </div>
                                </div>
                                
                                <!-- Featured Badge -->
                                @if($video->is_featured)
                                <span class="position-absolute top-0 start-0 m-2 badge bg-warning">
                                    <i class="fas fa-star me-1"></i> Featured
                                </span>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">
                                @if($video->category)
                                <a href="{{ route('website.videos.category', $video->category->slug) }}"
                                    class="badge video-category-badge text-decoration-none mb-2">
                                    {{ $video->category->name }}
                                </a>
                                @endif

                                <h5 class="card-title">
                                    <a href="{{ route('website.videos.show', $video->slug) }}"
                                        class="text-dark text-decoration-none">
                                        {{ Str::limit($video->title, 50) }}
                                    </a>
                                </h5>

                                <p class="card-text text-muted small">
                                    {{ Str::limit($video->description, 100) }}
                                </p>

                                <!-- Video Meta -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        @if($video->user->profile_picture)
                                        <img src="{{ asset('website/' . $video->user->profile_picture) }}" 
                                             class="rounded-circle me-2" 
                                             style="width: 30px; height: 30px; object-fit: cover;"
                                             alt="{{ $video->user->name }}">
                                        @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2"
                                             style="width: 30px; height: 30px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        @endif
                                        <small class="text-muted">{{ Str::limit($video->user->name, 15) }}</small>
                                    </div>
                                    
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $video->created_at->diffForHumans() }}
                                    </small>
                                </div>

                                <!-- Video Stats -->
                                <div class="row mt-3 text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-eye"></i>
                                            {{ number_format($video->views) }}
                                        </small>
                                        <small class="text-muted">views</small>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-heart"></i>
                                            {{ number_format($video->likes_count) }}
                                        </small>
                                        <small class="text-muted">likes</small>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-comment"></i>
                                            {{ number_format($video->comments_count) }}
                                        </small>
                                        <small class="text-muted">comments</small>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($videos->hasPages())
                <div class="mt-5">
                    <nav>
                        {{ $videos->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
                @endif

                @else
                <!-- No Videos Found -->
                <div class="empty-state">
                    <i class="fas fa-video-slash empty-state-icon"></i>
                    <h4 class="text-muted">No videos found</h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['category', 'school', 'shop', 'filter', 'search']))
                        No videos match your search criteria. Try different filters.
                        @else
                        No videos have been uploaded yet. Check back soon!
                        @endif
                    </p>
                    @if(request()->hasAny(['category', 'school', 'shop', 'filter', 'search']))
                    <a href="{{ route('website.videos.index') }}" class="btn btn-primary">
                        <i class="fas fa-video me-2"></i>View All Videos
                    </a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Categories Widget -->
                <div class="sidebar-widget card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Video Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($categories as $category)
                            <a href="{{ route('website.videos.category', $category->slug) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                {{ $category->name }}
                                <span class="badge bg-primary rounded-pill">{{ $category->videos->count() ?? 0 }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Featured Videos Widget -->
                @if($featuredVideos->count() > 0)
                <div class="sidebar-widget card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-star me-2"></i>Featured Videos</h5>
                    </div>
                    <div class="card-body">
                        @foreach($featuredVideos as $featuredVideo)
                        <div class="featured-video-item">
                            <div class="featured-video-thumb">
                                @if($featuredVideo->video_id)
                                <img src="https://img.youtube.com/vi/{{ $featuredVideo->video_id }}/default.jpg" 
                                     alt="{{ $featuredVideo->title }}">
                                @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-video text-muted"></i>
                                </div>
                                @endif
                            </div>
                            <div class="featured-video-content">
                                <h6 class="featured-video-title">
                                    <a href="{{ route('website.videos.show', $featuredVideo->slug) }}">
                                        {{ Str::limit($featuredVideo->title, 40) }}
                                    </a>
                                </h6>
                                <div class="featured-video-meta">
                                    <small>
                                        <i class="fas fa-eye me-1"></i>{{ number_format($featuredVideo->views) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Popular Videos Widget -->
                @if($popularVideos->count() > 0)
                <div class="sidebar-widget card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Popular Videos</h5>
                    </div>
                    <div class="card-body">
                        @foreach($popularVideos as $popularVideo)
                        <div class="featured-video-item">
                            <div class="featured-video-thumb">
                                @if($popularVideo->video_id)
                                <img src="https://img.youtube.com/vi/{{ $popularVideo->video_id }}/default.jpg" 
                                     alt="{{ $popularVideo->title }}">
                                @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-video text-muted"></i>
                                </div>
                                @endif
                            </div>
                            <div class="featured-video-content">
                                <h6 class="featured-video-title">
                                    <a href="{{ route('website.videos.show', $popularVideo->slug) }}">
                                        {{ Str::limit($popularVideo->title, 40) }}
                                    </a>
                                </h6>
                                <div class="featured-video-meta">
                                    <small>
                                        <i class="fas fa-eye me-1"></i>{{ number_format($popularVideo->views) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Schools Widget -->
                <div class="sidebar-widget card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-school me-2"></i>Schools</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($schools->take(5) as $school)
                            <a href="{{ route('browseSchools.show', $school->uuid) }}"
                                class="list-group-item list-group-item-action d-flex align-items-center">
                                @if($school->logo)
                                <img src="{{ asset('website/' . $school->logo) }}" 
                                     class="rounded-circle me-2"
                                     style="width: 30px; height: 30px; object-fit: cover;"
                                     alt="{{ $school->name }}">
                                @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                     style="width: 30px; height: 30px;">
                                    <i class="fas fa-school"></i>
                                </div>
                                @endif
                                <span>{{ Str::limit($school->name, 25) }}</span>
                            </a>
                            @endforeach
                            @if($schools->count() > 5)
                            <a href="{{ route('website.schools.index') }}" class="list-group-item list-group-item-action text-center text-primary">
                                View All Schools <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Newsletter Widget -->
                {{-- <div class="sidebar-widget card shadow-sm bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-video fa-2x mb-3"></i>
                        <h5>Stay Updated</h5>
                        <p class="small opacity-75">Get notified about new educational videos</p>
                        <form class="mt-3">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Your email">
                                <button class="btn btn-light" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter form submission
        const filterForm = document.getElementById('video-filters-form');
        const filterInputs = filterForm.querySelectorAll('select');
        
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        });

        // Quick filter buttons
        const quickFilterBtns = document.querySelectorAll('.quick-filter-btn');
        const filterHiddenInput = document.getElementById('filter');
        
        quickFilterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const filterValue = this.getAttribute('data-filter');
                
                // Update active state
                quickFilterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Update hidden input
                filterHiddenInput.value = filterValue;
                
                // Submit form
                filterForm.submit();
            });
        });

        // Video card hover effect
        const videoCards = document.querySelectorAll('.video-card');
        videoCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Play icon hover
        const playIcons = document.querySelectorAll('.play-icon');
        playIcons.forEach(icon => {
            icon.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1)';
            });
            
            icon.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    });
</script>
@endpush