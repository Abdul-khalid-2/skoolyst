@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@php
    $defaultAvatar = $user->profile_picture_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=200&background=15a362&color=fff';
@endphp

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-0">Edit profile</h1>
            <p class="text-muted small mb-0">Update your account information</p>
        </div>
        <a href="{{ LaravelLocalization::localizeUrl(route('user_profile.show', [], false)) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to profile
        </a>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('user_profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h2 class="h6 mb-0">Profile picture</h2>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img id="profilePicturePreview"
                                src="{{ $defaultAvatar }}"
                                alt="Profile"
                                class="rounded-circle border mb-2"
                                style="width: 180px; height: 180px; object-fit: cover;">
                        </div>
                        <input type="file" class="form-control form-control-sm" id="profile_picture" name="profile_picture"
                            accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted d-block mt-1">Max 2MB — JPEG, PNG, JPG, GIF</small>
                        @error('profile_picture')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @if($user->profile_picture)
                        <div class="form-check text-start mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_profile_picture" id="remove_profile_picture" value="1">
                            <label class="form-check-label" for="remove_profile_picture">Remove profile picture</label>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h2 class="h6 mb-0">Personal information</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                    value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">School</label>
                                <input type="text" class="form-control" value="{{ $user->school->name ?? 'Not assigned' }}" disabled>
                                <small class="text-muted">Contact support to change school assignment</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3" placeholder="Tell us about yourself…">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h2 class="h6 mb-0">Change password</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                            @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label">New password</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                                @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm new password</label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                            </div>
                        </div>
                        <small class="text-muted">Leave blank to keep your current password.</small>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save changes
                    </button>
                    <a href="{{ LaravelLocalization::localizeUrl(route('user_profile.show', [], false)) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('profilePicturePreview');
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) { preview.src = e.target.result; };
            reader.readAsDataURL(file);
        }
    }
    document.getElementById('remove_profile_picture')?.addEventListener('change', function() {
        const preview = document.getElementById('profilePicturePreview');
        const restoreSrc = @json($user->profile_picture_url ?? $defaultAvatar);
        preview.src = this.checked ? @json($defaultAvatar) : restoreSrc;
    });
</script>
@endpush
