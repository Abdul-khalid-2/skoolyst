<x-app-layout>
    <main class="main-content">
        <section id="product-edit" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Edit Product</h2>
                    <p class="mb-0 text-muted">Update product information</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-2"></i> View Product
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Products
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
                            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

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
                                                        <option value="{{ $shop->id }}" {{ old('shop_id', $product->shop_id) == $shop->id ? 'selected' : '' }}>
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
                                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                               value="{{ old('name', $product->name) }}" required>
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
                                                       value="{{ old('sku', $product->sku) }}">
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
                                                    <option value="book" {{ old('product_type', $product->product_type) == 'book' ? 'selected' : '' }}>Book</option>
                                                    <option value="copy" {{ old('product_type', $product->product_type) == 'copy' ? 'selected' : '' }}>Copy</option>
                                                    <option value="stationery" {{ old('product_type', $product->product_type) == 'stationery' ? 'selected' : '' }}>Stationery</option>
                                                    <option value="bag" {{ old('product_type', $product->product_type) == 'bag' ? 'selected' : '' }}>Bag</option>
                                                    <option value="uniform" {{ old('product_type', $product->product_type) == 'uniform' ? 'selected' : '' }}>Uniform</option>
                                                    <option value="other" {{ old('product_type', $product->product_type) == 'other' ? 'selected' : '' }}>Other</option>
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
                                                  placeholder="Brief description of the product">{{ old('short_description', $product->short_description) }}</textarea>
                                        @error('short_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Full Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="5" 
                                                  placeholder="Detailed description of the product">{{ old('description', $product->description) }}</textarea>
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
                                                       value="{{ old('base_price', $product->base_price) }}" required>
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
                                                       value="{{ old('sale_price', $product->sale_price) }}">
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
                                                       value="{{ old('cost_price', $product->cost_price) }}">
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
                                                       value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
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
                                                       value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}">
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
                                                    <option value="1" {{ old('manage_stock', $product->manage_stock) ? 'selected' : '' }}>Yes</option>
                                                    <option value="0" {{ !old('manage_stock', $product->manage_stock) ? 'selected' : '' }}>No</option>
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
                                                       value="{{ old('brand', $product->brand) }}">
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
                                                       value="{{ old('color', $product->color) }}">
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
                                                       value="{{ old('size', $product->size) }}">
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
                                               value="{{ old('material', $product->material) }}">
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
                                        <label for="main_image" class="form-label">Main Image</label>
                                        <input type="file" class="form-control @error('main_image') is-invalid @enderror" 
                                               id="main_image" name="main_image" accept="image/*">
                                        @error('main_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Recommended size: 800x600 pixels</div>
                                        
                                        @if($product->main_image_url)
                                        <div class="mt-2">
                                            <p class="small text-muted mb-1">Current Main Image:</p>
                                            <img src="{{ asset('storage/' . $product->main_image_url) }}" 
                                                 alt="Current Main Image" class="img-thumbnail" style="max-height: 200px;">
                                        </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="image_gallery" class="form-label">Gallery Images</label>
                                        <input type="file" class="form-control @error('image_gallery') is-invalid @enderror" 
                                               id="image_gallery" name="image_gallery[]" accept="image/*" multiple>
                                        @error('image_gallery')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">You can select multiple images for product gallery</div>
                                        
                                        @if($product->image_gallery && count(json_decode($product->image_gallery)) > 0)
                                        <div class="mt-2">
                                            <p class="small text-muted mb-1">Current Gallery Images:</p>
                                            <div class="row g-2">
                                                @foreach(json_decode($product->image_gallery) as $galleryImage)
                                                <div class="col-3">
                                                    <img src="{{ asset('storage/' . $galleryImage) }}" 
                                                         alt="Gallery Image" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
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
                                                       value="{{ old('meta_title', $product->meta_title) }}">
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
                                                    <option value="0" {{ !old('is_featured', $product->is_featured) ? 'selected' : '' }}>No</option>
                                                    <option value="1" {{ old('is_featured', $product->is_featured) ? 'selected' : '' }}>Yes</option>
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
                                                  placeholder="Enter meta description">{{ old('meta_description', $product->meta_description) }}</textarea>
                                        @error('meta_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                        <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                               id="meta_keywords" name="meta_keywords" placeholder="Enter keywords separated by commas" 
                                               value="{{ old('meta_keywords', $product->meta_keywords) }}">
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
                                                    <option value="1" {{ old('is_active', $product->is_active) ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ !old('is_active', $product->is_active) ? 'selected' : '' }}>Inactive</option>
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
                                                    <option value="1" {{ old('is_approved', $product->is_approved) ? 'selected' : '' }}>Approved</option>
                                                    <option value="0" {{ !old('is_approved', $product->is_approved) ? 'selected' : '' }}>Pending</option>
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
                                        <i class="fas fa-save me-2"></i>Update Product
                                    </button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                    
                                    <button type="button" class="btn btn-outline-danger ms-auto" 
                                            onclick="confirmDelete()">
                                        <i class="fas fa-trash me-2"></i>Delete Product
                                    </button>
                                </div>
                            </form>

                            <!-- Delete Form -->
                            <form id="deleteForm" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Product Summary -->
                    <div class="card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cube text-primary me-2"></i>Product Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Created</h6>
                                <p class="mb-0">{{ $product->created_at->format('M j, Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Last Updated</h6>
                                <p class="mb-0">{{ $product->updated_at->format('M j, Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Product Type</h6>
                                <p class="mb-0">{{ Str::title($product->product_type) }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">SKU</h6>
                                <p class="mb-0">
                                    <code>{{ $product->sku }}</code>
                                </p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Shop</h6>
                                <p class="mb-0">{{ $product->shop->name }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Category</h6>
                                <p class="mb-0">{{ $product->category->name }}</p>
                            </div>
                            <div>
                                <h6 class="small text-uppercase text-muted">Product URL</h6>
                                <p class="mb-0">
                                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="text-decoration-none">
                                        {{ route('products.show', $product->slug) }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="card mt-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-box text-primary me-2"></i>Stock Status
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="mb-3">
                                    <h3 class="{{ $product->is_in_stock ? 'text-success' : 'text-danger' }}">
                                        {{ $product->stock_quantity }}
                                    </h3>
                                    <p class="text-muted mb-1">Current Stock</p>
                                </div>
                                
                                @if($product->is_in_stock)
                                    @if($product->stock_quantity <= $product->low_stock_threshold)
                                    <div class="alert alert-warning py-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Low Stock Alert
                                    </div>
                                    @else
                                    <div class="alert alert-success py-2">
                                        <i class="fas fa-check-circle me-2"></i>
                                        In Stock
                                    </div>
                                    @endif
                                @else
                                <div class="alert alert-danger py-2">
                                    <i class="fas fa-times-circle me-2"></i>
                                    Out of Stock
                                </div>
                                @endif
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        Low Stock Threshold: {{ $product->low_stock_threshold }}
                                    </small>
                                </div>
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
                                <a href="{{ route('products.show', $product->slug) }}" target="_blank" 
                                   class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-external-link-alt me-2"></i>View Public Page
                                </a>
                                <a href="{{ route('admin.products.create') }}?shop_id={{ $product->shop_id }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Add Similar Product
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
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        // Image preview functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mainImageInput = document.getElementById('main_image');
            const galleryInput = document.getElementById('image_gallery');

            function previewImage(input, previewId) {
                const previewContainer = document.getElementById(previewId);
                if (!previewContainer) {
                    const container = input.closest('.mb-3');
                    container.innerHTML += `<div id="${previewId}" class="mt-2"></div>`;
                }

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById(previewId).innerHTML = `
                            <p class="small text-muted mb-1">New Preview:</p>
                            <div class="border rounded p-2 bg-light">
                                <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;" alt="New Preview">
                            </div>
                        `;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function previewGallery(input) {
                const previewContainer = document.getElementById('newGalleryPreview');
                if (!previewContainer) {
                    const container = input.closest('.mb-3');
                    container.innerHTML += `<div id="newGalleryPreview" class="mt-2"><p class="small text-muted mb-1">New Gallery Images:</p><div class="row g-2" id="galleryImagesContainer"></div></div>`;
                }

                document.getElementById('galleryImagesContainer').innerHTML = '';
                if (input.files) {
                    Array.from(input.files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-4';
                            col.innerHTML = `
                                <div class="border rounded p-1 bg-light">
                                    <img src="${e.target.result}" class="img-fluid rounded" style="height: 100px; object-fit: cover;" alt="New Gallery Image ${index + 1}">
                                </div>
                            `;
                            document.getElementById('galleryImagesContainer').appendChild(col);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            }

            mainImageInput.addEventListener('change', function() {
                previewImage(this, 'mainImagePreview');
            });

            galleryInput.addEventListener('change', function() {
                previewGallery(this);
            });
        });
    </script>
</x-app-layout>