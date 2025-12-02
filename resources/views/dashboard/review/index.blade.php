<x-app-layout>
    <main class="main-content">
        <section id="reviews" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Review Management</h2>
                    <p class="mb-0 text-muted">Manage and moderate school reviews</p>
                </div>
                <div class="d-flex gap-2">
                    {{-- <a href="{{ route('reviews.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add Review
                    </a> --}}
                </div>
            </div>

            <!-- Review Statistics -->
            <div class="row mb-4">
                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Reviews</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reviewStats['total'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Average Rating</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reviewStats['average_rating'] }}/5</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-star fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        5-Star Reviews</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reviewStats['five_star'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-star-half-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Pending Reviews</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reviewStats['pending'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-2 g-md-3">
                        @role('super-admin')
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">School</label>
                            <select name="school_id" class="form-select" id="schoolFilter">
                                <option value="">All Schools</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endrole
                        
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Branch</label>
                            <select name="branch_id" class="form-select" id="branchFilter">
                                <option value="">All Branches</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-6 col-lg-2">
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-select">
                                <option value="">All Ratings</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-6 col-lg-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-6 col-lg-2">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        
                        <div class="col-12 col-md-6 col-lg-2">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        
                        <div class="col-12">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search reviews, reviewer name, school..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reviews Table -->
            <div class="card">
                <div class="card-body p-0">
                    <form id="bulkActionForm" action="{{ route('reviews.bulk-action') }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="action" id="bulkAction">
                        <input type="hidden" name="reviews" id="bulkReviews">
                    </form>
                    
                    <!-- Bulk Actions Header -->
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">
                                    Select All
                                </label>
                            </div>
                            <span class="text-muted d-none d-md-inline" id="selectedCount">0 selected</span>
                        </div>
                        
                        <!-- Bulk Actions Dropdown -->
                        {{-- <div class="dropdown" id="bulkActionsDropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog me-1"></i> Bulk Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" onclick="performBulkAction('approve')">
                                    <i class="fas fa-check-circle text-success me-2"></i> Approve Selected
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="performBulkAction('reject')">
                                    <i class="fas fa-times-circle text-danger me-2"></i> Reject Selected
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="performBulkAction('delete')">
                                    <i class="fas fa-trash me-2"></i> Delete Selected
                                </a></li>
                            </ul>
                        </div> --}}
                    </div>
                    
                    <!-- Table Container -->
                    <div class="position-relative">
                        <div class="table-responsive-lg">
                            <table class="table table-hover table-borderless mb-0">
                                <thead class="table-light">
                                    <tr>
                                        {{-- <th style="width: 50px; min-width: 50px;">#</th> --}}
                                        <th style="min-width: 150px;">Reviewer</th>
                                        <th style="min-width: 150px;">School/Branch</th>
                                        <th style="min-width: 200px;">Review</th>
                                        <th style="width: 100px;">Rating</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 120px;">Date</th>
                                        <th style="width: 130px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reviews as $review)
                                    <tr>
                                        {{-- <td>
                                            <div class="form-check">
                                                <input class="form-check-input review-checkbox" 
                                                       type="checkbox" 
                                                       value="{{ $review->id }}"
                                                       data-review-id="{{ $review->id }}">
                                            </div>
                                        </td> --}}
                                        <td>
                                            <div class="table-cell-content">
                                                <strong class="d-block text-truncate" style="max-width: 150px;">{{ $review->reviewer_name }}</strong>
                                                @if($review->user)
                                                    <small class="text-muted d-block text-truncate" style="max-width: 150px;">{{ $review->user->email }}</small>
                                                @elseif($review->reviewer_email)
                                                    <small class="text-muted d-block text-truncate" style="max-width: 150px;">{{ $review->reviewer_email }}</small>
                                                @endif
                                                @if($review->created_by_admin)
                                                    <span class="badge bg-info mt-1">Added by Admin</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="table-cell-content">
                                                <strong class="d-block text-truncate" style="max-width: 150px;">{{ $review->school->name }}</strong>
                                                @if($review->branch)
                                                    <small class="text-muted d-block text-truncate" style="max-width: 150px;">{{ $review->branch->name }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="table-cell-content">
                                                <div class="review-text text-truncate" style="max-width: 200px;">
                                                    {{ $review->review }}
                                                </div>
                                                @if(strlen($review->review) > 50)
                                                    <a href="javascript:void(0)" class="text-primary read-more-btn small" 
                                                       data-bs-toggle="modal" data-bs-target="#reviewModal{{ $review->id }}">
                                                        Read more
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="rating-stars d-inline-flex align-items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star text-warning" style="font-size: 0.875rem;"></i>
                                                    @else
                                                        <i class="far fa-star text-muted" style="font-size: 0.875rem;"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-1 small">{{ $review->rating }}.0</span>
                                            </div>
                                        </td>
                                        <td>
                                            {!! $review->status_badge !!}
                                        </td>
                                        <td>
                                            <small class="text-muted d-block">{{ $review->created_at->format('M j, Y') }}</small>
                                            <small class="text-muted">{{ $review->created_at->format('g:i A') }}</small>
                                        </td>
                                        <td>
                                            <!-- Mobile Actions -->
                                            <div class="d-block d-md-none">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100" 
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i> Actions
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1060;">
                                                        <li>
                                                            <a href="{{ route('reviews.show', $review) }}" class="dropdown-item">
                                                                <i class="fas fa-eye me-2"></i> View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('reviews.edit', $review) }}" class="dropdown-item">
                                                                <i class="fas fa-edit me-2"></i> Edit
                                                            </a>
                                                        </li>
                                                        @if($review->status != 'approved')
                                                        <li>
                                                            <a class="dropdown-item update-status" href="#" 
                                                               data-review-id="{{ $review->id }}" data-status="approved">
                                                                <i class="fas fa-check-circle text-success me-2"></i>Approve
                                                            </a>
                                                        </li>
                                                        @endif
                                                        @if($review->status != 'rejected')
                                                        <li>
                                                            <a class="dropdown-item update-status" href="#" 
                                                               data-review-id="{{ $review->id }}" data-status="rejected">
                                                                <i class="fas fa-times-circle text-danger me-2"></i>Reject
                                                            </a>
                                                        </li>
                                                        @endif
                                                        <li>
                                                            <a class="dropdown-item" href="#" 
                                                               data-bs-toggle="modal" data-bs-target="#notesModal{{ $review->id }}">
                                                                <i class="fas fa-sticky-note me-2"></i>Add Notes
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('reviews.destroy', $review) }}" 
                                                                  method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger" 
                                                                        onclick="return confirm('Are you sure you want to delete this review?')">
                                                                    <i class="fas fa-trash me-2"></i>Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <!-- Desktop Actions -->
                                            <div class="d-none d-md-flex align-items-center gap-1">
                                                {{-- <a href="{{ route('reviews.show', $review) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('reviews.edit', $review) }}" 
                                                   class="btn btn-sm btn-outline-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a> --}}
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1060;">
                                                        @if($review->status != 'approved')
                                                        <li>
                                                            <a class="dropdown-item update-status" href="#" 
                                                               data-review-id="{{ $review->id }}" data-status="approved">
                                                                <i class="fas fa-check-circle text-success me-2"></i>Approve
                                                            </a>
                                                        </li>
                                                        @endif
                                                        @if($review->status != 'rejected')
                                                        <li>
                                                            <a class="dropdown-item update-status" href="#" 
                                                               data-review-id="{{ $review->id }}" data-status="rejected">
                                                                <i class="fas fa-times-circle text-danger me-2"></i>Reject
                                                            </a>
                                                        </li>
                                                        @endif
                                                        <li>
                                                            <a class="dropdown-item" href="#" 
                                                               data-bs-toggle="modal" data-bs-target="#notesModal{{ $review->id }}">
                                                                <i class="fas fa-sticky-note me-2"></i>Add Notes
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('reviews.destroy', $review) }}" 
                                                                  method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger" 
                                                                        onclick="return confirm('Are you sure you want to delete this review?')">
                                                                    <i class="fas fa-trash me-2"></i>Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Review Modal -->
                                    <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Review Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <h6>Reviewer:</h6>
                                                        <p>{{ $review->reviewer_name }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <h6>School:</h6>
                                                        <p>{{ $review->school->name }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <h6>Rating:</h6>
                                                        <div class="rating-stars mb-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->rating)
                                                                    <i class="fas fa-star text-warning fa-lg"></i>
                                                                @else
                                                                    <i class="far fa-star text-muted fa-lg"></i>
                                                                @endif
                                                            @endfor
                                                            <span class="ms-2">{{ $review->rating }}.0</span>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <h6>Review:</h6>
                                                        <p>{{ $review->review }}</p>
                                                    </div>
                                                    @if($review->admin_notes)
                                                    <div class="mb-3">
                                                        <h6>Admin Notes:</h6>
                                                        <p class="text-muted">{{ $review->admin_notes }}</p>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notes Modal -->
                                    <div class="modal fade" id="notesModal{{ $review->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add Admin Notes</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('reviews.update', $review) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Admin Notes</label>
                                                            <textarea name="admin_notes" class="form-control" rows="4">{{ $review->admin_notes }}</textarea>
                                                            <small class="text-muted">These notes are only visible to admins.</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save Notes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-comments fa-2x mb-3"></i>
                                                <p>No reviews found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Scroll indicator for mobile -->
                        <div class="scroll-indicator d-md-none">
                            <div class="text-center py-2 bg-light border-top">
                                <small class="text-muted">
                                    <i class="fas fa-arrows-alt-h me-1"></i> Scroll horizontally to view more columns
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    @if($reviews->hasPages())
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top">
                        <div class="mb-2 mb-md-0">
                            <small class="text-muted">
                                Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} reviews
                            </small>
                        </div>
                        <div>
                            <nav aria-label="Reviews pagination">
                                {{ $reviews->withQueryString()->onEachSide(1)->links() }}
                            </nav>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Review Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                        @csrf
                        <input type="hidden" name="review_id" id="reviewId">
                        <input type="hidden" name="status" id="reviewStatus">
                        
                        <div class="mb-3">
                            <label class="form-label">Status Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="Add any notes about this status change..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Fix for dropdown z-index issue */
        .dropdown-menu {
            z-index: 1060 !important;
        }
        
        /* Ensure dropdowns appear above everything on mobile */
        @media (max-width: 768px) {
            .dropdown-menu {
                position: fixed !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                width: 90% !important;
                max-width: 300px;
                max-height: 80vh;
                overflow-y: auto;
                z-index: 1080 !important;
            }
            
            /* Add backdrop for mobile dropdowns */
            .dropdown-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1070 !important;
            }
        }
        
        /* Table responsive styles */
        .table-responsive-lg {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        @media (max-width: 768px) {
            .table-responsive-lg {
                min-height: 400px;
            }
            
            .table {
                font-size: 0.875rem;
            }
            
            .table th,
            .table td {
                padding: 0.5rem;
                vertical-align: middle;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.8125rem;
            }
            
            .badge {
                font-size: 0.75rem;
            }
            
            /* Hide unnecessary columns on mobile */
            .table th:nth-child(3),  /* School/Branch */
            .table td:nth-child(3) {
                max-width: 100px;
            }
            
            .table th:nth-child(4),  /* Review */
            .table td:nth-child(4) {
                max-width: 150px;
            }
        }
        
        @media (max-width: 576px) {
            .table {
                font-size: 0.8125rem;
            }
            
            .btn-sm {
                padding: 0.2rem 0.4rem;
                font-size: 0.75rem;
            }
            
            .badge {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }
            
            .table th,
            .table td {
                padding: 0.375rem;
            }
        }
        
        /* Text truncation */
        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .table-cell-content {
            min-height: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        /* Rating stars */
        .rating-stars i {
            margin-right: 1px;
        }
        
        /* Review text */
        .review-text {
            line-height: 1.4;
            margin-bottom: 0.25rem;
        }
        
        .read-more-btn {
            font-size: 0.8125rem;
        }
        
        /* Ensure table headers stick on scroll */
        .table thead th {
            position: sticky;
            top: 0;
            background: #f8f9fa;
            z-index: 10;
        }
        
        /* Scroll indicator */
        .scroll-indicator {
            position: sticky;
            bottom: 0;
            background: linear-gradient(to right, transparent, #f8f9fa, transparent);
            z-index: 5;
        }
        
        /* Ensure dropdown buttons don't break line */
        .dropdown-toggle::after {
            margin-left: 0.255em;
            vertical-align: 0.255em;
        }
        
        /* Bulk actions dropdown fix */
        #bulkActionsDropdown .dropdown-menu {
            min-width: 200px;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status update functionality
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        const statusForm = document.getElementById('statusForm');
        const confirmBtn = document.getElementById('confirmStatusUpdate');
        
        // School filter change - load branches
        const schoolFilter = document.getElementById('schoolFilter');
        const branchFilter = document.getElementById('branchFilter');
        
        if (schoolFilter) {
            schoolFilter.addEventListener('change', function() {
                const schoolId = this.value;
                
                if (schoolId) {
                    fetch(`/dashboard/reviews/get-branches?school_id=${schoolId}`)
                        .then(response => response.json())
                        .then(data => {
                            branchFilter.innerHTML = '<option value="">All Branches</option>';
                            data.forEach(branch => {
                                const option = document.createElement('option');
                                option.value = branch.id;
                                option.textContent = branch.name;
                                branchFilter.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    branchFilter.innerHTML = '<option value="">All Branches</option>';
                }
            });
        }
        
        // Mobile dropdown backdrop
        document.querySelectorAll('.dropdown-toggle').forEach(button => {
            button.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    const dropdown = this.closest('.dropdown');
                    const menu = dropdown.querySelector('.dropdown-menu');
                    
                    // Remove existing backdrop
                    document.querySelectorAll('.dropdown-backdrop').forEach(backdrop => {
                        backdrop.remove();
                    });
                    
                    // Create backdrop
                    const backdrop = document.createElement('div');
                    backdrop.className = 'dropdown-backdrop';
                    backdrop.addEventListener('click', function() {
                        menu.classList.remove('show');
                        this.remove();
                    });
                    
                    document.body.appendChild(backdrop);
                }
            });
        });
        
        // Close mobile dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && !e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
                document.querySelectorAll('.dropdown-backdrop').forEach(backdrop => {
                    backdrop.remove();
                });
            }
        });
        
        // Update selected count
        function updateSelectedCount() {
            const selected = document.querySelectorAll('.review-checkbox:checked');
            const countElement = document.getElementById('selectedCount');
            if (countElement) {
                countElement.textContent = `${selected.length} selected`;
            }
        }
        
        // Select All functionality
        const selectAll = document.getElementById('selectAll');
        const reviewCheckboxes = document.querySelectorAll('.review-checkbox');
        
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                reviewCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });
        }
        
        // Individual checkbox selection
        reviewCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedCount();
            });
        });
        
        // Status update
        document.querySelectorAll('.update-status').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const reviewId = this.getAttribute('data-review-id');
                const status = this.getAttribute('data-status');
                
                document.getElementById('reviewId').value = reviewId;
                document.getElementById('reviewStatus').value = status;
                
                statusModal.show();
            });
        });
        
        confirmBtn.addEventListener('click', function() {
            const formData = new FormData(statusForm);
            const reviewId = formData.get('review_id');
            
            fetch(`/dashboard/reviews/${reviewId}/update-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: formData.get('status'),
                    notes: formData.get('notes')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusModal.hide();
                    showToast('Review status updated successfully!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while updating status', 'error');
            });
        });
        
        function performBulkAction(action) {
            const selectedReviews = Array.from(reviewCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selectedReviews.length === 0) {
                showToast('Please select at least one review.', 'warning');
                return;
            }
            
            let confirmMessage = '';
            switch(action) {
                case 'delete':
                    confirmMessage = 'Are you sure you want to delete the selected reviews?';
                    break;
                case 'approve':
                    confirmMessage = 'Are you sure you want to approve the selected reviews?';
                    break;
                case 'reject':
                    confirmMessage = 'Are you sure you want to reject the selected reviews?';
                    break;
            }
            
            if (!confirm(confirmMessage)) {
                return;
            }
            
            document.getElementById('bulkAction').value = action;
            document.getElementById('bulkReviews').value = JSON.stringify(selectedReviews);
            document.getElementById('bulkActionForm').submit();
        }
        
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.style.zIndex = '1090';
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', function () {
                document.body.removeChild(toast);
            });
        }
    });
    </script>
</x-app-layout>