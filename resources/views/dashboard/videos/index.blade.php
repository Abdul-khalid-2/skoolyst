<x-app-layout>
    <main class="main-content">
        <section id="videos-section" class="page-section">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="row mb-4">
                    <div class="col">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="page-title">Videos</h1>
                                <p class="page-subtitle">Explore educational videos from schools and shops</p>
                            </div>
                            {{-- @can('create-videos') --}}
                            <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Upload Video
                            </a>
                            {{-- @endcan --}}
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                <div class="row mb-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.videos.index') }}" method="GET" id="filter-form">
                                    <div class="row g-3">
                                        <!-- Search -->
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label">Search</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" name="search" class="form-control" 
                                                       placeholder="Search videos..." value="{{ request('search') }}">
                                            </div>
                                        </div>

                                        <!-- Category Filter -->
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label">Category</label>
                                            <select name="category" class="form-select" onchange="this.form.submit()">
                                                <option value="">All Categories</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- School Filter -->
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label">School</label>
                                            <select name="school" class="form-select" onchange="this.form.submit()">
                                                <option value="">All Schools</option>
                                                @foreach($schools as $school)
                                                <option value="{{ $school->id }}" 
                                                    {{ request('school') == $school->id ? 'selected' : '' }}>
                                                    {{ $school->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Shop Filter -->
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label">Shop</label>
                                            <select name="shop" class="form-select" onchange="this.form.submit()">
                                                <option value="">All Shops</option>
                                                @foreach($shops as $shop)
                                                <option value="{{ $shop->id }}" 
                                                    {{ request('shop') == $shop->id ? 'selected' : '' }}>
                                                    {{ $shop->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Quick Filter Buttons -->
                                        <div class="col-12">
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                <input type="radio" class="btn-check" name="filter" id="filter-all" 
                                                       value="all" {{ !request('filter') || request('filter') == 'all' ? 'checked' : '' }} 
                                                       onchange="this.form.submit()">
                                                <label class="btn btn-outline-secondary" for="filter-all">All</label>

                                                <input type="radio" class="btn-check" name="filter" id="filter-featured" 
                                                       value="featured" {{ request('filter') == 'featured' ? 'checked' : '' }} 
                                                       onchange="this.form.submit()">
                                                <label class="btn btn-outline-warning" for="filter-featured">
                                                    <i class="fas fa-star me-1"></i> Featured
                                                </label>

                                                <input type="radio" class="btn-check" name="filter" id="filter-popular" 
                                                       value="popular" {{ request('filter') == 'popular' ? 'checked' : '' }} 
                                                       onchange="this.form.submit()">
                                                <label class="btn btn-outline-danger" for="filter-popular">
                                                    <i class="fas fa-fire me-1"></i> Popular
                                                </label>

                                                <input type="radio" class="btn-check" name="filter" id="filter-recent" 
                                                       value="recent" {{ request('filter') == 'recent' ? 'checked' : '' }} 
                                                       onchange="this.form.submit()">
                                                <label class="btn btn-outline-success" for="filter-recent">
                                                    <i class="fas fa-clock me-1"></i> Recent
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Videos Grid -->
                <div class="row">
                    @if($videos->count() > 0)
                        @foreach($videos as $video)
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card video-card h-100">
                                <!-- Video Thumbnail -->
                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                    @if($video->video_id)
                                    <img src="https://img.youtube.com/vi/{{ $video->video_id }}/hqdefault.jpg" 
                                         class="card-img-top h-100 object-fit-cover" 
                                         alt="{{ $video->title }}">
                                    @else
                                    <div class="h-100 bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-video fa-3x text-muted"></i>
                                    </div>
                                    @endif
                                    
                                    <!-- Video Duration Badge -->
                                    @if($video->duration)
                                    <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark">
                                        {{ $video->duration }}
                                    </span>
                                    @endif

                                    <!-- Featured Badge -->
                                    @if($video->is_featured)
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-warning">
                                        <i class="fas fa-star me-1"></i> Featured
                                    </span>
                                    @endif

                                    <!-- Play Button Overlay -->
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <div class="play-btn">
                                            <i class="fas fa-play-circle"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body">
                                    <h5 class="card-title video-title">
                                        <a href="{{ route('admin.videos.show', $video->slug) }}" class="text-decoration-none">
                                            {{ Str::limit($video->title, 60) }}
                                        </a>
                                    </h5>
                                    
                                    <p class="card-text text-muted small">
                                        {{ Str::limit($video->description, 100) }}
                                    </p>

                                    <!-- Video Stats -->
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex gap-3">
                                            <span class="small text-muted">
                                                <i class="fas fa-eye me-1"></i> {{ number_format($video->views) }}
                                            </span>
                                            <span class="small text-muted">
                                                <i class="fas fa-heart me-1"></i> {{ number_format($video->likes_count) }}
                                            </span>
                                            <span class="small text-muted">
                                                <i class="fas fa-comment me-1"></i> {{ number_format($video->comments_count) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Video Meta -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            @if($video->user->profile_picture)
                                            <img src="{{ asset('website/' . $video->user->profile_picture) }}" 
                                                 class="rounded-circle me-2" width="30" height="30" 
                                                 alt="{{ $video->user->name }}">
                                            @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 30px; height: 30px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            @endif
                                            <small class="text-muted">{{ $video->user->name }}</small>
                                        </div>
                                        
                                        <small class="text-muted">
                                            {{ $video->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="card-footer bg-transparent border-top-0 pt-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        @if($video->category)
                                        <span class="badge bg-primary">
                                            {{ $video->category->name }}
                                        </span>
                                        @endif
                                        
                                        @if($video->school)
                                        <span class="badge bg-info">
                                            <i class="fas fa-school me-1"></i> {{ Str::limit($video->school->name, 15) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <!-- No Videos Found -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-video-slash fa-3x text-muted"></i>
                                    </div>
                                    <h4 class="mt-3">No Videos Found</h4>
                                    <p class="text-muted">
                                        @if(request()->hasAny(['category', 'school', 'shop', 'filter', 'search']))
                                        No videos match your search criteria. Try different filters.
                                        @else
                                        No videos have been uploaded yet. Be the first to upload one!
                                        @endif
                                    </p>
                                    @can('create-videos')
                                    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i> Upload First Video
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if($videos->hasPages())
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                {{ $videos->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </section>
    </main>

    @push('styles')
    <style>
        .video-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        
        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border-color: #0d6efd;
        }
        
        .video-card:hover .play-btn {
            transform: scale(1.1);
            opacity: 1;
        }
        
        .play-btn {
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            opacity: 0.8;
        }
        
        .video-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 3em;
        }
        
        .video-card .card-img-top {
            transition: transform 0.5s ease;
        }
        
        .video-card:hover .card-img-top {
            transform: scale(1.05);
        }
        
        .empty-state {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .empty-state-icon {
            margin-bottom: 1rem;
        }
        
        .object-fit-cover {
            object-fit: cover;
        }
    </style>
    @endpush

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event to entire video card for navigation
            document.querySelectorAll('.video-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    // Don't navigate if clicking on links, buttons, or form elements
                    if (e.target.tagName === 'A' || 
                        e.target.tagName === 'BUTTON' || 
                        e.target.closest('a') || 
                        e.target.closest('button')) {
                        return;
                    }
                    
                    const videoLink = this.querySelector('.video-title a');
                    if (videoLink) {
                        window.location.href = videoLink.href;
                    }
                });
            });

            // Search debounce
            let searchTimeout;
            const searchInput = document.querySelector('input[name="search"]');
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        document.getElementById('filter-form').submit();
                    }, 500);
                });
            }

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
</x-app-layout>