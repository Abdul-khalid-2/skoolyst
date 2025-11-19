<x-app-layout>
    <main class="main-content">
        <section id="blog-category-edit" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Edit Blog Category</h2>
                    <p class="mb-0 text-muted">Update category details</p>
                </div>
                <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Categories
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.blog-categories.update', $blogCategory) }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" placeholder="Enter category name" 
                                           value="{{ old('name', $blogCategory->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Enter category description">{{ old('description', $blogCategory->description) }}</textarea>
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
                                                       value="{{ old('icon', $blogCategory->icon) }}">
                                                @error('icon')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text">
                                                Enter Font Awesome icon name (without the "fa-" prefix). 
                                                <a href="https://fontawesome.com/icons" target="_blank" class="text-decoration-none">
                                                    Browse icons
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="is_active" class="form-label">Status</label>
                                            <select class="form-select @error('is_active') is-invalid @enderror" 
                                                    id="is_active" name="is_active">
                                                <option value="1" {{ old('is_active', $blogCategory->is_active) ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ !old('is_active', $blogCategory->is_active) ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('is_active')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Icon Preview -->
                                <div class="mb-3">
                                    <label class="form-label">Icon Preview</label>
                                    <div class="border rounded p-4 text-center bg-light">
                                        <div id="iconPreview" class="mb-2" style="font-size: 2rem;">
                                            @if($blogCategory->icon)
                                                <i class="fas fa-{{ $blogCategory->icon }} text-primary"></i>
                                            @else
                                                <i class="fas fa-icons text-muted"></i>
                                            @endif
                                        </div>
                                        <small class="text-muted" id="iconPreviewText">
                                            @if($blogCategory->icon)
                                                fa-{{ $blogCategory->icon }}
                                            @else
                                                Icon preview will appear here
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                <!-- Category Info -->
                                <div class="alert alert-info">
                                    <div class="d-flex">
                                        <i class="fas fa-info-circle me-2 mt-1"></i>
                                        <div>
                                            <strong>Category Information</strong>
                                            <ul class="mb-0 mt-1">
                                                <li>Slug: <code>{{ $blogCategory->slug }}</code></li>
                                                <li>Created: {{ $blogCategory->created_at->format('M j, Y') }}</li>
                                                <li>Last Updated: {{ $blogCategory->updated_at->format('M j, Y') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Category
                                    </button>
                                    <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quick Tips -->
                    <div class="card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-lightbulb text-warning me-2"></i>Editing Tips
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Name Changes</h6>
                                <p class="small mb-0">Changing the category name will update the slug automatically.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">URL Impact</h6>
                                <p class="small mb-0">Existing URLs using the old slug will redirect to the new one.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Status</h6>
                                <p class="small mb-0">Inactive categories won't be available for new posts but existing posts remain.</p>
                            </div>
                            <div>
                                <h6 class="small text-uppercase text-muted">Posts</h6>
                                <p class="small mb-0">This category currently has <strong>{{ $blogCategory->blog_posts_count ?? 0 }}</strong> posts.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Popular Icons -->
                    <div class="card mt-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-star text-warning me-2"></i>Popular Icons
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2 text-center">
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="book">
                                        <i class="fas fa-book"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="graduation-cap">
                                        <i class="fas fa-graduation-cap"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="pencil-alt">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="laptop">
                                        <i class="fas fa-laptop"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="science">
                                        <i class="fas fa-flask"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="music">
                                        <i class="fas fa-music"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="paint-brush">
                                        <i class="fas fa-paint-brush"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="running">
                                        <i class="fas fa-running"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="card mt-4 border-danger">
                        <div class="card-header bg-white border-danger">
                            <h5 class="card-title mb-0 text-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted mb-3">
                                Once you delete a category, there is no going back. Please be certain.
                            </p>
                            <form action="{{ route('admin.blog-categories.destroy', $blogCategory) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i class="fas fa-trash me-2"></i>Delete Category
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
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

            // Icon suggestion buttons
            document.querySelectorAll('.icon-suggestion').forEach(button => {
                button.addEventListener('click', function() {
                    const icon = this.getAttribute('data-icon');
                    iconInput.value = icon;
                    
                    // Trigger the input event to update preview
                    const event = new Event('input');
                    iconInput.dispatchEvent(event);
                });
            });
        });
    </script>

    <style>
        .icon-suggestion {
            transition: all 0.2s;
        }
        
        .icon-suggestion:hover {
            transform: scale(1.05);
        }
        
        #iconPreview i {
            transition: all 0.3s;
        }
    </style>
</x-app-layout>