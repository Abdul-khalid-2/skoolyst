<x-app-layout>
    <!-- Main Content -->
    <main class="main-content">
        <!-- Dashboard Page -->
        <section id="dashboard" class="page-section active">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card stats-card text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title opacity-75">Total Revenue</h6>
                                    <h2 class="mb-0">$125,430</h2>
                                    <small class="opacity-75">+12% from last month</small>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                    <i class="fas fa-dollar-sign fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card stats-card text-white"
                        style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title opacity-75">Total Orders</h6>
                                    <h2 class="mb-0">1,234</h2>
                                    <small class="opacity-75">+8% from last month</small>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                    <i class="fas fa-shopping-cart fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card stats-card text-white"
                        style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title opacity-75">Total Products</h6>
                                    <h2 class="mb-0">567</h2>
                                    <small class="opacity-75">+15 new this week</small>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                    <i class="fas fa-box fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card stats-card text-white"
                        style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title opacity-75">Active Users</h6>
                                    <h2 class="mb-0">2,890</h2>
                                    <small class="opacity-75">+5% from last month</small>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-lg-8 mb-4">
                    <div class="card chart-card">
                        <div class="card-header bg-white border-0 pb-0">
                            <h5 class="card-title">Sales Overview</h5>
                            <p class="text-muted small">Revenue trends over the past 6 months</p>
                        </div>
                        <div class="card-body">
                            <div class="bg-light rounded p-5 text-center">
                                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Sales Chart Placeholder</p>
                                <p class="small text-muted">Integration point for Chart.js or similar library</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card chart-card h-100">
                        <div class="card-header bg-white border-0 pb-0">
                            <h5 class="card-title">Top Categories</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Electronics</span>
                                <span class="badge bg-primary">45%</span>
                            </div>
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar" style="width: 45%"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Clothing</span>
                                <span class="badge bg-success">30%</span>
                            </div>
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: 30%"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Home & Garden</span>
                                <span class="badge bg-warning">15%</span>
                            </div>
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: 15%"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span>Others</span>
                                <span class="badge bg-secondary">10%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" style="width: 10%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card chart-card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title">Recent Orders</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-shopping-cart text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Order #1234</h6>
                                        <small class="text-muted">John Doe - $125.99</small>
                                    </div>
                                    <span class="badge bg-success">Completed</span>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-clock text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Order #1235</h6>
                                        <small class="text-muted">Jane Smith - $89.50</small>
                                    </div>
                                    <span class="badge bg-warning">Pending</span>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-truck text-info"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Order #1236</h6>
                                        <small class="text-muted">Mike Johnson - $256.75</small>
                                    </div>
                                    <span class="badge bg-info">Shipped</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card chart-card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title">Low Stock Alerts</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-exclamation-triangle text-danger"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">iPhone 13 Pro</h6>
                                        <small class="text-muted">Only 3 items left</small>
                                    </div>
                                    <span class="badge bg-danger">Critical</span>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-exclamation-circle text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Nike Air Max</h6>
                                        <small class="text-muted">8 items left</small>
                                    </div>
                                    <span class="badge bg-warning">Low</span>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-exclamation-circle text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Samsung Galaxy Buds</h6>
                                        <small class="text-muted">12 items left</small>
                                    </div>
                                    <span class="badge bg-warning">Low</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>