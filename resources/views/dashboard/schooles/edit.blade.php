<x-app-layout>
    <!-- Main Content -->
    <main class="main-content">
        <!-- School Edit Page -->
        <section id="school-edit" class="page-section active">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Edit School</h2>
                    <p class="mb-0 text-muted">Update information for {{ $school->name }}</p>
                </div>
                <a href="{{ route('schools.show', $school->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to School
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('schools.update', $school->id) }}">
                                @csrf
                                @method('PUT')

                                <!-- Basic Information -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">School Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $school->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $school->email) }}">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">Address *</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" value="{{ old('address', $school->address) }}" required>
                                        @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="city" class="form-label">City *</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                                            id="city" name="city" value="{{ old('city', $school->city) }}" required>
                                        @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_number" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control @error('contact_number') is-invalid @enderror"
                                            id="contact_number" name="contact_number" value="{{ old('contact_number', $school->contact_number) }}">
                                        @error('contact_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="website" class="form-label">Website</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror"
                                            id="website" name="website" value="{{ old('website', $school->website) }}">
                                        @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">School Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" rows="4">{{ old('description', $school->description) }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="facilities" class="form-label">Facilities</label>
                                    <textarea class="form-control @error('facilities') is-invalid @enderror"
                                        id="facilities" name="facilities" rows="4">{{ old('facilities', $school->facilities) }}</textarea>
                                    @error('facilities')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="school_type" class="form-label">School Type *</label>
                                    <select class="form-control @error('school_type') is-invalid @enderror" id="school_type" name="school_type" required>
                                        <option value="Co-Ed" {{ old('school_type', $school->school_type) == 'Co-Ed' ? 'selected' : '' }}>Co-Ed</option>
                                        <option value="Boys" {{ old('school_type', $school->school_type) == 'Boys' ? 'selected' : '' }}>Boys</option>
                                        <option value="Girls" {{ old('school_type', $school->school_type) == 'Girls' ? 'selected' : '' }}>Girls</option>
                                    </select>
                                    @error('school_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update School
                                    </button>
                                    <a href="{{ route('schools.show', $school->id) }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- School Status -->
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">School Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Created:</strong>
                                <p class="mb-0">{{ $school->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Last Updated:</strong>
                                <p class="mb-0">{{ $school->updated_at->format('M d, Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Total Reviews:</strong>
                                <p class="mb-0">{{ $school->reviews->count() }}</p>
                            </div>
                            <div>
                                <strong>Upcoming Events:</strong>
                                <p class="mb-0">{{ $school->events->where('event_date', '>=', now())->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Delete School Card -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0 text-danger">Danger Zone</h5>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted">Once you delete a school, there is no going back. Please be certain.</p>
                            <form action="{{ route('schools.destroy', $school->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Are you sure you want to delete this school? This action cannot be undone.')">
                                    <i class="fas fa-trash me-2"></i> Delete School
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>