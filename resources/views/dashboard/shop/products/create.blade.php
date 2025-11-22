<x-app-layout>
    <main class="main-content">
        <section id="product-create" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Create Product</h2>
                    <p class="mb-0 text-muted">Add a new product to your shop</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Products
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- Basic Information -->
                                <div class="mb-4">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-info-circle text-primary me-2"></i>Basic Information
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="shop_id" class="form-label">Shop <span class="text-danger">*</span></label>
                                                <select class="form-select @error('shop_id') is-invalid @enderror" 
                                                        id="shop_id" name="shop_id" required>
                                                    <option value="">Select Shop</option>
                                                    @foreach($shops as $shop)
                                                        <option value="{{ $shop->id }}" {{ old('shop_id', request('shop_id')) == $shop->id ? 'selected' : '' }}>
                                                            {{ $shop->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('shop_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                                        id="category_id" name="category_id" required>
                                                    <option value="">Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" placeholder="Enter product name" 
                                               value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sku" class="form-label">SKU (Stock Keeping Unit)</label>
                                                <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                                       id="sku" name="sku" placeholder="e.g., PROD-001" 
                                                       value="{{ old('sku') }}">
                                                @error('sku')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="product_type" class="form-label">Product Type <span class="text-danger">*</span></label>
                                                <select class="form-select @error('product_type') is-invalid @enderror" 
                                                        id="product_type" name="product_type" required>
                                                    <option value="">Select Type</option>
                                                    <option value="book" {{ old('product_type') == 'book' ? 'selected' : '' }}>Book</option>
                                                    <option value="copy" {{ old('product_type') == 'copy' ? 'selected' : '' }}>Copy</option>
                                                    <option value="stationery" {{ old('product_type') == 'stationery' ? 'selected' : '' }}>Stationery</option>
                                                    <option value="bag" {{ old('product_type') == 'bag' ? 'selected' : '' }}>Bag</option>
                                                    <option value="uniform" {{ old('product_type') == 'uniform' ? 'selected' : '' }}>Uniform</option>
                                                    <option value="other" {{ old('product_type') == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('product_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="short_description" class="form-label">Short Description</label>
                                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                                  id="short_description" name="short_description" rows="3" 
                                                  placeholder="Brief description of the product">{{ old('short_description') }}</textarea>
                                        @error('short_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Full Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="5" 
                                                  placeholder="Detailed description of the product">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Pricing -->
                                <div class="mb-4">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-tag text-primary me-2"></i>Pricing
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="base_price" class="form-label">Base Price (Rs.) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" class="form-control @error('base_price') is-invalid @enderror" 
                                                       id="base_price" name="base_price" placeholder="0.00" 
                                                       value="{{ old('base_price') }}" required>
                                                @error('base_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="sale_price" class="form-label">Sale Price (Rs.)</label>
                                                <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" 
                                                       id="sale_price" name="sale_price" placeholder="0.00" 
                                                       value="{{ old('sale_price') }}">
                                                @error('sale_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="cost_price" class="form-label">Cost Price (Rs.)</label>
                                                <input type="number" step="0.01" class="form-control @error('cost_price') is-invalid @enderror" 
                                                       id="cost_price" name="cost_price" placeholder="0.00" 
                                                       value="{{ old('cost_price') }}">
                                                @error('cost_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Inventory -->
                                <div class="mb-4">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-boxes text-primary me-2"></i>Inventory
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                                       id="stock_quantity" name="stock_quantity" placeholder="0" 
                                                       value="{{ old('stock_quantity', 0) }}" required>
                                                @error('stock_quantity')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                                                <input type="number" class="form-control @error('low_stock_threshold') is-invalid @enderror" 
                                                       id="low_stock_threshold" name="low_stock_threshold" placeholder="5" 
                                                       value="{{ old('low_stock_threshold', 5) }}">
                                                @error('low_stock_threshold')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="manage_stock" class="form-label">Manage Stock</label>
                                                <select class="form-select @error('manage_stock') is-invalid @enderror" 
                                                        id="manage_stock" name="manage_stock">
                                                    <option value="1" {{ old('manage_stock', true) ? 'selected' : '' }}>Yes</option>
                                                    <option value="0" {{ !old('manage_stock', true) ? 'selected' : '' }}>No</option>
                                                </select>
                                                @error('manage_stock')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Attributes -->
                                <div class="mb-4">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-list text-primary me-2"></i>Product Attributes
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="brand" class="form-label">Brand</label>
                                                <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                                       id="brand" name="brand" placeholder="Enter brand name" 
                                                       value="{{ old('brand') }}">
                                                @error('brand')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="color" class="form-label">Color</label>
                                                <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                                       id="color" name="color" placeholder="Enter color" 
                                                       value="{{ old('color') }}">
                                                @error('color')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="size" class="form-label">Size</label>
                                                <input type="text" class="form-control @error('size') is-invalid @enderror" 
                                                       id="size" name="size" placeholder="Enter size" 
                                                       value="{{ old('size') }}">
                                                @error('size')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="material" class="form-label">Material</label>
                                        <input type="text" class="form-control @error('material') is-invalid @enderror" 
                                               id="material" name="material" placeholder="Enter material" 
                                               value="{{ old('material') }}">
                                        @error('material')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Media -->
                                <div class="mb-4">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-images text-primary me-2"></i>Media
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <label for="main_image" class="form-label">Main Image <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('main_image') is-invalid @enderror" 
                                               id="main_image" name="main_image" accept="image/*" required>
                                        @error('main_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Recommended size: 800x600 pixels</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image_gallery" class="form-label">Gallery Images</label>
                                        <input type="file" class="form-control @error('image_gallery') is-invalid @enderror" 
                                               id="image_gallery" name="image_gallery[]" accept="image/*" multiple>
                                        @error('image_gallery')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">You can select multiple images for product gallery</div>
                                    </div>
                                </div>

                                <!-- SEO & Settings -->
                                <div class="mb-4">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-search text-primary me-2"></i>SEO & Settings
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="meta_title" class="form-label">Meta Title</label>
                                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                                       id="meta_title" name="meta_title" placeholder="Enter meta title" 
                                                       value="{{ old('meta_title') }}">
                                                @error('meta_title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="is_featured" class="form-label">Featured Product</label>
                                                <select class="form-select @error('is_featured') is-invalid @enderror" 
                                                        id="is_featured" name="is_featured">
                                                    <option value="0" {{ !old('is_featured', false) ? 'selected' : '' }}>No</option>
                                                    <option value="1" {{ old('is_featured', false) ? 'selected' : '' }}>Yes</option>
                                                </select>
                                                @error('is_featured')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                                  id="meta_description" name="meta_description" rows="3" 
                                                  placeholder="Enter meta description">{{ old('meta_description') }}</textarea>
                                        @error('meta_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                        <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                               id="meta_keywords" name="meta_keywords" placeholder="Enter keywords separated by commas" 
                                               value="{{ old('meta_keywords') }}">
                                        @error('meta_keywords')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
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
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="is_approved" class="form-label">Approval Status</label>
                                                <select class="form-select @error('is_approved') is-invalid @enderror" 
                                                        id="is_approved" name="is_approved">
                                                    <option value="1" {{ old('is_approved', true) ? 'selected' : '' }}>Approved</option>
                                                    <option value="0" {{ !old('is_approved', true) ? 'selected' : '' }}>Pending</option>
                                                </select>
                                                @error('is_approved')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Create Product
                                    </button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
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
                                <h6 class="small text-uppercase text-muted">Product Name</h6>
                                <p class="small mb-0">Use clear, descriptive names that help customers find your product.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Pricing</h6>
                                <p class="small mb-0">Set competitive prices. Sale prices can help attract customers.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Inventory</h6>
                                <p class="small mb-0">Keep track of stock levels to avoid overselling.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Images</h6>
                                <p class="small mb-0">High-quality images significantly improve conversion rates.</p>
                            </div>
                            <div>
                                <h6 class="small text-uppercase text-muted">SEO</h6>
                                <p class="small mb-0">Optimize meta tags to improve search engine visibility.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Product Type Guide -->
                    <div class="card mt-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cube text-primary me-2"></i>Product Types
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Book</h6>
                                <p class="small mb-0">Textbooks, reference books, story books, educational publications.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Copy</h6>
                                <p class="small mb-0">Notebooks, exercise books, registers, writing pads.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Stationery</h6>
                                <p class="small mb-0">Pens, pencils, erasers, rulers, art supplies.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Bag</h6>
                                <p class="small mb-0">School bags, backpacks, lunch bags, carrying cases.</p>
                            </div>
                            <div>
                                <h6 class="small text-uppercase text-muted">Uniform</h6>
                                <p class="small mb-0">School uniforms, sports kits, accessories.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview
            const mainImageInput = document.getElementById('main_image');
            const galleryInput = document.getElementById('image_gallery');

            function previewImage(input, previewId) {
                const preview = document.getElementById(previewId);
                if (!preview) {
                    const container = input.closest('.mb-3');
                    container.innerHTML += `<div id="${previewId}" class="mt-2"></div>`;
                }

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById(previewId).innerHTML = `
                            <div class="border rounded p-2 bg-light">
                                <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;" alt="Preview">
                            </div>
                        `;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            mainImageInput.addEventListener('change', function() {
                previewImage(this, 'mainImagePreview');
            });

            galleryInput.addEventListener('change', function() {
                const previewContainer = document.getElementById('galleryPreview');
                if (!previewContainer) {
                    const container = this.closest('.mb-3');
                    container.innerHTML += `<div id="galleryPreview" class="mt-2 row g-2"></div>`;
                }

                document.getElementById('galleryPreview').innerHTML = '';
                if (this.files) {
                    Array.from(this.files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-4';
                            col.innerHTML = `
                                <div class="border rounded p-1 bg-light">
                                    <img src="${e.target.result}" class="img-fluid rounded" style="height: 100px; object-fit: cover;" alt="Gallery Preview ${index + 1}">
                                </div>
                            `;
                            document.getElementById('galleryPreview').appendChild(col);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            });

            // Auto-generate SKU if empty
            const skuInput = document.getElementById('sku');
            const nameInput = document.getElementById('name');

            nameInput.addEventListener('blur', function() {
                if (!skuInput.value) {
                    const name = this.value.trim().toUpperCase().replace(/\s+/g, '-');
                    const random = Math.random().toString(36).substring(2, 8).toUpperCase();
                    skuInput.value = `PROD-${name.substring(0, 10)}-${random}`;
                }
            });
        });
    </script>
</x-app-layout>