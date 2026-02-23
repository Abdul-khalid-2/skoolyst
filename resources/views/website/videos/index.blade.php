@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/videos.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== VIDEOS HERO SECTION (compact) ==================== -->
<section class="videos-hero-section" id="videos-hero">
    <div class="videos-hero-content">
        <h1 class="videos-hero-title">SKOOLYST EduVideos</h1>
        <p class="videos-hero-subheading">
            Explore our collection of educational videos from schools and shops. 
            Learn, discover, and get inspired with quality content.
        </p>
    </div>
</section>

<!-- ==================== VIDEOS SEARCH BAR (below header) ==================== -->
<section class="videos-search-section">
    <div class="container">
        <div class="videos-search-container">
            <form action="{{ route('website.videos.index') }}" method="GET" class="videos-search-form">
                <div class="videos-search-box">
                    <input type="text" name="search" class="videos-search-input" 
                        placeholder="Search videos by title or description..." value="{{ request('search') }}">
                    <button class="videos-search-btn" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
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
                    <div class="col-md-6 col-lg-6 col-xl-6 mb-4">
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
                                     <a href="{{ route('website.videos.show', $video->slug) }}"
                                        class="text-dark text-decoration-none">
                                        <div class="play-icon">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </a>
                                    
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

                                <h6 class="card-title">
                                    <a href="{{ route('website.videos.show', $video->slug) }}"
                                        class="text-dark text-decoration-none">
                                        {{ Str::limit($video->title, 50) }}
                                    </a>
                                </h6>

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
                            <a href="{{ route('browseSchools.index') }}" class="list-group-item list-group-item-action text-center text-primary">
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