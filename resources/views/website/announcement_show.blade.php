@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/announcement-show.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== ANNOUNCEMENT HERO SECTION ==================== -->
<section class="announcement-hero">
    <div class="container">
        <div class="hero-content">
            <nav class="announcement-breadcrumb">
                <a href="{{ route('website.home') }}" class="breadcrumb-link">Home</a>
                <span class="text-white mx-2">/</span>
                <a href="{{ route('browseSchools.show', $announcement->school->uuid) }}" class="breadcrumb-link">{{ $announcement->school->name }}</a>
                <span class="text-white mx-2">/</span>
                <span class="text-white-50">Announcement</span>
            </nav>

            <!-- Announcement Title -->
            <h1 class="announcement-title">{{ $announcement->title }}</h1>
        </div>
    </div>
</section>


<!-- ==================== ANNOUNCEMENT CONTENT SECTION ==================== -->
<section class="announcement-content-section">
    <div class="container">
        <div class="row g-5">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="content-wrapper">
                    <div class="announcement-card">
                        @if($announcement->feature_image)
                            <img src="{{ $announcement->feature_image_url }}" alt="{{ $announcement->title }}" class="featured-image">
                        @endif
                        
                        <div class="announcement-body">
                            <div class="announcement-content">
                                {!! $announcement->content !!}
                            </div>
                        </div>
                    </div>

                    {{-- Comments Section --}}
                    <div class="comments-section">
                        <h3 class="section-title">
                            <i class="fas fa-comments"></i>
                            Discussion ({{ $announcement->comments->count() }})
                        </h3>

                        <div class="comment-form">
                            <h4 class="form-title">
                                <i class="fas fa-edit"></i>
                                Leave a Comment
                            </h4>
                            <form action="{{ route('announcements.comments.store', $announcement->uuid) }}" method="POST">
                                @csrf
                                
                                @if(!auth()->check())
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="form-label">Your Name *</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" name="name" value="{{ old('name') }}" required>
                                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="form-label">Email Address *</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" name="email" value="{{ old('email') }}" required>
                                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="comment" class="form-label">Your Comment *</label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" 
                                              id="comment" name="comment" rows="5" required>{{ old('comment') }}</textarea>
                                    @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-paper-plane me-2"></i> Post Comment
                                </button>
                            </form>
                        </div>
                        <div class="comments-list">
                            @forelse($announcement->comments as $comment)
                                <div class="comment-item">
                                    <div class="comment-header">
                                        <div class="comment-avatar">
                                            {{ strtoupper(substr($comment->getCommenterNameAttribute(), 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="comment-author">{{ $comment->getCommenterNameAttribute() }}</h4>
                                            <div class="comment-date">{{ $comment->created_at->format('M d, Y \a\t h:i A') }}</div>
                                        </div>
                                    </div>
                                    <p class="comment-text">{{ $comment->comment }}</p>
                                </div>
                            @empty
                                <div class="no-comments">
                                    <i class="fas fa-comment-slash"></i>
                                    <p class="mb-0">No comments yet. Be the first to share your thoughts.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- School Info Card -->
                <div class="sidebar-card">
                    <h4 class="sidebar-title">
                        <i class="fas fa-info-circle"></i>
                        Announcement Details
                    </h4>
                    <div class="meta-list">
                        <div class="meta-item-sidebar">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Posted on <strong>{{ $announcement->created_at->format('F d, Y') }}</strong></span>
                        </div>
                        <div class="meta-item-sidebar">
                            <i class="fas fa-eye"></i>
                            <span><strong>{{ number_format($announcement->view_count) }}</strong> views</span>
                        </div>
                        <div class="meta-item-sidebar">
                            <i class="fas fa-comments"></i>
                            <span><strong>{{ $announcement->comments->count() }}</strong> comments</span>
                        </div>
                        @if($announcement->branch)
                            <div class="meta-item-sidebar">
                                <i class="fas fa-building"></i>
                                <span>Branch: <strong>{{ $announcement->branch->name }}</strong></span>
                            </div>
                        @endif
                        @if($announcement->created_at->gt(now()->subDays(3)))
                            <div class="meta-item-sidebar">
                                <i class="fas fa-bolt me-1"></i>
                                <span class="badge-just-posted"> Just Posted</span>
                            </div>
                        @endif
                        @if($announcement->publish_at)
                            <div class="meta-item-sidebar">
                                <i class="fas fa-clock"></i>
                                <span>Published: <strong>{{ $announcement->publish_at->format('M d, Y') }}</strong></span>
                            </div>
                        @endif
                        @if($announcement->expire_at)
                            <div class="meta-item-sidebar">
                                <i class="fas fa-hourglass-end"></i>
                                <span>Last Day: <strong>{{ $announcement->expire_at->format('M d, Y') }}</strong></span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- School Info Card --}}
                <div class="school-info-card-sidebar">
                    <div class="school-header-sidebar">
                        @if($announcement->school->logo)
                            <img src="{{ asset('storage/' . $announcement->school->logo) }}" 
                                 alt="{{ $announcement->school->name }}" class="school-logo-sidebar">
                        @else
                            <div class="school-logo-sidebar bg-light">
                                <i class="fas fa-school fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div class="school-details-sidebar">
                            <h3>{{ $announcement->school->name }}</h3>
                            <div class="school-location-sidebar">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $announcement->school->city ?? 'Location not specified' }}
                            </div>
                        </div>
                    </div>
                    <div class="school-stats-sidebar">
                        <div class="stat-item-sidebar">
                            <span class="stat-number-sidebar">{{ $announcement->school->announcements()->where('status', 'published')->count() }}</span>
                            <span class="stat-label-sidebar">Updates</span>
                        </div>
                        <div class="stat-item-sidebar">
                            <span class="stat-number-sidebar">{{ $announcement->school->reviews->count() }}</span>
                            <span class="stat-label-sidebar">Reviews</span>
                        </div>
                        <div class="stat-item-sidebar">
                            <span class="stat-number-sidebar">{{ $announcement->school->branches->count() }}</span>
                            <span class="stat-label-sidebar">Locations</span>
                        </div>
                    </div>
                    <a href="{{ route('browseSchools.show', $announcement->school->uuid) }}" class="view-school-btn-sidebar">
                        <i class="fas fa-external-link-alt me-2"></i> View School Profile
                    </a>
                </div>

                <!-- Related Announcements -->
                @if($relatedAnnouncements->count() > 0)
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">
                            <i class="fas fa-bullhorn"></i>
                            More from this School
                        </h4>
                        <div class="related-list">
                            @foreach($relatedAnnouncements->take(4) as $related)
                                <div class="related-item">
                                    <a href="{{ route('announcements.show', $related->uuid) }}">
                                        {{ Str::limit($related->title, 60) }}
                                    </a>
                                    <small><i class="fas fa-clock me-1"></i>{{ $related->created_at->diffForHumans() }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection