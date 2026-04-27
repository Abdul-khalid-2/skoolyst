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

            @if (session('success'))
                <x-alert variant="success" class="mb-4">
                    {{ session('success') }}
                </x-alert>
            @endif

            <x-data-table
                class="mb-0"
                :headers="[
                    ['label' => 'Title', 'key' => 'title', 'sortable' => true],
                    ['label' => 'Category', 'key' => 'category', 'sortable' => true],
                    ['label' => 'Author', 'key' => 'author', 'sortable' => true],
                    ['label' => 'Status', 'key' => 'status', 'sortable' => true],
                    ['label' => 'Featured', 'key' => 'is_featured', 'sortable' => true],
                    ['label' => 'Views', 'key' => 'view_count', 'sortable' => true],
                    ['label' => 'Published At', 'key' => 'published_at', 'sortable' => true],
                    ['label' => 'Actions', 'key' => 'actions', 'sortable' => false],
                ]"
                :records="$posts"
                :sortBy="request('sort_by')"
                :sortDir="request('sort_dir', 'desc')"
                :searchValue="request('search')"
                emptyTitle="No blog posts found"
                emptyDescription="Create your first post to get started."
                emptyIcon="fa-newspaper"
            >
                <x-slot name="emptyActions">
                    <x-button href="{{ route('admin.blog-posts.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i>Create Post
                    </x-button>
                </x-slot>
                <x-slot name="rows">
                    @foreach ($posts as $post)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($post->featured_image)
                                        <img
                                            src="{{ asset('website/' . $post->featured_image) }}"
                                            alt="{{ $post->title }}"
                                            class="rounded me-3"
                                            style="width: 40px; height: 30px; object-fit: cover;"
                                        >
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
                                @if ($post->is_featured)
                                    <i class="fas fa-star text-warning" aria-hidden="true"></i>
                                @endif
                            </td>
                            <td>{{ $post->view_count }}</td>
                            <td>{{ $post->published_at?->format('M j, Y') ?? '-' }}</td>
                            <td class="text-end">
                                <x-table-action>
                                    <x-table-action-item
                                        href="{{ route('admin.blog-posts.show', ['blog_post' => $post->slug]) }}"
                                        icon="fa-eye"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        View
                                    </x-table-action-item>
                                    <x-table-action-item href="{{ route('admin.blog-posts.edit', $post) }}" icon="fa-edit">
                                        Edit
                                    </x-table-action-item>
                                    <x-table-action-item
                                        href="{{ route('admin.blog-posts.destroy', $post) }}"
                                        icon="fa-trash"
                                        variant="danger"
                                        method="DELETE"
                                        onclick="return confirm('Are you sure?')"
                                    >
                                        Delete
                                    </x-table-action-item>
                                </x-table-action>
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-data-table>
        </section>
    </main>
</x-app-layout>
