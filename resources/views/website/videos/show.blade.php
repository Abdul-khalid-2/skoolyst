@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== VIDEO DETAIL STYLES ==================== */
    .video-container {
        background: #000;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .video-player {
        width: 100%;
        height: 500px;
        border: none;
    }

    .video-stats {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 0.5rem;
    }

    .stat-item h4 {
        font-weight: 700;
        margin: 0.5rem 0;
        color: #1a1a1a;
    }

    .stat-item i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .lawyer-card {
        transition: transform 0.3s ease;
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .lawyer-card:hover {
        transform: translateY(-3px);
    }

    .related-video-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .related-video-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .video-thumbnail {
        position: relative;
        overflow: hidden;
        height: 160px;
    }

    .video-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .video-thumbnail::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.3) 100%);
        z-index: 1;
    }

    .video-play-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(220, 53, 69, 0.9);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .video-play-icon:hover {
        background: rgba(220, 53, 69, 1);
        transform: translate(-50%, -50%) scale(1.1);
    }

    .video-play-icon i {
        color: white;
        font-size: 14px;
        margin-left: 2px;
    }

    .badge-category {
        background: linear-gradient(135deg, #4361ee, #3a56d4);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .video-description {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        border-left: 4px solid #4361ee;
        margin: 1.5rem 0;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #e9ecef;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.25rem 1.5rem;
    }

    .card-header h5 {
        margin: 0;
        font-weight: 600;
        color: #1a1a1a;
    }

    .card-header h5 i {
        color: #4361ee;
    }

    .card-body {
        padding: 1.5rem;
    }

    .btn-social {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.5rem;
        font-size: 0.875rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-social:hover {
        transform: translateY(-2px);
    }

    .video-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #666;
        font-size: 0.875rem;
    }

    .video-meta-item i {
        color: #4361ee;
        width: 20px;
    }

    .section-title {
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1a1a1a;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: #0f4077;
        border-radius: 2px;
    }

    .sidebar-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .sidebar-card .card-body {
        padding: 1.25rem;
    }

    .cta-card {
        background: linear-gradient(135deg, #4361ee, #3a56d4);
        border: none;
        color: white;
        text-align: center;
    }

    .cta-card i {
        font-size: 2rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .cta-card h5 {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .cta-card .btn-light {
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1.5rem;
        transition: all 0.3s ease;
    }

    .cta-card .btn-light:hover {
        background: white;
        transform: translateY(-2px);
    }

    .lawyer-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .lawyer-avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #0f4077;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        border: 3px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    /* Comments Section */
    .comments-section {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #e9ecef;
    }

    .comment-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        background: white;
        transition: transform 0.2s ease;
    }

    .comment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .comment-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .comment-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #0f4077;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }

    .comment-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .comment-author {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .comment-time {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .comment-content {
        color: #333;
        line-height: 1.6;
        margin-bottom: 0.5rem;
    }

    .comment-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .comment-action-btn {
        background: none;
        border: none;
        color: #666;
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .comment-action-btn:hover {
        background: #f8f9fa;
        color: #4361ee;
    }

    .replies-section {
        margin-left: 3rem;
        margin-top: 1rem;
        padding-left: 1.5rem;
        border-left: 2px solid #e9ecef;
    }

    .reply-form {
        margin-top: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        display: none;
    }

    .reply-form.active {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-prompt {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        border: 1px solid #ffecb5;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 2rem;
    }

    /* Video Details */
    .video-details-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .detail-item {
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .detail-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .detail-label i {
        color: #4361ee;
        width: 20px;
    }

    .detail-value {
        color: #666;
        padding-left: 2rem;
    }

    .detail-value a {
        color: #4361ee;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .detail-value a:hover {
        color: #3a56d4;
        text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .video-player {
            height: 300px;
        }

        .video-thumbnail {
            height: 120px;
        }

        .lawyer-avatar,
        .lawyer-avatar-placeholder {
            width: 60px;
            height: 60px;
            font-size: 1rem;
        }

        .stat-item i {
            font-size: 1.5rem;
        }

        .replies-section {
            margin-left: 1.5rem;
            padding-left: 1rem;
        }

        .comment-avatar,
        .comment-avatar-placeholder {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 576px) {
        .video-player {
            height: 200px;
        }

        .replies-section {
            margin-left: 1rem;
            padding-left: 0.75rem;
        }

        .video-thumbnail {
            height: 100px;
        }

        .section-title {
            font-size: 1.25rem;
        }

        .card-header h5 {
            font-size: 1rem;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')
<!-- Video Detail Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Video Content -->
            <div class="col-lg-8">
                <div class="video-container mb-4">
                    <iframe class="video-player"
                        src="https://www.youtube.com/embed/{{ $video->video_id }}?rel=0" 
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>

                <!-- Video Info -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        @if($video->category)
                        <a href="{{ route('website.videos.category', $video->category->slug) }}"
                            class="badge-category text-decoration-none mb-3 d-inline-block">
                            <i class="fas fa-tag me-1"></i>{{ $video->category->name }}
                        </a>
                        @endif
                        
                        <h1 class="h2 mb-3">{{ $video->title }}</h1>

                        <!-- Video Stats -->
                        {{-- <div class="d-flex flex-wrap gap-3 mb-3">
                            <div class="video-meta-item">
                                <i class="fas fa-eye"></i>
                                <span>{{ number_format($video->views) }} views</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-heart"></i>
                                <span id="likes-count">{{ number_format($video->likes_count) }} likes</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-comment"></i>
                                <span id="comments-count">{{ number_format($video->comments_count) }} comments</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $video->created_at->format('M j, Y') }}</span>
                            </div>
                        </div> --}}

                        @if($video->description)
                        <div class="video-description">
                            <h5 class="mb-3">Description</h5>
                            <div class="content">
                                {!! nl2br(e($video->description)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="video-stats">
                            <div class="row">
                                <div class="col-6">
                                    <div class="stat-item">
                                        <i class="fas fa-eye fa-2x text-primary mb-2"></i>
                                        <h4 class="mb-1">{{ number_format($video->views) }}</h4>
                                        <small class="text-muted">Views</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item">
                                        <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                                        <h4 class="mb-1" id="likes-count-stat">{{ number_format($video->likes_count) }}</h4>
                                        <small class="text-muted">Likes</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small class="text-muted d-block mb-1">
                                    <i class="far fa-clock me-1"></i> 
                                    Published: {{ $video->created_at->format('F j, Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex gap-2 flex-wrap">
                            <!-- Video Actions -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex gap-2 flex-wrap">
                                      @auth
                                        @php
                                            $userHasLiked = $video->reactions()
                                                ->where('user_id', Auth::id())
                                                ->where('reaction', 'like')
                                                ->exists();
                                        @endphp
                                        
                                        <button class="btn {{ $userHasLiked ? 'btn-primary' : 'btn-outline-primary' }}" 
                                                onclick="reactToVideo('like')" 
                                                id="like-btn">
                                            <i class="fas fa-thumbs-up me-2"></i>
                                            <span id="like-text">{{ $userHasLiked ? 'Liked' : 'Like' }}</span>
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-thumbs-up me-2"></i> Like
                                        </a>
                                    @endauth
                                        <!-- other buttons -->
                                    </div>
                                </div>
                            </div>
                            {{-- <button class="btn btn-outline-secondary" onclick="shareVideo()">
                                <i class="fas fa-share-alt me-2"></i> Share
                            </button>
                            <button class="btn btn-outline-success" id="save-btn">
                                <i class="fas fa-bookmark me-2"></i> Save
                            </button>
                            <a href="#" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reportModal">
                                <i class="fas fa-flag me-2"></i> Report
                            </a> --}}
                        </div>
                    </div>
                </div>

                <!-- Video Details -->
                <div class="video-details-card mb-4">
                    <h4 class="mb-3">Video Details</h4>
                    <div class="row">
                        @if($video->category)
                        <div class="col-md-6 detail-item">
                            <div class="detail-label">
                                <i class="fas fa-tag"></i> Category
                            </div>
                            <div class="detail-value">
                                <a href="{{ route('website.videos.category', $video->category->slug) }}" 
                                   class="text-decoration-none">
                                    {{ $video->category->name }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($video->school)
                        <div class="col-md-6 detail-item">
                            <div class="detail-label">
                                <i class="fas fa-school"></i> School
                            </div>
                            <div class="detail-value">
                                <a href="{{ route('browseSchools.index', $video->school->uuid) }}" 
                                   class="text-decoration-none">
                                    {{ $video->school->name }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($video->shop)
                        <div class="col-md-6 detail-item">
                            <div class="detail-label">
                                <i class="fas fa-store"></i> Shop
                            </div>
                            <div class="detail-value">
                                <a href="{{ route('website.shop.show', $video->shop->uuid) }}" 
                                   class="text-decoration-none">
                                    {{ $video->shop->name }}
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-6 detail-item">
                            <div class="detail-label">
                                <i class="fas fa-user"></i> Uploaded By
                            </div>
                            <div class="detail-value">
                                {{ $video->user->name }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Uploader Info -->
                <div class="card border-0 shadow-sm mb-5 lawyer-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                @if($video->user->profile_picture)
                                <img src="{{ asset('storage/' . $video->user->profile_picture) }}" 
                                     alt="{{ $video->user->name }}"
                                     class="lawyer-avatar">
                                @else
                                <div class="lawyer-avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                                @endif
                            </div>
                            <div class="col">
                                <h4 class="mb-1">{{ $video->user->name }}</h4>
                                <p class="text-muted mb-2">
                                    Video Creator
                                </p>
                                <p class="text-muted small mb-2">
                                    Member since {{ $video->user->created_at->format('F Y') }}
                                </p>
                                <div class="d-flex gap-2">
                                    {{-- <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-user-plus me-1"></i> Follow
                                    </button> --}}
                                    <a href="{{ route('website.videos.index') }}?user={{ $video->user_id }}" 
                                       class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-video me-1"></i> View All Videos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="comments-section">
                    <h3 class="section-title">Comments</h3>

                    <!-- Comment Form -->
                    @auth
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Leave a Comment</h5>
                        </div>
                        <div class="card-body">
                            <form id="commentForm" action="{{ route('website.videos.comments.store', $video) }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent_id" id="parent_id" value="">
                                
                                <div class="form-group mb-3">
                                    <label for="message" class="form-label">Your Comment *</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" 
                                              placeholder="Share your thoughts about this video..." required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Post Comment
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="login-prompt mb-4">
                        <h5><i class="fas fa-user-circle me-2"></i>Join the Conversation</h5>
                        <p class="mb-3">Please log in to leave a comment and share your thoughts.</p>
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </div>
                    </div>
                    @endauth

                    <!-- Comments List -->
                    <div class="comments-list">
                        @if($video->comments->count() > 0)
                            @foreach($video->comments as $comment)
                            <div class="comment-card">
                                <div class="comment-header">
                                    <div>
                                        @if($comment->user && $comment->user->profile_picture)
                                        <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" 
                                             alt="{{ $comment->user->name }}" class="comment-avatar">
                                        @elseif($comment->name)
                                        <div class="comment-avatar-placeholder">
                                            {{ substr($comment->name, 0, 1) }}
                                        </div>
                                        @else
                                        <div class="comment-avatar-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="comment-author">
                                                    {{ $comment->user ? $comment->user->name : $comment->name }}
                                                </div>
                                                <div class="comment-time">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comment-content mt-2">
                                            {{ $comment->message }}
                                        </div>
                                        <div class="comment-actions mt-2">
                                            <!-- <button class="comment-action-btn like-comment-btn" data-comment-id="{{ $comment->id }}">
                                                <i class="fas fa-thumbs-up me-1"></i>
                                                <span class="like-count">{{ $comment->likes_count }}</span>
                                            </button> -->
                                             <!-- <button class="comment-action-btn reply-btn" data-comment-id="{{ $comment->id }}">
                                                <i class="fas fa-reply me-1"></i> Reply
                                            </button> -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Replies -->
                                @if($comment->replies->count() > 0)
                                <div class="replies-section">
                                    @foreach($comment->replies as $reply)
                                    <div class="comment-card" style="border: none; padding: 0.75rem;">
                                        <div class="comment-header">
                                            <div>
                                                @if($reply->user && $reply->user->profile_picture)
                                                <img src="{{ asset('storage/' . $reply->user->profile_picture) }}" 
                                                     alt="{{ $reply->user->name }}" 
                                                     style="width: 32px; height: 32px;" class="comment-avatar">
                                                @elseif($reply->name)
                                                <div class="comment-avatar-placeholder" style="width: 32px; height: 32px; font-size: 0.875rem;">
                                                    {{ substr($reply->name, 0, 1) }}
                                                </div>
                                                @else
                                                <div class="comment-avatar-placeholder" style="width: 32px; height: 32px; font-size: 0.875rem;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <div class="comment-author">
                                                            {{ $reply->user ? $reply->user->name : $reply->name }}
                                                        </div>
                                                        <div class="comment-time">
                                                            {{ $reply->created_at->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="comment-content mt-2">
                                                    {{ $reply->message }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endforeach
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No comments yet</h5>
                            <p class="text-muted">Be the first to comment on this video</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Related Videos -->
                @if($relatedVideos->count() > 0)
                <div class="related-videos-section mt-5">
                    <h3 class="section-title">Related Videos</h3>
                    <div class="row">
                        @foreach($relatedVideos as $relatedVideo)
                        <div class="col-md-6 mb-4">
                            <a href="{{ route('website.videos.show', $relatedVideo->slug) }}" 
                               class="card related-video-card text-decoration-none h-100">
                                <div class="video-thumbnail position-relative">
                                    @if($relatedVideo->video_id)
                                    <img src="https://img.youtube.com/vi/{{ $relatedVideo->video_id }}/mqdefault.jpg" 
                                         alt="{{ $relatedVideo->title }}">
                                    @else
                                    <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-video fa-2x text-muted"></i>
                                    </div>
                                    @endif
                                    <div class="video-play-icon">
                                        <i class="fas fa-play"></i>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($relatedVideo->category)
                                    <span class="badge bg-primary mb-2">
                                        {{ Str::limit($relatedVideo->category->name, 15) }}
                                    </span>
                                    @endif
                                    <h6 class="card-title mb-2">{{ Str::limit($relatedVideo->title, 60) }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="far fa-eye me-1"></i>{{ number_format($relatedVideo->views) }}
                                        </small>
                                        <small class="text-muted">
                                            {{ $relatedVideo->created_at->format('M j, Y') }}
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Share Video Widget -->
                {{-- <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-share-alt me-2"></i>Share This Video</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-outline-primary btn-social flex-fill" onclick="shareOnFacebook()">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </button>
                            <button class="btn btn-outline-info btn-social flex-fill" onclick="shareOnTwitter()">
                                <i class="fab fa-twitter"></i>
                                <span>Twitter</span>
                            </button>
                            <button class="btn btn-outline-danger btn-social flex-fill" onclick="shareOnWhatsApp()">
                                <i class="fab fa-whatsapp"></i>
                                <span>WhatsApp</span>
                            </button>
                        </div>
                    </div>
                </div> --}}

                <!-- More from Uploader -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2 text-primary"></i>
                            More from {{ $video->user->name }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                        $userVideos = App\Models\Video::where('user_id', $video->user_id)
                            ->where('id', '!=', $video->id)
                            ->where('status', 'published')
                            ->limit(5)
                            ->get();
                        @endphp

                        @if($userVideos->count() > 0)
                        @foreach($userVideos as $userVideo)
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0 position-relative">
                                @if($userVideo->video_id)
                                <img src="https://img.youtube.com/vi/{{ $userVideo->video_id }}/default.jpg" 
                                     alt="{{ $userVideo->title }}"
                                     class="rounded me-3"
                                     style="width: 80px; height: 60px; object-fit: cover;">
                                @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-3"
                                     style="width: 80px; height: 60px;">
                                    <i class="fas fa-video text-muted"></i>
                                </div>
                                @endif
                                <div class="video-play-icon" style="width: 30px; height: 30px;">
                                    <i class="fas fa-play" style="font-size: 12px;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('website.videos.show', $userVideo->slug) }}"
                                        class="text-dark text-decoration-none">
                                        {{ Str::limit($userVideo->title, 40) }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="far fa-eye me-1"></i>{{ number_format($userVideo->views) }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <p class="text-muted mb-0">No other videos from this uploader yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Popular Videos -->
                @if($popularVideos->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-fire me-2 text-danger"></i>
                            Popular Videos
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($popularVideos as $popularVideo)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 position-relative">
                                @if($popularVideo->video_id)
                                <img src="https://img.youtube.com/vi/{{ $popularVideo->video_id }}/default.jpg" 
                                     alt="{{ $popularVideo->title }}"
                                     class="rounded me-3"
                                     style="width: 60px; height: 45px; object-fit: cover;">
                                @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-3"
                                     style="width: 60px; height: 45px;">
                                    <i class="fas fa-video text-muted"></i>
                                </div>
                                @endif
                                <div class="video-play-icon" style="width: 20px; height: 20px;">
                                    <i class="fas fa-play" style="font-size: 8px;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1" style="font-size: 0.875rem;">
                                    <a href="{{ route('website.videos.show', $popularVideo->slug) }}"
                                        class="text-dark text-decoration-none">
                                        {{ Str::limit($popularVideo->title, 35) }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="far fa-eye me-1"></i>{{ number_format($popularVideo->views) }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Browse All Videos CTA -->
                <div class="card cta-card">
                    <div class="card-body">
                        <i class="fas fa-video"></i>
                        <h5>Explore More Videos</h5>
                        <p class="small opacity-75 mb-3">Discover more amazing content</p>
                        <a href="{{ route('website.videos.index') }}" class="btn btn-light btn-sm">
                            Browse All Videos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="reportReason" class="form-label">Reason for reporting</label>
                        <select class="form-select" id="reportReason">
                            <option selected>Select a reason</option>
                            <option value="inappropriate">Inappropriate content</option>
                            <option value="copyright">Copyright infringement</option>
                            <option value="spam">Spam or misleading</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reportDetails" class="form-label">Additional details</label>
                        <textarea class="form-control" id="reportDetails" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Submit Report</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Like functionality
        const likeBtn = document.getElementById('like-btn');
        const likeText = document.getElementById('like-text');
        const likesCountStat = document.getElementById('likes-count-stat'); // Changed ID

        function updateLikeUI(data) {
            // Update likes count in stats section
            const likesCountStat = document.getElementById('likes-count-stat');
            if (likesCountStat) {
                likesCountStat.textContent = data.likes_count;
            }
            
            // Update the like button
            if (likeBtn) {
                if (data.reaction) {
                    likeBtn.classList.remove('btn-outline-primary');
                    likeBtn.classList.add('btn-primary');
                    likeBtn.innerHTML = '<i class="fas fa-thumbs-up me-2"></i> <span id="like-text">Liked</span>';
                } else {
                    likeBtn.classList.remove('btn-primary');
                    likeBtn.classList.add('btn-outline-primary');
                    likeBtn.innerHTML = '<i class="fas fa-thumbs-up me-2"></i> <span id="like-text">Like</span>';
                }
            }
        }

        // Add click event listener
        likeBtn.addEventListener('click', function() {
            if (!{{ Auth::check() ? 'true' : 'false' }}) {
                window.location.href = '{{ route("login") }}';
                return;
            }

            fetch('{{ route("videos.reactions.store", $video) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reaction: 'like' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showToast(data.error, 'error');
                    return;
                }
                
                updateLikeUI(data);
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
            });
        });

        // Initialize button state on page load
        document.addEventListener('DOMContentLoaded', function() {
            // You might want to pass initial reaction state from backend
            @if(Auth::check())
                const userHasLiked = {{ $video->reactions()->where('user_id', Auth::id())->where('reaction', 'like')->exists() ? 'true' : 'false' }};
                if (userHasLiked) {
                    likeBtn.classList.remove('btn-outline-primary');
                    likeBtn.classList.add('btn-primary');
                    likeBtn.innerHTML = '<i class="fas fa-thumbs-up me-2"></i> <span id="like-text">Liked</span>';
                }
            @endif
        });

        // Share functionality
        window.shareVideo = function() {
            const shareData = {
                title: '{{ $video->title }}',
                text: 'Check out this video: {{ $video->title }}',
                url: window.location.href
            };
            
            if (navigator.share) {
                navigator.share(shareData)
                    .then(() => console.log('Shared successfully'))
                    .catch(error => console.log('Error sharing:', error));
            } else {
                // Fallback: Copy to clipboard
                navigator.clipboard.writeText(window.location.href)
                    .then(() => showToast('Link copied to clipboard!', 'success'))
                    .catch(() => {
                        // Alternative fallback
                        const tempInput = document.createElement('input');
                        tempInput.value = window.location.href;
                        document.body.appendChild(tempInput);
                        tempInput.select();
                        document.execCommand('copy');
                        document.body.removeChild(tempInput);
                        showToast('Link copied to clipboard!', 'success');
                    });
            }
        };

        // Comment like functionality
        document.querySelectorAll('.like-comment-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!{{ Auth::check() ? 'true' : 'false' }}) {
                    window.location.href = '{{ route("login") }}';
                    return;
                }

                const commentId = this.getAttribute('data-comment-id');
                const likeCount = this.querySelector('.like-count');

                fetch(`/video-comments/${commentId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRK-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        showToast(data.error, 'error');
                        return;
                    }
                    
                    likeCount.textContent = data.likes_count;
                    if (data.liked) {
                        this.classList.add('active');
                    } else {
                        this.classList.remove('active');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                });
            });
        });

        // Reply functionality
        document.querySelectorAll('.reply-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const commentId = this.getAttribute('data-comment-id');
                const commentItem = this.closest('.comment-item');
                const replyForm = document.createElement('form');
                
                replyForm.innerHTML = `
                    <div class="mt-3">
                        <textarea class="form-control mb-2" rows="2" placeholder="Write a reply..." required></textarea>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary cancel-reply">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-primary">Reply</button>
                        </div>
                    </div>
                `;

                // Remove existing reply form
                const existingForm = commentItem.querySelector('.reply-form');
                if (existingForm) {
                    existingForm.remove();
                }

                replyForm.classList.add('reply-form');
                commentItem.appendChild(replyForm);

                // Cancel reply
                replyForm.querySelector('.cancel-reply').addEventListener('click', function() {
                    replyForm.remove();
                });

                // Submit reply
                replyForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    if (!{{ Auth::check() ? 'true' : 'false' }}) {
                        window.location.href = '{{ route("login") }}';
                        return;
                    }

                    const message = this.querySelector('textarea').value;
                    
                    fetch('{{ route("website.videos.comments.store", $video) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message: message,
                            parent_id: commentId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Reply added successfully!', 'success');
                            location.reload();
                        } else {
                            showToast('Error adding reply', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred. Please try again.', 'error');
                    });
                });
            });
        });

        // Save functionality
        const saveBtn = document.getElementById('save-btn');
        saveBtn.addEventListener('click', function() {
            if (!{{ Auth::check() ? 'true' : 'false' }}) {
                window.location.href = '{{ route("login") }}';
                return;
            }

            this.classList.toggle('active');
            if (this.classList.contains('active')) {
                this.innerHTML = '<i class="fas fa-bookmark"></i> Saved';
                showToast('Video saved to your collection', 'success');
            } else {
                this.innerHTML = '<i class="fas fa-bookmark"></i> Save';
                showToast('Video removed from your collection', 'info');
            }
        });

        // Toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
            toast.style.zIndex = '1060';

            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-circle me-2"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;

            document.body.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            toast.addEventListener('hidden.bs.toast', function() {
                document.body.removeChild(toast);
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