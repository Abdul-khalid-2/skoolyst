<x-app-layout>
    <main class="main-content">
        <section id="product-categories" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Product Categories</h2>
                    <p class="mb-0 text-muted">Manage your product categories and subcategories</p>
                </div>
                <a href="{{ route('admin.product-categories.create') }}" class="btn btn-primary">
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Parent</th>
                                    <th>Icon</th>
                                    <th>Status</th>
                                    <th>Products</th>
                                    <th>Subcategories</th>
                                    <th>Sort Order</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($category->image_url)
                                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" 
                                                 class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                            @elseif($category->icon)
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-{{ $category->icon }} text-primary"></i>
                                            </div>
                                            @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-folder text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <strong>{{ $category->name }}</strong>
                                                @if($category->description)
                                                <br>
                                                <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($category->parent)
                                        <span class="badge bg-light text-dark">
                                            {{ $category->parent->name }}
                                        </span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
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
                                            {{ $category->products_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $category->children->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        <code>{{ $category->sort_order }}</code>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $category->created_at->format('M j, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.product-categories.show', $category) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.product-categories.edit', $category) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.product-categories.destroy', $category) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open fa-2x mb-3"></i>
                                            <p>No categories found. Create your first category to get started.</p>
                                            <a href="{{ route('admin.product-categories.create') }}" class="btn btn-primary">
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