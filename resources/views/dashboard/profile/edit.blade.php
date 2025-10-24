<x-app-layout>
    <main class="main-content">
        <section id="profile-edit" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Edit Profile</h2>
                    <p class="mb-0 text-muted">Update your profile information</p>
                </div>
                <a href="{{ route('user_profile.show') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Profile
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('user_profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-lg-4">
                                        <!-- Profile Picture -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-white">
                                                <h5 class="card-title mb-0">Profile Picture</h5>
                                            </div>
                                            <div class="card-body text-center">
                                                <div class="mb-3">
                                                    <img id="profilePicturePreview" 
                                                         src="{{ $user->profile_picture_url }}" 
                                                         alt="Profile Picture" 
                                                         class="rounded-circle img-fluid border mb-3"
                                                         style="width: 200px; height: 200px; object-fit: cover;">
                                                </div>
                                                <div class="mb-3">
                                                    <input type="file" 
                                                           class="form-control" 
                                                           id="profile_picture" 
                                                           name="profile_picture" 
                                                           accept="image/*"
                                                           onchange="previewImage(this)">
                                                    <small class="text-muted">Max file size: 2MB. Allowed formats: JPEG, PNG, JPG, GIF</small>
                                                    @error('profile_picture')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @if($user->profile_picture)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remove_profile_picture" id="remove_profile_picture" value="1">
                                                    <label class="form-check-label" for="remove_profile_picture">
                                                        Remove profile picture
                                                    </label>
                                                </div>
                                                @endif
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
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="name" class="form-label">Full Name *</label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                                                               value="{{ old('name', $user->name) }}" required>
                                                        @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="email" class="form-label">Email Address *</label>
                                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
                                                               value="{{ old('email', $user->email) }}" required>
                                                        @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="phone" class="form-label">Phone Number</label>
                                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" 
                                                               value="{{ old('phone', $user->phone) }}">
                                                        @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">School</label>
                                                        <input type="text" class="form-control" 
                                                               value="{{ $user->school->name ?? 'Not assigned' }}" disabled>
                                                        <small class="text-muted">School assignment cannot be changed here</small>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" 
                                                              rows="3">{{ old('address', $user->address) }}</textarea>
                                                    @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="bio" class="form-label">Bio</label>
                                                    <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" 
                                                              rows="4" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                                    @error('bio')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Password Change -->
                                        <div class="card">
                                            <div class="card-header bg-white">
                                                <h5 class="card-title mb-0">Change Password</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label for="current_password" class="form-label">Current Password</label>
                                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                                           id="current_password" name="current_password">
                                                    @error('current_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="new_password" class="form-label">New Password</label>
                                                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                                               id="new_password" name="new_password">
                                                        @error('new_password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                                        <input type="password" class="form-control" 
                                                               id="new_password_confirmation" name="new_password_confirmation">
                                                    </div>
                                                </div>

                                                <small class="text-muted">Leave password fields empty if you don't want to change your password.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 pt-4 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Profile
                                    </button>
                                    <a href="{{ route('user_profile.show') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('js')
    <script>
        function previewImage(input) {
            const preview = document.getElementById('profilePicturePreview');
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                
                reader.readAsDataURL(file);
            }
        }

        // Remove profile picture checkbox handler
        document.getElementById('remove_profile_picture')?.addEventListener('change', function() {
            const preview = document.getElementById('profilePicturePreview');
            if (this.checked) {
                preview.src = '{{ asset("images/default-avatar.png") }}';
            } else {
                preview.src = '{{ $user->profile_picture_url }}';
            }
        });
    </script>
    @endpush
</x-app-layout>