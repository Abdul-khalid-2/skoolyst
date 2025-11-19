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

    /* ==================== RESPONSIVE DESIGN ==================== */
    @media (max-width: 768px) {
        .blog-post-title {
            font-size: 2rem;
        }
        
        .blog-post-header {
            padding: 60px 0 30px;
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

                <!-- Comments Section -->
                <div class="mt-5 pt-5 border-top">
                    <h3 class="mb-4 fw-bold">Comments</h3>
                    <!-- You can integrate a comments system here -->
                    <div class="text-center py-4 bg-light rounded">
                        <i class="fas fa-comments fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Comments feature coming soon!</p>
                    </div>
                </div>
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