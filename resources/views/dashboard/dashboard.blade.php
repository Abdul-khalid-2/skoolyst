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
                                @else
                                    Welcome to {{ $school->name }} Dashboard!
                                @endif
                            </h4>
                            <p class="mb-0 opacity-75">
                                @if(auth()->user()->hasRole('super-admin'))
                                    Here's an overview of all schools in the system.
                                @else
                                    Manage your school profile, events, and more.
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

                @else
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
                @endif
            </div>

            <!-- Recent Activity & Upcoming Events -->
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
                                @else
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>