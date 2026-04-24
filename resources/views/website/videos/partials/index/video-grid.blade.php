@php
    $noindex = $noindex ?? false;
    $categories = $categories ?? collect();
@endphp
@if($videos->count() > 0)
<div class="row">
    @foreach($videos as $video)
    <div class="col-md-6 col-lg-6 col-xl-6 mb-4">
        <article class="video-card card h-100 shadow-sm" itemscope itemtype="https://schema.org/VideoObject">
            <div class="position-relative card-img-top">
                @if($video->video_id)
                <img src="https://img.youtube.com/vi/{{ $video->video_id }}/hqdefault.jpg"
                     class="w-100 h-100 object-fit-cover"
                     alt="{{ $video->title }}"
                     itemprop="thumbnailUrl">
                @else
                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center video-thumb-placeholder">
                    <i class="fas fa-video fa-3x text-muted"></i>
                </div>
                @endif

                <div class="video-play-overlay">
                    <a href="{{ route('website.videos.show', $video->slug) }}"
                        class="text-dark text-decoration-none" itemprop="url">
                        <div class="play-icon">
                            <i class="fas fa-play"></i>
                        </div>
                    </a>
                </div>

                @if($video->is_featured)
                <span class="position-absolute top-0 start-0 m-2 badge bg-warning">
                    <i class="fas fa-star me-1"></i> Featured
                </span>
                @endif
            </div>

            <div class="card-body">
                @if($video->category)
                <a href="{{ route('website.videos.category', $video->category->slug) }}"
                    class="badge video-category-badge text-decoration-none mb-2">
                    {{ $video->category->name }}
                </a>
                @endif

                <h2 class="card-title h6 mb-2" itemprop="name">
                    <a href="{{ route('website.videos.show', $video->slug) }}"
                        class="text-dark text-decoration-none">
                        {{ Str::limit($video->title, 50) }}
                    </a>
                </h2>

                <p class="card-text text-muted small" itemprop="description">
                    {{ Str::limit($video->description, 100) }}
                </p>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        @if($video->user->profile_picture)
                        <img src="{{ asset('website/' . $video->user->profile_picture) }}"
                             class="rounded-circle me-2 video-author-thumb"
                             alt="{{ $video->user->name }}">
                        @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2 video-author-thumb">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        @endif
                        <small class="text-muted">{{ Str::limit($video->user->name, 15) }}</small>
                    </div>

                    <small class="text-muted" itemprop="uploadDate" content="{{ $video->created_at->toIso8601String() }}">
                        <i class="far fa-clock me-1"></i>
                        {{ $video->created_at->diffForHumans() }}
                    </small>
                </div>

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

@if($videos->hasPages())
<div class="mt-5">
    <nav aria-label="Videos pagination">
        {{ $videos->links('pagination::bootstrap-5') }}
    </nav>
</div>
@endif

@else
@php
    $selectedForEmpty = (request('category') && request('category') != 'all')
        ? $categories->firstWhere('id', (int) request('category'))
        : null;
@endphp
<div class="empty-state">
    <i class="fas fa-video-slash empty-state-icon" aria-hidden="true"></i>
    <h4 class="text-muted">No videos found</h4>
    <p class="text-muted">
        @if($noindex && $selectedForEmpty)
            No published videos in &ldquo;{{ $selectedForEmpty->name }}&rdquo; yet. More content is coming soon. Browse all videos or try another category.
        @elseif(request()->hasAny(['category', 'school', 'shop', 'filter', 'search']))
            No videos match your search criteria. Try different filters.
        @else
            No videos have been uploaded yet. Check back soon!
        @endif
    </p>
    @if(($noindex && $selectedForEmpty) || request()->hasAny(['category', 'school', 'shop', 'filter', 'search']))
    <a href="{{ route('website.videos.index') }}" class="btn btn-primary">
        <i class="fas fa-video me-2"></i>View All Videos
    </a>
    @endif
</div>
@endif
