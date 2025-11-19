<x-app-layout>
    <main class="main-content">
        <section id="blog-categories" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Blog Categories</h2>
                    <p class="mb-0 text-muted">Manage your blog categories</p>
                </div>
                <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Create Category
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
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
                                        <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
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
                                        <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $category->blog_posts_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $category->created_at->format('M j, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.blog-categories.edit', $category) }}" 
                                               class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.blog-categories.destroy', $category) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
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
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open fa-2x mb-3"></i>
                                            <p>No categories found. Create your first category to get started.</p>
                                            <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Create Category
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $categories->links() }}
                </div>
            </div>
        </section>
    </main>
</x-app-layout>