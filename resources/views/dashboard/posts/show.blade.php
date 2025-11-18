<x-app-layout>
    <main class="main-content">
        <section id="blog-show" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">{{ $blogPost->title }}</h2>
                    <p class="mb-0 text-muted">Blog post details and statistics</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('blog.show', [$blogPost->category?->slug, $blogPost->slug]) }}" 
                       class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-eye me-2"></i> View Live
                    </a>
                    <a href="{{ route('admin.blog-posts.edit', $blogPost) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Edit
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            @if($blogPost->featured_image)
                            <img src="{{ Storage::url($blogPost->featured_image) }}" alt="{{ $blogPost->title }}" 
                                 class="img-fluid rounded mb-4">
                            @endif
                            
                            <div class="mb-4">
                                {!! $blogPost->content !!}
                            </div>
                            
                            @if($blogPost->tags && count($blogPost->tags) > 0)
                            <div class="mb-4">
                                <strong>Tags:</strong>
                                @foreach($blogPost->tags as $tag)
                                <span class="badge bg-secondary me-1">{{ $tag }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Comments ({{ $blogPost->comments->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @forelse($blogPost->comments->where('parent_id', null) as $comment)
                            <div class="comment mb-4">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            {{ substr($comment->name ?? $comment->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $comment->name ?? $comment->user->name }}</h6>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-2">{{ $comment->comment }}</p>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-{{ $comment->status === 'approved' ? 'success' : ($comment->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($comment->status) }}
                                            </span>
                                        </div>
                                        
                                        <!-- Replies -->
                                        @foreach($comment->replies as $reply)
                                        <div class="d-flex mt-3">
                                            <div class="flex-shrink-0">
                                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 30px; height: 30px;">
                                                    {{ substr($reply->name ?? $reply->user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0 small">{{ $reply->name ?? $reply->user->name }}</h6>
                                                    <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-0 small">{{ $reply->comment }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted text-center">No comments yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Post Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $blogPost->status === 'published' ? 'success' : ($blogPost->status === 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($blogPost->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $blogPost->category->name ?? 'Uncategorized' }}</td>
                                </tr>
                                <tr>
                                    <th>School:</th>
                                    <td>{{ $blogPost->school->name ?? 'General' }}</td>
                                </tr>
                                <tr>
                                    <th>Author:</th>
                                    <td>{{ $blogPost->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Published:</th>
                                    <td>{{ $blogPost->published_at?->format('M j, Y g:i A') ?? 'Not published' }}</td>
                                </tr>
                                <tr>
                                    <th>Read Time:</th>
                                    <td>{{ $blogPost->read_time }} min</td>
                                </tr>
                                <tr>
                                    <th>Views:</th>
                                    <td>{{ $blogPost->view_count }}</td>
                                </tr>
                                <tr>
                                    <th>Featured:</th>
                                    <td>
                                        @if($blogPost->is_featured)
                                        <i class="fas fa-star text-warning"></i> Featured
                                        @else
                                        No
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($blogPost->excerpt)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Excerpt</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $blogPost->excerpt }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($blogPost->meta_title || $blogPost->meta_description)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">SEO Meta</h5>
                        </div>
                        <div class="card-body">
                            @if($blogPost->meta_title)
                            <p><strong>Title:</strong> {{ $blogPost->meta_title }}</p>
                            @endif
                            @if($blogPost->meta_description)
                            <p><strong>Description:</strong> {{ $blogPost->meta_description }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
</x-app-layout>