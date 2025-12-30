<x-app-layout>
    <main class="main-content">
        <section id="branch-show" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Branch Details: {{ $branch->name }}</h2>
                    <p class="mb-0 text-muted">View complete information about this branch</p>
                </div>
                <div>
                    <a href="{{ route('schools.branches.images.index', [$school, $branch]) }}" class="btn btn-info me-2">
                        <i class="fas fa-images me-2"></i> Manage Images
                    </a>
                    <a href="{{ route('schools.branches.edit', [$school, $branch]) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-2"></i> Edit Branch
                    </a>
                    <a href="{{ route('schools.branches.index', $school) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Branches
                    </a>
                </div>
            </div>

            <!-- Branch Banner/Featured Image -->
            @if($branch->mainBanner())
            <div class="card mb-4">
                <div class="card-body p-0">
                    <img src="{{ asset('website/'.$branch->mainBanner()->image_path) }}" 
                         alt="{{ $branch->mainBanner()->title }}" 
                         class="img-fluid rounded-top" 
                         style="max-height: 300px; width: 100%; object-fit: cover;">
                </div>
            </div>
            @endif

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Branch Information Card -->
                    <div class="card mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Branch Information</h5>
                            @if($branch->is_main_branch)
                            <span class="badge bg-primary">Main Branch</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong class="d-block text-muted mb-1">Branch Name</strong>
                                        <p class="mb-0 fs-5">{{ $branch->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong class="d-block text-muted mb-1">School</strong>
                                        <p class="mb-0 fs-5">{{ $school->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <strong class="d-block text-muted mb-1">Status</strong>
                                        @if($branch->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <strong class="d-block text-muted mb-1">School Type</strong>
                                        <p class="mb-0">
                                            @if($branch->school_type)
                                            {{ $branch->school_type }}
                                            @else
                                            <span class="text-muted">Not specified</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <strong class="d-block text-muted mb-1">Contact Number</strong>
                                        <p class="mb-0">{{ $branch->contact_number ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="mb-4">
                                <strong class="d-block text-muted mb-2">Location</strong>
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="mb-1"><strong>Address:</strong> {{ $branch->address }}</p>
                                        <p class="mb-1"><strong>City:</strong> {{ $branch->city }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        @if($branch->latitude && $branch->longitude)
                                        <p class="mb-1"><strong>Coordinates:</strong></p>
                                        <p class="mb-0 text-muted small">{{ $branch->latitude }}, {{ $branch->longitude }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Branch Head Information -->
                            <div class="mb-4">
                                <strong class="d-block text-muted mb-2">Branch Leadership</strong>
                                <p class="mb-0">{{ $branch->branch_head_name ?? 'Not specified' }}</p>
                            </div>

                            <!-- Description -->
                            @if($branch->description)
                            <div class="mb-4">
                                <strong class="d-block text-muted mb-2">Description</strong>
                                <div class="border rounded p-3 bg-light">
                                    {!! nl2br(e($branch->description)) !!}
                                </div>
                            </div>
                            @endif

                            <!-- Academic Information -->
                            <div class="row mb-4">
                                @if($branch->curriculums)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong class="d-block text-muted mb-2">Curriculums Offered</strong>
                                        <div class="border rounded p-3 bg-light">
                                            {!! nl2br(e($branch->curriculums)) !!}
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($branch->classes)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong class="d-block text-muted mb-2">Classes Offered</strong>
                                        <div class="border rounded p-3 bg-light">
                                            {!! nl2br(e($branch->classes)) !!}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Fee Structure -->
                            @if($branch->fee_structure)
                            <div class="mb-4">
                                <strong class="d-block text-muted mb-2">Fee Structure</strong>
                                <div class="border rounded p-3 bg-light">
                                    {!! nl2br(e($branch->fee_structure)) !!}
                                </div>
                            </div>
                            @endif

                            <!-- Features -->
                            @php
                                $selectedFeatures = $branch->getFeaturesArray();
                                $featuresList = $selectedFeatures ? App\Models\Feature::whereIn('id', $selectedFeatures)->get() : collect();
                            @endphp
                            
                            @if($featuresList->count() > 0)
                            <div class="mb-4">
                                <strong class="d-block text-muted mb-2">Features & Facilities</strong>
                                <div class="row">
                                    @foreach($featuresList as $feature)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="d-flex align-items-center">
                                            @if($feature->icon)
                                            <i class="{{ $feature->icon }} me-2 text-primary"></i>
                                            @else
                                            <i class="fas fa-check-circle me-2 text-success"></i>
                                            @endif
                                            <span>{{ $feature->name }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Timestamps -->
                            <div class="border-top pt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Created: {{ $branch->created_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <small class="text-muted">Last Updated: {{ $branch->updated_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Gallery Preview -->
                    @if($branch->images->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Image Gallery</h5>
                            <a href="{{ route('schools.branches.images.index', [$school, $branch]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-images me-1"></i> Manage All Images
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($branch->images->take(12) as $image)
                                <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                    <div class="position-relative">
                                        <img src="{{ asset('website/'.$image->image_path) }}" 
                                             alt="{{ $image->title }}" 
                                             class="img-fluid rounded shadow-sm"
                                             style="height: 150px; width: 100%; object-fit: cover; cursor: pointer;"
                                             onclick="openImageModal('{{ asset('website/'.$image->image_path) }}', '{{ $image->title }}')">
                                        
                                        @if($image->is_featured)
                                        <span class="badge bg-success position-absolute top-0 start-0 m-2">
                                            <i class="fas fa-star"></i>
                                        </span>
                                        @endif
                                        
                                        @if($image->is_main_banner)
                                        <span class="badge bg-primary position-absolute top-0 end-0 m-2">
                                            <i class="fas fa-image"></i>
                                        </span>
                                        @endif
                                        
                                        <span class="badge bg-info position-absolute bottom-0 start-0 m-2">
                                            {{ ucfirst($image->type) }}
                                        </span>
                                    </div>
                                    <p class="mt-2 mb-0 small text-truncate">{{ $image->title }}</p>
                                </div>
                                @endforeach
                            </div>
                            
                            @if($branch->images->count() > 12)
                            <div class="text-center mt-3">
                                <a href="{{ route('schools.branches.images.index', [$school, $branch]) }}" class="btn btn-outline-primary">
                                    View All {{ $branch->images->count() }} Images
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Events Section -->
                    @if($branch->events->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Upcoming Events</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach($branch->events->where('event_date', '>=', now())->take(5) as $event)
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $event->event_name }}</h6>
                                        <small class="text-muted">{{ $event->event_date->format('M d, Y') }}</small>
                                    </div>
                                    <p class="mb-1">{{ Str::limit($event->event_description, 100) }}</p>
                                    <small class="text-muted">{{ $event->event_location }}</small>
                                </a>
                                @endforeach
                            </div>
                            @if($branch->events->where('event_date', '>=', now())->count() > 5)
                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-sm btn-outline-secondary">View All Events</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Reviews Section -->
                    @if($branch->reviews->count() > 0)
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Recent Reviews</h5>
                        </div>
                        <div class="card-body">
                            @foreach($branch->reviews->take(3) as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong>{{ $review->reviewer_name }}</strong>
                                        <div class="small text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $review->rating ? '' : '-empty' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                </div>
                                <p class="mb-0">{{ Str::limit($review->review, 150) }}</p>
                            </div>
                            @endforeach
                            
                            @if($branch->reviews->count() > 3)
                            <div class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-secondary">View All {{ $branch->reviews->count() }} Reviews</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Quick Actions Card -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('schools.branches.edit', [$school, $branch]) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i> Edit Branch Details
                                </a>
                                
                                <a href="{{ route('schools.branches.images.index', [$school, $branch]) }}" class="btn btn-info">
                                    <i class="fas fa-images me-2"></i> Manage Images
                                </a>
                                
                                <a href="#" class="btn btn-outline-primary">
                                    <i class="fas fa-calendar-plus me-2"></i> Add Event
                                </a>
                                
                                <a href="#" class="btn btn-outline-secondary">
                                    <i class="fas fa-map-marked-alt me-2"></i> View on Map
                                </a>
                                
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBranchModal">
                                    <i class="fas fa-trash me-2"></i> Delete Branch
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Card -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Branch Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="stat-box">
                                        <div class="stat-number text-primary">{{ $branch->events_count }}</div>
                                        <div class="stat-label text-muted small">Events</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-box">
                                        <div class="stat-number text-success">{{ $branch->reviews_count }}</div>
                                        <div class="stat-label text-muted small">Reviews</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-box">
                                        <div class="stat-number text-info">{{ $branch->images->count() }}</div>
                                        <div class="stat-label text-muted small">Images</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-box">
                                        <div class="stat-number text-warning">{{ $featuresList->count() }}</div>
                                        <div class="stat-label text-muted small">Features</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Status</span>
                                    @if($branch->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-muted">Main Branch</span>
                                    @if($branch->is_main_branch)
                                    <span class="badge bg-primary">Yes</span>
                                    @else
                                    <span class="badge bg-secondary">No</span>
                                    @endif
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-muted">Created</span>
                                    <small class="text-muted">{{ $branch->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Types Summary -->
                    @if($branch->images->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Image Summary</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $imageTypes = $branch->images->groupBy('type');
                            @endphp
                            
                            @foreach($imageTypes as $type => $images)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-capitalize">{{ $type }}</span>
                                <span class="badge bg-primary">{{ $images->count() }}</span>
                            </div>
                            @endforeach
                            
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <strong>Total Images</strong>
                                <strong class="text-primary">{{ $branch->images->count() }}</strong>
                            </div>
                            
                            @if($branch->mainBanner())
                            <div class="alert alert-info mt-3 mb-0 py-2">
                                <i class="fas fa-image me-2"></i>
                                <small>Main banner image is set</small>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Contact Information Card -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Contact Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong class="d-block text-muted small mb-1">Branch Head</strong>
                                <p class="mb-0">{{ $branch->branch_head_name ?? 'Not specified' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong class="d-block text-muted small mb-1">Contact Number</strong>
                                <p class="mb-0">{{ $branch->contact_number ?? 'Not provided' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong class="d-block text-muted small mb-1">Address</strong>
                                <p class="mb-0 small">{{ $branch->address }}</p>
                                <p class="mb-0 small">{{ $branch->city }}</p>
                            </div>
                            
                            @if($school->email || $school->website)
                            <div class="border-top pt-3">
                                <strong class="d-block text-muted small mb-2">School Contact</strong>
                                
                                @if($school->email)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope text-muted me-2"></i>
                                    <small>{{ $school->email }}</small>
                                </div>
                                @endif
                                
                                @if($school->website)
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-globe text-muted me-2"></i>
                                    <small>{{ $school->website }}</small>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid rounded">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('schools.branches.images.index', [$school, $branch]) }}" class="btn btn-primary">
                        <i class="fas fa-images me-2"></i> Manage Images
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Branch Modal -->
    <div class="modal fade" id="deleteBranchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone.
                    </div>
                    <p>Are you sure you want to delete the branch <strong>"{{ $branch->name }}"</strong>?</p>
                    <p class="text-danger mb-0">This will also delete:</p>
                    <ul class="text-danger">
                        <li>All branch images ({{ $branch->images->count() }} files)</li>
                        <li>All associated events ({{ $branch->events_count }})</li>
                        <li>All associated reviews ({{ $branch->reviews_count }})</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('schools.branches.destroy', [$school, $branch]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Branch</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

  
    <style>
        .stat-box {
            padding: 10px;
            border-radius: 8px;
            background: #f8f9fa;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            line-height: 1;
        }
        .stat-label {
            font-size: 12px;
            margin-top: 5px;
        }
        .list-group-item {
            border-left: none;
            border-right: none;
        }
        .list-group-item:first-child {
            border-top: none;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
    </style>



    <script>
        function openImageModal(imageSrc, title) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModalTitle').textContent = title;
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        }

        // Auto-open image modal if there's an image ID in URL hash
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash;
            if (hash.startsWith('#image-')) {
                const imageId = hash.replace('#image-', '');
                // You can implement logic to find and open specific image
                // For now, just scroll to gallery section
                document.getElementById('branch-show').scrollIntoView();
            }
        });
    </script>
  
</x-app-layout>