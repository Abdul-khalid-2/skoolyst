<x-app-layout>
    <!-- Main Content -->
    <main class="main-content">
        <!-- School Show Page -->
        <section id="school-show" class="page-section active">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">School Details</h2>
                    <p class="mb-0 text-muted">View complete information about {{ $school->name }}</p>
                </div>
                <div>
                    <a href="{{ route('schools.edit', $school->id) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-2"></i> Edit School
                    </a>
                    <a href="{{ route('schools.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Schools
                    </a>
                </div>
            </div>

            <!-- School Header Section -->
            <div class="card mb-4 overflow-hidden">
                <div class="school-header-card {{ $school->banner_image ? 'has-banner-image' : '' }}"
                    @if($school->banner_image)
                    style="background-image: url('{{ asset('website/' . $school->banner_image) }}'); height: 200px; background-size: cover; background-position: center;"
                    @else
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 150px;"
                    @endif
                    >
                    <div class="school-header-overlay" style="background: rgba(0,0,0,0.3); position: absolute; top: 0; left: 0; right: 0; bottom: 0;"></div>

                    <div class="container position-relative" style="z-index: 2; height: 100%;">
                        <div class="d-flex align-items-center h-100">


                            <div class="school-text-content text-white">
                                <h1 class="h3 mb-1">{{ $school->banner_title ?? $school->name }}</h1>
                                @if($school->banner_tagline)
                                <p class="mb-1">{{ $school->banner_tagline ?? "" }}</p>
                                @endif
                                <p class="mb-0 opacity-75">{{ $school->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Basic Information Card -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>School Name:</strong>
                                    <p>{{ $school->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>School Type:</strong>
                                    <p>{{ $school->school_type }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Email:</strong>
                                    <p>{{ $school->email ?? 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Contact Number:</strong>
                                    <p>{{ $school->contact_number ?? 'Not provided' }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <strong>Address:</strong>
                                    <p>{{ $school->address }}, {{ $school->city }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Website:</strong>
                                    <p>
                                        @if($school->website)
                                        <a href="{{ $school->website }}" target="_blank">{{ $school->website }}</a>
                                        @else
                                        Not provided
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Created At:</strong>
                                    <p>{{ $school->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- About School Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">About Our School</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $school->description ?? 'No description available for this school.' }}</p>
                        </div>
                    </div>

                    <!-- Quick Facts Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Quick Facts</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar text-primary me-3"></i>
                                        <div>
                                            <strong>Established</strong>
                                            <p class="mb-0">{{ $school->profile->established_year ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users text-primary me-3"></i>
                                        <div>
                                            <strong>Student Strength</strong>
                                            <p class="mb-0">{{ $school->profile->student_strength ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chalkboard-teacher text-primary me-3"></i>
                                        <div>
                                            <strong>Faculty</strong>
                                            <p class="mb-0">{{ $school->profile->faculty_count ?? 'N/A' }} teachers</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-building text-primary me-3"></i>
                                        <div>
                                            <strong>Campus Size</strong>
                                            <p class="mb-0">{{ $school->profile->campus_size ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Quick Facts from JSON -->
                                @if($school->profile && $school->profile->quick_facts)
                                @php
                                $quickFacts = json_decode($school->profile->quick_facts, true);
                                @endphp

                                @foreach($quickFacts as $key => $value)
                                @if(!in_array($key, ['established_year', 'student_strength', 'faculty_count', 'campus_size']))
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-primary me-3"></i>
                                        <div>
                                            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong>
                                            <p class="mb-0">{{ $value }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">School Gallery</h5>
                        </div>
                        <div class="card-body">
                            @if($school->images && $school->images->count() > 0)
                            <div class="row">
                                @foreach($school->images as $image)
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('website/' . $image->image_path) }}"
                                            class="card-img-top"
                                            alt="{{ $image->title ?? 'School Image' }}"
                                            style="height: 200px; object-fit: cover;">
                                        @if($image->title)
                                        <div class="card-footer">
                                            <p class="mb-0">{{ $image->title }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No gallery images available.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Curriculum Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Curriculum & Programs</h5>
                        </div>
                        <div class="card-body">
                            @if($school->curriculums && $school->curriculums->count() > 0)
                            <div class="row">
                                @foreach($school->curriculums as $curriculum)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $curriculum->name }}</h5>
                                            <p class="card-text">{{ $curriculum->description ?? 'No description available.' }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Curriculum information not available.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Facilities Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Facilities & Features</h5>
                        </div>
                        <div class="card-body">
                            @if($school->features && $school->features->count() > 0)
                            <div class="row">
                                @foreach($school->features as $feature)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-{{ $feature->icon ?? 'check' }} text-primary mt-1 me-3"></i>
                                        <div>
                                            <h6 class="mb-1">{{ $feature->name }}</h6>
                                            @if($feature->pivot->description)
                                            <p class="text-muted mb-0 small">{{ $feature->pivot->description }}</p>
                                            @elseif($feature->description)
                                            <p class="text-muted mb-0 small">{{ $feature->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Facilities information not available.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Mission & Vision Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Mission & Vision</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($school->profile && $school->profile->mission)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 text-center">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="fas fa-bullseye fa-2x text-primary"></i>
                                            </div>
                                            <h5>Our Mission</h5>
                                            <p class="card-text">{{ $school->profile->mission }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($school->profile && $school->profile->vision)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 text-center">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="fas fa-eye fa-2x text-primary"></i>
                                            </div>
                                            <h5>Our Vision</h5>
                                            <p class="card-text">{{ $school->profile->vision }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($school->profile && $school->profile->school_motto)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 text-center">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="fas fa-quote-left fa-2x text-primary"></i>
                                            </div>
                                            <h5>Our Motto</h5>
                                            <p class="card-text">"{{ $school->profile->school_motto }}"</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            @if(!$school->profile || (!$school->profile->mission && !$school->profile->vision && !$school->profile->school_motto))
                            <div class="text-center py-5">
                                <i class="fas fa-bullseye fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Mission and vision information not available.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Parent & Student Reviews</h5>
                        </div>
                        <div class="card-body">
                            @if($school->reviews && $school->reviews->count() > 0)
                            <div class="row">
                                @foreach($school->reviews as $review)
                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h6 class="mb-1">
                                                        {{ $review->user_id ? $review->user->name : $review->reviewer_name }}
                                                        @if($review->user_id)
                                                        <span class="badge bg-success ms-1">Verified</span>
                                                        @endif
                                                    </h6>
                                                    <div class="text-warning">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <=$review->rating)
                                                            <i class="fas fa-star"></i>
                                                            @else
                                                            <i class="far fa-star"></i>
                                                            @endif
                                                            @endfor
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                            </div>
                                            <p class="mb-0">{{ $review->review }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No reviews yet.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Events Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Upcoming Events</h5>
                        </div>
                        <div class="card-body">
                            @if($school->events && $school->events->count() > 0)
                            <div class="row">
                                @foreach($school->events as $event)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="bg-primary text-white text-center rounded me-3" style="width: 60px;">
                                                    <div class="p-2">
                                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</div>
                                                        <div class="small">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5 class="card-title mb-1">{{ $event->event_name }}</h5>
                                                    <p class="card-text mb-2">{{ $event->event_description }}</p>
                                                    <div class="d-flex align-items-center text-muted small">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        {{ $event->event_location }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No upcoming events scheduled.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Branches Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Our Branches</h5>
                            <a href="{{ route('schools.branches.create', $school) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i> Add Branch
                            </a>
                        </div>
                        <div class="card-body">
                            @if($school->branches && $school->branches->count() > 0)
                            <div class="row">
                                @foreach($school->branches as $branch)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0">{{ $branch->name }}</h5>
                                                @if($branch->is_main_branch)
                                                <span class="badge bg-primary">Main Branch</span>
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                                    <span>{{ $branch->address }}, {{ $branch->city }}</span>
                                                </div>
                                                @if($branch->contact_number)
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="fas fa-phone text-muted me-2"></i>
                                                    <span>{{ $branch->contact_number }}</span>
                                                </div>
                                                @endif
                                                @if($branch->branch_head_name)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-tie text-muted me-2"></i>
                                                    <span>{{ $branch->branch_head_name }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ route('schools.branches.show', [$school, $branch]) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                                <a href="{{ route('schools.branches.edit', [$school, $branch]) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-code-branch fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No branches information available.</p>
                                <a href="{{ route('schools.branches.create', $school) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add First Branch
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Actions Card -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('schools.edit', $school->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i> Edit School
                                </a>
                                <a href="{{ route('schools.branches.create', $school) }}" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i> Add Branch
                                </a>
                                <form action="{{ route('schools.destroy', $school->id) }}" method="POST" class="d-grid">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this school?')">
                                        <i class="fas fa-trash me-2"></i> Delete School
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- School Statistics -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">School Statistics</h5>
                        </div>
                        <div class="card-body">
                            @if($school->profile && ($school->profile->visitor_count > 0 || $school->profile->total_time_spent > 0))
                            <div class="row text-center">
                                @if($school->profile->visitor_count > 0)
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <h3 class="text-primary mb-1">{{ $school->profile->visitor_count }}</h3>
                                        <small class="text-muted">Profile Visitors</small>
                                    </div>
                                </div>
                                @endif
                                @if($school->profile->total_time_spent > 0)
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <h3 class="text-primary mb-1">{{ round($school->profile->total_time_spent / 60) }}</h3>
                                        <small class="text-muted">Minutes Spent</small>
                                    </div>
                                </div>
                                @endif
                                @if($school->profile->last_visited_at)
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <h5 class="text-primary mb-1">{{ $school->profile->last_visited_at->format('M d, Y') }}</h5>
                                        <small class="text-muted">Last Visited</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @else
                            <p class="text-muted text-center mb-0">No statistics available yet.</p>
                            @endif

                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Total Reviews</span>
                                <span class="badge bg-primary">{{ $school->reviews->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Upcoming Events</span>
                                <span class="badge bg-success">{{ $school->events->where('event_date', '>=', now())->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Total Branches</span>
                                <span class="badge bg-info">{{ $school->branches->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Active Since</span>
                                <span class="badge bg-info">{{ $school->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Contact Information</h5>
                        </div>
                        <div class="card-body">
                            @if($school->contact_number)
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-phone text-muted me-2"></i>
                                <span>{{ $school->contact_number }}</span>
                            </div>
                            @endif
                            @if($school->email)
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-envelope text-muted me-2"></i>
                                <span>{{ $school->email }}</span>
                            </div>
                            @endif
                            @if($school->website)
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-globe text-muted me-2"></i>
                                <a href="{{ $school->website }}" target="_blank">Visit Website</a>
                            </div>
                            @endif
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                <span>{{ $school->address }}, {{ $school->city }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Fee Structure -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Fee Structure</h5>
                        </div>
                        <div class="card-body">
                            @if($school->regular_fees)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Regular Fees:</span>
                                <strong>Rs{{ number_format($school->regular_fees) }}/month</strong>
                            </div>
                            @endif
                            @if($school->discounted_fees)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discounted Fees:</span>
                                <strong>Rs{{ number_format($school->discounted_fees) }}/month</strong>
                            </div>
                            @endif
                            @if($school->admission_fees)
                            <div class="d-flex justify-content-between">
                                <span>Admission Fees:</span>
                                <strong>Rs{{ number_format($school->admission_fees) }}</strong>
                            </div>
                            @endif
                            @if(!$school->regular_fees && !$school->discounted_fees && !$school->admission_fees)
                            <p class="text-muted text-center mb-0">Fee information not available.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Social Media</h5>
                        </div>
                        <div class="card-body">
                            @if($school->profile && $school->profile->social_media)
                            @php
                            $socialMedia = json_decode($school->profile->social_media, true);
                            @endphp

                            <div class="d-flex flex-wrap gap-2">
                                @foreach($socialMedia as $platform => $url)
                                @if($url)
                                <a href="{{ $url }}" target="_blank" class="btn btn-outline-primary btn-sm" title="{{ ucfirst($platform) }}">
                                    <i class="fab fa-{{ $platform }} me-1"></i> {{ ucfirst($platform) }}
                                </a>
                                @endif
                                @endforeach
                            </div>
                            @else
                            <p class="text-muted text-center mb-0">No social media links available.</p>
                            @endif
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Profile Visitors</h5>
                        </div>
                        <div class="card-body">
                            @if($school->profile && ($school->profile->visitor_count > 0 || $school->profile->total_time_spent > 0))
                            <div class="school-statistics">
                                <h3>School Statistics</h3>
                                <div class="stats-grid">
                                    @if($school->profile->visitor_count > 0)
                                    <div class="stat-item">
                                        <div class="stat-number">{{ $school->profile->visitor_count }}</div>
                                        <div class="stat-label">Profile Visitors</div>
                                    </div>
                                    @endif
                                    @if($school->profile->total_time_spent > 0)
                                    <div class="stat-item">
                                        <div class="stat-number">{{ round($school->profile->total_time_spent / 60) }}</div>
                                        <div class="stat-label">Minutes Spent by Visitors</div>
                                    </div>
                                    @endif
                                    @if($school->profile->last_visited_at)
                                    <div class="stat-item">
                                        <div class="stat-number">{{ $school->profile->last_visited_at->format('M d') }}</div>
                                        <div class="stat-label">Last Visited</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>