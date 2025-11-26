<x-app-layout>
    <main class="main-content">
        <section id="add-product" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Add New Product</h2>
                    <p class="mb-0 text-muted">Create a new product in your inventory</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Products
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-lg-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Basic Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">Product Name *</label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                           id="name" name="name" value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sku" class="form-label">SKU</label>
                                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                                           id="sku" name="sku" value="{{ old('sku') }}">
                                                    @error('sku')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="short_description" class="form-label">Short Description</label>
                                            <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                                      id="short_description" name="short_description" rows="2">{{ old('short_description') }}</textarea>
                                            @error('short_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="description" class="form-label">Full Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Attributes -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Product Attributes</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="product_type" class="form-label">Product Type *</label>
                                                    <select class="form-control @error('product_type') is-invalid @enderror" 
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="brand" class="form-label">Brand</label>
                                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                                           id="brand" name="brand" value="{{ old('brand') }}">
                                                    @error('brand')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-2">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="material" class="form-label">Material</label>
                                                    <input type="text" class="form-control @error('material') is-invalid @enderror" 
                                                           id="material" name="material" value="{{ old('material') }}">
                                                    @error('material')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="color" class="form-label">Color</label>
                                                    <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                                           id="color" name="color" value="{{ old('color') }}">
                                                    @error('color')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="size" class="form-label">Size</label>
                                                    <input type="text" class="form-control @error('size') is-invalid @enderror" 
                                                           id="size" name="size" value="{{ old('size') }}">
                                                    @error('size')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="col-lg-4">
                                <!-- Shop & Category -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Shop & Category</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="shop_id" class="form-label">Shop *</label>
                                            <select class="form-control @error('shop_id') is-invalid @enderror" 
                                                    id="shop_id" name="shop_id" required>
                                                <option value="">Select Shop</option>
                                                @foreach($shops as $shop)
                                                    <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                                                        {{ $shop->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('shop_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="category_id" class="form-label">Category *</label>
                                            <select class="form-control @error('category_id') is-invalid @enderror" 
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

                                <!-- Pricing -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Pricing</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="base_price" class="form-label">Base Price (Rs.) *</label>
                                            <input type="number" step="0.01" class="form-control @error('base_price') is-invalid @enderror" 
                                                   id="base_price" name="base_price" value="{{ old('base_price') }}" required>
                                            @error('base_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="sale_price" class="form-label">Sale Price (Rs.)</label>
                                            <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" 
                                                   id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
                                            @error('sale_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="cost_price" class="form-label">Cost Price (Rs.)</label>
                                            <input type="number" step="0.01" class="form-control @error('cost_price') is-invalid @enderror" 
                                                   id="cost_price" name="cost_price" value="{{ old('cost_price') }}">
                                            @error('cost_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Inventory -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Inventory</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="stock_quantity" class="form-label">Stock Quantity *</label>
                                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required>
                                            @error('stock_quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                                            <input type="number" class="form-control @error('low_stock_threshold') is-invalid @enderror" 
                                                   id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}">
                                            @error('low_stock_threshold')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-check mt-3">
                                            <input type="checkbox" class="form-check-input" id="manage_stock" name="manage_stock" value="1" {{ old('manage_stock', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="manage_stock">Manage Stock</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check mb-2">
                                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">Featured Product</label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Create Product
                                    </button>
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>