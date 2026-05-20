<x-app-layout>
    <!-- Main Content -->
    <main class="main-content">
        <!-- Dashboard Page -->
        <section id="dashboard" class="page-section active">
            {{-- Welcome Banner --}}
            <div class="welcome-banner">
                <div class="welcome-banner__body">
                    <div>
                        <h2 class="welcome-banner__title">
                            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name }}!
                        </h2>
                        <p class="welcome-banner__sub">
                            @if(auth()->user()->hasRole('super-admin'))
                                Here's an overview of all schools in the system.
                            @else
                                Manage your school profile, events, and more from here.
                            @endif
                        </p>
                    </div>
                    <div class="text-white opacity-75 small d-none d-md-block">
                        {{ now()->format('l, F j, Y') }}
                    </div>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="row mb-4">
                @if(auth()->user()->hasRole('super-admin'))
                    <div class="col-lg-3 col-md-6 mb-4">
                        <x-stat-card
                            label="Total Schools"
                            value="{{ $stats['total_schools'] }}"
                            sub="Registered institutions"
                            icon="fa-school"
                            color="primary"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <x-stat-card
                            label="Total Users"
                            value="{{ $stats['total_users'] }}"
                            sub="System users"
                            icon="fa-users"
                            color="info"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <x-stat-card
                            label="Total Reviews"
                            value="{{ $stats['total_reviews'] }}"
                            sub="Across all schools"
                            icon="fa-star"
                            color="warning"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <x-stat-card
                            label="Avg Rating"
                            value="{{ number_format($stats['average_rating'], 1) }}/5"
                            sub="Overall satisfaction"
                            icon="fa-chart-line"
                            color="success"
                        />
                    </div>
                @else
                    <div class="col-lg-3 col-md-6 mb-4">
                        <x-stat-card
                            label="Profile Visits"
                            value="{{ $stats['profile_visits'] }}"
                            sub="Total visitors"
                            icon="fa-eye"
                            color="primary"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <x-stat-card
                            label="Reviews"
                            value="{{ $stats['total_reviews'] }}"
                            sub="Avg: {{ number_format($stats['average_rating'], 1) }}/5"
                            icon="fa-star"
                            color="success"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <x-stat-card
                            label="Upcoming Events"
                            value="{{ $stats['total_events'] }}"
                            sub="Scheduled activities"
                            icon="fa-calendar-alt"
                            color="warning"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <x-stat-card
                            label="Branches"
                            value="{{ $stats['total_branches'] }}"
                            sub="School locations"
                            icon="fa-code-branch"
                            color="info"
                        />
                    </div>
                @endif
            </div>

            {{-- Recent Activity & Upcoming Events --}}
            @if(auth()->user()->hasRole('school-admin'))
            <div class="row">
                <!-- Recent Reviews -->
                <div class="col-lg-6 mb-4">
                    <div class="card chart-card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold" style="color: var(--color-gray-700)">Recent Reviews</h6>
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
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold" style="color: var(--color-gray-700)">Upcoming Events</h6>
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
            <!-- <div class="row">
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
            </div> -->
        </section>
    </main>
</x-app-layout>