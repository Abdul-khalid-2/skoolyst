<x-app-layout>
    <main class="main-content">
        <section id="blog-posts" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Blog Posts</h2>
                    <p class="mb-0 text-muted">Manage your blog posts and content</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('admin.blog-posts.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i> Create Post
                    </x-button>
                </x-slot>
            </x-page-header>

            <x-card>
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
                                        @php
                                            $postStatusVariant = $post->status?->value === 'published' ? 'success' : ($post->status?->value === 'draft' ? 'warning' : 'secondary');
                                        @endphp
                                        <x-badge :variant="$postStatusVariant">
                                            {{ $post->status_label }}
                                        </x-badge>
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
                                            <x-button
                                                href="{{ route('admin.blog-posts.show', ['blog_post' => $post->slug]) }}"
                                                variant="outline-primary"
                                                class="btn-sm"
                                                target="_blank"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </x-button>
                                            <x-button href="{{ route('admin.blog-posts.edit', $post) }}" variant="outline-secondary" class="btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </x-button>
                                            <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <x-button type="submit" variant="outline-danger" class="btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </x-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <x-empty-state title="No blog posts found." icon="fa-newspaper" class="py-4" />
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $posts->links() }}
                </div>
            </x-card>
        </section>
    </main>
</x-app-layout>
