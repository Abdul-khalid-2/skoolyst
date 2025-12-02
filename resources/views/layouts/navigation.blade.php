    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header d-flex justify-content-between align-items-center">
            <h4 class="text-white mb-0">
                <a class="navbar-brand text-white" href="{{ url('/') }}">SKOOLYST</a>
            </h4>
            <button class="btn btn-link d-md-none p-0 text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <ul class="sidebar-nav list-unstyled">

            {{-- ===================== COMMON ITEMS FOR ALL ROLES ===================== --}}
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" 
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- ===================== SUPER ADMIN MENU ===================== --}}
            @role('super-admin')

                <li class="nav-item">
                    <a href="{{ route('schools.index') }}" 
                    class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}">
                        <i class="fas fa-school"></i>
                        <span>Schools</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('announcements.index') }}"
                    class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                        <i class="fas fa-bullhorn"></i>
                        <span>Announcements</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.blog-posts.index') }}"
                    class="nav-link {{ request()->routeIs('admin.blog-posts.*') ? 'active' : '' }}">
                        <i class="fas fa-blog me-1"></i>
                        <span>Posts</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('shops.index') }}"
                    class="nav-link {{ request()->routeIs('shops.*') ? 'active' : '' }}">
                        <i class="fas fa-store me-1"></i>
                        <span>Shops</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('events.index') }}"
                    class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Events</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.blog-categories.index') }}"
                    class="nav-link {{ request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}">
                        <i class="fas fa-list-alt"></i>
                        <span>Blog Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('product-categories.index') }}"
                    class="nav-link {{ request()->routeIs('product-categories.*') ? 'active' : '' }}">
                        <i class="fas fa-list-alt"></i>
                        <span>Productes Categories</span>
                    </a>
                </li>

            @endrole


            {{-- ===================== SCHOOL ADMIN MENU ===================== --}}
            @role('school-admin')

                <li class="nav-item">
                    <a href="{{ route('schools.index') }}"
                    class="nav-link {{ request()->routeIs('schools.*') ? 'active' : '' }}">
                        <i class="fas fa-school"></i>
                        <span>Schools</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('announcements.index') }}"
                    class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                        <i class="fas fa-bullhorn"></i>
                        <span>Announcements</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.blog-posts.index') }}"
                    class="nav-link {{ request()->routeIs('admin.blog-posts.*') ? 'active' : '' }}">
                        <i class="fas fa-blog"></i>
                        <span>Posts</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('shops.index') }}"
                    class="nav-link {{ request()->routeIs('shops.*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <span>Shops</span>
                    </a>
                </li>

            @endrole


            {{-- ===================== SHOP OWNER MENU ===================== --}}
            @role('shop-owner')

                <li class="nav-item">
                    <a href="{{ route('shops.index') }}"
                    class="nav-link {{ request()->routeIs('shops.*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <span>My Shop</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products.index') }}"
                    class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <span>Productcs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard.orders.index') }}"
                    class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <span>Orders</span>
                    </a>
                </li>

            @endrole

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
                <button class="btn btn-link position-relative" data-bs-toggle="dropdown" id="contactNotifications">
                    <i class="fas fa-bell text-gray-600"></i>
                    @php
                    $newInquiriesCount = \App\Models\ContactInquiry::forSchool(auth()->user()->school_id)
                    ->where('status', 'new')
                    ->count();
                    
                    // For shop owners, also count new orders
                    $newOrdersCount = 0;
                    $totalNotifications = $newInquiriesCount;
                    
                    if (auth()->user()->hasRole('shop-owner') && auth()->user()->shop_id) {
                        $newOrdersCount = \App\Models\Order::where('shop_id', auth()->user()->shop_id)
                            ->whereIn('status', ['pending', 'processing'])
                            ->count();
                        
                        // Total notifications (orders + inquiries)
                        $totalNotifications = $newOrdersCount + $newInquiriesCount;
                    }
                    @endphp
                    @if($totalNotifications > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="font-size: 0.6rem;" id="notificationBadge">
                        {{ $totalNotifications }}
                    </span>
                    @endif
                </button>
                @role('school-admin')
                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px;" id="contactNotificationsDropdown">
                    <li class="dropdown-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Contact Inquiries</strong>
                            @if($newInquiriesCount > 0)
                            <span class="badge bg-primary">{{ $newInquiriesCount }} new</span>
                            @endif
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-item-text">
                            @php
                            $recentInquiries = \App\Models\ContactInquiry::with(['user', 'branch'])
                            ->forSchool(auth()->user()->school_id)
                            ->latest()
                            ->limit(5)
                            ->get();
                            @endphp

                            @if($recentInquiries->count() > 0)
                            <div class="notification-list">
                                @foreach($recentInquiries as $inquiry)
                                <div class="notification-item p-2 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-truncate" style="max-width: 200px;">
                                                {{ $inquiry->name }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $inquiry->full_subject }}
                                            </small>
                                            <div class="text-truncate small" style="max-width: 250px;">
                                                {{ Str::limit($inquiry->message, 50) }}
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-{{ $inquiry->status === 'new' ? 'danger' : 'secondary' }} badge-sm">
                                                {{ ucfirst($inquiry->status) }}
                                            </span>
                                            <div class="text-muted small">
                                                {{ $inquiry->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-3 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p class="mb-0">No contact inquiries yet</p>
                            </div>
                            @endif
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-center" href="{{ route('admin.inquiries.index') }}">
                            <i class="fas fa-eye me-1"></i> View All Inquiries
                        </a>
                    </li>
                </ul>
                @endrole
                
                @role('shop-owner')
                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 350px;" id="shopNotificationsDropdown">
                    <!-- Orders Tab -->
                    <li class="nav-item">
                        <div class="dropdown-header border-bottom">
                            <ul class="nav nav-pills nav-fill" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#orders-tab" type="button">
                                        <i class="fas fa-shopping-cart me-1"></i> Orders
                                        @if($newOrdersCount > 0)
                                        <span class="badge bg-danger ms-1">{{ $newOrdersCount }}</span>
                                        @endif
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#inquiries-tab" type="button">
                                        <i class="fas fa-envelope me-1"></i> Inquiries
                                        @if($newInquiriesCount > 0)
                                        <span class="badge bg-primary ms-1">{{ $newInquiriesCount }}</span>
                                        @endif
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <div class="tab-content p-2">
                            <!-- Orders Tab Content -->
                            <div class="tab-pane fade show active" id="orders-tab">
                                @php
                                // Fetch recent orders for this shop
                                $recentOrders = \App\Models\Order::with(['user', 'orderItems.product'])
                                    ->where('shop_id', auth()->user()->shop_id)
                                    ->latest()
                                    ->limit(5)
                                    ->get();
                                @endphp

                                @if($recentOrders->count() > 0)
                                <div class="notification-list">
                                    @foreach($recentOrders as $order)
                                    <div class="notification-item p-2 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-truncate" style="max-width: 200px;">
                                                    Order #{{ $order->order_number }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ $order->user->name ?? 'Customer' }}
                                                </small>
                                                <div class="small">
                                                    {{ $order->orderItems->count() }} item(s)
                                                    • {{ number_format($order->total_amount, 2) }} {{ config('app.currency', 'USD') }}
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-{{ 
                                                    $order->status === 'pending' ? 'warning' : 
                                                    ($order->status === 'processing' ? 'info' : 
                                                    ($order->status === 'completed' ? 'success' : 
                                                    ($order->status === 'cancelled' ? 'danger' : 'secondary'))) 
                                                }} badge-sm">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                                <div class="text-muted small">
                                                    {{ $order->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                        @if($order->note)
                                        <div class="small text-muted mt-1">
                                            <i class="fas fa-sticky-note me-1"></i>
                                            {{ Str::limit($order->note, 30) }}
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                    <p class="mb-0">No orders yet</p>
                                </div>
                                @endif
                                
                                <div class="mt-2">
                                    <a class="dropdown-item text-center" href="{{ route('dashboard.orders.index') }}">
                                        <i class="fas fa-list me-1"></i> View All Orders
                                    </a>
                                </div>
                            </div>

                            <!-- Inquiries Tab Content -->
                            <div class="tab-pane fade" id="inquiries-tab">
                                @php
                                $recentInquiries = \App\Models\ContactInquiry::with(['user', 'branch'])
                                    ->forSchool(auth()->user()->school_id)
                                    ->latest()
                                    ->limit(5)
                                    ->get();
                                @endphp

                                @if($recentInquiries->count() > 0)
                                <div class="notification-list">
                                    @foreach($recentInquiries as $inquiry)
                                    <div class="notification-item p-2 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-truncate" style="max-width: 200px;">
                                                    {{ $inquiry->name }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ $inquiry->full_subject }}
                                                </small>
                                                <div class="text-truncate small" style="max-width: 250px;">
                                                    {{ Str::limit($inquiry->message, 50) }}
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-{{ $inquiry->status === 'new' ? 'danger' : 'secondary' }} badge-sm">
                                                    {{ ucfirst($inquiry->status) }}
                                                </span>
                                                <div class="text-muted small">
                                                    {{ $inquiry->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No contact inquiries yet</p>
                                </div>
                                @endif
                                
                                <div class="mt-2">
                                    <a class="dropdown-item text-center" href="{{ route('admin.inquiries.index') }}">
                                        <i class="fas fa-eye me-1"></i> View All Inquiries
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                @endrole
            </div>

            <div class="dropdown">
                <button class="btn btn-link d-flex align-items-center" data-bs-toggle="dropdown">
                    @if (auth()->user()->profile_picture_url)
                    <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->name }}" class="rounded-circle me-2" width="32" height="32">
                    @else
                    <i class="fas fa-user me-2"></i>
                    @endif
                    <span class="text-gray-700 d-none d-sm-inline">{{ auth()->user()->name }}</span>
                    <i class="fas fa-chevron-down ms-2 text-gray-500"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('user_profile.show') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
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
                    </li>
                </ul>
            </div>
        </div>
    </header>