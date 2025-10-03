    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header d-flex justify-content-between align-items-center">
            <h4 class="text-white mb-0">
                <i class="fas fa-store me-2"></i>
                {{ __('Dashboard') }}
            </h4>
            <button class="btn btn-link d-md-none p-0" style="color: white;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="sidebar-nav list-unstyled">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link active">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('schools.index') }}" class="nav-link">
                    <i class="fas fa-box"></i>
                    <span>Schooles</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('events.index') }}" class="nav-link">
                    <i class="fas fa-box"></i>
                    <span>Events</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pages.index') }}" class="nav-link">
                    <i class="fas fa-box"></i>
                    <span>Advertisements</span>
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
                    <li>
                        <form class="dropdown-item" method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>

                        <!-- <a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Logout</a> -->
                    </li>
                </ul>
            </div>
        </div>
    </header>