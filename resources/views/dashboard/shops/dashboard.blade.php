<x-app-layout>
    <!-- Main Content -->
    <main class="main-content">
        <!-- Dashboard Page -->
        <section id="dashboard" class="page-section active">
            <!-- Welcome Message -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h4 class="mb-1">
                                @if(auth()->user()->hasRole('super-admin'))
                                    Welcome back, Super Admin!
                                @elseif(auth()->user()->hasRole('school-admin'))
                                    Welcome to {{ $school->name }} Dashboard!
                                @elseif(auth()->user()->hasRole('shop-owner'))
                                    Welcome to {{ $shop->name }} Dashboard!
                                @endif
                            </h4>
                            <p class="mb-0 opacity-75">
                                @if(auth()->user()->hasRole('super-admin'))
                                    Here's an overview of all schools in the system.
                                @elseif(auth()->user()->hasRole('school-admin'))
                                    Manage your school profile, events, and more.
                                @elseif(auth()->user()->hasRole('shop-owner'))
                                    Manage your shop, products, and school associations.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                @if(auth()->user()->hasRole('super-admin'))
                    <!-- Super Admin Stats -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card stats-card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title opacity-75">Total Schools</h6>
                                        <h2 class="mb-0">{{ $stats['total_schools'] }}</h2>
                                        <small class="opacity-75">Registered institutions</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="fas fa-school fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card stats-card text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title opacity-75">Total Users</h6>
                                        <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                                        <small class="opacity-75">System users</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card stats-card text-white" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title opacity-75">Total Reviews</h6>
                                        <h2 class="mb-0">{{ $stats['total_reviews'] }}</h2>
                                        <small class="opacity-75">Across all schools</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="fas fa-star fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card stats-card text-white" style="background: linear-gradient(135deg, #a8c0ff 0%, #3f2b96 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title opacity-75">Avg Rating</h6>
                                        <h2 class="mb-0">{{ number_format($stats['average_rating'], 1) }}/5</h2>
                                        <small class="opacity-75">Overall satisfaction</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif(auth()->user()->hasRole('school-admin'))
                    <!-- School Admin Stats -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card stats-card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title opacity-75">Profile Visits</h6>
                                        <h2 class="mb-0">{{ $stats['profile_visits'] }}</h2>
                                        <small class="opacity-75">Total visitors</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="fas fa-eye fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card stats-card text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title opacity-75">Reviews</h6>
                                        <h2 class="mb-0">{{ $stats['total_reviews'] }}</h2>
                                        <small class="opacity-75">
                                            Avg: {{ number_format($stats['average_rating'], 1) }}/5
                                        </small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="fas fa-star fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card stats-card text-white" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title opacity-75">Upcoming Events</h6>
                                        <h2 class="mb-0">{{ $stats['total_events'] }}</h2>
                                        <small class="opacity-75">Scheduled activities</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="fas fa-calendar-alt fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card stats-card text-white" style="background: linear-gradient(135deg, #a8c0ff 0%, #3f2b96 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title opacity-75">Branches</h6>
                                        <h2 class="mb-0">{{ $stats['total_branches'] }}</h2>
                                        <small class="opacity-75">School locations</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="fas fa-code-branch fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif(auth()->user()->hasRole('shop-owner'))
                    <!-- Shop Owner Stats -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card stats-card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title opacity-75">Total Products</h6>
                                        <h2 class="mb-0">{{ $stats['total_products'] }}</h2>
                                        <small class="opacity-75">All products</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="fas fa-boxes fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card stats-card text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title opacity-75">Active Products</h6>
                                        <h2 class="mb-0">{{ $stats['active_products'] }}</h2>
                                        <small class="opacity-75">Available for sale</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card stats-card text-white" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title opacity-75">Associated Schools</h6>
                                        <h2 class="mb-0">{{ $stats['total_schools'] }}</h2>
                                        <small class="opacity-75">Partner schools</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="fas fa-handshake fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card stats-card text-white" style="background: linear-gradient(135deg, #a8c0ff 0%, #3f2b96 100%);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title opacity-75">Shop Rating</h6>
                                        <h2 class="mb-0">{{ number_format($stats['average_rating'], 1) }}/5</h2>
                                        <small class="opacity-75">{{ $stats['total_reviews'] }} reviews</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="fas fa-star fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Recent Activity & Alerts -->
            @if(auth()->user()->hasRole('school-admin'))
            <div class="row">
                <!-- Recent Reviews -->
                <div class="col-lg-6 mb-4">
                    <div class="card chart-card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title">Recent Reviews</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($recentReviews as $review)
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $review->reviewer_name ?? 'Anonymous' }}</h6>
                                        <small class="text-muted">
                                            {{ Str::limit($review->review, 50) }}
                                        </small>
                                        <div class="mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-dark">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                @empty
                                <div class="list-group-item text-center text-muted py-4">
                                    <i class="fas fa-comment-slash fa-2x mb-2"></i>
                                    <p>No reviews yet</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="col-lg-6 mb-4">
                    <div class="card chart-card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title">Upcoming Events</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($upcomingEvents as $event)
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-calendar text-success"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $event->event_name }}</h6>
                                        <small class="text-muted">
                                            {{ $event->event_location }} • 
                                            {{ $event->event_date->format('M d, Y') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-success">Upcoming</span>
                                </div>
                                @empty
                                <div class="list-group-item text-center text-muted py-4">
                                    <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                    <p>No upcoming events</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @elseif(auth()->user()->hasRole('shop-owner'))
            <div class="row">
                <!-- Recent Products -->
                <div class="col-lg-6 mb-4">
                    <div class="card chart-card">
                        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Recent Products</h5>
                            <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">Add New</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($recentProducts as $product)
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        @if($product->main_image_url)
                                        <img src="{{ asset('website/'.$product->main_image_url) }}" 
                                             alt="{{ $product->name }}" class="rounded" width="40" height="40">
                                        @else
                                        <i class="fas fa-box text-primary"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ $product->category->name }}</small>
                                        <div class="mt-1">
                                            <span class="badge bg-{{ $product->is_in_stock ? 'success' : 'danger' }}">
                                                {{ $product->is_in_stock ? 'In Stock' : 'Out of Stock' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <strong class="text-primary">Rs {{ number_format($product->current_price, 2) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @empty
                                <div class="list-group-item text-center text-muted py-4">
                                    <i class="fas fa-box-open fa-2x mb-2"></i>
                                    <p>No products yet</p>
                                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">Create First Product</a>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Low Stock & Pending Associations -->
                <div class="col-lg-6 mb-4">
                    <!-- Low Stock Alert -->
                    @if($lowStockProducts->count() > 0)
                    <div class="card chart-card mb-3">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title text-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($lowStockProducts as $product)
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-box text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $product->name }}</h6>
                                        <small class="text-muted">Only {{ $product->stock_quantity }} left</small>
                                    </div>
                                    <span class="badge bg-warning">Restock</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Pending Associations -->
                    @if($pendingAssociations->count() > 0)
                    <div class="card chart-card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title text-info">
                                <i class="fas fa-clock me-2"></i>Pending Associations
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($pendingAssociations as $association)
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-school text-info"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $association->school->name }}</h6>
                                        <small class="text-muted">{{ $association->school->city }}, {{ $association->school->state }}</small>
                                    </div>
                                    <span class="badge bg-warning">Pending</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($lowStockProducts->count() == 0 && $pendingAssociations->count() == 0)
                    <div class="card chart-card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title">Quick Stats</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary mb-0">{{ $stats['out_of_stock'] }}</h4>
                                    <small class="text-muted">Out of Stock</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-warning mb-0">{{ $stats['pending_associations'] }}</h4>
                                    <small class="text-muted">Pending Requests</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if(auth()->user()->hasRole('super-admin'))
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="{{ route('schools.index') }}" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-school me-2"></i>Manage Schools
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="" class="btn btn-outline-success w-100">
                                            <i class="fas fa-users me-2"></i>Manage Users
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="" class="btn btn-outline-warning w-100">
                                            <i class="fas fa-star me-2"></i>View Reviews
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="{{ route('events.index') }}" class="btn btn-outline-info w-100">
                                            <i class="fas fa-calendar me-2"></i>All Events
                                        </a>
                                    </div>
                                @elseif(auth()->user()->hasRole('school-admin'))
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-edit me-2"></i>Edit Profile
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="{{ route('events.create') }}" class="btn btn-outline-success w-100">
                                            <i class="fas fa-plus me-2"></i>Add Event
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="" class="btn btn-outline-warning w-100">
                                            <i class="fas fa-images me-2"></i>Manage Gallery
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="" class="btn btn-outline-info w-100">
                                            <i class="fas fa-file me-2"></i>Create Page
                                        </a>
                                    </div>
                                @elseif(auth()->user()->hasRole('shop-owner'))
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="{{ route('products.create') }}" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-plus me-2"></i>Add Product
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="{{ route('products.index') }}" class="btn btn-outline-success w-100">
                                            <i class="fas fa-boxes me-2"></i>Manage Products
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="{{ route('shops.associations', $shop) }}" class="btn btn-outline-warning w-100">
                                            <i class="fas fa-handshake me-2"></i>School Associations
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <a href="{{ route('shops.edit', $shop) }}" class="btn btn-outline-info w-100">
                                            <i class="fas fa-store me-2"></i>Shop Settings
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>