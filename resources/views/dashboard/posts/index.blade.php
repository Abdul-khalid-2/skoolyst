<x-app-layout>
    <main class="main-content">
        <section id="blog-posts" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Blog Posts</h2>
                    <p class="mb-0 text-muted">Manage your blog posts and content</p>
                </div>
                <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Create Post
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th>Views</th>
                                    <th>Published At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $post)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($post->featured_image)
                                                <img src="{{ asset('website/' . $post->featured_image) }}" alt="{{ $post->title }}" 
                                                    class="rounded me-3" style="width: 40px; height: 30px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ Str::limit($post->title, 30) }}</h6>
                                                <small class="text-muted">{{ Str::limit($post->slug, 30) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $post->category->name ?? 'Uncategorized' }}</td>
                                    <td>{{ $post->user->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $post->status?->value === 'published' ? 'success' : ($post->status?->value === 'draft' ? 'warning' : 'secondary') }}">
                                            {{ $post->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($post->is_featured)
                                        <i class="fas fa-star text-warning"></i>
                                        @endif
                                    </td>
                                    <td>{{ $post->view_count }}</td>
                                    <td>{{ $post->published_at?->format('M j, Y') ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.blog-posts.show', ['blog_post' => $post->slug]) }}" 
                                               class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.blog-posts.edit', $post) }}" 
                                               class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No blog posts found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $posts->links() }}
                </div>
            </div>
        </section>
    </main>
</x-app-layout>