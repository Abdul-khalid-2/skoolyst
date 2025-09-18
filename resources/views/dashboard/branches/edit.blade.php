<x-app-layout>
    <main class="main-content">
        <section id="branches-edit" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Edit Branch: {{ $branch->name }}</h2>
                    <p class="mb-0 text-muted">Update branch information</p>
                </div>
                <a href="{{ route('schools.branches.index', $school) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Branches
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('schools.branches.update', [$school, $branch]) }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Branch Name *</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $branch->name) }}" placeholder="Enter branch name" required>
                                    @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address *</label>
                                    <textarea class="form-control" id="address" name="address"
                                        rows="3" placeholder="Enter branch address" required>{{ old('address', $branch->address) }}</textarea>
                                    @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="city" class="form-label">City *</label>
                                        <input type="text" class="form-control" id="city" name="city"
                                            value="{{ old('city', $branch->city) }}" placeholder="Enter city" required>
                                        @error('city')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_number" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number"
                                            value="{{ old('contact_number', $branch->contact_number) }}" placeholder="Enter contact number">
                                        @error('contact_number')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="branch_head_name" class="form-label">Branch Head Name</label>
                                    <input type="text" class="form-control" id="branch_head_name" name="branch_head_name"
                                        value="{{ old('branch_head_name', $branch->branch_head_name) }}" placeholder="Enter branch head name">
                                    @error('branch_head_name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <input type="number" step="any" class="form-control" id="latitude" name="latitude"
                                            value="{{ old('latitude', $branch->latitude) }}" placeholder="e.g., 40.7128">
                                        @error('latitude')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input type="number" step="any" class="form-control" id="longitude" name="longitude"
                                            value="{{ old('longitude', $branch->longitude) }}" placeholder="e.g., -74.0060">
                                        @error('longitude')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_main_branch" name="is_main_branch"
                                            value="1" {{ old('is_main_branch', $branch->is_main_branch) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_main_branch">
                                            Set as main branch
                                        </label>
                                    </div>
                                    <small class="text-muted">If checked, this branch will be marked as the main branch and any existing main branch will be demoted.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="active" {{ old('status', $branch->status) === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $branch->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Branch
                                    </button>
                                    <a href="{{ route('schools.branches.index', $school) }}" class="btn btn-outline-secondary">
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
</x-app-layout>