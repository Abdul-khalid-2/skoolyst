<x-app-layout>
    <main class="main-content">
        <section id="shop-details" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Shop Details</h2>
                    <p class="mb-0 text-muted">View and manage shop information</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('shops.edit', $shop) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-edit me-2"></i> Edit
                    </a>
                    <a href="{{ route('shops.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Shops
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
                <!-- Shop Information -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-store me-2"></i>Shop Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Shop Name:</strong></td>
                                            <td>{{ $shop->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Type:</strong></td>
                                            <td>
                                                <span class="badge bg-info text-capitalize">
                                                    {{ str_replace('_', ' ', $shop->shop_type) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $shop->email ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone:</strong></td>
                                            <td>{{ $shop->phone ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $shop->is_active ? 'success' : 'secondary' }}">
                                                    {{ $shop->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                                @if($shop->is_verified)
                                                <span class="badge bg-primary ms-1">Verified</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Rating:</strong></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-star text-warning me-1"></i>
                                                    <span>{{ number_format($shop->rating, 1) }}</span>
                                                    <small class="text-muted ms-1">({{ $shop->total_reviews }} reviews)</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $shop->created_at->format('M j, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Last Updated:</strong></td>
                                            <td>{{ $shop->updated_at->format('M j, Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($shop->description)
                            <div class="mt-3">
                                <strong>Description:</strong>
                                <p class="mb-0 text-muted">{{ $shop->description }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Address Information -->
                    @if($shop->address || $shop->city)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>Location
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($shop->address)
                            <p><strong>Address:</strong><br>{{ $shop->address }}</p>
                            @endif
                            <div class="row">
                                @if($shop->city)
                                <div class="col-md-4">
                                    <strong>City:</strong>
                                    <p class="mb-0">{{ $shop->city }}</p>
                                </div>
                                @endif
                                @if($shop->state)
                                <div class="col-md-4">
                                    <strong>State:</strong>
                                    <p class="mb-0">{{ $shop->state }}</p>
                                </div>
                                @endif
                                @if($shop->country)
                                <div class="col-md-4">
                                    <strong>Country:</strong>
                                    <p class="mb-0">{{ $shop->country }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Quick Stats -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar me-2"></i>Quick Stats
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Total Products:</span>
                                <strong class="text-primary">{{ $shop->products->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>School Associations:</span>
                                <strong class="text-warning">{{ $shop->schoolAssociations->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Active Products:</span>
                                <strong class="text-success">
                                    {{ $shop->products->where('is_active', true)->count() }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <!-- School Associations -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-school me-2"></i>School Associations
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($shop->schoolAssociations->count() > 0)
                                @foreach($shop->schoolAssociations->take(3) as $association)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                    <div>
                                        <strong>{{ $association->school->name }}</strong>
                                        <br>
                                        <small class="text-muted text-capitalize">
                                            {{ str_replace('_', ' ', $association->association_type) }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $association->status == 'approved' ? 'success' : 'warning' }}">
                                        {{ $association->status }}
                                    </span>
                                </div>
                                @endforeach
                                
                                @if($shop->schoolAssociations->count() > 3)
                                <div class="text-center mt-2">
                                    <a href="{{ route('shops.associations', $shop) }}" class="btn btn-sm btn-outline-primary">
                                        View All ({{ $shop->schoolAssociations->count() }})
                                    </a>
                                </div>
                                @endif
                            @else
                                <p class="text-muted text-center mb-0">No school associations</p>
                                <div class="text-center mt-2">
                                    <a href="{{ route('shops.associations', $shop) }}" class="btn btn-sm btn-primary">
                                        Associate School
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>