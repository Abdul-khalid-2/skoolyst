<x-app-layout>
    <main class="main-content">
        <section id="branch-show" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Branch Details: {{ $branch->name }}</h2>
                    <p class="mb-0 text-muted">View complete information about this branch</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('schools.branches.images.index', [$school, $branch]) }}" variant="info" class="me-2">
                        <i class="fas fa-images me-2"></i> Manage Images
                    </x-button>
                    <x-button href="{{ route('schools.branches.edit', [$school, $branch]) }}" variant="primary" class="me-2">
                        <i class="fas fa-edit me-2"></i> Edit Branch
                    </x-button>
                    <x-button href="{{ route('schools.branches.index', $school) }}" variant="secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Branches
                    </x-button>
                </x-slot>
            </x-page-header>

            <!-- Branch Banner/Featured Image -->
            @if($branch->mainBanner())
            <x-card class="mb-4">
                <div class="card-body p-0">
                    <img src="{{ asset('website/'.$branch->mainBanner()->image_path) }}"
                         alt="{{ $branch->mainBanner()->title }}"
                         class="img-fluid rounded-top"
                         style="max-height: 300px; width: 100%; object-fit: cover;">
                </div>
            </x-card>
            @endif

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Branch Information Card -->
                    <x-card class="mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Branch Information</h5>
                            @if($branch->is_main_branch)
                            <x-badge variant="primary">Main Branch</x-badge>
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
                                        <x-badge variant="success">Active</x-badge>
                                        @else
                                        <x-badge variant="secondary">Inactive</x-badge>
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
                    </x-card>

                    <!-- Image Gallery Preview -->
                    @if($branch->images->count() > 0)
                    <x-card class="mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Image Gallery</h5>
                            <x-button href="{{ route('schools.branches.images.index', [$school, $branch]) }}" variant="outline-primary" class="btn-sm">
                                <i class="fas fa-images me-1"></i> Manage All Images
                            </x-button>
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
                                        <x-badge variant="success" class="position-absolute top-0 start-0 m-2">
                                            <i class="fas fa-star"></i>
                                        </x-badge>
                                        @endif

                                        @if($image->is_main_banner)
                                        <x-badge variant="primary" class="position-absolute top-0 end-0 m-2">
                                            <i class="fas fa-image"></i>
                                        </x-badge>
                                        @endif

                                        <x-badge variant="info" class="position-absolute bottom-0 start-0 m-2">
                                            {{ ucfirst($image->type) }}
                                        </x-badge>
                                    </div>
                                    <p class="mt-2 mb-0 small text-truncate">{{ $image->title }}</p>
                                </div>
                                @endforeach
                            </div>
                            
                            @if($branch->images->count() > 12)
                            <div class="text-center mt-3">
                                <x-button href="{{ route('schools.branches.images.index', [$school, $branch]) }}" variant="outline-primary">
                                    View All {{ $branch->images->count() }} Images
                                </x-button>
                            </div>
                            @endif
                        </div>
                    </x-card>
                    @endif

                    <!-- Events Section -->
                    @if($branch->events->count() > 0)
                    <x-card class="mb-4">
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
                                <x-button href="#" variant="outline-secondary" class="btn-sm">View All Events</x-button>
                            </div>
                            @endif
                        </div>
                    </x-card>
                    @endif

                    <!-- Reviews Section -->
                    @if($branch->reviews->count() > 0)
                    <x-card>
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
                                <x-button href="#" variant="outline-secondary" class="btn-sm">View All {{ $branch->reviews->count() }} Reviews</x-button>
                            </div>
                            @endif
                        </div>
                    </x-card>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Quick Actions Card -->
                    <x-card class="mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <x-button href="{{ route('schools.branches.edit', [$school, $branch]) }}" variant="primary">
                                    <i class="fas fa-edit me-2"></i> Edit Branch Details
                                </x-button>

                                <x-button href="{{ route('schools.branches.images.index', [$school, $branch]) }}" variant="info">
                                    <i class="fas fa-images me-2"></i> Manage Images
                                </x-button>

                                <x-button href="#" variant="outline-primary">
                                    <i class="fas fa-calendar-plus me-2"></i> Add Event
                                </x-button>

                                <x-button href="#" variant="outline-secondary">
                                    <i class="fas fa-map-marked-alt me-2"></i> View on Map
                                </x-button>

                                <x-button type="button" variant="outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBranchModal">
                                    <i class="fas fa-trash me-2"></i> Delete Branch
                                </x-button>
                            </div>
                        </div>
                    </x-card>

                    <!-- Statistics Card -->
                    <x-card class="mb-4">
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
                                    <x-badge variant="success">Active</x-badge>
                                    @else
                                    <x-badge variant="secondary">Inactive</x-badge>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-muted">Main Branch</span>
                                    @if($branch->is_main_branch)
                                    <x-badge variant="primary">Yes</x-badge>
                                    @else
                                    <x-badge variant="secondary">No</x-badge>
                                    @endif
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-muted">Created</span>
                                    <small class="text-muted">{{ $branch->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </x-card>

                    <!-- Image Types Summary -->
                    @if($branch->images->count() > 0)
                    <x-card class="mb-4">
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
                                <x-badge variant="primary">{{ $images->count() }}</x-badge>
                            </div>
                            @endforeach
                            
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <strong>Total Images</strong>
                                <strong class="text-primary">{{ $branch->images->count() }}</strong>
                            </div>
                            
                            @if($branch->mainBanner())
                            <x-alert variant="info" :icon="false" :dismissible="false" class="mt-3 mb-0 py-2">
                                <i class="fas fa-image me-2"></i>
                                <small>Main banner image is set</small>
                            </x-alert>
                            @endif
                        </div>
                    </x-card>
                    @endif

                    <!-- Contact Information Card -->
                    <x-card>
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
                    </x-card>
                </div>
            </div>
        </section>
    </main>

    <x-bs-modal id="imageModal" :title="''" labelledBy="imageModalTitle" size="lg">
        <div class="text-center">
            <img id="modalImage" src="" alt="" class="img-fluid rounded">
        </div>
        <x-slot name="footer">
            <x-button type="button" variant="secondary" data-bs-dismiss="modal">Close</x-button>
            <x-button href="{{ route('schools.branches.images.index', [$school, $branch]) }}" variant="primary">
                <i class="fas fa-images me-2"></i> Manage Images
            </x-button>
        </x-slot>
    </x-bs-modal>

    <x-bs-modal id="deleteBranchModal" title="Confirm Delete">
        <x-alert variant="error" :icon="false" :dismissible="false" class="mb-3">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Warning:</strong> This action cannot be undone.
        </x-alert>
        <p>Are you sure you want to delete the branch <strong>"{{ $branch->name }}"</strong>?</p>
        <p class="text-danger mb-0">This will also delete:</p>
        <ul class="text-danger">
            <li>All branch images ({{ $branch->images->count() }} files)</li>
            <li>All associated events ({{ $branch->events_count }})</li>
            <li>All associated reviews ({{ $branch->reviews_count }})</li>
        </ul>
        <x-slot name="footer">
            <x-button type="button" variant="secondary" data-bs-dismiss="modal">Cancel</x-button>
            <form action="{{ route('schools.branches.destroy', [$school, $branch]) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <x-button type="submit" variant="danger">Delete Branch</x-button>
            </form>
        </x-slot>
    </x-bs-modal>

  
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