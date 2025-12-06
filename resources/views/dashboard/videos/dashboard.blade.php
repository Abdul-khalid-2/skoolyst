<x-app-layout>
    <main class="main-content">
        <section id="videos" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Video Gallery</h2>
                    <p class="mb-0 text-muted">Browse and watch educational videos</p>
                </div>
                @can('create-videos')
                <a href="{{ route('videos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Upload Video
                </a>
                @endcan
            </div>

            <!-- Video Statistics -->
            <div class="row mb-4">
                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Videos</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $videos->total() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-video fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Views</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalViews) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-eye fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Featured Videos</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $featuredCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-star fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        My Videos</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $myVideosCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('videos.index') }}" class="row g-2 g-md-3">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Filter By</label>
                            <select name="filter" class="form-select">
                                <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All Videos</option>
                                <option value="featured" {{ request('filter') == 'featured' ? 'selected' : '' }}>Featured</option>
                                <option value="popular" {{ request('filter') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                <option value="recent" {{ request('filter') == 'recent' ? 'selected' : '' }}>Newest First</option>
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="all">All Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        @role('super-admin')
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">School</label>
                            <select name="school" class="form-select">
                                <option value="all">All Schools</option>
                                @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ request('school') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Shop</label>
                            <select name="shop" class="form-select">
                                <option value="all">All Shops</option>
                                @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ request('shop') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endrole
                        
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-2"></i> Apply Filters
                                </button>
                                <a href="{{ route('videos.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Videos Grid -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @forelse($videos as $video)
                <div class="col">
                    <div class="card h-100 video-card">
                        <div class="position-relative">
                            <a href="{{ route('videos.show', $video->slug) }}">
                                <img src="{{ $video->thumbnail ? asset('storage/' . $video->thumbnail) : 'https://img.youtube.com/vi/' . $video->video_id . '/hqdefault.jpg' }}" 
                                     class="card-img-top" alt="{{ $video->title }}"
                                     style="height: 180px; object-fit: cover;">
                            </a>
                            @if($video->is_featured)
                            <span class="position-absolute top-0 start-0 m-2 badge bg-warning">
                                <i class="fas fa-star me-1"></i> Featured
                            </span>
                            @endif
                            <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark">
                                {{ $video->duration ?? '--:--' }}
                            </span>
                        </div>
                        
                        <div class="card-body">
                            <h6 class="card-title mb-2">
                                <a href="{{ route('videos.show', $video->slug) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($video->title, 60) }}
                                </a>
                            </h6>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    @if($video->user->profile_picture)
                                    <img src="{{ asset('storage/' . $video->user->profile_picture) }}" 
                                         class="rounded-circle me-2" width="24" height="24" alt="{{ $video->user->name }}">
                                    @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" 
                                         style="width: 24px; height: 24px;">
                                        <i class="fas fa-user text-white fa-xs"></i>
                                    </div>
                                    @endif
                                    <small>{{ $video->user->name }}</small>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i> {{ $video->created_at->format('M d') }}
                                </small>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted me-3">
                                        <i class="fas fa-eye"></i> {{ number_format($video->views) }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-thumbs-up"></i> {{ number_format($video->likes_count) }}
                                    </small>
                                </div>
                                @if($video->category)
                                <span class="badge bg-secondary">{{ $video->category->name }}</span>
                                @endif
                            </div>
                        </div>
                        
                        @can('update', $video)
                        <div class="card-footer bg-transparent border-top-0 pt-0">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('videos.edit', $video) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <form action="{{ route('videos.destroy', $video) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this video?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-video fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No videos found</h4>
                            <p class="text-muted mb-4">Try adjusting your filters or upload a video</p>
                            @can('create-videos')
                            <a href="{{ route('videos.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Upload First Video
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($videos->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $videos->withQueryString()->links() }}
            </div>
            @endif
        </section>
    </main>

    <style>
        .video-card {
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }
        
        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-color: #007bff;
        }
        
        .video-card .card-img-top {
            border-bottom: 1px solid #e0e0e0;
        }
        
        .video-card .card-title {
            font-size: 0.95rem;
            line-height: 1.4;
            min-height: 2.8rem;
        }
        
        @media (max-width: 768px) {
            .video-card .card-title {
                font-size: 0.875rem;
                min-height: auto;
            }
            
            .video-card .card-body {
                padding: 0.75rem;
            }
            
            .video-card .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add video card hover effects
            const videoCards = document.querySelectorAll('.video-card');
            
            videoCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.zIndex = '10';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.zIndex = '1';
                });
            });
            
            // Filter form submission enhancement
            const filterSelects = document.querySelectorAll('select[name="filter"], select[name="category"], select[name="school"], select[name="shop"]');
            
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            });
        });
    </script>
</x-app-layout>