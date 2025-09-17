<x-app-layout-web>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Product Form Page -->
        <section id="product-form" class="page-section">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-white border-0">
                            <h4 class="card-title mb-0">Add New Product</h4>
                            <p class="text-muted">Fill in the product information below</p>
                        </div>
                        <div class="card-body">
                            <form>
                                <!-- Basic Information -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="productName" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="productName"
                                            placeholder="Enter product name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="productSku" class="form-label">SKU</label>
                                        <input type="text" class="form-control" id="productSku" placeholder="Enter SKU">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="productCategory" class="form-label">Category</label>
                                        <select class="form-control" id="productCategory">
                                            <option value="">Select Category</option>
                                            <option value="electronics">Electronics</option>
                                            <option value="clothing">Clothing</option>
                                            <option value="home-garden">Home & Garden</option>
                                            <option value="books">Books</option>
                                            <option value="toys">Toys</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="productBrand" class="form-label">Brand</label>
                                        <input type="text" class="form-control" id="productBrand"
                                            placeholder="Enter brand name">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="productDescription" class="form-label">Description</label>
                                    <textarea class="form-control" id="productDescription" rows="4"
                                        placeholder="Enter product description"></textarea>
                                </div>

                                <!-- Pricing -->
                                <h5 class="mb-3">Pricing</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="regularPrice" class="form-label">Regular Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="regularPrice"
                                                placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="salePrice" class="form-label">Sale Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="salePrice" placeholder="0.00"
                                                step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="costPrice" class="form-label">Cost Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="costPrice" placeholder="0.00"
                                                step="0.01">
                                        </div>
                                    </div>
                                </div>

                                <!-- Inventory -->
                                <h5 class="mb-3">Inventory</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="stockQuantity" class="form-label">Stock Quantity</label>
                                        <input type="number" class="form-control" id="stockQuantity" placeholder="0">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="lowStockAlert" class="form-label">Low Stock Alert</label>
                                        <input type="number" class="form-control" id="lowStockAlert" placeholder="5">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="stockStatus" class="form-label">Stock Status</label>
                                        <select class="form-control" id="stockStatus">
                                            <option value="in-stock">In Stock</option>
                                            <option value="out-of-stock">Out of Stock</option>
                                            <option value="backorder">Backorder</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Shipping -->
                                <h5 class="mb-3">Shipping</h5>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="weight" class="form-label">Weight (lbs)</label>
                                        <input type="number" class="form-control" id="weight" placeholder="0.00"
                                            step="0.01">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="length" class="form-label">Length (in)</label>
                                        <input type="number" class="form-control" id="length" placeholder="0.00"
                                            step="0.01">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="width" class="form-label">Width (in)</label>
                                        <input type="number" class="form-control" id="width" placeholder="0.00"
                                            step="0.01">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="height" class="form-label">Height (in)</label>
                                        <input type="number" class="form-control" id="height" placeholder="0.00"
                                            step="0.01">
                                    </div>
                                </div>

                                <!-- SEO -->
                                <h5 class="mb-3">SEO</h5>
                                <div class="mb-3">
                                    <label for="metaTitle" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="metaTitle"
                                        placeholder="Enter meta title">
                                </div>
                                <div class="mb-3">
                                    <label for="metaDescription" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="metaDescription" rows="2"
                                        placeholder="Enter meta description"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="keywords" class="form-label">Keywords</label>
                                    <input type="text" class="form-control" id="keywords"
                                        placeholder="Enter keywords separated by commas">
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Product
                                    </button>
                                    <button type="button" class="btn btn-success">
                                        <i class="fas fa-plus me-2"></i>Save & Add New
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="showPage('products')">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Product Status -->
                    <div class="card mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">Product Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="productStatus" class="form-label">Status</label>
                                <select class="form-control" id="productStatus">
                                    <option value="draft">Draft</option>
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="visibility" class="form-label">Visibility</label>
                                <select class="form-control" id="visibility">
                                    <option value="public">Public</option>
                                    <option value="private">Private</option>
                                    <option value="password">Password Protected</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="publishDate" class="form-label">Publish Date</label>
                                <input type="datetime-local" class="form-control" id="publishDate">
                            </div>
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div class="card mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">Product Images</h5>
                        </div>
                        <div class="card-body">
                            <div class="border border-dashed border-2 rounded p-4 text-center mb-3"
                                style="border-color: #dee2e6 !important;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-2">Drag & drop images here or click to browse</p>
                                <input type="file" class="form-control" multiple accept="image/*">
                            </div>
                            <p class="text-muted small mb-0">Upload up to 10 images. Max file size: 5MB each.</p>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="card mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">Categories</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="cat1">
                                <label class="form-check-label" for="cat1">Electronics</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="cat2">
                                <label class="form-check-label" for="cat2">Clothing</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="cat3">
                                <label class="form-check-label" for="cat3">Home & Garden</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="cat4">
                                <label class="form-check-label" for="cat4">Books</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat5">
                                <label class="form-check-label" for="cat5">Toys</label>
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">Product Tags</h5>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control" placeholder="Add tags separated by commas">
                            <p class="text-muted small mt-2 mb-0">Tags help customers find your product</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app-layout-web>