<x-app-layout>
    <!-- Main Content -->
    <main class="main-content">
        <!-- schoolyes Create -->
        <section id="schooles-create" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Create New School</h2>
                    <p class="mb-0 text-muted">Add a new school to the system</p>
                </div>
                <a href="{{ route('schools.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Schooles
                </a>
            </div>


            <form class="row" method="POST" action="{{ route('schools.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- Admin Information -->
                            <h5 class="mb-3">Admin Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="admin-name" class="form-label">Admin Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="admin-name" name="admin-name" placeholder="Enter Admin Name" required value="{{ old('admin-name') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="admin-email" class="form-label">Admin Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="admin-email" name="admin-email" placeholder="Enter Admin email address" required value="{{ old('admin-email') }}">
                                </div>
                            </div>

                            <!-- Basic School Information -->
                            <h5 class="mb-3">Basic Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">School Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter school name" required value="{{ old('name') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">School Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter address" required value="{{ old('address') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" required value="{{ old('city') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Enter contact number" value="{{ old('contact_number') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" class="form-control" id="website" name="website" placeholder="Enter school website URL" value="{{ old('website') }}">
                                </div>
                            </div>

                            <!-- School Profile Information -->
                            <h5 class="mb-3">School Profile Details</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="established_year" class="form-label">Established Year</label>
                                    <input type="text" class="form-control" id="established_year" name="established_year" placeholder="e.g., 1995" value="{{ old('established_year') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="student_strength" class="form-label">Student Strength</label>
                                    <input type="number" class="form-control" id="student_strength" name="student_strength" placeholder="e.g., 1000" value="{{ old('student_strength') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="faculty_count" class="form-label">Faculty Count</label>
                                    <input type="number" class="form-control" id="faculty_count" name="faculty_count" placeholder="e.g., 50" value="{{ old('faculty_count') }}">
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

                            <!-- Quick Facts (JSON) -->
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
                                    <input type="url" class="form-control" id="facebook_url" name="facebook_url" placeholder="https://facebook.com/..." value="{{ old('facebook_url') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="twitter_url" class="form-label">Twitter</label>
                                    <input type="url" class="form-control" id="twitter_url" name="twitter_url" placeholder="https://twitter.com/..." value="{{ old('twitter_url') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="instagram_url" class="form-label">Instagram</label>
                                    <input type="url" class="form-control" id="instagram_url" name="instagram_url" placeholder="https://instagram.com/..." value="{{ old('instagram_url') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="linkedin_url" class="form-label">LinkedIn</label>
                                    <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" placeholder="https://linkedin.com/..." value="{{ old('linkedin_url') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="youtube_url" class="form-label">YouTube</label>
                                    <input type="url" class="form-control" id="youtube_url" name="youtube_url" placeholder="https://youtube.com/..." value="{{ old('youtube_url') }}">
                                </div>
                            </div>

                            <!-- Continue with existing sections... -->
                            <div class="mb-3">
                                <label for="description" class="form-label">School Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter school description">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="facilities" class="form-label">Facilities</label>
                                <textarea class="form-control" id="facilities" name="facilities" rows="4" placeholder="Enter available facilities">{{ old('facilities') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="school_type" class="form-label">School Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="school_type" name="school_type" required>
                                    <option value="">Select School Type</option>
                                    <option value="Co-Ed" {{ old('school_type') == 'Co-Ed' ? 'selected' : '' }}>Co-Ed</option>
                                    <option value="Boys" {{ old('school_type') == 'Boys' ? 'selected' : '' }}>Boys</option>
                                    <option value="Girls" {{ old('school_type') == 'Girls' ? 'selected' : '' }}>Girls</option>
                                </select>
                            </div>

                            <!-- Features Section -->
                            <div class="mb-3">
                                <label class="form-label">School Features</label>
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
                                                    <input class="form-check-input" type="checkbox" name="features[]" value="{{ $feature->id }}" id="feature_{{ $feature->id }}" {{ in_array($feature->id, old('features', [])) ? 'checked' : '' }}>
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
                                            <input class="form-check-input" type="radio" name="curriculum_id" value="{{ $curriculum->id }}" id="curriculum_{{ $curriculum->id }}" {{ old('curriculum_id', $curriculum->id) == $curriculum->id ? 'checked' : '' }}>
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
                            </div>

                            <!-- Admin Account -->
                            <h5 class="mt-4 mb-3">Admin Account</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Admin Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter admin password" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <h5 class="mb-3">School Fees</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="regular_fees" class="form-label">Regular Fees</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="regular_fees" name="regular_fees" placeholder="0.00" step="0.01" min="0" value="{{ old('regular_fees') }}">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="discounted_fees" class="form-label">Discounted Fees</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="discounted_fees" name="discounted_fees" placeholder="0.00" step="0.01" min="0" value="{{ old('discounted_fees') }}">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="admission_fees" class="form-label">Admission Fees</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="admission_fees" name="admission_fees" placeholder="0.00" step="0.01" min="0" value="{{ old('admission_fees') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <h5 class="mb-3">Additional Information</h5>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="visibility" class="form-label">Visibility <span class="text-danger">*</span></label>
                                <select class="form-control" id="visibility" name="visibility" required>
                                    <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="private" {{ old('visibility', 'private') == 'private' ? 'selected' : '' }}>Private</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="publish_date" class="form-label">Publish Date</label>
                                <input type="datetime-local" class="form-control" id="publish_date" name="publish_date" value="{{ old('publish_date') }}">
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save School
                                </button>
                                <button type="button" class="btn btn-success" id="save-and-add">
                                    <i class="fas fa-plus me-2"></i>Save & Add New
                                </button>
                                <a href="{{ route('schools.index') }}" class="btn btn-outline-secondary">
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
                            <div class="mb-3">
                                <label for="logo" class="form-label">Upload school logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept=".webp,.jpg,.png">
                                <div class="form-text">Recommended size: 200x200px. Max file size: 2MB.</div>
                            </div>
                            <div class="logo-preview mt-3 text-center" id="logo-preview" style="display: none;">
                                <p class="text-muted">Logo Preview</p>
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
                            <div class="mb-3">
                                <label for="banner_image" class="form-label">Upload cover/banner image</label>
                                <input type="file" class="form-control" id="banner_image" name="banner_image" accept=".webp,.jpg,.png">
                                <div class="form-text">Recommended size: 1200x400px. Max file size: 2MB.</div>
                            </div>

                            <div class="mb-3">
                                <label for="banner_title" class="form-label">Banner Title</label>
                                <input type="text" class="form-control" id="banner_title" name="banner_title" placeholder="Enter banner title" value="{{ old('banner_title') }}">
                            </div>

                            <div class="mb-3">
                                <label for="banner_tagline" class="form-label">Banner Tagline</label>
                                <input type="text" class="form-control" id="banner_tagline" name="banner_tagline" placeholder="Enter banner tagline" value="{{ old('banner_tagline') }}">
                            </div>

                            <div class="banner-preview mt-3 text-center" id="banner-preview" style="display: none;">
                                <p class="text-muted">Banner Preview</p>
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
                                    <!-- Image fields will be added here dynamically -->
                                </div>
                            </div>
                            <p class="text-muted small mb-0">Upload up to 10 images with title. Max file size: 2MB each.</p>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>

    <!-- Template for image fields -->
    <template id="image-field-template">
        <div class="image-field card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0">Image <span class="image-number">1</span></h6>
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
            // Logo preview
            const logoInput = document.getElementById('logo');
            const logoPreview = document.getElementById('logo-preview');
            const logoPreviewImg = document.getElementById('logo-preview-img');

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

            // Banner image preview
            const bannerInput = document.getElementById('banner_image');
            const bannerPreview = document.getElementById('banner-preview');
            const bannerPreviewImg = document.getElementById('banner-preview-img');

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

            // Quick Facts functionality
            const quickFactsContainer = document.getElementById('quick-facts-container');
            const addQuickFactBtn = document.getElementById('add-quick-fact');
            const quickFactTemplate = document.getElementById('quick-fact-template');

            addQuickFactBtn.addEventListener('click', function() {
                const newQuickFact = quickFactTemplate.content.cloneNode(true);

                // Set up remove button
                const removeBtn = newQuickFact.querySelector('.remove-quick-fact');
                removeBtn.addEventListener('click', function() {
                    this.closest('.quick-fact-item').remove();
                });

                quickFactsContainer.appendChild(newQuickFact);

                // Show remove buttons on all items if there's more than one
                const quickFactItems = quickFactsContainer.querySelectorAll('.quick-fact-item');
                if (quickFactItems.length > 1) {
                    quickFactItems.forEach(item => {
                        item.querySelector('.remove-quick-fact').style.display = 'block';
                    });
                }
            });

            // Show remove button on first quick fact if there are existing values from old input
            const firstRemoveBtn = quickFactsContainer.querySelector('.remove-quick-fact');
            if (firstRemoveBtn) {
                const quickFactItems = quickFactsContainer.querySelectorAll('.quick-fact-item');
                if (quickFactItems.length > 1) {
                    firstRemoveBtn.style.display = 'block';
                }
            }

            // Rest of your existing JavaScript for images...
            // [Keep all your existing image upload JavaScript code here]

            // Add image field
            const addImageBtn = document.getElementById('add-image-btn');
            const imageFieldsContainer = document.getElementById('image-fields-container');
            const imageFieldTemplate = document.getElementById('image-field-template');
            let imageCount = 0;
            const maxImages = 10;

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

            // Drag and drop functionality for images
            const dropArea = document.querySelector('.image-upload-area');
            const fileInput = document.getElementById('image-upload-input');

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

            function updateImageNumbers() {
                const imageFields = document.querySelectorAll('.image-field');
                imageFields.forEach((field, index) => {
                    field.querySelector('.image-number').textContent = index + 1;
                });
            }

            // Save and add new functionality
            document.getElementById('save-and-add').addEventListener('click', function() {
                const form = document.querySelector('form');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'save_and_add';
                hiddenInput.value = '1';
                form.appendChild(hiddenInput);
                form.submit();
            });
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