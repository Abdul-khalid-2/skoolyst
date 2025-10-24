<x-app-layout>
    <main class="main-content">
        <section id="profile-show" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">My Profile</h2>
                    <p class="mb-0 text-muted">View and manage your profile information</p>
                </div>
                <a href="{{ route('user_profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i> Edit Profile
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-4">
                    <!-- Profile Card -->
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img src="{{ $user->profile_picture_url }}"
                                    alt="Profile Picture"
                                    class="rounded-circle img-fluid border"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <h4 class="card-title">{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->email }}</p>

                            @if($user->school)
                            <div class="mb-3">
                                <span class="badge bg-primary">{{ $user->school->name }}</span>
                            </div>
                            @endif

                            <div class="d-grid">
                                <a href="{{ route('user_profile.edit') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit me-2"></i> Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Account Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Member Since:</strong>
                                <p class="mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Last Updated:</strong>
                                <p class="mb-0">{{ $user->updated_at->format('M d, Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Email Verified:</strong>
                                <p class="mb-0">
                                    @if($user->email_verified_at)
                                    <span class="badge bg-success">Verified</span>
                                    @else
                                    <span class="badge bg-warning">Not Verified</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Personal Information -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Full Name:</strong>
                                    <p class="mb-2">{{ $user->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Email Address:</strong>
                                    <p class="mb-2">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Phone Number:</strong>
                                    <p class="mb-2">{{ $user->phone ?? 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>School:</strong>
                                    <p class="mb-2">{{ $user->school->name ?? 'Not assigned' }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <strong>Address:</strong>
                                    <p class="mb-2">{{ $user->address ?? 'Not provided' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <strong>Bio:</strong>
                                    <p class="mb-0">{{ $user->bio ?? 'No bio provided' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- School Information (if assigned to a school) -->
                    @if($user->school)
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">School Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>School Name:</strong>
                                    <p class="mb-2">{{ $user->school->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Email:</strong>
                                    <p class="mb-2">{{ $user->school->email ?? 'Not provided' }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Contact Number:</strong>
                                    <p class="mb-2">{{ $user->school->contact_number ?? 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Address:</strong>
                                    <p class="mb-2">{{ $user->school->address }}, {{ $user->school->city }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('schools.show', $user->school->id) }}" class="btn btn-sm btn-outline-primary">
                                        View School Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
</x-app-layout>