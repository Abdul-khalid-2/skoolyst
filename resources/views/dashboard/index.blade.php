<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            line-height: 1.6;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.5rem 1rem;
        }

        .nav-link {
            color: #cbd5e1 !important;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff !important;
            transform: translateX(5px);
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .header {
            height: var(--header-height);
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            transition: left 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
            transition: margin-left 0.3s ease;
        }

        .footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 1.5rem 2rem;
            margin-top: auto;
        }

        .page-section {
            display: none;
        }

        .page-section.active {
            display: block;
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .chart-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: box-shadow 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .table th {
            background-color: #f8fafc;
            border: none;
            font-weight: 600;
            color: #475569;
            padding: 1rem;
        }

        .table td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }

        /* Mobile Responsive */
        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0);
            }

            .header {
                left: var(--sidebar-width);
            }

            .main-content {
                margin-left: var(--sidebar-width);
            }
        }

        @media (max-width: 767px) {
            .main-content {
                padding: 1rem;
            }

            .stats-card {
                margin-bottom: 1rem;
            }
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Collapsed sidebar for desktop */
        @media (min-width: 768px) {
            .sidebar.collapsed {
                width: 70px;
                /* adjust to fit icons */
            }

            .sidebar.collapsed .sidebar-header h4,
            .sidebar.collapsed .sidebar-nav .nav-link span {
                display: none;
                /* hide text */
            }

            .sidebar.collapsed .nav-link {
                text-align: center;
            }

            .sidebar.collapsed .nav-link i {
                margin-right: 0;
            }

            /* shift header and main content */
            .header.collapsed {
                left: 70px;
            }

            .main-content.collapsed {
                margin-left: 70px;
            }
        }

        @media (min-width: 768px) {
            .sidebar {
                overflow: hidden;
                /* ensures text can't overflow */
            }

            .sidebar.collapsed {
                width: 70px;
            }

            /* hide title in header */
            .sidebar.collapsed .sidebar-header h4 {
                display: none;
            }

            /* adjust nav links */
            .sidebar .nav-link {
                display: flex;
                align-items: center;
                white-space: nowrap;
                /* prevents wrapping */
                overflow: hidden;
                /* cut overflowing text */
            }

            .sidebar.collapsed .nav-link span {
                display: none;
                /* hide link text */
            }

            /* icons stay centered */
            .sidebar.collapsed .nav-link {
                justify-content: center;
            }

            /* shift header & content */
            .header.collapsed {
                left: 70px;
            }

            .main-content.collapsed {
                margin-left: 70px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header d-flex justify-content-between align-items-center">
            <h4 class="text-white mb-0">
                <i class="fas fa-store me-2"></i>
                E-Commerce Admin
            </h4>
            <button class="btn btn-link d-md-none p-0" onclick="closeSidebar()" style="color: white;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="sidebar-nav list-unstyled">
            <li class="nav-item">
                <a href="#dashboard" class="nav-link active" onclick="showPage('dashboard')">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#products" class="nav-link" onclick="showPage('products')">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#product-form" class="nav-link" onclick="showPage('product-form')">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Product</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#orders" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#customers" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#analytics" class="nav-link">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytics</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#settings" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Header -->
    <header class="header d-flex align-items-center justify-content-between px-4">
        <div class="d-flex align-items-center">
            <button class="btn btn-link me-3" onclick="toggleSidebar()" id="sidebarToggle">
                <i class="fas fa-bars text-gray-600"></i>
            </button>
            <h5 class="mb-0 text-gray-800" id="pageTitle">Dashboard</h5>
        </div>

        <div class="d-flex align-items-center">
            <div class="dropdown me-3">
                <button class="btn btn-link position-relative" data-bs-toggle="dropdown">
                    <i class="fas fa-bell text-gray-600"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="font-size: 0.6rem;">
                        3
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">New order received</a></li>
                    <li><a class="dropdown-item" href="#">Product out of stock</a></li>
                    <li><a class="dropdown-item" href="#">Customer message</a></li>
                </ul>
            </div>

            <div class="dropdown">
                <button class="btn btn-link d-flex align-items-center" data-bs-toggle="dropdown">
                    <img src="https://images.pexels.com/photos/1040880/pexels-photo-1040880.jpeg?auto=compress&cs=tinysrgb&w=40&h=40&fit=crop&crop=face"
                        alt="User" class="rounded-circle me-2" width="32" height="32">
                    <span class="text-gray-700 d-none d-sm-inline">Admin User</span>
                    <i class="fas fa-chevron-down ms-2 text-gray-500"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

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

    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="d-flex justify-content-between align-items-center">
            <p class="text-muted mb-0">&copy; 2025 E-Commerce Admin. All rights reserved.</p>
            <div class="d-flex gap-3">
                <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
                <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                <a href="#" class="text-muted text-decoration-none">Support</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Navigation functionality
        function showPage(pageId) {
            // Hide all pages
            const pages = document.querySelectorAll('.page-section');
            pages.forEach(page => page.classList.remove('active'));

            // Show selected page
            document.getElementById(pageId).classList.add('active');

            // Update active nav link
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => link.classList.remove('active'));

            // Find and activate the correct nav link
            const activeLink = document.querySelector(`[onclick="showPage('${pageId}')"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }

            // Update page title
            const titles = {
                'dashboard': 'Dashboard',
                'products': 'Products',
                'product-form': 'Add Product'
            };
            document.getElementById('pageTitle').textContent = titles[pageId] || 'Dashboard';

            // Close sidebar on mobile after navigation
            if (window.innerWidth < 768) {
                closeSidebar();
            }
        }

        // Sidebar functionality
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("sidebarOverlay");
            const header = document.querySelector(".header");
            const mainContent = document.querySelector(".main-content");

            if (window.innerWidth < 768) {
                // Mobile behavior
                sidebar.classList.toggle("active");
                overlay.classList.toggle("active");
            } else {
                // Desktop collapse behavior
                sidebar.classList.toggle("collapsed");
                header.classList.toggle("collapsed");
                mainContent.classList.toggle("collapsed");
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("sidebarOverlay");

            sidebar.classList.remove("active");
            overlay.classList.remove("active");
        }
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                closeSidebar();
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Show dashboard by default
            showPage('dashboard');

            // Initialize tooltips if needed
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Form handling
        document.addEventListener('submit', function(e) {
            if (e.target.tagName === 'FORM') {
                e.preventDefault();

                // Show success message (in a real app, this would be an API call)
                alert('Product saved successfully!');

                // Redirect to products page
                showPage('products');
            }
        });

        // Search functionality
        function handleSearch(searchTerm) {
            console.log('Searching for:', searchTerm);
            // In a real application, this would filter the products table
        }

        // Add some interactive features
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to stats cards
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Add real-time search
            const searchInput = document.querySelector('input[placeholder="Search products..."]');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const tableRows = document.querySelectorAll('#products tbody tr');

                    tableRows.forEach(row => {
                        const productName = row.querySelector('h6').textContent.toLowerCase();
                        const productSku = row.querySelector('small').textContent.toLowerCase();

                        if (productName.includes(searchTerm) || productSku.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>