<x-app-layout>
    <main class="main-content">
        <section id="product-category-edit" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Edit Product Category</h2>
                    <p class="mb-0 text-muted">Update category information</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.product-categories.show', $productCategory) }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-2"></i> View Category
                    </a>
                    <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Categories
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.product-categories.update', $productCategory) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" placeholder="Enter category name" 
                                           value="{{ old('name', $productCategory->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="parent_id" class="form-label">Parent Category</label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror" 
                                            id="parent_id" name="parent_id">
                                        <option value="">No Parent (Main Category)</option>
                                        @foreach($parentCategories as $parent)
                                            <option value="{{ $parent->id }}" {{ old('parent_id', $productCategory->parent_id) == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Enter category description">{{ old('description', $productCategory->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="icon" class="form-label">Icon</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-icons"></i>
                                                </span>
                                                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                                       id="icon" name="icon" placeholder="e.g., book, graduation-cap, pencil-alt"
                                                       value="{{ old('icon', $productCategory->icon) }}">
                                                @error('icon')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sort_order" class="form-label">Sort Order</label>
                                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                                   id="sort_order" name="sort_order" placeholder="0" 
                                                   value="{{ old('sort_order', $productCategory->sort_order) }}" min="0">
                                            @error('sort_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Category Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    
                                    @if($productCategory->image_url)
                                    <div class="mt-2">
                                        <p class="small text-muted mb-1">Current Image:</p>
                                        <img src="{{ asset('storage/' . $productCategory->image_url) }}" 
                                             alt="Current Image" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                    @endif
                                </div>

                                <!-- Icon Preview -->
                                <div class="mb-3">
                                    <label class="form-label">Icon Preview</label>
                                    <div class="border rounded p-4 text-center bg-light">
                                        <div id="iconPreview" class="mb-2" style="font-size: 2rem;">
                                            @if($productCategory->icon)
                                                <i class="fas fa-{{ $productCategory->icon }} text-primary"></i>
                                            @else
                                                <i class="fas fa-icons text-muted"></i>
                                            @endif
                                        </div>
                                        <small class="text-muted" id="iconPreviewText">
                                            {{ $productCategory->icon ? 'fa-' . $productCategory->icon : 'Icon preview will appear here' }}
                                        </small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <select class="form-select @error('is_active') is-invalid @enderror" 
                                            id="is_active" name="is_active">
                                        <option value="1" {{ old('is_active', $productCategory->is_active) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !old('is_active', $productCategory->is_active) ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Category
                                    </button>
                                    <a href="{{ route('admin.product-categories.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                    
                                    <button type="button" class="btn btn-outline-danger ms-auto" 
                                            onclick="confirmDelete()">
                                        <i class="fas fa-trash me-2"></i>Delete Category
                                    </button>
                                </div>
                            </form>

                            <!-- Delete Form -->
                            <form id="deleteForm" action="{{ route('admin.product-categories.destroy', $productCategory) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Category Summary -->
                    <div class="card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle text-primary me-2"></i>Category Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Created</h6>
                                <p class="mb-0">{{ $productCategory->created_at->format('M j, Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Last Updated</h6>
                                <p class="mb-0">{{ $productCategory->updated_at->format('M j, Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Slug</h6>
                                <p class="mb-0"><code>{{ $productCategory->slug }}</code></p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Products</h6>
                                <p class="mb-0">{{ $productCategory->products_count }} products</p>
                            </div>
                            <div>
                                <h6 class="small text-uppercase text-muted">Subcategories</h6>
                                <p class="mb-0">{{ $productCategory->children->count() }} subcategories</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mt-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.products.index', ['category_id' => $productCategory->id]) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-box me-2"></i>View Products
                                </a>
                                <a href="{{ route('admin.product-categories.create', ['parent_id' => $productCategory->id]) }}" 
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Add Subcategory
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const iconInput = document.getElementById('icon');
            const iconPreview = document.getElementById('iconPreview');
            const iconPreviewText = document.getElementById('iconPreviewText');

            // Update icon preview when icon input changes
            iconInput.addEventListener('input', function() {
                const iconName = this.value.trim();
                if (iconName) {
                    iconPreview.innerHTML = `<i class="fas fa-${iconName} text-primary"></i>`;
                    iconPreviewText.textContent = `fa-${iconName}`;
                } else {
                    iconPreview.innerHTML = '<i class="fas fa-icons text-muted"></i>';
                    iconPreviewText.textContent = 'Icon preview will appear here';
                }
            });

            // Image preview for new image
            const imageInput = document.getElementById('image');
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const currentImage = document.querySelector('img[alt="Current Image"]');
                        if (currentImage) {
                            currentImage.parentElement.innerHTML += `
                                <div class="mt-2">
                                    <p class="small text-muted mb-1">New Image Preview:</p>
                                    <img src="${e.target.result}" class="img-thumbnail" style="max-height: 100px;" alt="New Preview">
                                </div>
                            `;
                        }
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
</x-app-layout>