<x-app-layout>
    <main class="main-content">
        <section id="branch-show" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Branch Details: {{ $branch->name }}</h2>
                    <p class="mb-0 text-muted">View complete information about this branch</p>
                </div>
                <div>
                    <a href="{{ route('schools.branches.edit', [$school, $branch]) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-2"></i> Edit Branch
                    </a>
                    <a href="{{ route('schools.branches.index', $school) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Branches
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Branch Information Card -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Branch Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Branch Name:</strong>
                                    <p>{{ $branch->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>School:</strong>
                                    <p>{{ $school->name }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Status:</strong>
                                    <p>
                                        @if($branch->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                        @if($branch->is_main_branch)
                                        <span class="badge bg-primary ms-1">Main Branch</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Contact Number:</strong>
                                    <p>{{ $branch->contact_number ?? 'Not provided' }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <strong>Address:</strong>
                                    <p>{{ $branch->address }}, {{ $branch->city }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Branch Head:</strong>
                                    <p>{{ $branch->branch_head_name ?? 'Not specified' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Coordinates:</strong>
                                    <p>
                                        @if($branch->latitude && $branch->longitude)
                                        {{ $branch->latitude }}, {{ $branch->longitude }}
                                        @else
                                        Not provided
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Created At:</strong>
                                    <p>{{ $branch->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Last Updated:</strong>
                                    <p>{{ $branch->updated_at->format('M d, Y') }}</p>
                                </div>
                            </div>
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
                                <a href="{{ route('schools.branches.edit', [$school, $branch]) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i> Edit Branch
                                </a>
                                <form action="{{ route('schools.branches.destroy', [$school, $branch]) }}" method="POST" class="d-grid">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this branch? This will also delete all events and reviews associated with it.')">
                                        <i class="fas fa-trash me-2"></i> Delete Branch
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Card -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Branch Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Total Events</span>
                                <span class="badge bg-primary">{{ $branch->events_count }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Total Reviews</span>
                                <span class="badge bg-success">{{ $branch->reviews_count }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Active Since</span>
                                <span class="badge bg-info">{{ $branch->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>