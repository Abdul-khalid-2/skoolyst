@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== BLOG POST STYLES ==================== */
    .blog-post-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 80px 0 40px;
    }

    .blog-post-title {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1.2;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
    }

    .blog-post-meta {
        border-left: 4px solid #4361ee;
        padding-left: 1rem;
    }

    .blog-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
    }

    .blog-content h2 {
        margin-top: 3rem;
        margin-bottom: 1.5rem;
        color: #1a1a1a;
        font-weight: 700;
    }

    .blog-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #1a1a1a;
        font-weight: 600;
    }

    .blog-content p {
        margin-bottom: 1.5rem;
    }

    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 15px;
        margin: 2rem 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .blog-content blockquote {
        border-left: 4px solid #4361ee;
        padding-left: 2rem;
        margin: 2rem 0;
        font-style: italic;
        color: #666;
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 0 15px 15px 0;
    }

    .author-bio {
        background: linear-gradient(135deg, #4361ee15, #38b00015);
        border-radius: 15px;
        padding: 2rem;
        margin-top: 3rem;
    }

    .related-post-card {
        transition: transform 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .related-post-card:hover {
        transform: translateY(-5px);
    }

    /* ==================== COMMENTS SECTION STYLES ==================== */
    .comments-section {
        margin-top: 4rem;
        padding-top: 3rem;
        border-top: 1px solid #e9ecef;
    }

    .comments-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .comments-count {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .comment-form-card {
        background: #f8f9fa;
        border-radius: 15px;
        border: none;
        margin-bottom: 3rem;
    }

    .comment-form-header {
        background: linear-gradient(135deg, #4361ee, #3a56d4);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 1.5rem;
    }

    .comment-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e9ecef;
    }

    .comment-card {
        border: none;
        border-radius: 15px;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease;
    }

    .comment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .comment-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .comment-author {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.25rem;
    }

    .comment-date {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .comment-content {
        color: #333;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .comment-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .reply-btn, .like-btn {
        background: none;
        border: none;
        color: #4361ee;
        font-size: 0.875rem;
        cursor: pointer;
        transition: color 0.2s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 5px;
    }

    .reply-btn:hover, .like-btn:hover {
        background: #4361ee15;
        color: #3a56d4;
    }

    .like-count {
        font-size: 0.875rem;
        color: #6c757d;
        margin-left: 0.25rem;
    }

    .replies-section {
        margin-left: 3rem;
        margin-top: 1rem;
        padding-left: 2rem;
        border-left: 3px solid #e9ecef;
    }

    .reply-form {
        margin-top: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
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

    .no-comments {
        text-align: center;
        padding: 3rem 2rem;
        background: #f8f9fa;
        border-radius: 15px;
        color: #6c757d;
    }

    .comment-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .star-rating {
        color: #ffc107;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, #4361ee, #3a56d4);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .login-prompt {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        border: 1px solid #ffecb5;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 2rem;
    }

    /* ==================== RESPONSIVE DESIGN ==================== */
    @media (max-width: 768px) {
        .blog-post-title {
            font-size: 2rem;
        }
        
        .blog-post-header {
            padding: 60px 0 30px;
        }

        .comments-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .replies-section {
            margin-left: 1rem;
            padding-left: 1rem;
        }

        .comment-avatar {
            width: 40px;
            height: 40px;
        }

        .comment-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .comment-actions {
            width: 100%;
            justify-content: flex-start;
        }
    }

    @media (max-width: 576px) {
        .replies-section {
            margin-left: 0.5rem;
            padding-left: 0.5rem;
        }

        .comment-form-card .row {
            flex-direction: column;
        }

        .comment-form-card .col-md-6 {
            width: 100%;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== BLOG POST HEADER ==================== -->
<section class="blog-post-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('website.blog.index') }}">Blog</a></li>
                        @if($post->category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('website.blog.category', $post->category->slug) }}">
                                {{ $post->category->name }}
                            </a>
                        </li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">Article</li>
                    </ol>
                </nav>

                @if($post->category)
                <a href="{{ route('website.blog.category', $post->category->slug) }}"
                    class="badge category-badge text-decoration-none mb-3">
                    {{ $post->category->name }}
                </a>
                @endif

                <h1 class="blog-post-title">{{ $post->title }}</h1>

                <div class="blog-post-meta">
                    <div class="d-flex align-items-center mb-3">
                        @if($post->author && $post->author->profile_image)
                        <img src="{{ asset('website/' . $post->author->profile_image) }}"
                            alt="{{ $post->author->name }}"
                            class="rounded-circle me-3"
                            style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px;">
                            <i class="fas fa-user"></i>
                        </div>
                        @endif
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $post->author->name ?? 'Admin' }}</h6>
                            <small class="text-muted">
                                {{ $post->published_at->format('F j, Y') }} ·
                                {{ $post->view_count }} views
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== BLOG POST CONTENT ==================== -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Main Content -->
            <div class="col-lg-8">
                @if($post->featured_image)
                <img src="{{ asset('website/' . $post->featured_image) }}"
                    alt="{{ $post->title }}"
                    class="img-fluid rounded w-100 mb-5"
                    style="max-height: 500px; object-fit: cover;">
                @endif

                <!-- Blog Content -->
                <article class="blog-content">
                    @if($post->excerpt)
                    <div class="lead mb-5 p-4 bg-light rounded">
                        <strong>{{ $post->excerpt }}</strong>
                    </div>
                    @endif

                    <div class="content">
                        {!! $post->content !!}
                    </div>

                    <!-- Tags -->
                    @if($post->tags && count($post->tags) > 0)
                        <div class="mt-5 pt-4 border-top">
                            <h6 class="mb-3 fw-bold">Tags:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($post->tags as $tag)
                                    @if(!empty(trim($tag)))
                                    <a href="{{ route('website.blog.tag', $tag) }}" 
                                    class="badge bg-light text-dark text-decoration-none">
                                        #{{ $tag }}
                                    </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Author Bio -->
                    @if($post->author)
                    <div class="author-bio mt-5">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center mb-3 mb-md-0">
                                @if($post->author->profile_image)
                                <img src="{{ asset('website/' . $post->author->profile_image) }}"
                                    alt="{{ $post->author->name }}"
                                    class="rounded-circle"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 100px; height: 100px;">
                                    <i class="fas fa-user fa-2x"></i>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h4>About {{ $post->author->name }}</h4>
                                @if($post->author->bio)
                                <p class="mb-3">{{ $post->author->bio }}</p>
                                @endif
                                <div class="d-flex gap-3">
                                    @if($post->author->email)
                                    <a href="mailto:{{ $post->author->email }}" class="text-primary">
                                        <i class="fas fa-envelope me-1"></i>Email
                                    </a>
                                    @endif
                                    @if($post->author->website)
                                    <a href="{{ $post->author->website }}" target="_blank" class="text-primary">
                                        <i class="fas fa-globe me-1"></i>Website
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </article>

                <!-- Comments Section -->
                <div class="comments-section">
                    <div class="comments-header">
                        <h3 class="comments-count">
                            <i class="fas fa-comments me-2"></i>
                            Comments ({{ $post->comments->where('status', 'approved')->count() }})
                        </h3>
                    </div>

                    <!-- Comment Form -->
                    @auth
                    <div class="card comment-form-card">
                        <div class="comment-form-header">
                            <h5 class="mb-0">
                                <i class="fas fa-edit me-2"></i>
                                Leave a Comment
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="commentForm" action="{{ route('website.blog.comment.store', $post->slug) }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent_id" id="parent_id" value="">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Your Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="{{ auth()->user()->name }}" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email Address *</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="{{ auth()->user()->email }}" required readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="comment" class="form-label">Your Comment *</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="5" 
                                              placeholder="Share your thoughts..." required></textarea>
                                </div>

                                <!-- Rating System (Optional) -->
                                {{-- <div class="form-group">
                                    <label class="form-label">Rate this article (optional)</label>
                                    <div class="comment-rating">
                                        <div class="star-rating">
                                            @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                                            <label for="star{{ $i }}" class="star">
                                                <i class="fas fa-star"></i>
                                            </label>
                                            @endfor
                                        </div>
                                    </div>
                                </div> --}}

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Post Comment
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="login-prompt">
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
                        @if($post->comments->where('status', 'approved')->whereNull('parent_id')->count() > 0)
                            @foreach($post->comments->where('status', 'approved')->whereNull('parent_id')->sortByDesc('created_at') as $comment)
                                @include('website.blog.partials.comment', ['comment' => $comment])
                            @endforeach
                        @else
                            <div class="no-comments">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <h5>No comments yet</h5>
                                <p class="text-muted">Be the first to share your thoughts!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <div class="mt-5 pt-5 border-top">
                    <h3 class="mb-4 fw-bold">Related Articles</h3>
                    <div class="row">
                        @foreach($relatedPosts as $relatedPost)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card related-post-card shadow-sm h-100">
                                @if($relatedPost->featured_image)
                                <img src="{{ asset('website/' . $relatedPost->featured_image) }}"
                                    class="card-img-top" alt="{{ $relatedPost->title }}"
                                    style="height: 150px; object-fit: cover;">
                                @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                    style="height: 150px;">
                                    <i class="fas fa-newspaper fa-2x text-muted"></i>
                                </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="{{ route('website.blog.show', $relatedPost->slug) }}"
                                            class="text-dark text-decoration-none">
                                            {{ Str::limit($relatedPost->title, 60) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        {{ $relatedPost->published_at->format('M j, Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                @include('website.blog.partials.sidebar', [
                    'popularPosts' => $popularPosts,
                    'categories' => $categories,
                    'tags' => $tags
                ])
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Star rating functionality
        const stars = document.querySelectorAll('.star-rating input');
        stars.forEach(star => {
            star.addEventListener('change', function() {
                const rating = this.value;
                // You can handle the rating value here
                console.log('Selected rating:', rating);
            });
        });

        // Reply functionality
        const replyButtons = document.querySelectorAll('.reply-btn');
        replyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const commentId = this.getAttribute('data-comment-id');
                const replyForm = document.getElementById(`reply-form-${commentId}`);
                
                // Hide all other reply forms
                document.querySelectorAll('.reply-form').forEach(form => {
                    if (form.id !== `reply-form-${commentId}`) {
                        form.classList.remove('active');
                    }
                });
                
                // Toggle current reply form
                replyForm.classList.toggle('active');
                
                // Set parent_id for the reply
                document.getElementById('parent_id').value = commentId;
                
                // Scroll to comment form
                if (replyForm.classList.contains('active')) {
                    document.getElementById('commentForm').scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'center'
                    });
                    document.getElementById('comment').focus();
                }
            });
        });

        // Comment form submission
        const commentForm = document.getElementById('commentForm');
        if (commentForm) {
            commentForm.addEventListener('submit', function(e) {
                const commentText = document.getElementById('comment').value.trim();
                if (commentText === '') {
                    e.preventDefault();
                    alert('Please enter your comment before submitting.');
                    return;
                }
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Posting...';
                submitBtn.disabled = true;
            });
        }

        // Like functionality
        const likeButtons = document.querySelectorAll('.like-btn');
        likeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const commentId = this.getAttribute('data-comment-id');
                // Implement like functionality via AJAX
                this.classList.toggle('liked');
                if (this.classList.contains('liked')) {
                    this.innerHTML = '<i class="fas fa-thumbs-up me-1"></i>Liked';
                } else {
                    this.innerHTML = '<i class="far fa-thumbs-up me-1"></i>Like';
                }
            });
        });

        // Auto-resize textarea
        const commentTextarea = document.getElementById('comment');
        if (commentTextarea) {
            commentTextarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }
    });

    // Function to cancel reply
    function cancelReply(commentId) {
        document.getElementById(`reply-form-${commentId}`).classList.remove('active');
        document.getElementById('parent_id').value = '';
    }
</script>

<style>
    /* Additional styles for star rating */
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #ddd;
        transition: color 0.2s ease;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #ffc107;
    }

    .star-rating input:checked + label {
        color: #ffc107;
    }

    /* Like button styles */
    .like-btn.liked {
        color: #4361ee;
        font-weight: 600;
    }

    /* Smooth transitions */
    .comment-card,
    .reply-form,
    .btn {
        transition: all 0.3s ease;
    }
</style>
@endpush