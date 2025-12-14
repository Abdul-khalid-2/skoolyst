@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== BLOG HEADER SECTION ==================== */
    .category-header {
        background: linear-gradient(135deg, #4361ee 0%, #38b000 100%);
        color: white;
        padding: 100px 0 60px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .category-header::before {
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

    .category-hero-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    }

    .category-breadcrumb {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 10px 25px;
        display: inline-block;
        margin-top: 20px;
    }

    .category-breadcrumb a {
        color: white;
        text-decoration: none;
        transition: opacity 0.3s ease;
    }

    .category-breadcrumb a:hover {
        opacity: 0.8;
    }

    .category-info {
        margin-top: 30px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        backdrop-filter: blur(10px);
    }

    /* ==================== BLOG CONTENT STYLES ==================== */
    .blog-search-form .form-control {
        border: none;
        border-radius: 50px;
        padding: 15px 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .blog-search-form .btn {
        border: none;
        border-radius: 50px;
        padding: 15px 30px;
        margin-left: 10px;
    }

    .blog-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        height: 100%;
    }

    .blog-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .blog-card .card-img-top {
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .blog-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .blog-card .card-title a {
        color: #1a1a1a;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .blog-card .card-title a:hover {
        color: #4361ee !important;
    }

    .category-badge {
        background: linear-gradient(135deg, #4361ee, #38b000);
        border: none;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .current-category-badge {
        background: linear-gradient(135deg, #38b000, #4361ee);
        color: white;
        font-size: 1rem;
        font-weight: 600;
        padding: 8px 20px;
        border-radius: 50px;
        display: inline-block;
        margin-bottom: 20px;
    }

    /* ==================== SIDEBAR STYLES ==================== */
    .sidebar-widget {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .sidebar-widget .card-header {
        background: linear-gradient(135deg, #4361ee, #38b000);
        color: white;
        border: none;
        font-weight: 700;
    }

    .sidebar-widget .list-group-item {
        border: none;
        padding: 0.75rem 0;
        background: transparent;
    }

    .sidebar-widget .list-group-item:hover {
        background: transparent;
        color: #4361ee;
    }

    .sidebar-widget .list-group-item.active {
        background: linear-gradient(135deg, #4361ee, #38b000);
        color: white;
        border: none;
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

    /* ==================== RESPONSIVE DESIGN ==================== */
    @media (max-width: 768px) {
        .category-hero-title {
            font-size: 2.2rem;
        }

        .blog-search-form .btn {
            width: 100%;
            margin-left: 0;
            margin-top: 10px;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== CATEGORY HERO SECTION ==================== -->
<section class="category-header">
    <div class="container">
        <nav aria-label="breadcrumb" class="category-breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('website.home') }}"><i class="fas fa-home me-1"></i>Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('website.blog.index') }}"><i class="fas fa-newspaper me-1"></i>Blog</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-folder me-1"></i>{{ $category->name }}
                </li>
            </ol>
        </nav>

        <h1 class="category-hero-title mt-4">{{ $category->name }}</h1>
        
        <div class="category-info">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <div class="d-flex justify-content-center gap-4">
                        <span class="text-white">
                            <i class="fas fa-file-alt me-1"></i>
                            {{ $posts->total() }} {{ Str::plural('article', $posts->total()) }}
                        </span>
                        <span class="text-white">
                            <i class="fas fa-eye me-1"></i>
                            {{ $category->blogPosts->sum('view_count') }} total views
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Form -->
        <form action="{{ route('website.blog.category', $category->slug) }}" method="GET" class="blog-search-form mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search within {{ $category->name }} articles..."
                            value="{{ request('search') }}">
                        <button class="btn btn-light" type="submit">
                            <i class="fas fa-search me-2"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- ==================== CATEGORY CONTENT SECTION ==================== -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Current Category Badge -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="current-category-badge">
                            <i class="fas fa-folder me-2"></i>{{ $category->name }}
                        </span>
                        <span class="text-muted ms-3">
                            Showing {{ $posts->count() }} of {{ $posts->total() }} articles
                        </span>
                    </div>
                    
                    <!-- Sort Options -->
                    {{-- <div class="btn-group">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}"
                            class="btn btn-outline-primary btn-sm {{ request('sort', 'latest') === 'latest' ? 'active' : '' }}">
                            <i class="fas fa-clock me-1"></i> Latest
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}"
                            class="btn btn-outline-primary btn-sm {{ request('sort') === 'popular' ? 'active' : '' }}">
                            <i class="fas fa-fire me-1"></i> Popular
                        </a>
                    </div> --}}
                </div>

                <!-- Blog Posts Grid -->
                @if($posts->count() > 0)
                <div class="row">
                    @foreach($posts as $post)
                    <div class="col-md-6 mb-4">
                        <article class="blog-card card h-100 shadow-sm">
                            @if($post->featured_image)
                            <img src="{{ asset('website/' . $post->featured_image) }}"
                                class="card-img-top" alt="{{ $post->title }}">
                            @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                style="height: 250px;">
                                <i class="fas fa-newspaper fa-3x text-muted"></i>
                            </div>
                            @endif

                            <div class="card-body">
                                <a href="{{ route('website.blog.category', $post->category->slug) }}"
                                    class="badge category-badge text-decoration-none mb-2">
                                    {{ $post->category->name }}
                                </a>

                                <h5 class="card-title">
                                    <a href="{{ route('website.blog.show', $post->slug) }}"
                                        class="text-dark text-decoration-none">
                                        {{ Str::limit($post->title, 60) }}
                                    </a>
                                </h5>

                                <p class="card-text text-muted">
                                    {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 120) }}
                                </p>
                            </div>

                            <div class="card-footer bg-transparent border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        @if($post->author && $post->author->profile_image)
                                        <img src="{{ asset('website/' . $post->author->profile_image) }}"
                                            alt="{{ $post->author->name }}"
                                            class="rounded-circle me-2"
                                            style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                            style="width: 32px; height: 32px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        @endif
                                        <small class="text-muted">
                                            {{ $post->author->name ?? 'Admin' }}
                                        </small>
                                    </div>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $post->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">
                                        {{ $post->published_at->format('M j, Y') }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="far fa-eye me-1"></i>{{ $post->view_count }}
                                    </small>
                                </div>
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                <div class="mt-5">
                    <nav>
                        {{ $posts->withQueryString()->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
                @endif

                @else
                <!-- No Posts Found -->
                <div class="text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-folder-open fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No articles found in this category</h4>
                    <p class="text-muted mb-4">There are no published articles in the "{{ $category->name }}" category yet.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('website.blog.index') }}" class="btn btn-primary">
                            <i class="fas fa-newspaper me-2"></i>View All Articles
                        </a>
                        <a href="{{ route('website.blog.category', $category->slug) }}" class="btn btn-outline-primary">
                            <i class="fas fa-sync me-2"></i>Refresh
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Categories Widget -->
                <div class="sidebar-widget card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-folder me-2"></i>All Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($categories as $cat)
                            <a href="{{ route('website.blog.category', $cat->slug) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                                {{ $cat->id == $category->id ? 'active' : '' }}">
                                <div>
                                    <i class="fas fa-folder me-2"></i>
                                    {{ $cat->name }}
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $cat->posts_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Popular Posts Widget -->
                <div class="sidebar-widget card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Popular Articles</h5>
                    </div>
                    <div class="card-body">
                        @foreach($popularPosts as $popularPost)
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            @if($popularPost->featured_image)
                            <img src="{{ asset('website/' . $popularPost->featured_image) }}"
                                alt="{{ $popularPost->title }}"
                                class="flex-shrink-0 me-3 rounded"
                                style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                            <div class="flex-shrink-0 me-3 bg-light rounded d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="fas fa-newspaper text-muted"></i>
                            </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('website.blog.show', $popularPost->slug) }}"
                                        class="text-dark text-decoration-none">
                                        {{ Str::limit($popularPost->title, 50) }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    {{ $popularPost->published_at->format('M j') }} ·
                                    <i class="far fa-eye me-1"></i>{{ $popularPost->view_count }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tags Widget -->
                @if($tags->count() > 0)
                <div class="sidebar-widget card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Popular Tags</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                                @if(!empty(trim($tag)))
                                <a href="{{ route('website.blog.tag', $tag) }}" 
                                class="badge bg-light text-dark text-decoration-none">
                                    #{{ $tag }}
                                </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Back to Blog -->
                <div class="sidebar-widget card shadow-sm bg-light">
                    <div class="card-body text-center">
                        <i class="fas fa-arrow-left fa-2x mb-3 text-primary"></i>
                        <h5>Explore All Articles</h5>
                        <p class="small text-muted">Browse articles from all categories</p>
                        <a href="{{ route('website.blog.index') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-newspaper me-2"></i>View All Blog Posts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Add active class to current category in sidebar
    document.addEventListener('DOMContentLoaded', function() {
        const currentCategoryId = {{ $category->id }};
        const categoryLinks = document.querySelectorAll('.list-group-item[href*="category"]');
        
        categoryLinks.forEach(link => {
            if (link.textContent.includes('{{ $category->name }}')) {
                link.classList.add('active');
            }
        });
    });
</script>
@endpush