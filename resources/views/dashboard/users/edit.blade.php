<x-app-layout>
    <main class="main-content">
        <section id="edit-user" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Edit User</h2>
                    <p class="text-muted">Update user information</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-2"></i>View User
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Users
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <h5 class="mb-3">Basic Information</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="">Select Role</option>
                                    @role('super-admin')
                                    <option value="super-admin" {{ old('role', $userRole) == 'super-admin' ? 'selected' : '' }}>Super Admin</option>
                                    @endrole
                                    <option value="school-admin" {{ old('role', $userRole) == 'school-admin' ? 'selected' : '' }}>School Admin</option>
                                    <option value="teacher" {{ old('role', $userRole) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="student" {{ old('role', $userRole) == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="parent" {{ old('role', $userRole) == 'parent' ? 'selected' : '' }}>Parent</option>
                                </select>
                                @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @role('super-admin')
                            <!-- <div class="col-md-6">
                                <label for="school_id" class="form-label">School</label>
                                <select class="form-select @error('school_id') is-invalid @enderror" 
                                        id="school_id" 
                                        name="school_id">
                                    <option value="">No School (Platform User)</option>
                                    @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ old('school_id', $user->school_id) == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('school_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> -->
                            @endrole
                        </div>

                        <!-- Password (Optional) -->
                        <h5 class="mb-3">Change Password <small class="text-muted">(Leave blank to keep current)</small></h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Minimum 8 characters</small>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <h5 class="mb-3">Additional Information</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="2">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="bio" class="form-label">Bio / Description</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          id="bio" 
                                          name="bio" 
                                          rows="3">{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Profile Picture -->
                            <div class="col-md-6">
                                <label class="form-label">Current Profile Picture</label>
                                <div class="mb-2">
                                    @if($user->profile_picture)
                                        <img src="{{ Storage::url($user->profile_picture) }}" 
                                             alt="{{ $user->name }}" 
                                             class="rounded-circle" 
                                             width="100" 
                                             height="100"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 100px; height: 100px; font-size: 40px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                
                                <label for="profile_picture" class="form-label">Change Profile Picture</label>
                                <input type="file" 
                                       class="form-control @error('profile_picture') is-invalid @enderror" 
                                       id="profile_picture" 
                                       name="profile_picture" 
                                       accept="image/jpeg,image/png,image/jpg,image/webp">
                                <small class="text-muted">Max size: 2MB. Allowed: jpeg, png, jpg, webp</small>
                                
                                @if($user->profile_picture)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="remove_profile_picture" 
                                           name="remove_profile_picture" 
                                           value="1">
                                    <label class="form-check-label text-danger" for="remove_profile_picture">
                                        Remove current profile picture
                                    </label>
                                </div>
                                @endif
                                
                                @error('profile_picture')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Account Status -->
                            <div class="col-md-6">
                                <label class="form-label">Account Status</label>
                                <div class="border rounded p-3">
                                    <div class="mb-2">
                                        <strong>Email Verified:</strong> 
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Verified on {{ $user->email_verified_at->format('M d, Y') }}</span>
                                        @else
                                            <span class="badge bg-warning">Not Verified</span>
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <strong>Account Created:</strong> 
                                        <span>{{ $user->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <div>
                                        <strong>Last Updated:</strong> 
                                        <span>{{ $user->updated_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
        }

        // Preview new image before upload
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Optional: Show preview
                    console.log('New image selected');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
</x-app-layout>