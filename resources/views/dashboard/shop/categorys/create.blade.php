<x-app-layout>
    <main class="main-content">
        <section id="product-category-create" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Create Product Category</h2>
                    <p class="mb-0 text-muted">Add a new product category</p>
                </div>
                <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Categories
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.product-categories.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" placeholder="Enter category name" 
                                           value="{{ old('name') }}" required>
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
                                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
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
                                              placeholder="Enter category description">{{ old('description') }}</textarea>
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
                                                       value="{{ old('icon') }}">
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
                                            <label for="sort_order" class="form-label">Sort Order</label>
                                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                                   id="sort_order" name="sort_order" placeholder="0" 
                                                   value="{{ old('sort_order', 0) }}" min="0">
                                            @error('sort_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Lower numbers appear first</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Category Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Recommended size: 400x300 pixels</div>
                                </div>

                                <!-- Icon Preview -->
                                <div class="mb-3">
                                    <label class="form-label">Icon Preview</label>
                                    <div class="border rounded p-4 text-center bg-light">
                                        <div id="iconPreview" class="mb-2" style="font-size: 2rem;">
                                            <i class="fas fa-icons text-muted"></i>
                                        </div>
                                        <small class="text-muted" id="iconPreviewText">Icon preview will appear here</small>
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div class="mb-4">
                                    <label class="form-label">Image Preview</label>
                                    <div class="border rounded p-4 text-center bg-light">
                                        <div id="imagePreview">
                                            <i class="fas fa-image fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Image preview will appear here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <select class="form-select @error('is_active') is-invalid @enderror" 
                                            id="is_active" name="is_active">
                                        <option value="1" {{ old('is_active', true) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !old('is_active', true) ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Create Category
                                    </button>
                                    <a href="{{ route('admin.product-categories.index') }}" class="btn btn-outline-secondary">
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
                                <i class="fas fa-lightbulb text-warning me-2"></i>Tips
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Category Structure</h6>
                                <p class="small mb-0">Use parent categories to create hierarchical structures for better organization.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Icons</h6>
                                <p class="small mb-0">Icons help make categories visually distinctive and improve user experience.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Sort Order</h6>
                                <p class="small mb-0">Use sort order to control the display sequence of categories.</p>
                            </div>
                            <div>
                                <h6 class="small text-uppercase text-muted">Status</h6>
                                <p class="small mb-0">Inactive categories won't be available when creating new products.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Popular Icons -->
                    <div class="card mt-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-star text-warning me-2"></i>Popular Icons for Categories
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
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="pencil-alt">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="ruler">
                                        <i class="fas fa-ruler"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="palette">
                                        <i class="fas fa-palette"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="tshirt">
                                        <i class="fas fa-tshirt"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="briefcase">
                                        <i class="fas fa-briefcase"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="flask">
                                        <i class="fas fa-flask"></i>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 icon-suggestion" data-icon="calculator">
                                        <i class="fas fa-calculator"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Examples -->
                    <div class="card mt-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list text-primary me-2"></i>Category Examples
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Main Categories</h6>
                                <ul class="small mb-0 ps-3">
                                    <li>Textbooks</li>
                                    <li>Stationery</li>
                                    <li>Uniforms</li>
                                    <li>Bags & Backpacks</li>
                                </ul>
                            </div>
                            <div>
                                <h6 class="small text-uppercase text-muted">Subcategories</h6>
                                <ul class="small mb-0 ps-3">
                                    <li>Textbooks → Mathematics</li>
                                    <li>Stationery → Pens & Pencils</li>
                                    <li>Uniforms → School Uniforms</li>
                                </ul>
                            </div>
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
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');

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

            // Update image preview when image input changes
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.innerHTML = `
                            <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px;" alt="Preview">
                            <p class="mt-2 mb-0 text-muted">Image preview</p>
                        `;
                    }
                    reader.readAsDataURL(this.files[0]);
                } else {
                    imagePreview.innerHTML = `
                        <i class="fas fa-image fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Image preview will appear here</p>
                    `;
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

            // Initial preview update
            if (iconInput.value) {
                const event = new Event('input');
                iconInput.dispatchEvent(event);
            }
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