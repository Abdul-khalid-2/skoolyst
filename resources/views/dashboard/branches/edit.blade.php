<x-app-layout>
    <main class="main-content">
        <section id="branches-edit" class="page-section">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Edit Branch: {{ $branch->name }}</h2>
                    <p class="mb-0 text-muted">Update branch information</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('schools.branches.images.index', [$school, $branch]) }}" variant="info" class="me-2">
                        <i class="fas fa-images me-2"></i>Manage Images
                    </x-button>
                    <x-button href="{{ route('schools.branches.index', $school) }}" variant="secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Branches
                    </x-button>
                </x-slot>
            </x-page-header>

            <div class="row">
                <div class="col-lg-12">
                    <x-card>
                        <div class="card-body">
                            <form method="POST" action="{{ route('schools.branches.update', [$school, $branch]) }}" id="branchForm">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Basic Information -->
                                    <div class="col-lg-8">
                                        <h5 class="mb-4">Basic Information</h5>

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

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description"
                                                rows="4" placeholder="Enter branch description">{{ old('description', $branch->description) }}</textarea>
                                            @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <h5 class="mb-4 mt-5">Academic Information</h5>

                                        <div class="mb-3">
                                            <label for="school_type" class="form-label">School Type</label>
                                            <select class="form-select" id="school_type" name="school_type">
                                                <option value="">Select Type</option>
                                                <option value="Co-Ed" {{ old('school_type', $branch->school_type) == 'Co-Ed' ? 'selected' : '' }}>Co-Educational</option>
                                                <option value="Boys" {{ old('school_type', $branch->school_type) == 'Boys' ? 'selected' : '' }}>Boys Only</option>
                                                <option value="Girls" {{ old('school_type', $branch->school_type) == 'Girls' ? 'selected' : '' }}>Girls Only</option>
                                            </select>
                                            @error('school_type')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="curriculums" class="form-label">Curriculums Offered</label>
                                            <textarea class="form-control" id="curriculums" name="curriculums"
                                                rows="3" placeholder="Enter curriculums offered">{{ old('curriculums', $branch->curriculums) }}</textarea>
                                            <small class="text-muted">Separate multiple curriculums with commas</small>
                                            @error('curriculums')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="classes" class="form-label">Classes Offered</label>
                                            <textarea class="form-control" id="classes" name="classes"
                                                rows="3" placeholder="Enter classes offered (e.g., Playgroup to Grade 10)">{{ old('classes', $branch->classes) }}</textarea>
                                            @error('classes')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="fee_structure" class="form-label">Fee Structure</label>
                                            <textarea class="form-control" id="fee_structure" name="fee_structure"
                                                rows="4" placeholder="Enter fee structure details">{{ old('fee_structure', $branch->fee_structure) }}</textarea>
                                            <small class="text-muted">You can enter fee details like monthly fees, admission fees, etc.</small>
                                            @error('fee_structure')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Sidebar -->
                                    <div class="col-lg-4">
                                        <h5 class="mb-4">Settings & Features</h5>

                                        <x-card class="mb-4">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label for="features" class="form-label">Features</label>
                                                    <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                                        @php
                                                            $selectedFeatures = $branch->getFeaturesArray();
                                                        @endphp
                                                        @foreach($features as $feature)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="features[]"
                                                                value="{{ $feature->id }}"
                                                                id="feature_{{ $feature->id }}"
                                                                {{ in_array($feature->id, old('features', $selectedFeatures)) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="feature_{{ $feature->id }}">
                                                                @if($feature->icon)
                                                                <i class="{{ $feature->icon }} me-2"></i>
                                                                @endif
                                                                {{ $feature->name }}
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    <small class="text-muted">Select features available at this branch</small>
                                                    @error('features')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
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
                                                    <label for="status" class="form-label">Status *</label>
                                                    <select class="form-select" id="status" name="status" required>
                                                        <option value="active" {{ old('status', $branch->status) == 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ old('status', $branch->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                    @error('status')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </x-card>

                                        <x-card class="mb-4">
                                            <div class="card-body">
                                                <h6 class="mb-3">Location</h6>

                                                <div class="mb-3">
                                                    <label for="latitude" class="form-label">Latitude</label>
                                                    <input type="number" step="any" class="form-control" id="latitude" name="latitude"
                                                        value="{{ old('latitude', $branch->latitude) }}" placeholder="e.g., 40.7128">
                                                    @error('latitude')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="longitude" class="form-label">Longitude</label>
                                                    <input type="number" step="any" class="form-control" id="longitude" name="longitude"
                                                        value="{{ old('longitude', $branch->longitude) }}" placeholder="e.g., -74.0060">
                                                    @error('longitude')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mt-3">
                                                    <x-button type="button" variant="outline-primary" class="btn-sm w-100" id="getLocation">
                                                        <i class="fas fa-map-marker-alt me-2"></i>Get Current Location
                                                    </x-button>
                                                </div>
                                            </div>
                                        </x-card>

                                        <x-card>
                                            <div class="card-body">
                                                <h6 class="mb-3">Actions</h6>
                                                <div class="d-grid gap-2">
                                                    <x-button type="submit" variant="primary">
                                                        <i class="fas fa-save me-2"></i>Update Branch
                                                    </x-button>
                                                    <x-button type="button" variant="outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                        <i class="fas fa-trash me-2"></i>Delete Branch
                                                    </x-button>
                                                    <x-button href="{{ route('schools.branches.index', $school) }}" variant="outline-secondary">
                                                        Cancel
                                                    </x-button>
                                                </div>
                                            </div>
                                        </x-card>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Image Gallery Preview -->
            @if($branch->images->count() > 0)
            <div class="row mt-4">
                <div class="col-lg-12">
                    <x-card>
                        <div class="card-header">
                            <h5 class="mb-0">Image Gallery ({{ $branch->images->count() }} images)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($branch->images->take(6) as $image)
                                <div class="col-md-2 col-sm-4 mb-3">
                                    <div class="image-preview">
                                        <img src="{{ asset('website/'. $image->image_path) }}"
                                             alt="{{ $image->title }}"
                                             class="img-fluid rounded"
                                             style="height: 120px; width: 100%; object-fit: cover;">
                                        @if($image->is_featured)
                                        <x-badge variant="success" class="position-absolute top-0 start-0 m-2">Featured</x-badge>
                                        @endif
                                        @if($image->is_main_banner)
                                        <x-badge variant="primary" class="position-absolute top-0 end-0 m-2">Banner</x-badge>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                @if($branch->images->count() > 6)
                                <div class="col-md-2 col-sm-4 mb-3">
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <span class="text-muted">+{{ $branch->images->count() - 6 }} more</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="mt-3">
                                <x-button href="{{ route('schools.branches.images.index', [$school, $branch]) }}" variant="outline-primary" class="btn-sm">
                                    <i class="fas fa-images me-2"></i>Manage All Images
                                </x-button>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
            @endif
        </section>
    </main>

    <x-bs-modal id="deleteModal" title="Confirm Delete">
        <p>Are you sure you want to delete this branch? This action cannot be undone.</p>
        <p class="text-danger"><strong>Warning:</strong> All associated images, events, and reviews will also be deleted.</p>
        <x-slot name="footer">
            <x-button type="button" variant="secondary" data-bs-dismiss="modal">Cancel</x-button>
            <form method="POST" action="{{ route('schools.branches.destroy', [$school, $branch]) }}">
                @csrf
                @method('DELETE')
                <x-button type="submit" variant="danger">Delete Branch</x-button>
            </form>
        </x-slot>
    </x-bs-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get current location
            document.getElementById('getLocation').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                            document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                        },
                        function(error) {
                            alert('Unable to retrieve your location. Please enter manually.');
                        }
                    );
                } else {
                    alert('Geolocation is not supported by your browser. Please enter manually.');
                }
            });

            // Initialize text editor for description
            @if(config('app.enable_rich_text_editor'))
            ClassicEditor
                .create(document.querySelector('#description'))
                .catch(error => {
                    console.error(error);
                });
            @endif
        });
    </script>

</x-app-layout>
