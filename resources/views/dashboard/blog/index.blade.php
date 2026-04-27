<x-app-layout>
    <main class="main-content">
        <section id="blog-categories" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Blog Categories</h2>
                    <p class="mb-0 text-muted">Manage your blog categories</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('admin.blog-categories.create') }}" variant="primary">
                        <i class="fas fa-plus me-2"></i> Create Category
                    </x-button>
                </x-slot>
            </x-page-header>

            @if(session('success'))
                <x-alert variant="success" class="mb-4">
                    {{ session('success') }}
                </x-alert>
            @endif

            <x-card>
                <div class="card-body">
                    <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Icon</th>
                                    <th>Status</th>
                                    <th>Posts</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($category->icon)
                                            <i class="fas fa-{{ $category->icon }} text-primary me-2"></i>
                                            @endif
                                            <strong>{{ $category->name }}</strong>
                                        </div>
                                        @if($category->description)
                                        <small class="text-muted">{{ Str::limit($category->description, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $category->slug }}</code>
                                    </td>
                                    <td>
                                        @if($category->icon)
                                        <i class="fas fa-{{ $category->icon }} text-muted"></i>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <x-badge :variant="$category->is_active ? 'success' : 'secondary'">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </x-badge>
                                    </td>
                                    <td>
                                        <x-badge variant="info">
                                            {{ $category->blog_posts_count ?? 0 }}
                                        </x-badge>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $category->created_at->format('M j, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <x-button href="{{ route('admin.blog-categories.edit', $category) }}"
                                               variant="outline-secondary" class="btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </x-button>
                                            <form action="{{ route('admin.blog-categories.destroy', $category) }}" method="POST"
                                                  class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
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
                                    <td colspan="7" class="text-center py-4">
                                        <x-empty-state
                                            title="No categories found"
                                            description="Create your first category to get started."
                                            icon="fa-folder-open"
                                        >
                                            <x-slot name="actions">
                                                <x-button href="{{ route('admin.blog-categories.create') }}" variant="primary">
                                                    <i class="fas fa-plus me-2"></i>Create Category
                                                </x-button>
                                            </x-slot>
                                        </x-empty-state>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $categories->links() }}
                </div>
            </x-card>
        </section>
    </main>
</x-app-layout>
