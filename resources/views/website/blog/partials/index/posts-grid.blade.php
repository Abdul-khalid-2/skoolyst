@if($posts->count() > 0)
<div class="row">
    @foreach($posts as $post)
    <div class="col-md-6 mb-4">
        <article class="blog-card card h-100 shadow-sm" itemscope itemtype="https://schema.org/BlogPosting">
            @if($post->featured_image)
            <img src="{{ asset('website/' . $post->featured_image) }}"
                class="card-img-top" alt="{{ $post->title }}" itemprop="image">
            @else
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center empty-blog-thumb">
                <i class="fas fa-newspaper fa-3x text-muted"></i>
            </div>
            @endif

            <div class="card-body">
                @if($post->category)
                <a href="{{ route('website.blog.category', $post->category->slug) }}"
                    class="badge category-badge text-decoration-none mb-2">
                    {{ $post->category->name }}
                </a>
                @endif

                <h2 class="card-title h5" itemprop="headline">
                    <a href="{{ route('website.blog.show', $post->slug) }}"
                        class="text-dark text-decoration-none" itemprop="url">
                        {{ Str::limit($post->title, 60) }}
                    </a>
                </h2>

                <p class="card-text text-muted" itemprop="description">
                    {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 120) }}
                </p>
            </div>

            <div class="card-footer bg-transparent border-top-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        @if($post->author && $post->author->profile_image)
                        <img src="{{ asset('website/' . $post->author->profile_image) }}"
                            alt="{{ $post->author->name }}"
                            class="rounded-circle me-2 author-thumb">
                        @else
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2 author-thumb">
                            <i class="fas fa-user"></i>
                        </div>
                        @endif
                        <small class="text-muted" itemprop="author">{{ $post->author->name ?? 'Admin' }}</small>
                    </div>
                    <small class="text-muted">
                        <i class="far fa-clock me-1"></i>{{ $post->created_at->diffForHumans() }}
                    </small>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <small class="text-muted" itemprop="datePublished">{{ $post->published_at->format('M j, Y') }}</small>
                    <small class="text-muted"><i class="far fa-eye me-1"></i>{{ $post->view_count }}@include('website.blog.partials.post-card-tracked-read', ['post' => $post])</small>
                </div>
            </div>
        </article>
    </div>
    @endforeach
</div>

@if($posts->hasPages())
<div class="mt-5">
    <nav aria-label="Blog posts pagination">
        {{ $posts->links('pagination::bootstrap-5') }}
    </nav>
</div>
@endif
@else
<div class="text-center py-5">
    <i class="fas fa-search fa-3x text-muted mb-3"></i>
    <h4 class="text-muted">No articles found</h4>
    <p class="text-muted">Try adjusting your search or filters</p>
    <a href="{{ route('website.blog.index') }}" class="btn btn-primary">
        <i class="fas fa-newspaper me-2"></i>View All Articles
    </a>
</div>
@endif
