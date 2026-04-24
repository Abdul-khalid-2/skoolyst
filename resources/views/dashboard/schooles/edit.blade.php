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

            <form class="row" method="POST" action="{{ route('schools.update', $school->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- Admin Information -->
                            <h5 class="mb-3">Admin Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin-name" class="form-label">Admin Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('admin-name') is-invalid @enderror"
                                        id="admin-name" name="admin-name" value="{{ old('admin-name', $user->name) }}" required>
                                    @error('admin-name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin-email" class="form-label">Admin Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('admin-email') is-invalid @enderror"
                                        id="admin-email" name="admin-email" value="{{ old('admin-email', $user->email) }}" required>
                                    @error('admin-email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Basic Information -->
                            <h5 class="mb-3">Basic Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">School Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $school->name) }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">School Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $school->email) }}">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                        id="address" name="address" value="{{ old('address', $school->address) }}" required>
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
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

                            <!-- School Profile Information -->
                            <h5 class="mb-3">School Profile Details</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="established_year" class="form-label">Established Year</label>
                                    <input type="text" class="form-control @error('established_year') is-invalid @enderror"
                                        id="established_year" name="established_year"
                                        value="{{ old('established_year', $school->profile->established_year ?? '') }}" placeholder="e.g., 1995">
                                    @error('established_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="student_strength" class="form-label">Student Strength</label>
                                    <input type="number" class="form-control @error('student_strength') is-invalid @enderror"
                                        id="student_strength" name="student_strength"
                                        value="{{ old('student_strength', $school->profile->student_strength ?? '') }}" placeholder="e.g., 1000">
                                    @error('student_strength')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="faculty_count" class="form-label">Faculty Count</label>
                                    <input type="number" class="form-control @error('faculty_count') is-invalid @enderror"
                                        id="faculty_count" name="faculty_count"
                                        value="{{ old('faculty_count', $school->profile->faculty_count ?? '') }}" placeholder="e.g., 50">
                                    @error('faculty_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="campus_size" class="form-label">Campus Size</label>
                                    <input type="text" class="form-control @error('campus_size') is-invalid @enderror"
                                        id="campus_size" name="campus_size"
                                        value="{{ old('campus_size', $school->profile->campus_size ?? '') }}" placeholder="e.g., 5 acres">
                                    @error('campus_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="school_motto" class="form-label">School Motto</label>
                                <input type="text" class="form-control @error('school_motto') is-invalid @enderror"
                                    id="school_motto" name="school_motto"
                                    value="{{ old('school_motto', $school->profile->school_motto ?? '') }}" placeholder="Enter school motto">
                                @error('school_motto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mission" class="form-label">Mission</label>
                                <textarea class="form-control @error('mission') is-invalid @enderror"
                                    id="mission" name="mission" rows="3"
                                    placeholder="Enter school mission statement">{{ old('mission', $school->profile->mission ?? '') }}</textarea>
                                @error('mission')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="vision" class="form-label">Vision</label>
                                <textarea class="form-control @error('vision') is-invalid @enderror"
                                    id="vision" name="vision" rows="3"
                                    placeholder="Enter school vision statement">{{ old('vision', $school->profile->vision ?? '') }}</textarea>
                                @error('vision')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Quick Facts (JSON) -->
                            <div class="mb-3">
                                <label class="form-label">Quick Facts</label>
                                <div id="quick-facts-container">
                                    @php
                                        $quickFacts = $school->profile && $school->profile->quick_facts 
                                            ? json_decode($school->profile->quick_facts, true) 
                                            : [];
                                        $hasQuickFacts = !empty($quickFacts);
                                        $standardFields = ['established_year', 'student_strength', 'faculty_count', 'campus_size'];
                                    @endphp
                                    
                                    @if($hasQuickFacts)
                                        @foreach($quickFacts as $key => $value)
                                            @if(!in_array($key, $standardFields))
                                            <div class="quick-fact-item mb-2">
                                                <div class="row g-2">
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="quick_fact_keys[]" 
                                                               placeholder="Fact key (e.g., accreditation)" value="{{ $key }}">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="quick_fact_values[]" 
                                                               placeholder="Fact value (e.g., CBSE Affiliated)" value="{{ $value }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger btn-sm remove-quick-fact">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    
                                    <!-- Always show at least one empty field -->
                                    <div class="quick-fact-item mb-2">
                                        <div class="row g-2">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="quick_fact_keys[]" placeholder="Fact key (e.g., accreditation)">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="quick_fact_values[]" placeholder="Fact value (e.g., CBSE Affiliated)">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-quick-fact" style="display: none;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-quick-fact">
                                    <i class="fas fa-plus me-1"></i> Add Quick Fact
                                </button>
                            </div>

                           <!-- Social Media Links -->
                            <h5 class="mb-3">Social Media Links</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="facebook_url" class="form-label">Facebook</label>
                                    <input type="url" class="form-control @error('facebook_url') is-invalid @enderror"
                                        id="facebook_url" name="facebook_url"
                                        value="{{ old('facebook_url', $school->profile && $school->profile->social_media ? json_decode($school->profile->social_media, true)['facebook'] ?? '' : '') }}" placeholder="https://facebook.com/...">
                                    @error('facebook_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="twitter_url" class="form-label">Twitter</label>
                                    <input type="url" class="form-control @error('twitter_url') is-invalid @enderror"
                                        id="twitter_url" name="twitter_url"
                                        value="{{ old('twitter_url', $school->profile && $school->profile->social_media ? json_decode($school->profile->social_media, true)['twitter'] ?? '' : '') }}" placeholder="https://twitter.com/...">
                                    @error('twitter_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="instagram_url" class="form-label">Instagram</label>
                                    <input type="url" class="form-control @error('instagram_url') is-invalid @enderror"
                                        id="instagram_url" name="instagram_url"
                                        value="{{ old('instagram_url', $school->profile && $school->profile->social_media ? json_decode($school->profile->social_media, true)['instagram'] ?? '' : '') }}" placeholder="https://instagram.com/...">
                                    @error('instagram_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="linkedin_url" class="form-label">LinkedIn</label>
                                    <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror"
                                        id="linkedin_url" name="linkedin_url"
                                        value="{{ old('linkedin_url', $school->profile && $school->profile->social_media ? json_decode($school->profile->social_media, true)['linkedin'] ?? '' : '') }}" placeholder="https://linkedin.com/...">
                                    @error('linkedin_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="youtube_url" class="form-label">YouTube</label>
                                    <input type="url" class="form-control @error('youtube_url') is-invalid @enderror"
                                        id="youtube_url" name="youtube_url"
                                        value="{{ old('youtube_url', $school->profile && $school->profile->social_media ? json_decode($school->profile->social_media, true)['youtube'] ?? '' : '') }}" placeholder="https://youtube.com/...">
                                    @error('youtube_url')
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

                            @include('dashboard.schooles.partials.urdu-translation-fields', ['school' => $school])

                            <div class="mb-3">
                                <label for="school_type" class="form-label">School Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('school_type') is-invalid @enderror" id="school_type" name="school_type" required>
                                    <option value="">Select School Type</option>
                                    <option value="Co-Ed" {{ old('school_type', $school->school_type) == 'Co-Ed' ? 'selected' : '' }}>Co-Ed</option>
                                    <option value="Boys" {{ old('school_type', $school->school_type) == 'Boys' ? 'selected' : '' }}>Boys</option>
                                    <option value="Girls" {{ old('school_type', $school->school_type) == 'Girls' ? 'selected' : '' }}>Girls</option>
                                    <option value="Separate" {{ old('school_type', $school->school_type) == 'Separate' ? 'selected' : '' }}>Separate Girls And Boys Campuses</option>
                                </select>
                                @error('school_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Features Section - Grouped by Category -->
                            <div class="mb-3">
                                <label class="form-label">School Features</label>

                                <!-- Feature Search (Optional) -->
                                <!-- <div class="mb-3">
                                    <input type="text" class="form-control" placeholder="Search features..." onkeyup="filterFeatures(this.value)">
                                </div> -->

                                @foreach($features->groupBy('category') as $category => $categoryFeatures)
                                <div class="card mb-3 feature-category" data-category="{{ Str::slug($category) }}">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 text-capitalize">{{ $category }} Features</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($categoryFeatures as $feature)
                                            <div class="col-md-6 mb-2 feature-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="features[]"
                                                        value="{{ $feature->id }}" id="feature_{{ $feature->id }}"
                                                        {{ in_array($feature->id, $schoolFeatures) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="feature_{{ $feature->id }}">
                                                        {{ $feature->name }}
                                                        @if($feature->icon)
                                                        <i class="fas fa-{{ $feature->icon }} ms-1 text-muted"></i>
                                                        @endif
                                                    </label>
                                                </div>
                                                @if($feature->description)
                                                <small class="text-muted ms-4">{{ $feature->description }}</small>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Curriculum Section -->
                            <div class="mb-3">
                                <label class="form-label">Curriculum <span class="text-danger">*</span></label>
                                <div class="row">
                                    @foreach($curriculums as $curriculum)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="curriculum_ids[]" value="{{ $curriculum->id }}" id="curriculum_{{ $curriculum->id }}"
                                                {{ in_array($curriculum->id, old('curriculum_ids', $schoolCurriculums)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="curriculum_{{ $curriculum->id }}">
                                                {{ $curriculum->name }}
                                            </label>
                                        </div>
                                        @if($curriculum->description)
                                        <small class="text-muted ms-4">{{ $curriculum->description }}</small>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @error('curriculum_ids')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Admin Account (Optional Update) -->
                            <h5 class="mt-4 mb-3">Admin Account (Optional)</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                                </div>
                            </div>

                            <!-- Pricing -->
                            <h5 class="mb-3">School Fees</h5>

                            <!-- Fee Structure Type -->
                            <div class="mb-3">
                                <label class="form-label">Fee Structure Type <span class="text-danger">*</span></label>
                                <div class="fee-structure-toggle bg-light p-3 rounded mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="fee_structure_type" id="fee_fixed_edit" value="fixed" 
                                            {{ old('fee_structure_type', $school->fee_structure_type) == 'fixed' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fee_fixed_edit">
                                            Fixed Structure (Regular/Discounted Fees)
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="fee_structure_type" id="fee_class_wise_edit" value="class_wise" 
                                            {{ old('fee_structure_type', $school->fee_structure_type) == 'class_wise' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fee_class_wise_edit">
                                            Class-wise Structure
                                        </label>
                                    </div>
                                </div>
                                @error('fee_structure_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fixed Fee Structure Fields -->
                            <div id="fixed_fee_structure_edit" class="fee-structure-section bg-light p-3 rounded mb-3" 
                                style="{{ old('fee_structure_type', $school->fee_structure_type) == 'fixed' ? '' : 'display: none;' }}">
                                <h6 class="mb-3 text-muted">Fixed Fee Structure</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="regular_fees" class="form-label">Regular Fees</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rs</span>
                                            <input type="number" class="form-control" id="regular_fees" name="regular_fees"
                                                value="{{ old('regular_fees', $school->regular_fees) }}" placeholder="0.00" step="0.01" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="discounted_fees" class="form-label">Discounted Fees</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rs</span>
                                            <input type="number" class="form-control" id="discounted_fees" name="discounted_fees"
                                                value="{{ old('discounted_fees', $school->discounted_fees) }}" placeholder="0.00" step="0.01" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="admission_fees" class="form-label">Admission Fees</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rs</span>
                                            <input type="number" class="form-control" id="admission_fees" name="admission_fees"
                                                value="{{ old('admission_fees', $school->admission_fees) }}" placeholder="0.00" step="0.01" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Class-wise Fee Structure Fields -->
                            <div id="class_wise_fee_structure_edit" class="fee-structure-section bg-light p-3 rounded mb-3" 
                                style="{{ old('fee_structure_type', $school->fee_structure_type) == 'class_wise' ? '' : 'display: none;' }}">
                                <h6 class="mb-3 text-muted">Class-wise Fee Structure</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">Class-wise Fees <span class="text-danger">*</span></label>
                                    <div id="class_wise_fees_container_edit"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add_class_wise_fee_row_edit">
                                        <i class="fas fa-plus me-1"></i> Add Fee Entry
                                    </button>
                                    <small class="text-muted d-block mt-2">Maximum 5 fee entries allowed. Class Range max 25 characters. Fees max 8 characters.</small>
                                    @error('class_wise_fees')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    @foreach ($errors->get('class_wise_fees.*') as $messages)
                                        @foreach ($messages as $message)
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @endforeach
                                    @endforeach
                                </div>
                                
                                <div class="mb-3">
                                    <label for="class_wise_admission_fees" class="form-label">Admission Fees (Optional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rs</span>
                                        <input type="number" class="form-control" id="class_wise_admission_fees" name="admission_fees"
                                            value="{{ old('admission_fees', $school->admission_fees) }}" placeholder="0.00" step="0.01" min="0">
                                    </div>
                                    <small class="text-muted">If admission fee is same for all classes, enter it here</small>
                                    @error('admission_fees')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Additional Info -->
                            <h5 class="mb-3">Additional Information</h5>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active" {{ old('status', $school->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $school->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="visibility" class="form-label">Visibility <span class="text-danger">*</span></label>
                                <select class="form-control" id="visibility" name="visibility" required>
                                    <option value="public" {{ old('visibility', $school->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="private" {{ old('visibility', $school->visibility) == 'private' ? 'selected' : '' }}>Private</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="publish_date" class="form-label">Publish Date</label>
                                <input type="datetime-local" class="form-control" id="publish_date" name="publish_date"
                                    value="{{ old('publish_date', $school->publish_date ? \Carbon\Carbon::parse($school->publish_date)->format('Y-m-d\TH:i') : '') }}">
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

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- School Logo -->
                    <div class="card mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">School Logo</h5>
                        </div>
                        <div class="card-body">
                            @if($school->logo_url)
                            <div class="mb-3 text-center">
                                <p class="text-muted">Current Logo</p>
                                <img src="{{ $school->logo_url }}" class="img-fluid rounded mb-3" style="max-height: 150px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo" value="1">
                                    <label class="form-check-label text-danger" for="remove_logo">
                                        Remove current logo
                                    </label>
                                </div>
                            </div>
                            @endif

                            <div class="mb-3">
                                <label for="logo" class="form-label">Upload new logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept=".webp,.jpg,.png">
                                <div class="form-text">Recommended size: 200x200px. Max file size: 2MB.</div>
                            </div>

                            <div class="logo-preview mt-3 text-center" id="logo-preview" style="display: none;">
                                <p class="text-muted">New Logo Preview</p>
                                <img id="logo-preview-img" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                        </div>
                    </div>

                    <!-- Banner Image -->
                    <div class="card mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">Banner Image</h5>
                        </div>
                        <div class="card-body">
                            @if($school->banner_url)
                            <div class="mb-3 text-center">
                                <p class="text-muted">Current Banner</p>
                                <img src="{{ $school->banner_url }}" class="img-fluid rounded mb-3" style="max-height: 150px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remove_banner" name="remove_banner" value="1">
                                    <label class="form-check-label text-danger" for="remove_banner">
                                        Remove current banner
                                    </label>
                                </div>
                            </div>
                            @endif

                            <div class="mb-3">
                                <label for="banner_image" class="form-label">Upload new banner image</label>
                                <input type="file" class="form-control" id="banner_image" name="banner_image" accept=".webp,.jpg,.png">
                                <div class="form-text">Recommended size: 1200x400px. Max file size: 2MB.</div>
                            </div>

                            <div class="mb-3">
                                <label for="banner_title" class="form-label">Banner Title</label>
                                <input type="text" class="form-control" id="banner_title" name="banner_title"
                                    value="{{ old('banner_title', $school->banner_title) }}" placeholder="Enter banner title">
                            </div>

                            <div class="mb-3">
                                <label for="banner_tagline" class="form-label">Banner Tagline</label>
                                <input type="text" class="form-control" id="banner_tagline" name="banner_tagline"
                                    value="{{ old('banner_tagline', $school->banner_tagline) }}" placeholder="Enter banner tagline">
                            </div>

                            <div class="banner-preview mt-3 text-center" id="banner-preview" style="display: none;">
                                <p class="text-muted">New Banner Preview</p>
                                <img id="banner-preview-img" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                        </div>
                    </div>

                    <!-- School Images -->
                    <div class="card mb-4">
                        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">School Images</h5>
                            <button type="button" class="btn btn-sm btn-primary" id="add-image-btn">
                                <i class="fas fa-plus me-1"></i> Add Image
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Existing Images -->
                            @if($school->images && $school->images->count() > 0)
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Current Images</h6>
                                @foreach($school->images as $image)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="card-title mb-0">Image {{ $loop->iteration }}</h6>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="remove_image_{{ $image->id }}"
                                                    name="remove_images[]" value="{{ $image->id }}">
                                                <label class="form-check-label text-danger" for="remove_image_{{ $image->id }}">
                                                    Remove
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <img src="{{ asset('website/' . $image->image_path) }}" class="img-thumbnail" style="max-height: 100px;">
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0"><strong>Title:</strong> {{ $image->title }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <div id="image-upload-container">
                                <div class="border border-dashed border-2 rounded p-4 text-center mb-3 image-upload-area"
                                    style="border-color: #dee2e6 !important;">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-2">Drag & drop images here or click to browse</p>
                                    <input type="file" class="d-none" id="image-upload-input" multiple accept="image/*">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('image-upload-input').click()">
                                        Select Images
                                    </button>
                                </div>

                                <div id="image-fields-container">
                                    <!-- New image fields will be added here dynamically -->
                                </div>
                            </div>
                            <p class="text-muted small mb-0">Upload up to 10 images with title. Max file size: 2MB each.</p>
                        </div>
                    </div>

                    <!-- School Information -->
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
                            <button type="button" class="btn btn-danger w-100" id="delete-school-btn">
                                <i class="fas fa-trash me-2"></i> Delete School
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Hidden Delete Form (outside main form, not nested) -->
            <form id="delete-form" action="{{ route('schools.destroy', $school->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </section>
    </main>

    <!-- Template for image fields -->
    <template id="image-field-template">
        <div class="image-field card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0">New Image <span class="image-number">1</span></h6>
                    <button type="button" class="btn btn-sm btn-danger remove-image-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Image File</label>
                            <input type="file" class="form-control image-file-input" name="school_images[]" accept=".webp,.jpg,.png">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Image Title</label>
                            <input type="text" class="form-control image-title-input" name="image_titles[]" placeholder="Enter image title">
                        </div>
                    </div>
                </div>

                <div class="image-preview text-center mt-2" style="display: none;">
                    <img class="img-thumbnail preview-img" style="max-height: 100px;">
                </div>
            </div>
        </div>
    </template>

    <!-- Template for quick facts -->
    <template id="quick-fact-template">
        <div class="quick-fact-item mb-2">
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="text" class="form-control" name="quick_fact_keys[]" placeholder="Fact key (e.g., accreditation)">
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="quick_fact_values[]" placeholder="Fact value (e.g., CBSE Affiliated)">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-quick-fact">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const deleteButton = document.getElementById('delete-school-btn');
            const deleteForm = document.getElementById('delete-form');

            if (deleteButton && deleteForm) {
                deleteButton.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this school? This action cannot be undone.')) {
                        deleteForm.submit();
                    }
                });
            }

            // Logo preview functionality
            const logoInput = document.getElementById('logo');
            const logoPreview = document.getElementById('logo-preview');
            const logoPreviewImg = document.getElementById('logo-preview-img');

            if (logoInput && logoPreview) {
                logoInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            logoPreviewImg.src = e.target.result;
                            logoPreview.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        logoPreview.style.display = 'none';
                    }
                });
            }

            // Banner image preview
            const bannerInput = document.getElementById('banner_image');
            const bannerPreview = document.getElementById('banner-preview');
            const bannerPreviewImg = document.getElementById('banner-preview-img');

            if (bannerInput) {
                bannerInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            bannerPreviewImg.src = e.target.result;
                            bannerPreview.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        bannerPreview.style.display = 'none';
                    }
                });
            }

            // Quick Facts functionality
            const quickFactsContainer = document.getElementById('quick-facts-container');
            const addQuickFactBtn = document.getElementById('add-quick-fact');
            const quickFactTemplate = document.getElementById('quick-fact-template');

            if (addQuickFactBtn && quickFactsContainer) {
                addQuickFactBtn.addEventListener('click', function() {
                    const newQuickFact = quickFactTemplate.content.cloneNode(true);

                    // Set up remove button
                    const removeBtn = newQuickFact.querySelector('.remove-quick-fact');
                    removeBtn.addEventListener('click', function() {
                        this.closest('.quick-fact-item').remove();
                        updateQuickFactRemoveButtons();
                    });

                    quickFactsContainer.appendChild(newQuickFact);
                    updateQuickFactRemoveButtons();
                });

                // Set up existing remove buttons
                document.querySelectorAll('.remove-quick-fact').forEach(btn => {
                    btn.addEventListener('click', function() {
                        this.closest('.quick-fact-item').remove();
                        updateQuickFactRemoveButtons();
                    });
                });

                updateQuickFactRemoveButtons();
            }

            function updateQuickFactRemoveButtons() {
                const quickFactItems = quickFactsContainer.querySelectorAll('.quick-fact-item');
                const shouldShowRemove = quickFactItems.length > 1;
                
                quickFactItems.forEach(item => {
                    const removeBtn = item.querySelector('.remove-quick-fact');
                    if (removeBtn) {
                        removeBtn.style.display = shouldShowRemove ? 'block' : 'none';
                    }
                });
            }

            // Add image field functionality
            const addImageBtn = document.getElementById('add-image-btn');
            const imageFieldsContainer = document.getElementById('image-fields-container');
            const imageFieldTemplate = document.getElementById('image-field-template');
            let imageCount = 0;
            const maxImages = 10;

            if (addImageBtn && imageFieldsContainer) {
                addImageBtn.addEventListener('click', function() {
                    if (imageCount >= maxImages) {
                        alert(`You can only upload up to ${maxImages} images.`);
                        return;
                    }

                    const newImageField = imageFieldTemplate.content.cloneNode(true);
                    imageCount++;
                    newImageField.querySelector('.image-number').textContent = imageCount;

                    // Set up file input preview
                    const fileInput = newImageField.querySelector('.image-file-input');
                    const previewContainer = newImageField.querySelector('.image-preview');
                    const previewImg = newImageField.querySelector('.preview-img');

                    fileInput.addEventListener('change', function() {
                        const file = this.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewImg.src = e.target.result;
                                previewContainer.style.display = 'block';
                            }
                            reader.readAsDataURL(file);
                        } else {
                            previewContainer.style.display = 'none';
                        }
                    });

                    // Set up remove button
                    const removeBtn = newImageField.querySelector('.remove-image-btn');
                    removeBtn.addEventListener('click', function() {
                        this.closest('.image-field').remove();
                        imageCount--;
                        updateImageNumbers();
                    });

                    imageFieldsContainer.appendChild(newImageField);
                });
            }

            // Drag and drop functionality
            const dropArea = document.querySelector('.image-upload-area');
            const fileInput = document.getElementById('image-upload-input');

            if (dropArea && fileInput) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropArea.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, unhighlight, false);
                });

                function highlight() {
                    dropArea.style.borderColor = '#4e73df';
                    dropArea.style.backgroundColor = 'rgba(78, 115, 223, 0.05)';
                }

                function unhighlight() {
                    dropArea.style.borderColor = '#dee2e6';
                    dropArea.style.backgroundColor = '';
                }

                dropArea.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;

                    if (imageCount + files.length > maxImages) {
                        alert(`You can only upload up to ${maxImages} images.`);
                        return;
                    }

                    handleFiles(files);
                }

                fileInput.addEventListener('change', function() {
                    if (imageCount + this.files.length > maxImages) {
                        alert(`You can only upload up to ${maxImages} images.`);
                        this.value = '';
                        return;
                    }

                    handleFiles(this.files);
                });

                function handleFiles(files) {
                    for (let i = 0; i < files.length; i++) {
                        if (imageCount >= maxImages) break;

                        if (files[i].type.match('image.*')) {
                            addImageBtn.click();

                            // Set the file to the last added input
                            const lastImageField = imageFieldsContainer.lastElementChild;
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(files[i]);
                            lastImageField.querySelector('.image-file-input').files = dataTransfer.files;

                            // Trigger change event to show preview
                            const event = new Event('change');
                            lastImageField.querySelector('.image-file-input').dispatchEvent(event);
                        }
                    }
                }
            }

            function updateImageNumbers() {
                const imageFields = document.querySelectorAll('.image-field');
                imageFields.forEach((field, index) => {
                    field.querySelector('.image-number').textContent = index + 1;
                });
            }

            // Features and Curriculum enhancement
            // Feature search/filter functionality
            window.filterFeatures = function(searchTerm) {
                const featureItems = document.querySelectorAll('.feature-item');
                featureItems.forEach(item => {
                    const featureName = item.querySelector('.form-check-label').textContent.toLowerCase();
                    if (featureName.includes(searchTerm.toLowerCase())) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide category cards based on visible features
                const categoryCards = document.querySelectorAll('.feature-category');
                categoryCards.forEach(card => {
                    const visibleFeatures = card.querySelectorAll('.feature-item[style="display: block"]').length;
                    if (visibleFeatures > 0) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            };

            // Curriculum selection enhancement
            const curriculumRadios = document.querySelectorAll('input[name="curriculum_id"]');
            curriculumRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Add visual feedback when curriculum is selected
                    curriculumRadios.forEach(r => {
                        r.closest('.col-md-6').classList.remove('border-primary', 'border-2', 'rounded', 'p-2', 'bg-light');
                    });
                    if (this.checked) {
                        this.closest('.col-md-6').classList.add('border-primary', 'border-2', 'rounded', 'p-2', 'bg-light');
                    }
                });

                // Trigger change on page load for initial state
                if (radio.checked) {
                    radio.dispatchEvent(new Event('change'));
                }
            });

            // Feature category accordion (optional enhancement)
            const categoryHeaders = document.querySelectorAll('.feature-category .card-header');
            categoryHeaders.forEach(header => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', function() {
                    const cardBody = this.nextElementSibling;
                    const isHidden = cardBody.style.display === 'none';
                    cardBody.style.display = isHidden ? 'block' : 'none';
                    this.querySelector('h6').classList.toggle('text-muted', !isHidden);
                });
            });
        });

        // Select all features in a category (optional helper function)
        function selectAllFeatures(category) {
            const checkboxes = document.querySelectorAll(`.feature-category[data-category="${category}"] input[type="checkbox"]`);
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }

        // Fee Structure Toggle for Edit Form
        document.addEventListener('DOMContentLoaded', function() {
            const feeFixedRadioEdit = document.getElementById('fee_fixed_edit');
            const feeClassWiseRadioEdit = document.getElementById('fee_class_wise_edit');
            const fixedFeeSectionEdit = document.getElementById('fixed_fee_structure_edit');
            const classWiseFeeSectionEdit = document.getElementById('class_wise_fee_structure_edit');
            const regularFeesInputEdit = document.getElementById('regular_fees');
            const discountedFeesInputEdit = document.getElementById('discounted_fees');
            const admissionFeesInputEdit = document.getElementById('admission_fees');
            const classWiseAdmissionFeesInputEdit = document.getElementById('class_wise_admission_fees');
            const classWiseFeesContainerEdit = document.getElementById('class_wise_fees_container_edit');
            const addClassWiseFeeRowEdit = document.getElementById('add_class_wise_fee_row_edit');
            const existingClassWiseFeesEdit = normalizeClassWiseFees(@json(old('class_wise_fees', json_decode($school->class_wise_fees, true) ?? [])));

            function escapeHtmlEdit(value) {
                return String(value || '').replace(/[&<>"']/g, function (match) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    }[match];
                });
            }

            function normalizeClassWiseFees(value) {
                if (!value) {
                    return [];
                }

                if (Array.isArray(value)) {
                    return value.map((item) => {
                        if (item && typeof item === 'object' && ('range' in item || 'amount' in item)) {
                            return {
                                range: item.range || '',
                                amount: item.amount || ''
                            };
                        }
                        return {
                            range: '',
                            amount: ''
                        };
                    });
                }

                if (typeof value === 'object') {
                    return Object.entries(value).map(([range, amount]) => ({
                        range,
                        amount: amount ?? ''
                    }));
                }

                return [];
            }

            function createClassWiseFeeRowEdit(index, range = '', amount = '') {
                const row = document.createElement('div');
                row.className = 'row fee-row mb-2 align-items-end';
                row.innerHTML = `
                    <div class="col-md-5 mb-2">
                        <label class="form-label">Class Range</label>
                        <input type="text" class="form-control class-range" name="class_wise_fees[${index}][range]" maxlength="25" placeholder="e.g., KG to 1" value="${escapeHtmlEdit(range)}" required>
                    </div>
                    <div class="col-md-5 mb-2">
                        <label class="form-label">Fees</label>
                        <input type="text" class="form-control fees-amount" name="class_wise_fees[${index}][amount]" maxlength="15" placeholder="e.g., 1000" value="${escapeHtmlEdit(amount)}" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="button" class="btn btn-danger w-100 remove-fee-row-edit" style="display: none;">Remove</button>
                    </div>
                `;
                return row;
            }

            function updateClassWiseFeeRowNamesEdit() {
                const rows = classWiseFeesContainerEdit.querySelectorAll('.fee-row');
                rows.forEach((row, index) => {
                    const rangeInput = row.querySelector('.class-range');
                    const amountInput = row.querySelector('.fees-amount');
                    rangeInput.name = `class_wise_fees[${index}][range]`;
                    amountInput.name = `class_wise_fees[${index}][amount]`;
                });
            }

            function updateClassWiseFeeRemoveButtonsEdit() {
                const rows = classWiseFeesContainerEdit.querySelectorAll('.fee-row');
                const showRemove = rows.length > 1;
                rows.forEach((row) => {
                    const button = row.querySelector('.remove-fee-row-edit');
                    if (button) {
                        button.style.display = showRemove ? 'block' : 'none';
                    }
                });
            }

            function setClassWiseInputsDisabledEdit(disabled) {
                classWiseFeesContainerEdit.querySelectorAll('input').forEach((input) => {
                    input.disabled = disabled;
                });
            }

            function addFeeRowEdit(range = '', amount = '') {
                const rows = classWiseFeesContainerEdit.querySelectorAll('.fee-row');
                if (rows.length >= 5) {
                    alert('Maximum 5 fee entries allowed');
                    return;
                }
                const row = createClassWiseFeeRowEdit(rows.length, range, amount);
                classWiseFeesContainerEdit.appendChild(row);
                updateClassWiseFeeRemoveButtonsEdit();
            }

            function removeFeeRowEdit(button) {
                const row = button.closest('.fee-row');
                if (!row) return;
                row.remove();
                updateClassWiseFeeRowNamesEdit();
                updateClassWiseFeeRemoveButtonsEdit();
            }

            function populateClassWiseFeeRowsEdit(fees) {
                classWiseFeesContainerEdit.innerHTML = '';
                const normalized = normalizeClassWiseFees(fees);
                if (!normalized.length) {
                    addFeeRowEdit();
                    return;
                }
                normalized.forEach((fee) => addFeeRowEdit(fee.range, fee.amount));
            }

            function toggleFeeSectionsEdit() {
                if (feeFixedRadioEdit.checked) {
                    fixedFeeSectionEdit.style.display = 'block';
                    classWiseFeeSectionEdit.style.display = 'none';
                    setClassWiseInputsDisabledEdit(true);

                    if (classWiseAdmissionFeesInputEdit.value) {
                        admissionFeesInputEdit.value = classWiseAdmissionFeesInputEdit.value;
                    }
                } else {
                    fixedFeeSectionEdit.style.display = 'none';
                    classWiseFeeSectionEdit.style.display = 'block';
                    setClassWiseInputsDisabledEdit(false);

                    if (admissionFeesInputEdit.value) {
                        classWiseAdmissionFeesInputEdit.value = admissionFeesInputEdit.value;
                    }
                }
            }

            if (classWiseFeesContainerEdit) {
                populateClassWiseFeeRowsEdit(existingClassWiseFeesEdit);
            }

            if (feeFixedRadioEdit && feeClassWiseRadioEdit) {
                feeFixedRadioEdit.addEventListener('change', toggleFeeSectionsEdit);
                feeClassWiseRadioEdit.addEventListener('change', toggleFeeSectionsEdit);
                toggleFeeSectionsEdit();
            }

            if (addClassWiseFeeRowEdit) {
                addClassWiseFeeRowEdit.addEventListener('click', function() {
                    addFeeRowEdit();
                });
            }

            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-fee-row-edit')) {
                    removeFeeRowEdit(event.target);
                }
            });

            if (admissionFeesInputEdit && classWiseAdmissionFeesInputEdit) {
                admissionFeesInputEdit.addEventListener('input', function() {
                    if (feeFixedRadioEdit.checked) {
                        classWiseAdmissionFeesInputEdit.value = this.value;
                    }
                });

                classWiseAdmissionFeesInputEdit.addEventListener('input', function() {
                    if (feeClassWiseRadioEdit.checked) {
                        admissionFeesInputEdit.value = this.value;
                    }
                });
            }
        });
    </script>

    <style>
        .image-upload-area {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .image-upload-area:hover {
            border-color: #4e73df !important;
            background-color: rgba(78, 115, 223, 0.05);
        }

        .image-field {
            border-left: 3px solid #4e73df;
        }

        .remove-image-btn,
        .remove-quick-fact {
            padding: 0.25rem 0.5rem;
        }

        .border-dashed {
            border-style: dashed !important;
        }

        .quick-fact-item {
            padding: 0.5rem;
            border: 1px solid #e9ecef;
            border-radius: 0.375rem;
            background-color: #f8f9fa;
        }
    </style>
</x-app-layout>