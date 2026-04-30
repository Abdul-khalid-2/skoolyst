<!-- Categories Widget -->
<div class="sidebar-widget">
    <div class="card-header">
        <h2 class="videos-sidebar-title h5 mb-0">
            <i class="fas fa-folder me-2"></i>Video Categories
        </h2>
    </div>
    <div class="videos-sidebar-content">
        <div class="videos-category-list">
            @foreach($categories as $category)
            <a href="{{ route('website.videos.category', $category->slug) }}"
            class="videos-category-item">
                <span>{{ $category->name }}</span>
                <span class="videos-category-count">{{ $category->published_videos_count ?? 0 }}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>

@if($featuredVideos->count() > 0)
<div class="sidebar-widget">
    <div class="card-header">
        <h2 class="videos-sidebar-title h5 mb-0">
            <i class="fas fa-star me-2"></i>Featured Videos
        </h2>
    </div>
    <div class="videos-sidebar-content">
        @foreach($featuredVideos as $featuredVideo)
        <div class="featured-video-item">
            <div class="videos-featured-thumb">
                @if($featuredVideo->video_id)
                <img src="https://img.youtube.com/vi/{{ $featuredVideo->video_id }}/default.jpg"
                    alt="{{ $featuredVideo->title }}">
                @else
                <div class="videos-featured-placeholder">
                    <i class="fas fa-video"></i>
                </div>
                @endif
            </div>
            <div class="videos-featured-content">
                <h3 class="videos-featured-title h6">
                    <a href="{{ route('website.videos.show', $featuredVideo->slug) }}">
                        {{ Str::limit($featuredVideo->title, 40) }}
                    </a>
                </h3>
                <div class="videos-featured-meta">
                    <i class="fas fa-eye me-1"></i>{{ number_format($featuredVideo->views) }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($popularVideos->count() > 0)
<div class="sidebar-widget">
    <div class="card-header">
        <h2 class="videos-sidebar-title h5 mb-0">
            <i class="fas fa-fire me-2"></i>Popular Videos
        </h2>
    </div>
    <div class="videos-sidebar-content">
        @foreach($popularVideos as $popularVideo)
        <div class="featured-video-item">
            <div class="videos-featured-thumb">
                @if($popularVideo->video_id)
                <img src="https://img.youtube.com/vi/{{ $popularVideo->video_id }}/default.jpg"
                    alt="{{ $popularVideo->title }}">
                @else
                <div class="videos-featured-placeholder">
                    <i class="fas fa-video"></i>
                </div>
                @endif
            </div>
            <div class="videos-featured-content">
                <h3 class="videos-featured-title h6">
                    <a href="{{ route('website.videos.show', $popularVideo->slug) }}">
                        {{ Str::limit($popularVideo->title, 40) }}
                    </a>
                </h3>
                <div class="videos-featured-meta">
                    <i class="fas fa-eye me-1"></i>{{ number_format($popularVideo->views) }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="sidebar-widget">
    <div class="card-header">
        <h2 class="videos-sidebar-title h5 mb-0">
            <i class="fas fa-school me-2"></i>Schools
        </h2>
    </div>
    <div class="videos-sidebar-content">
        <div class="videos-school-list">
            @foreach($schools->take(5) as $school)
            <a href="{{ route('browseSchools.show', $school->uuid) }}"
            class="videos-school-item">
                @if($school->logo)
                <img src="{{ asset('website/' . $school->logo) }}"
                    class="videos-school-logo"
                    alt="{{ $school->localized('name') }}">
                @else
                <div class="videos-school-placeholder">
                    <i class="fas fa-school"></i>
                </div>
                @endif
                <span class="videos-school-name">{{ Str::limit($school->localized('name'), 25) }}</span>
            </a>
            @endforeach
            @if($schools->count() > 5)
            <a href="{{ route('browseSchools.index') }}" class="videos-view-all-link">
                View All Schools <i class="fas fa-arrow-right ms-1"></i>
            </a>
            @endif
        </div>
    </div>
</div>
