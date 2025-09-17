<x-app-layout>
    <!-- Main Content -->
    <main class="main-content">
        <!-- schoolyes Page -->
        <section id="products" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Products</h2>
                    <p class="text-muted">Manage your product inventory</p>
                </div>
                <button class="btn btn-primary" onclick="showPage('product-form')">
                    <i class="fas fa-plus me-2"></i>Add Product
                </button>
            </div>

            <!-- Filters and Search -->
            <div class="row mb-4">
                <div class="col-lg-4 mb-3">
                    <input type="text" class="form-control" placeholder="Search products...">
                </div>
                <div class="col-lg-2 mb-3">
                    <select class="form-control">
                        <option>All Categories</option>
                        <option>Electronics</option>
                        <option>Clothing</option>
                        <option>Home & Garden</option>
                    </select>
                </div>
                <div class="col-lg-2 mb-3">
                    <select class="form-control">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Inactive</option>
                        <option>Out of Stock</option>
                    </select>
                </div>
                <div class="col-lg-2 mb-3">
                    <select class="form-control">
                        <option>Sort by Name</option>
                        <option>Sort by Price</option>
                        <option>Sort by Stock</option>
                        <option>Sort by Date</option>
                    </select>
                </div>
                <div class="col-lg-2 mb-3">
                    <button class="btn btn-outline-secondary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </div>

            <!-- Products Table -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="form-check-input">
                                </th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/404280/pexels-photo-404280.jpeg?auto=compress&cs=tinysrgb&w=60&h=60&fit=crop"
                                            alt="Product" class="rounded me-3" width="50" height="50">
                                        <div>
                                            <h6 class="mb-0">iPhone 13 Pro</h6>
                                            <small class="text-muted">SKU: IP13P-001</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Electronics</td>
                                <td>$999.99</td>
                                <td>
                                    <span class="badge bg-danger">3</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="showPage('product-form')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/1456706/pexels-photo-1456706.jpeg?auto=compress&cs=tinysrgb&w=60&h=60&fit=crop"
                                            alt="Product" class="rounded me-3" width="50" height="50">
                                        <div>
                                            <h6 class="mb-0">Nike Air Max 270</h6>
                                            <small class="text-muted">SKU: NAM270-001</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Clothing</td>
                                <td>$159.99</td>
                                <td>
                                    <span class="badge bg-warning">8</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="showPage('product-form')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/788946/pexels-photo-788946.jpeg?auto=compress&cs=tinysrgb&w=60&h=60&fit=crop"
                                            alt="Product" class="rounded me-3" width="50" height="50">
                                        <div>
                                            <h6 class="mb-0">Samsung Galaxy Buds Pro</h6>
                                            <small class="text-muted">SKU: SGBP-001</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Electronics</td>
                                <td>$199.99</td>
                                <td>
                                    <span class="badge bg-warning">12</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="showPage('product-form')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/276517/pexels-photo-276517.jpeg?auto=compress&cs=tinysrgb&w=60&h=60&fit=crop"
                                            alt="Product" class="rounded me-3" width="50" height="50">
                                        <div>
                                            <h6 class="mb-0">MacBook Pro 14"</h6>
                                            <small class="text-muted">SKU: MBP14-001</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Electronics</td>
                                <td>$1999.99</td>
                                <td>
                                    <span class="badge bg-success">25</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="showPage('product-form')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.pexels.com/photos/934070/pexels-photo-934070.jpeg?auto=compress&cs=tinysrgb&w=60&h=60&fit=crop"
                                            alt="Product" class="rounded me-3" width="50" height="50">
                                        <div>
                                            <h6 class="mb-0">Adidas Ultraboost 22</h6>
                                            <small class="text-muted">SKU: AU22-001</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Clothing</td>
                                <td>$189.99</td>
                                <td>
                                    <span class="badge bg-secondary">0</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">Out of Stock</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="showPage('product-form')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Showing 1 to 5 of 567 entries</span>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#">Previous</a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

    </main>
</x-app-layout>