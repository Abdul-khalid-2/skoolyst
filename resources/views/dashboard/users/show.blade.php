<x-app-layout>
    <main class="main-content">
        <section id="show-user" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">User Details</h2>
                    <p class="text-muted">View complete user information</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit User
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Users
                    </a>
                </div>
            </div>

            <!-- User Profile Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            @if($user->profile_picture)
                                <img src="{{ Storage::url($user->profile_picture) }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle img-thumbnail" 
                                     width="150" 
                                     height="150"
                                     style="object-fit: cover;">
                            @else
                                <div class="avatar-circle mx-auto bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" 
                                     style="width: 150px; height: 150px; font-size: 60px; margin: 0 auto;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h3 class="mb-1">{{ $user->name }}</h3>
                            <p class="text-muted mb-2">
                                <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                                @if($user->phone)
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-phone me-2"></i>{{ $user->phone }}
                                @endif
                            </p>
                            
                            <div class="mb-3">
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary me-1">{{ ucfirst($role->name) }}</span>
                                @endforeach
                                
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-warning">Unverified</span>
                                @endif
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="border rounded p-3 text-center">
                                        <h6 class="text-muted mb-1">UUID</h6>
                                        <p class="mb-0"><code>{{ $user->uuid }}</code></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-3 text-center">
                                        <h6 class="text-muted mb-1">User ID</h6>
                                        <p class="mb-0">#{{ $user->id }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-3 text-center">
                                        <h6 class="text-muted mb-1">School</h6>
                                        <p class="mb-0">
                                            @if($user->school)
                                                <a href="{{ route('schools.show', $user->school->id) }}">
                                                    {{ $user->school->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">No School</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Details Tabs -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="userTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link-custom active" 
                                    id="profile-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#profile" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-user me-2"></i>Profile
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link-custom" 
                                    id="activity-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#activity" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-history me-2"></i>Activity
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link-custom" 
                                    id="reviews-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#reviews" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-star me-2"></i>Reviews
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link-custom" 
                                    id="security-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#security" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-shield-alt me-2"></i>Security
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="userTabsContent">
                        <!-- Profile Tab -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Personal Information</h6>
                                    
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="150">Full Name:</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $user->phone ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <td>{{ $user->address ?? 'Not provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Bio:</th>
                                            <td>{{ $user->bio ?? 'No bio provided' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Account Information</h6>
                                    
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="150">Role:</th>
                                            <td>
                                                @foreach($user->roles as $role)
                                                    <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>School:</th>
                                            <td>
                                                @if($user->school)
                                                    <a href="{{ route('schools.show', $user->school->id) }}">
                                                        {{ $user->school->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Independent User</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Email Status:</th>
                                            <td>
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success">Verified</span>
                                                    <small class="text-muted d-block">
                                                        Verified on {{ $user->email_verified_at->format('M d, Y h:i A') }}
                                                    </small>
                                                @else
                                                    <span class="badge bg-warning">Not Verified</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Tab -->
                        <div class="tab-pane fade" id="activity" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Account Timeline</h6>
                                    
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="150">Created:</th>
                                            <td>{{ $user->created_at->format('F d, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated:</th>
                                            <td>{{ $user->updated_at->format('F d, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Login:</th>
                                            <td>{{ $user->last_login_at ?? 'Not available' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last IP:</th>
                                            <td>{{ $user->last_login_ip ?? 'Not available' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Statistics</h6>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 text-center">
                                                <h3 class="mb-1">{{ $user->reviews_count ?? 0 }}</h3>
                                                <p class="text-muted mb-0">Reviews</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 text-center">
                                                <h3 class="mb-1">{{ $user->orders_count ?? 0 }}</h3>
                                                <p class="text-muted mb-0">Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            @if($user->reviews && $user->reviews->count() > 0)
                                <div class="row">
                                    @foreach($user->reviews as $review)
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0">{{ $review->school->name ?? 'School' }}</h6>
                                                <div>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="mb-2">{{ Str::limit($review->review, 100) }}</p>
                                            <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center text-muted py-4">No reviews found</p>
                            @endif
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Security information and permissions
                            </div>
                            
                            <h6 class="text-muted mb-3">Permissions</h6>
                            @if($user->getAllPermissions()->count() > 0)
                                <div class="row">
                                    @foreach($user->getAllPermissions()->chunk(5) as $chunk)
                                        <div class="col-md-4">
                                            <ul class="list-unstyled">
                                                @foreach($chunk as $permission)
                                                    <li><i class="fas fa-check-circle text-success me-2"></i>{{ $permission->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No specific permissions assigned</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone (for super-admin only) -->
            @role('super-admin')
                <!-- @if(auth()->id() !== $user->id)
                <div class="card mt-4 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Danger Zone</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Delete this user account</h6>
                                <p class="text-muted mb-0">Once deleted, all data associated with this user will be permanently removed.</p>
                            </div>
                            <button type="button" 
                                    class="btn btn-danger" 
                                    onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                <i class="fas fa-trash me-2"></i>Delete User
                            </button>
                        </div>
                    </div>
                </div>
                @endif -->
            @endrole
        </section>
    </main>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
                    <p class="text-danger">This action cannot be undone. All data associated with this user will be permanently removed.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Permanently</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .avatar-circle {
            width: 150px;
            height: 150px;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function confirmDelete(userId, userName) {
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteForm').action = '{{ url("users") }}/' + userId;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
    @endpush
</x-app-layout>