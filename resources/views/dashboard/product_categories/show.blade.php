<x-app-layout>
    <main class="main-content">
        <section id="product-category-details" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">{{ $productCategory->name }}</h2>
                    <p class="mb-0 text-muted">Category details and information</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('product-categories.edit', $productCategory) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <a href="{{ route('product-categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Category Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%">Name:</th>
                                            <td><strong>{{ $productCategory->name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Slug:</th>
                                            <td><code>{{ $productCategory->slug }}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Parent Category:</th>
                                            <td>
                                                @if($productCategory->parent)
                                                    <span class="badge bg-light text-dark">{{ $productCategory->parent->name }}</span>
                                                @else
                                                    <span class="text-muted">None</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%">Sort Order:</th>
                                            <td>
                                                <span class="badge bg-secondary">{{ $productCategory->sort_order }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="badge bg-{{ $productCategory->is_active ? 'success' : 'secondary' }}">
                                                    {{ $productCategory->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created:</th>
                                            <td>
                                                <small class="text-muted">{{ $productCategory->created_at->format('M j, Y') }}</small>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($productCategory->description)
                                <div class="mt-4">
                                    <strong>Description:</strong>
                                    <p class="mb-0 text-muted mt-2">{{ $productCategory->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Statistics</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <div class="display-4 text-primary fw-bold">{{ $productCategory->products_count ?? 0 }}</div>
                                <p class="text-muted mb-0">Total Products</p>
                            </div>
                            @if($productCategory->children->count() > 0)
                            <div>
                                <div class="display-4 text-success fw-bold">{{ $productCategory->children->count() }}</div>
                                <p class="text-muted mb-0">Sub Categories</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($productCategory->children->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Sub Categories</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach($productCategory->children as $child)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <strong>{{ $child->name }}</strong>
                                        <div>
                                            <span class="badge bg-{{ $child->is_active ? 'success' : 'secondary' }} me-2">
                                                {{ $child->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <small class="text-muted">{{ $child->products_count ?? 0 }} products</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('product-categories.show', $child) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($productCategory->image_url)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Category Image</h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ asset('website/'. $productCategory->image_url) }}" alt="{{ $productCategory->name }}" 
                                class="img-fluid rounded" style="max-height: 300px;">
                        </div>
                    </div>
                    @endif

                    @if($productCategory->icon)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Category Icon</h5>
                        </div>
                        <div class="card-body text-center">
                            <i class="{{ $productCategory->icon }} fa-3x text-primary"></i>
                            <p class="mt-2 mb-0"><code>{{ $productCategory->icon }}</code></p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
</x-app-layout>