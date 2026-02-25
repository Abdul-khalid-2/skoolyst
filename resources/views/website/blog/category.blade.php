@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/blog.css') }}">
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