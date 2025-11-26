<x-app-layout>
    <main class="main-content">
        <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>
        <section id="shop-details" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Shop Details</h2>
                    <p class="mb-0 text-muted">View and manage shop information</p>
                </div>
                <div class="btn-group">

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
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded association-item" data-association-id="{{ $association->id }}">
                                    <div>
                                        <strong>{{ $association->school->name }}</strong>
                                        <br>
                                        <small class="text-muted text-capitalize">
                                            {{ str_replace('_', ' ', $association->association_type) }}
                                        </small>
                                        @if($association->approved_at)
                                            <br>
                                            <small class="text-muted">
                                                Approved: {{ $association->approved_at->format('M j, Y') }}
                                            </small>
                                        @endif
                                    </div>
                                    
                                    @if (Auth::user()->school_id === $association->school_id)
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    type="button" 
                                                    id="statusDropdown{{ $association->id }}" 
                                                    data-bs-toggle="dropdown" 
                                                    aria-expanded="false">
                                                <span class="status-badge badge {{ $association->status == 'approved' ? 'bg-success' : ($association->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                                    {{ ucfirst($association->status) }}
                                                </span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $association->id }}">
                                                <li>
                                                    <a class="dropdown-item status-option" href="#" data-status="pending">
                                                        <span class="badge bg-warning me-2">Pending</span> Set as Pending
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item status-option" href="#" data-status="approved">
                                                        <span class="badge bg-success me-2">Approved</span> Approve Association
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item status-option" href="#" data-status="rejected">
                                                        <span class="badge bg-danger me-2">Rejected</span> Reject Association
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @else
                                        <span class="badge {{ $association->status == 'approved' ? 'bg-success' : ($association->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                            {{ ucfirst($association->status) }}
                                        </span>
                                    @endif
                                </div>
                                @endforeach
                                
                                {{-- <div class="text-center mt-2">
                                    <a href="{{ route('shops.associations', $shop) }}" class="btn btn-sm btn-outline-primary">
                                        View All ({{ $shop->schoolAssociations->count() }})
                                    </a>
                                </div> --}}
                            @else
                                <p class="text-muted text-center mb-0">No school associations</p>
                                {{-- <div class="text-center mt-2">
                                    <a href="{{ route('shops.associations', $shop) }}" class="btn btn-sm btn-primary">
                                        Associate School
                                    </a>
                                </div> --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Status update handler
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('status-option') || e.target.closest('.status-option')) {
                    e.preventDefault();
                    
                    const statusOption = e.target.classList.contains('status-option') ? e.target : e.target.closest('.status-option');
                    const associationItem = statusOption.closest('.association-item');
                    const associationId = associationItem.dataset.associationId;
                    const newStatus = statusOption.dataset.status;
                    const dropdownButton = associationItem.querySelector('.dropdown-toggle');
                    const statusBadge = associationItem.querySelector('.status-badge');
                    
                    // Show loading state
                    const originalHTML = dropdownButton.innerHTML;
                    dropdownButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
                    dropdownButton.disabled = true;
                    
                    // Get CSRF token safely
                    let csrfToken = '';
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    if (csrfMeta) {
                        csrfToken = csrfMeta.getAttribute('content');
                    } else {
                        console.error('CSRF token not found');
                        showToast('Error', 'Security token missing. Please refresh the page.', 'error');
                        dropdownButton.innerHTML = originalHTML;
                        dropdownButton.disabled = false;
                        return;
                    }
                    
                    // Make API request
                    fetch(`/shop-school-associations/${associationId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 403) {
                                throw new Error('You are not authorized to perform this action.');
                            }
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Update the badge
                            statusBadge.className = 'status-badge badge ' + getStatusBadgeClass(data.data.status);
                            statusBadge.textContent = data.data.status.charAt(0).toUpperCase() + data.data.status.slice(1);
                            
                            // Show success message
                            showToast('Success', data.message, 'success');
                            
                            // Update any additional data if shown
                            updateAssociationDetails(associationItem, data.data);
                        } else {
                            showToast('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating status:', error);
                        showToast('Error', error.message || 'Failed to update status. Please try again.', 'error');
                    })
                    .finally(() => {
                        // Restore button state
                        dropdownButton.innerHTML = originalHTML;
                        dropdownButton.disabled = false;
                        
                        // Close dropdown
                        const dropdownMenu = associationItem.querySelector('.dropdown-menu');
                        if (dropdownMenu) {
                            dropdownMenu.classList.remove('show');
                        }
                    });
                }
            });
            
            // Helper function to get badge class based on status
            function getStatusBadgeClass(status) {
                const badgeClasses = {
                    'pending': 'bg-warning',
                    'approved': 'bg-success',
                    'rejected': 'bg-danger'
                };
                return badgeClasses[status] || 'bg-secondary';
            }
            
            // Helper function to update association details
            function updateAssociationDetails(associationItem, data) {
                // Update approved date if it exists in the data
                if (data.approved_at) {
                    let approvedDateElement = associationItem.querySelector('.approved-date');
                    if (!approvedDateElement) {
                        // Create the element if it doesn't exist
                        const small = document.createElement('small');
                        small.className = 'text-muted approved-date';
                        associationItem.querySelector('div').appendChild(small);
                        approvedDateElement = associationItem.querySelector('.approved-date');
                    }
                    approvedDateElement.textContent = 'Approved: ' + data.approved_at;
                } else {
                    // Remove approved date if status changed from approved
                    const approvedDateElement = associationItem.querySelector('.approved-date');
                    if (approvedDateElement) {
                        approvedDateElement.remove();
                    }
                }
            }
            
            // Toast notification function
            function showToast(title, message, type = 'info') {
                // Create toast container if it doesn't exist
                let toastContainer = document.getElementById('toast-container');
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.id = 'toast-container';
                    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                    toastContainer.style.zIndex = '1055';
                    document.body.appendChild(toastContainer);
                }
                
                const toastId = 'toast-' + Date.now();
                const bgClass = type === 'error' ? 'text-bg-danger' : type === 'success' ? 'text-bg-success' : 'text-bg-info';
                
                const toastHtml = `
                    <div id="${toastId}" class="toast align-items-center ${bgClass} border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                <strong>${title}:</strong> ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;
                
                toastContainer.insertAdjacentHTML('beforeend', toastHtml);
                
                const toastElement = new bootstrap.Toast(document.getElementById(toastId));
                toastElement.show();
                
                // Remove toast after it's hidden
                document.getElementById(toastId).addEventListener('hidden.bs.toast', function() {
                    this.remove();
                });
            }
        });
    </script>
</x-app-layout>