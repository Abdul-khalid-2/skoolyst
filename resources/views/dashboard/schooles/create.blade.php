<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="{{ asset('css/dashboard/schooles/schools-forms.css') }}">
        <style>
            .step-wrapper {
                display: flex;
                justify-content: space-between;
                position: relative;
                padding: 0 1.5rem;
            }
            .step-wrapper::before {
                content: '';
                position: absolute;
                top: 20px;
                left: 2.5rem;
                right: 2.5rem;
                height: 2px;
                background: #dee2e6;
                z-index: 0;
            }
            .step-progress-line {
                position: absolute;
                top: 20px;
                left: 2.5rem;
                height: 2px;
                background: #0d6efd;
                z-index: 1;
                transition: width 0.35s ease;
                width: 0%;
            }
            .step-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                position: relative;
                z-index: 2;
                cursor: default;
            }
            .step-item.completed { cursor: pointer; }
            .step-circle {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #fff;
                border: 2px solid #dee2e6;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #6c757d;
                font-size: 13px;
                transition: all 0.3s ease;
            }
            .step-label {
                margin-top: 6px;
                font-size: 11px;
                color: #6c757d;
                text-align: center;
                max-width: 80px;
                transition: color 0.3s;
            }
            .step-item.active .step-circle  { background: #0d6efd; border-color: #0d6efd; color: #fff; }
            .step-item.active .step-label   { color: #0d6efd; font-weight: 600; }
            .step-item.completed .step-circle { background: #198754; border-color: #198754; color: #fff; }
            .step-item.completed .step-label  { color: #198754; }
            .form-step { display: none; }
            .form-step.active { display: block; }
        </style>
    @endpush

    <main class="main-content">
        <section id="schooles-create" class="page-section">
            <x-page-header>
                <x-slot name="heading">
                    <h2 class="h4 mb-0">Create New School</h2>
                    <p class="mb-0 text-muted">Add a new school to the system</p>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('schools.index') }}" variant="secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Schools
                    </x-button>
                </x-slot>
            </x-page-header>

            @if (session('success'))
                <x-alert variant="success">{{ session('success') }}</x-alert>
            @endif

            @if (session('error'))
                <x-alert variant="danger" class="border-danger" id="school-form-session-error" :icon="false" :dismissible="false">
                    <div class="d-flex align-items-start gap-2">
                        <i class="fas fa-exclamation-triangle fa-lg mt-1"></i>
                        <div>
                            <strong class="d-block">Something went wrong</strong>
                            <span class="d-block small">{{ session('error') }}</span>
                        </div>
                    </div>
                </x-alert>
            @endif

            @if ($errors->any())
                <x-alert variant="warning" class="border border-warning" id="school-form-validation-summary" :icon="false" :dismissible="false">
                    <h3 class="h6 text-dark mb-2">
                        <i class="fas fa-clipboard-list me-1 text-warning"></i> Please correct the following
                    </h3>
                    <ul class="mb-0 ps-3 small text-dark">
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            @php
                $step1Fields = ['admin-name','admin-email','name','email','address','city','password','password_confirmation','contact_number','website'];
                $step2Fields = ['established_year','student_strength','faculty_count','campus_size','school_motto','mission','vision','description','facilities'];
                $step3Fields = ['school_gender_type','school_ownership_type','features','curriculum_ids'];
                $step4Fields = ['fee_structure_type','regular_fees','discounted_fees','admission_fees','class_wise_fees','status','visibility','publish_date','facebook_url','twitter_url','instagram_url','linkedin_url','youtube_url'];
                $step5Fields = ['logo','banner_image','banner_title','banner_tagline','school_images'];

                $initialStep = 1;
                if ($errors->hasAny($step1Fields)) {
                    $initialStep = 1;
                } elseif ($errors->hasAny($step2Fields)) {
                    $initialStep = 2;
                } elseif ($errors->hasAny($step3Fields)) {
                    $initialStep = 3;
                } elseif ($errors->hasAny($step4Fields)) {
                    $initialStep = 4;
                } elseif ($errors->hasAny($step5Fields)) {
                    $initialStep = 5;
                }
            @endphp

            {{-- Step Indicator --}}
            <x-card class="mb-4">
                <div class="card-body py-3">
                    <div class="step-wrapper">
                        <div class="step-progress-line" id="step-progress-line"></div>

                        <div class="step-item" data-target-step="1">
                            <div class="step-circle"><i class="fas fa-user-shield"></i></div>
                            <span class="step-label">Admin &amp; Basic</span>
                        </div>
                        <div class="step-item" data-target-step="2">
                            <div class="step-circle"><i class="fas fa-info-circle"></i></div>
                            <span class="step-label">Profile Details</span>
                        </div>
                        <div class="step-item" data-target-step="3">
                            <div class="step-circle"><i class="fas fa-school"></i></div>
                            <span class="step-label">Type &amp; Curriculum</span>
                        </div>
                        <div class="step-item" data-target-step="4">
                            <div class="step-circle"><i class="fas fa-money-bill-wave"></i></div>
                            <span class="step-label">Fees &amp; Settings</span>
                        </div>
                        <div class="step-item" data-target-step="5">
                            <div class="step-circle"><i class="fas fa-images"></i></div>
                            <span class="step-label">Media</span>
                        </div>
                    </div>
                </div>
            </x-card>

            <form id="main-school-form" method="POST" action="{{ route('schools.store') }}" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- ==================== STEP 1 ==================== --}}
                <div class="form-step" id="step-1">
                    <x-card>
                        <div class="card-body">
                            <h5 class="mb-3">Admin Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin-name" class="form-label">Admin Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('admin-name') is-invalid @enderror" id="admin-name" name="admin-name" placeholder="Enter Admin Name" value="{{ old('admin-name') }}">
                                    @error('admin-name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin-email" class="form-label">Admin Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('admin-email') is-invalid @enderror" id="admin-email" name="admin-email" placeholder="Enter Admin email address" value="{{ old('admin-email') }}">
                                    @error('admin-email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <h5 class="mb-3">Basic Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">School Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter school name" value="{{ old('name') }}">
                                    @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">School Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter school contact email" value="{{ old('email') }}">
                                    <div class="form-text">Used for school contact record (must be unique).</div>
                                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Enter address" value="{{ old('address') }}">
                                    @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="Enter city" value="{{ old('city') }}">
                                    @error('city')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <textarea class="form-control" id="contact_number" name="contact_number" rows="3"
                                        placeholder="Phone #1: 0333-1234567&#10;Phone #2: 0349-7654321">{{ old('contact_number') }}</textarea>
                                    <small class="text-muted">Enter each number on a new line.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" placeholder="https://…" value="{{ old('website') }}">
                                    @error('website')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <h5 class="mt-2 mb-3">Admin Account</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Admin Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Min. 8 characters" autocomplete="new-password">
                                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Re-enter password" autocomplete="new-password">
                                    @error('password_confirmation')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-3 border-top">
                                <button type="button" class="btn btn-primary next-step-btn" data-next="2">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </x-card>
                </div>

                {{-- ==================== STEP 2 ==================== --}}
                <div class="form-step" id="step-2">
                    <x-card>
                        <div class="card-body">
                            <h5 class="mb-3">School Profile Details</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="established_year" class="form-label">Established Year</label>
                                    <input type="text" class="form-control" id="established_year" name="established_year" placeholder="e.g., 1995" value="{{ old('established_year') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="student_strength" class="form-label">Student Strength</label>
                                    <input type="text" class="form-control" id="student_strength" name="student_strength" placeholder="e.g., 200 to 500" value="{{ old('student_strength') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="faculty_count" class="form-label">Faculty Count</label>
                                    <input type="text" class="form-control" id="faculty_count" name="faculty_count" placeholder="e.g., 10 to 15" value="{{ old('faculty_count') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="campus_size" class="form-label">Campus Size</label>
                                    <input type="text" class="form-control" id="campus_size" name="campus_size" placeholder="e.g., 5 acres" value="{{ old('campus_size') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="school_motto" class="form-label">School Motto</label>
                                <input type="text" class="form-control" id="school_motto" name="school_motto" placeholder="Enter school motto" value="{{ old('school_motto') }}">
                            </div>
                            <div class="mb-3">
                                <label for="mission" class="form-label">Mission</label>
                                <textarea class="form-control" id="mission" name="mission" rows="3" placeholder="Enter school mission statement">{{ old('mission') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="vision" class="form-label">Vision</label>
                                <textarea class="form-control" id="vision" name="vision" rows="3" placeholder="Enter school vision statement">{{ old('vision') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Quick Facts</label>
                                <div id="quick-facts-container">
                                    <div class="quick-fact-item mb-2">
                                        <div class="row g-2">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="quick_fact_keys[]" placeholder="Fact key (e.g., accreditation)">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="quick_fact_values[]" placeholder="Fact value (e.g., CBSE Affiliated)">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-quick-fact" style="display:none;"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-quick-fact">
                                    <i class="fas fa-plus me-1"></i> Add Quick Fact
                                </button>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">School Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter school description">{{ old('description') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="facilities" class="form-label">Facilities</label>
                                <textarea class="form-control" id="facilities" name="facilities" rows="4" placeholder="Enter available facilities">{{ old('facilities') }}</textarea>
                            </div>

                            @include('dashboard.schooles.partials.urdu-translation-fields', ['school' => null])

                            <div class="d-flex justify-content-between pt-3 border-top mt-3">
                                <button type="button" class="btn btn-secondary prev-step-btn" data-prev="1">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary next-step-btn" data-next="3">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </x-card>
                </div>

                {{-- ==================== STEP 3 ==================== --}}
                <div class="form-step" id="step-3">
                    <x-card>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="school_gender_type" class="form-label">School Type (Gender) <span class="text-danger">*</span></label>
                                    <select class="form-control @error('school_gender_type') is-invalid @enderror" id="school_gender_type" name="school_gender_type">
                                        <option value="">Select Gender Type</option>
                                        <option value="girls"          {{ old('school_gender_type') == 'girls'          ? 'selected' : '' }}>Girls</option>
                                        <option value="boys"           {{ old('school_gender_type') == 'boys'           ? 'selected' : '' }}>Boys</option>
                                        <option value="co-education"   {{ old('school_gender_type') == 'co-education'   ? 'selected' : '' }}>Co-Education</option>
                                    </select>
                                    @error('school_gender_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="school_ownership_type" class="form-label">School Type (Ownership) <span class="text-danger">*</span></label>
                                    <select class="form-control @error('school_ownership_type') is-invalid @enderror" id="school_ownership_type" name="school_ownership_type">
                                        <option value="">Select Ownership Type</option>
                                        <option value="private"         {{ old('school_ownership_type') == 'private'         ? 'selected' : '' }}>Private</option>
                                        <option value="government"      {{ old('school_ownership_type') == 'government'      ? 'selected' : '' }}>Government</option>
                                        <option value="semi-government" {{ old('school_ownership_type') == 'semi-government' ? 'selected' : '' }}>Semi-Government</option>
                                        <option value="ngo"             {{ old('school_ownership_type') == 'ngo'             ? 'selected' : '' }}>NGO</option>
                                    </select>
                                    @error('school_ownership_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">School Features</label>
                                @foreach($features->groupBy('category') as $category => $categoryFeatures)
                                <x-card class="mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 text-capitalize">{{ $category }} Features</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($categoryFeatures as $feature)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="features[]" value="{{ $feature->id }}" id="feature_{{ $feature->id }}" {{ in_array($feature->id, old('features', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="feature_{{ $feature->id }}">
                                                        {{ $feature->name }}
                                                        @if($feature->icon)<i class="fas fa-{{ $feature->icon }} ms-1 text-muted"></i>@endif
                                                    </label>
                                                </div>
                                                @if($feature->description)<small class="text-muted ms-4">{{ $feature->description }}</small>@endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </x-card>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Curriculum <span class="text-danger">*</span></label>
                                <div class="row">
                                    @foreach($curriculums as $curriculum)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input curriculum-checkbox" type="checkbox" name="curriculum_ids[]" value="{{ $curriculum->id }}" id="curriculum_{{ $curriculum->id }}" {{ in_array($curriculum->id, old('curriculum_ids', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="curriculum_{{ $curriculum->id }}">{{ $curriculum->name }}</label>
                                        </div>
                                        @if($curriculum->description)<small class="text-muted ms-4">{{ $curriculum->description }}</small>@endif
                                    </div>
                                    @endforeach
                                </div>
                                <div id="curriculum-error" class="text-danger small mt-1" style="display:none;">Please select at least one curriculum.</div>
                                @error('curriculum_ids')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-between pt-3 border-top">
                                <button type="button" class="btn btn-secondary prev-step-btn" data-prev="2">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary next-step-btn" data-next="4">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </x-card>
                </div>

                {{-- ==================== STEP 4 ==================== --}}
                <div class="form-step" id="step-4">
                    <x-card>
                        <div class="card-body">
                            <h5 class="mb-3">Social Media Links</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="facebook_url" class="form-label">Facebook</label>
                                    <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" id="facebook_url" name="facebook_url" placeholder="https://facebook.com/..." value="{{ old('facebook_url') }}">
                                    @error('facebook_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="twitter_url" class="form-label">Twitter</label>
                                    <input type="url" class="form-control @error('twitter_url') is-invalid @enderror" id="twitter_url" name="twitter_url" placeholder="https://twitter.com/..." value="{{ old('twitter_url') }}">
                                    @error('twitter_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="instagram_url" class="form-label">Instagram</label>
                                    <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" id="instagram_url" name="instagram_url" placeholder="https://instagram.com/..." value="{{ old('instagram_url') }}">
                                    @error('instagram_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="linkedin_url" class="form-label">LinkedIn</label>
                                    <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror" id="linkedin_url" name="linkedin_url" placeholder="https://linkedin.com/..." value="{{ old('linkedin_url') }}">
                                    @error('linkedin_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="youtube_url" class="form-label">YouTube</label>
                                    <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" id="youtube_url" name="youtube_url" placeholder="https://youtube.com/..." value="{{ old('youtube_url') }}">
                                    @error('youtube_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <h5 class="mb-3">School Fees</h5>
                            <div class="mb-3">
                                <label class="form-label">Fee Structure Type <span class="text-danger">*</span></label>
                                <div class="fee-structure-toggle bg-light p-3 rounded mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="fee_structure_type" id="fee_fixed_create" value="fixed" {{ old('fee_structure_type', 'fixed') == 'fixed' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fee_fixed_create">Fixed Structure (Regular/Discounted Fees)</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="fee_structure_type" id="fee_class_wise_create" value="class_wise" {{ old('fee_structure_type') == 'class_wise' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fee_class_wise_create">Class-wise Structure</label>
                                    </div>
                                </div>
                                @error('fee_structure_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div id="fixed_fee_structure_create" class="fee-structure-section bg-light p-3 rounded mb-3" style="{{ old('fee_structure_type', 'fixed') == 'fixed' ? '' : 'display:none;' }}">
                                <h6 class="mb-3 text-muted">Fixed Fee Structure</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="regular_fees" class="form-label">Regular Fees</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rs</span>
                                            <input type="number" class="form-control" id="regular_fees" name="regular_fees" placeholder="0.00" step="0.01" min="0" value="{{ old('regular_fees') }}">
                                        </div>
                                        @error('regular_fees')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="discounted_fees" class="form-label">Discounted Fees</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rs</span>
                                            <input type="number" class="form-control" id="discounted_fees" name="discounted_fees" placeholder="0.00" step="0.01" min="0" value="{{ old('discounted_fees') }}">
                                        </div>
                                        @error('discounted_fees')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="admission_fees_fixed" class="form-label">Admission Fees</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rs</span>
                                            <input type="number" class="form-control" id="admission_fees_fixed" name="admission_fees" placeholder="0.00" step="0.01" min="0" value="{{ old('admission_fees') }}">
                                        </div>
                                        @error('admission_fees')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div id="class_wise_fee_structure_create" class="fee-structure-section bg-light p-3 rounded mb-3" style="{{ old('fee_structure_type') == 'class_wise' ? '' : 'display:none;' }}">
                                <h6 class="mb-3 text-muted">Class-wise Fee Structure</h6>
                                <div class="mb-3">
                                    <label class="form-label">Class-wise Fees <span class="text-danger">*</span></label>
                                    <div id="class_wise_fees_container_create"></div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add_class_wise_fee_row_create">
                                        <i class="fas fa-plus me-1"></i> Add Fee Entry
                                    </button>
                                    <small class="text-muted d-block mt-2">Maximum 5 fee entries. Class Range max 25 chars. Fees max 8 chars.</small>
                                    @error('class_wise_fees')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
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
                                        <input type="number" class="form-control" id="class_wise_admission_fees" name="admission_fees" placeholder="0.00" step="0.01" min="0" value="{{ old('admission_fees') }}">
                                    </div>
                                    <small class="text-muted">If admission fee is same for all classes, enter it here</small>
                                </div>
                            </div>

                            <h5 class="mb-3">Additional Information</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active"   {{ old('status', 'active') == 'active'   ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="visibility" class="form-label">Visibility <span class="text-danger">*</span></label>
                                    <select class="form-control @error('visibility') is-invalid @enderror" id="visibility" name="visibility">
                                        <option value="public"  {{ old('visibility') == 'public'                   ? 'selected' : '' }}>Public</option>
                                        <option value="private" {{ old('visibility', 'private') == 'private' ? 'selected' : '' }}>Private</option>
                                    </select>
                                    @error('visibility')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="publish_date" class="form-label">Publish Date</label>
                                    <input type="datetime-local" class="form-control" id="publish_date" name="publish_date" value="{{ old('publish_date') }}">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between pt-3 border-top">
                                <button type="button" class="btn btn-secondary prev-step-btn" data-prev="3">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary next-step-btn" data-next="5">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </x-card>
                </div>

                {{-- ==================== STEP 5 ==================== --}}
                <div class="form-step" id="step-5">
                    <div class="row">
                        <div class="col-md-4">
                            <x-card class="mb-4">
                                <div class="card-header bg-white border-0">
                                    <h5 class="card-title mb-0">School Logo</h5>
                                </div>
                                <div class="card-body">
                                    <label for="logo" class="form-label">Upload school logo</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/jpeg,image/png,image/jpg,image/webp,image/gif">
                                    <div class="form-text">JPEG, PNG, WebP, GIF. Max 2MB.</div>
                                    @error('logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    <div id="logo-preview" class="mt-3 text-center" style="display:none;">
                                        <p class="text-muted small">Logo Preview</p>
                                        <img id="logo-preview-img" class="img-fluid rounded" style="max-height:150px;">
                                    </div>
                                </div>
                            </x-card>
                        </div>

                        <div class="col-md-8">
                            <x-card class="mb-4">
                                <div class="card-header bg-white border-0">
                                    <h5 class="card-title mb-0">Banner Image</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="banner_image" class="form-label">Upload cover/banner image</label>
                                        <input type="file" class="form-control @error('banner_image') is-invalid @enderror" id="banner_image" name="banner_image" accept="image/jpeg,image/png,image/jpg,image/webp,image/gif">
                                        <div class="form-text">Recommended 1200×400px. Max 2MB.</div>
                                        @error('banner_image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="banner_title" class="form-label">Banner Title</label>
                                            <input type="text" class="form-control" id="banner_title" name="banner_title" placeholder="Enter banner title" value="{{ old('banner_title') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="banner_tagline" class="form-label">Banner Tagline</label>
                                            <input type="text" class="form-control" id="banner_tagline" name="banner_tagline" placeholder="Enter banner tagline" value="{{ old('banner_tagline') }}">
                                        </div>
                                    </div>
                                    <div id="banner-preview" class="mt-2 text-center" style="display:none;">
                                        <p class="text-muted small">Banner Preview</p>
                                        <img id="banner-preview-img" class="img-fluid rounded" style="max-height:150px;">
                                    </div>
                                </div>
                            </x-card>
                        </div>

                        <div class="col-12">
                            <x-card class="mb-4">
                                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">School Images</h5>
                                    <button type="button" class="btn btn-primary btn-sm" id="add-image-btn">
                                        <i class="fas fa-plus me-1"></i> Add Image
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="border border-dashed border-2 rounded p-4 text-center mb-3 image-upload-area" style="border-color:#dee2e6!important;">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-3"></i>
                                        <p class="text-muted mb-2">Drag &amp; drop images here or click to browse</p>
                                        <input type="file" class="d-none" id="image-upload-input" multiple accept="image/*">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('image-upload-input').click()">Select Images</button>
                                    </div>
                                    <div id="image-fields-container"></div>
                                    <p class="text-muted small mb-0">Upload up to 10 images with title. Max 2MB each.</p>
                                    @error('school_images')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                </div>
                            </x-card>
                        </div>
                    </div>

                    <x-card>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-secondary prev-step-btn" data-prev="4">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save School
                                    </button>
                                    <button type="button" class="btn btn-success" id="save-and-add">
                                        <i class="fas fa-plus me-2"></i>Save &amp; Add New
                                    </button>
                                    <a href="{{ route('schools.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>

            </form>
        </section>
    </main>

    {{-- Image field template --}}
    <template id="image-field-template">
        <div class="card image-field mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0">Image <span class="image-number"></span></h6>
                    <button type="button" class="btn btn-danger btn-sm remove-image-btn"><i class="fas fa-times"></i></button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Image File</label>
                        <input type="file" class="form-control image-file-input" name="school_images[]" accept=".webp,.jpg,.png">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Image Title</label>
                        <input type="text" class="form-control image-title-input" name="image_titles[]" placeholder="Enter image title">
                    </div>
                </div>
                <div class="image-preview text-center mt-2" style="display:none;">
                    <img class="img-thumbnail preview-img" style="max-height:100px;">
                </div>
            </div>
        </div>
    </template>

    {{-- Quick fact template --}}
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
                    <button type="button" class="btn btn-danger btn-sm remove-quick-fact"><i class="fas fa-times"></i></button>
                </div>
            </div>
        </div>
    </template>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var TOTAL_STEPS = 5;
        var currentStep = {{ $initialStep }};

        var stepItems  = document.querySelectorAll('.step-item');
        var formSteps  = document.querySelectorAll('.form-step');
        var progressEl = document.getElementById('step-progress-line');

        // ── Show a step ────────────────────────────────────────────
        function showStep(n) {
            formSteps.forEach(function (s) { s.classList.remove('active'); });
            var target = document.getElementById('step-' + n);
            if (target) target.classList.add('active');

            stepItems.forEach(function (item) {
                var num = parseInt(item.getAttribute('data-target-step'), 10);
                item.classList.remove('active', 'completed');
                if (num < n)       item.classList.add('completed');
                else if (num === n) item.classList.add('active');
            });

            if (progressEl) {
                var pct = ((n - 1) / (TOTAL_STEPS - 1)) * 100;
                progressEl.style.width = pct + '%';
            }
            currentStep = n;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ── Field-level error helper ────────────────────────────────
        function markError(el, msg) {
            el.classList.add('is-invalid');
            if (!el.parentNode.querySelector('.step-client-error')) {
                var d = document.createElement('div');
                d.className = 'invalid-feedback d-block step-client-error';
                d.textContent = msg;
                el.parentNode.appendChild(d);
            }
        }

        function clearStepErrors(stepEl) {
            stepEl.querySelectorAll('.step-client-error').forEach(function (e) { e.remove(); });
            stepEl.querySelectorAll('.is-invalid').forEach(function (e) { e.classList.remove('is-invalid'); });
        }

        // ── Validate before advancing ───────────────────────────────
        function validateStep(n) {
            var step = document.getElementById('step-' + n);
            clearStepErrors(step);
            var valid = true;
            var first = null;

            function fail(el, msg) {
                valid = false;
                markError(el, msg);
                if (!first) first = el;
            }

            if (n === 1) {
                var adminName  = document.getElementById('admin-name');
                var adminEmail = document.getElementById('admin-email');
                var schoolName = document.getElementById('name');
                var schoolEmail= document.getElementById('email');
                var address    = document.getElementById('address');
                var city       = document.getElementById('city');
                var pwd        = document.getElementById('password');
                var pwdConf    = document.getElementById('password_confirmation');
                var emailRx    = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!adminName.value.trim())                         fail(adminName,  'Admin Name is required.');
                if (!adminEmail.value.trim())                        fail(adminEmail, 'Admin Email is required.');
                else if (!emailRx.test(adminEmail.value.trim()))     fail(adminEmail, 'Admin Email must be a valid email address.');
                if (!schoolName.value.trim())                        fail(schoolName, 'School Name is required.');
                if (!schoolEmail.value.trim())                       fail(schoolEmail,'School Email is required.');
                else if (!emailRx.test(schoolEmail.value.trim()))    fail(schoolEmail,'School Email must be a valid email address.');
                if (!address.value.trim())                           fail(address,    'Address is required.');
                if (!city.value.trim())                              fail(city,       'City is required.');
                if (!pwd.value)                                      fail(pwd,        'Password is required.');
                else if (pwd.value.length < 8)                       fail(pwd,        'Password must be at least 8 characters.');
                if (!pwdConf.value)                                  fail(pwdConf,    'Please confirm your password.');
                else if (pwd.value && pwd.value !== pwdConf.value)   fail(pwdConf,    'Passwords do not match.');
            }

            if (n === 3) {
                var gender    = document.getElementById('school_gender_type');
                var ownership = document.getElementById('school_ownership_type');
                var checked   = step.querySelectorAll('.curriculum-checkbox:checked');
                var currErr   = document.getElementById('curriculum-error');

                if (!gender.value)    fail(gender,    'School Gender Type is required.');
                if (!ownership.value) fail(ownership, 'School Ownership Type is required.');

                if (checked.length === 0) {
                    valid = false;
                    if (currErr) { currErr.style.display = 'block'; if (!first) first = currErr; }
                } else {
                    if (currErr) currErr.style.display = 'none';
                }
            }

            if (first) {
                setTimeout(function () { first.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 60);
            }
            return valid;
        }

        // ── Button listeners ────────────────────────────────────────
        document.querySelectorAll('.next-step-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var next = parseInt(btn.getAttribute('data-next'), 10);
                if (validateStep(next - 1)) showStep(next);
            });
        });

        document.querySelectorAll('.prev-step-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                showStep(parseInt(btn.getAttribute('data-prev'), 10));
            });
        });

        // Clicking a completed step navigates back
        stepItems.forEach(function (item) {
            item.addEventListener('click', function () {
                var t = parseInt(item.getAttribute('data-target-step'), 10);
                if (t < currentStep) showStep(t);
            });
        });

        // Initialise
        showStep(currentStep);

        // ── Logo preview + size validation ──────────────────────────
        var logoInput = document.getElementById('logo');
        if (logoInput) {
            logoInput.addEventListener('change', function () {
                var f    = this.files[0];
                var prev = document.getElementById('logo-preview');
                var img  = document.getElementById('logo-preview-img');
                clearFileSizeError(this);
                prev.style.display = 'none';
                if (f) {
                    if (f.size > MAX_FILE_SIZE) {
                        showFileSizeError(this, f.name);
                        this.value = '';
                        return;
                    }
                    var r = new FileReader();
                    r.onload = function (e) { img.src = e.target.result; prev.style.display = 'block'; };
                    r.readAsDataURL(f);
                }
            });
        }

        // ── Banner preview + size validation ────────────────────────
        var bannerInput = document.getElementById('banner_image');
        if (bannerInput) {
            bannerInput.addEventListener('change', function () {
                var f    = this.files[0];
                var prev = document.getElementById('banner-preview');
                var img  = document.getElementById('banner-preview-img');
                clearFileSizeError(this);
                prev.style.display = 'none';
                if (f) {
                    if (f.size > MAX_FILE_SIZE) {
                        showFileSizeError(this, f.name);
                        this.value = '';
                        return;
                    }
                    var r = new FileReader();
                    r.onload = function (e) { img.src = e.target.result; prev.style.display = 'block'; };
                    r.readAsDataURL(f);
                }
            });
        }

        // ── Quick facts ─────────────────────────────────────────────
        var qfContainer = document.getElementById('quick-facts-container');
        var qfTemplate  = document.getElementById('quick-fact-template');
        var addQfBtn    = document.getElementById('add-quick-fact');

        function refreshQfButtons() {
            var items = qfContainer.querySelectorAll('.quick-fact-item');
            items.forEach(function (item) {
                var btn = item.querySelector('.remove-quick-fact');
                if (btn) btn.style.display = items.length > 1 ? 'inline-block' : 'none';
            });
        }

        if (addQfBtn) {
            addQfBtn.addEventListener('click', function () {
                var node = qfTemplate.content.cloneNode(true);
                node.querySelector('.remove-quick-fact').addEventListener('click', function () {
                    this.closest('.quick-fact-item').remove();
                    refreshQfButtons();
                });
                qfContainer.appendChild(node);
                refreshQfButtons();
            });
        }

        // ── Image upload ────────────────────────────────────────────
        var addImageBtn        = document.getElementById('add-image-btn');
        var imageContainer     = document.getElementById('image-fields-container');
        var imageTemplate      = document.getElementById('image-field-template');
        var imageUploadInput   = document.getElementById('image-upload-input');
        var dropArea           = document.querySelector('.image-upload-area');
        var imageCount = 0;
        var MAX_IMAGES   = 10;
        var MAX_FILE_SIZE = 2 * 1024 * 1024; // 2 MB in bytes

        function fileSizeMB(file) { return (file.size / 1024 / 1024).toFixed(2); }

        function showFileSizeError(inputEl, fileName) {
            inputEl.classList.add('is-invalid');
            var existing = inputEl.parentNode.querySelector('.file-size-error');
            if (existing) existing.remove();
            var d = document.createElement('div');
            d.className = 'invalid-feedback d-block file-size-error';
            d.textContent = (fileName ? '"' + fileName + '"' : 'File') + ' exceeds the 2MB limit. Please choose a smaller image.';
            inputEl.parentNode.appendChild(d);
        }

        function clearFileSizeError(inputEl) {
            inputEl.classList.remove('is-invalid');
            var existing = inputEl.parentNode.querySelector('.file-size-error');
            if (existing) existing.remove();
        }

        function addImageField(file) {
            if (imageCount >= MAX_IMAGES) { alert('You can only upload up to ' + MAX_IMAGES + ' images.'); return; }
            // Pre-check size for drag-drop / bulk-select files
            if (file && file.size > MAX_FILE_SIZE) {
                alert('"' + file.name + '" is ' + fileSizeMB(file) + 'MB and exceeds the 2MB limit. Please choose a smaller image.');
                return;
            }
            var node = imageTemplate.content.cloneNode(true);
            imageCount++;
            node.querySelector('.image-number').textContent = imageCount;

            var fi  = node.querySelector('.image-file-input');
            var pv  = node.querySelector('.image-preview');
            var pvi = node.querySelector('.preview-img');

            fi.addEventListener('change', function () {
                clearFileSizeError(this);
                pv.style.display = 'none';
                if (this.files[0]) {
                    if (this.files[0].size > MAX_FILE_SIZE) {
                        showFileSizeError(this, this.files[0].name);
                        this.value = '';
                        return;
                    }
                    var r = new FileReader();
                    r.onload = function (e) { pvi.src = e.target.result; pv.style.display = 'block'; };
                    r.readAsDataURL(this.files[0]);
                }
            });

            if (file) {
                var dt = new DataTransfer();
                dt.items.add(file);
                fi.files = dt.files;
                fi.dispatchEvent(new Event('change'));
            }

            node.querySelector('.remove-image-btn').addEventListener('click', function () {
                this.closest('.image-field').remove();
                imageCount--;
                document.querySelectorAll('.image-field').forEach(function (f, i) {
                    f.querySelector('.image-number').textContent = i + 1;
                });
            });

            imageContainer.appendChild(node);
        }

        if (addImageBtn) addImageBtn.addEventListener('click', function () { addImageField(null); });

        if (dropArea) {
            ['dragenter','dragover','dragleave','drop'].forEach(function (ev) {
                dropArea.addEventListener(ev, function (e) { e.preventDefault(); e.stopPropagation(); });
            });
            ['dragenter','dragover'].forEach(function (ev) {
                dropArea.addEventListener(ev, function () { dropArea.style.borderColor = '#0d6efd'; dropArea.style.backgroundColor = 'rgba(13,110,253,.05)'; });
            });
            ['dragleave','drop'].forEach(function (ev) {
                dropArea.addEventListener(ev, function () { dropArea.style.borderColor = '#dee2e6'; dropArea.style.backgroundColor = ''; });
            });
            dropArea.addEventListener('drop', function (e) {
                Array.from(e.dataTransfer.files).forEach(function (f) { if (f.type.match('image.*')) addImageField(f); });
            });
        }

        if (imageUploadInput) {
            imageUploadInput.addEventListener('change', function () {
                Array.from(this.files).forEach(function (f) { if (f.type.match('image.*')) addImageField(f); });
                this.value = '';
            });
        }

        // ── Final submit guard: block oversized images ──────────────
        document.getElementById('main-school-form').addEventListener('submit', function (e) {
            var oversized = [];

            var logo = document.getElementById('logo');
            if (logo && logo.files[0] && logo.files[0].size > MAX_FILE_SIZE) {
                oversized.push('Logo (' + fileSizeMB(logo.files[0]) + ' MB)');
                showFileSizeError(logo, logo.files[0].name);
            }

            var banner = document.getElementById('banner_image');
            if (banner && banner.files[0] && banner.files[0].size > MAX_FILE_SIZE) {
                oversized.push('Banner Image (' + fileSizeMB(banner.files[0]) + ' MB)');
                showFileSizeError(banner, banner.files[0].name);
            }

            document.querySelectorAll('.image-file-input').forEach(function (inp, idx) {
                if (inp.files[0] && inp.files[0].size > MAX_FILE_SIZE) {
                    oversized.push('School Image ' + (idx + 1) + ' — ' + inp.files[0].name + ' (' + fileSizeMB(inp.files[0]) + ' MB)');
                    showFileSizeError(inp, inp.files[0].name);
                }
            });

            if (oversized.length === 0) return; // all good, let form submit

            e.preventDefault();
            showStep(5); // jump to media step

            var box = document.getElementById('image-size-submit-error');
            if (!box) {
                box = document.createElement('div');
                box.id = 'image-size-submit-error';
                box.className = 'alert alert-danger mt-3';
                var container = document.getElementById('image-fields-container');
                container.parentNode.insertBefore(box, container.nextSibling);
            }
            box.innerHTML =
                '<strong><i class="fas fa-exclamation-triangle me-1"></i>File size error</strong>' +
                '<p class="mb-1 mt-1 small">The following files exceed the 2MB limit. Please remove them and choose smaller images:</p>' +
                '<ul class="mb-0 small">' + oversized.map(function (s) { return '<li>' + s + '</li>'; }).join('') + '</ul>';

            setTimeout(function () { box.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 80);
        });

        // ── Save & Add New ──────────────────────────────────────────
        var saveAdd = document.getElementById('save-and-add');
        if (saveAdd) {
            saveAdd.addEventListener('click', function () {
                var form = document.getElementById('main-school-form');
                var h = document.createElement('input');
                h.type = 'hidden'; h.name = 'save_and_add'; h.value = '1';
                form.appendChild(h);
                form.submit();
            });
        }

        // ── Fee structure toggle ─────────────────────────────────────
        var feeFixed        = document.getElementById('fee_fixed_create');
        var feeClassWise    = document.getElementById('fee_class_wise_create');
        var fixedSection    = document.getElementById('fixed_fee_structure_create');
        var classSection    = document.getElementById('class_wise_fee_structure_create');
        var admFixed        = document.getElementById('admission_fees_fixed');
        var admClass        = document.getElementById('class_wise_admission_fees');
        var cwContainer     = document.getElementById('class_wise_fees_container_create');
        var addCwBtn        = document.getElementById('add_class_wise_fee_row_create');
        var existingCwFees  = normalizeCwFees(@json(old('class_wise_fees', [])));

        function escHtml(v) {
            return String(v || '').replace(/[&<>"']/g, function (c) {
                return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
            });
        }

        function normalizeCwFees(val) {
            if (!val) return [];
            if (Array.isArray(val)) return val.map(function (i) {
                return (i && typeof i === 'object') ? { range: i.range || '', amount: i.amount || '' } : { range: '', amount: '' };
            });
            if (typeof val === 'object') return Object.entries(val).map(function (e) { return { range: e[0], amount: e[1] || '' }; });
            return [];
        }

        function createCwRow(idx, range, amount) {
            var row = document.createElement('div');
            row.className = 'row fee-row mb-2 align-items-end';
            row.innerHTML =
                '<div class="col-md-5 mb-2"><label class="form-label">Class Range</label>' +
                '<input type="text" class="form-control class-range" name="class_wise_fees[' + idx + '][range]" maxlength="25" placeholder="e.g., KG to 1" value="' + escHtml(range) + '" required></div>' +
                '<div class="col-md-5 mb-2"><label class="form-label">Fees</label>' +
                '<input type="text" class="form-control fees-amount" name="class_wise_fees[' + idx + '][amount]" maxlength="15" placeholder="e.g., 1000" value="' + escHtml(amount) + '" required></div>' +
                '<div class="col-md-2 mb-2"><button type="button" class="btn btn-danger w-100 remove-cw-row" style="display:none;">Remove</button></div>';
            return row;
        }

        function reindexCwRows() {
            cwContainer.querySelectorAll('.fee-row').forEach(function (row, i) {
                row.querySelector('.class-range').name  = 'class_wise_fees[' + i + '][range]';
                row.querySelector('.fees-amount').name  = 'class_wise_fees[' + i + '][amount]';
            });
        }

        function refreshCwRemove() {
            var rows = cwContainer.querySelectorAll('.fee-row');
            rows.forEach(function (row) {
                var btn = row.querySelector('.remove-cw-row');
                if (btn) btn.style.display = rows.length > 1 ? 'block' : 'none';
            });
        }

        function addCwRow(range, amount) {
            var rows = cwContainer.querySelectorAll('.fee-row');
            if (rows.length >= 5) { alert('Maximum 5 fee entries allowed.'); return; }
            cwContainer.appendChild(createCwRow(rows.length, range || '', amount || ''));
            refreshCwRemove();
        }

        function setCwDisabled(disabled) {
            cwContainer.querySelectorAll('input').forEach(function (inp) { inp.disabled = disabled; });
        }

        function populateCwRows(fees) {
            cwContainer.innerHTML = '';
            var n = normalizeCwFees(fees);
            if (!n.length) { addCwRow(); return; }
            n.forEach(function (f) { addCwRow(f.range, f.amount); });
        }

        function toggleFee() {
            if (feeFixed.checked) {
                fixedSection.style.display  = 'block';
                classSection.style.display  = 'none';
                setCwDisabled(true);
                if (admClass && admFixed && admClass.value) { admFixed.value = admClass.value; admClass.value = ''; }
            } else {
                fixedSection.style.display  = 'none';
                classSection.style.display  = 'block';
                setCwDisabled(false);
                if (admFixed && admClass && admFixed.value) { admClass.value = admFixed.value; admFixed.value = ''; }
            }
        }

        if (cwContainer)  populateCwRows(existingCwFees);
        if (feeFixed && feeClassWise) {
            feeFixed.addEventListener('change', toggleFee);
            feeClassWise.addEventListener('change', toggleFee);
            toggleFee();
        }
        if (addCwBtn) addCwBtn.addEventListener('click', function () { addCwRow(); });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-cw-row')) {
                var row = e.target.closest('.fee-row');
                if (row) { row.remove(); reindexCwRows(); refreshCwRemove(); }
            }
        });

        if (admFixed && admClass) {
            admFixed.addEventListener('input', function () { if (feeFixed.checked) admClass.value = this.value; });
            admClass.addEventListener('input', function () { if (feeClassWise.checked) admFixed.value = this.value; });
        }

        // ── Scroll to first error on page load ──────────────────────
        var errTarget = document.querySelector('#school-form-validation-summary')
                     || document.querySelector('#school-form-session-error')
                     || document.querySelector('.is-invalid');
        if (errTarget) {
            setTimeout(function () { errTarget.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 200);
        }
    });
    </script>
</x-app-layout>
