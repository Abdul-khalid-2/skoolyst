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
                    <a href="{{ route('schools') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Schools
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- School Information Card -->
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

                    <!-- Description Card -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Description</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ $school->description ?? 'No description provided.' }}</p>
                        </div>
                    </div>

                    <!-- Facilities Card -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Facilities</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ $school->facilities ?? 'No facilities information provided.' }}</p>
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

                    <!-- Statistics Card -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">School Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Total Reviews</span>
                                <span class="badge bg-primary">{{ $school->reviews->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Upcoming Events</span>
                                <span class="badge bg-success">{{ $school->events->where('event_date', '>=', now())->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Active Since</span>
                                <span class="badge bg-info">{{ $school->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Reviews Card -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Recent Reviews</h5>
                        </div>
                        <div class="card-body">
                            @if($school->reviews->count() > 0)
                            @foreach($school->reviews->take(3) as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $review->reviewer_name }}</strong>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                    </div>
                                </div>
                                <p class="mb-0 small">{{ Str::limit($review->review, 100) }}</p>
                                <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                            </div>
                            @endforeach
                            <a href="#" class="btn btn-sm btn-outline-primary w-100">View All Reviews</a>
                            @else
                            <p class="text-muted text-center">No reviews yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>