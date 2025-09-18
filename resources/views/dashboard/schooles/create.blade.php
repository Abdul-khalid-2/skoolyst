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


            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('schools.store') }}">
                                @csrf
                                <!-- Basic Information -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">School Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter school name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Enter address">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" name="city" placeholder="Enter city">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_number" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Enter contact number">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="website" class="form-label">Website</label>
                                        <input type="url" class="form-control" id="website" name="website" placeholder="Enter school website URL">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">School Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter school description"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="facilities" class="form-label">Facilities</label>
                                    <textarea class="form-control" id="facilities" name="facilities" rows="4" placeholder="Enter available facilities"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="school_type" class="form-label">School Type</label>
                                    <select class="form-control" id="school_type" name="school_type">
                                        <option value="Co-Ed">Co-Ed</option>
                                        <option value="Boys">Boys</option>
                                        <option value="Girls">Girls</option>
                                    </select>
                                </div>

                                <!-- Pricing -->
                                <h5 class="mb-3">School Fees</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="regular_fees" class="form-label">Regular Fees</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="regular_fees" name="regular_fees" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="discounted_fees" class="form-label">Discounted Fees</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="discounted_fees" name="discounted_fees" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="admission_fees" class="form-label">Admission Fees</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="admission_fees" name="admission_fees" placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Info -->
                                <h5 class="mb-3">Additional Information</h5>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="visibility" class="form-label">Visibility</label>
                                    <select class="form-control" id="visibility" name="visibility">
                                        <option value="public">Public</option>
                                        <option value="private">Private</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="publish_date" class="form-label">Publish Date</label>
                                    <input type="datetime-local" class="form-control" id="publish_date" name="publish_date">
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save School
                                    </button>
                                    <button type="button" class="btn btn-success">
                                        <i class="fas fa-plus me-2"></i>Save & Add New
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="showPage('schools')">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- School Status -->
                    <div class="card mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">School Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="draft">Draft</option>
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="visibility" class="form-label">Visibility</label>
                                <select class="form-control" id="visibility" name="visibility">
                                    <option value="public">Public</option>
                                    <option value="private">Private</option>
                                    <option value="password">Password Protected</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="publish_date" class="form-label">Publish Date</label>
                                <input type="datetime-local" class="form-control" id="publish_date" name="publish_date">
                            </div>
                        </div>
                    </div>

                    <!-- School Images -->
                    <div class="card mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">School Images</h5>
                        </div>
                        <div class="card-body">
                            <div class="border border-dashed border-2 rounded p-4 text-center mb-3"
                                style="border-color: #dee2e6 !important;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-2">Drag & drop images here or click to browse</p>
                                <input type="file" class="form-control" multiple accept="image/*">
                            </div>
                            <p class="text-muted small mb-0">Upload up to 10 images. Max file size: 5MB each.</p>
                        </div>
                    </div>
                </div>
            </div>

        </section>


    </main>
</x-app-layout>