<x-app-layout>
    <main class="main-content">
        <section id="shop-create" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Create Shop</h2>
                    <p class="mb-0 text-muted">Add a new educational shop</p>
                </div>
                <a href="{{ route('admin.shops.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Shops
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.shops.store') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- Basic Information -->
                                <div class="mb-4">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-info-circle text-primary me-2"></i>Basic Information
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Shop Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" name="name" placeholder="Enter shop name" 
                                                       value="{{ old('name') }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="shop_type" class="form-label">Shop Type <span class="text-danger">*</span></label>
                                                <select class="form-select @error('shop_type') is-invalid @enderror" 
                                                        id="shop_type" name="shop_type" required>
                                                    <option value="">Select Shop Type</option>
                                                    <option value="stationery" {{ old('shop_type') == 'stationery' ? 'selected' : '' }}>Stationery</option>
                                                    <option value="book_store" {{ old('shop_type') == 'book_store' ? 'selected' : '' }}>Book Store</option>
                                                    <option value="mixed" {{ old('shop_type') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                                    <option value="school_affiliated" {{ old('shop_type') == 'school_affiliated' ? 'selected' : '' }}>School Affiliated</option>
                                                </select>
                                                @error('shop_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="4" 
                                                  placeholder="Enter shop description">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="mb-4">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-address-book text-primary me-2"></i>Contact Information
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" name="email" placeholder="Enter email address" 
                                                       value="{{ old('email') }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone</label>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                       id="phone" name="phone" placeholder="Enter phone number" 
                                                       value="{{ old('phone') }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Information -->
                                <div class="mb-4">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>Location Information
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="3" 
                                                  placeholder="Enter complete address">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="city" class="form-label">City</label>
                                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                                       id="city" name="city" placeholder="Enter city" 
                                                       value="{{ old('city') }}">
                                                @error('city')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="state" class="form-label">State</label>
                                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                                       id="state" name="state" placeholder="Enter state" 
                                                       value="{{ old('state') }}">
                                                @error('state')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="country" class="form-label">Country</label>
                                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                                       id="country" name="country" value="{{ old('country', 'Pakistan') }}">
                                                @error('country')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Media Uploads -->
                                <div class="mb-4">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-images text-primary me-2"></i>Media
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="logo" class="form-label">Shop Logo</label>
                                                <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                                       id="logo" name="logo" accept="image/*">
                                                @error('logo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Recommended size: 200x200 pixels</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="banner" class="form-label">Shop Banner</label>
                                                <input type="file" class="form-control @error('banner') is-invalid @enderror" 
                                                       id="banner" name="banner" accept="image/*">
                                                @error('banner')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Recommended size: 1200x400 pixels</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Settings -->
                                <div class="mb-4">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-cog text-primary me-2"></i>Settings
                                    </h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="is_active" class="form-label">Status</label>
                                                <select class="form-select @error('is_active') is-invalid @enderror" 
                                                        id="is_active" name="is_active">
                                                    <option value="1" {{ old('is_active', true) ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ !old('is_active', true) ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                                @error('is_active')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="is_verified" class="form-label">Verification Status</label>
                                                <select class="form-select @error('is_verified') is-invalid @enderror" 
                                                        id="is_verified" name="is_verified">
                                                    <option value="1" {{ old('is_verified', false) ? 'selected' : '' }}>Verified</option>
                                                    <option value="0" {{ !old('is_verified', false) ? 'selected' : '' }}>Unverified</option>
                                                </select>
                                                @error('is_verified')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- School Association (for school_admin) -->
                                    @if(auth()->user()->hasRole('school_admin') && auth()->user()->school_id)
                                        <input type="hidden" name="school_id" value="{{ auth()->user()->school_id }}">
                                    @elseif(auth()->user()->hasRole('super_admin'))
                                        <div class="mb-3">
                                            <label for="school_id" class="form-label">Associated School (Optional)</label>
                                            <select class="form-select @error('school_id') is-invalid @enderror" 
                                                    id="school_id" name="school_id">
                                                <option value="">No School Association</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                                        {{ $school->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('school_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Create Shop
                                    </button>
                                    <a href="{{ route('admin.shops.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quick Tips -->
                    <div class="card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-lightbulb text-warning me-2"></i>Tips
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Shop Name</h6>
                                <p class="small mb-0">Choose a descriptive name that reflects your shop's specialty and location.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Shop Type</h6>
                                <p class="small mb-0">Select the most appropriate category for your shop to help customers find you.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Contact Info</h6>
                                <p class="small mb-0">Provide accurate contact information for customer inquiries.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Location</h6>
                                <p class="small mb-0">Complete address information helps customers locate your physical store.</p>
                            </div>
                            <div>
                                <h6 class="small text-uppercase text-muted">Media</h6>
                                <p class="small mb-0">High-quality logo and banner images improve shop credibility and appeal.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shop Type Guide -->
                    <div class="card mt-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-store text-primary me-2"></i>Shop Types
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Stationery</h6>
                                <p class="small mb-0">Pens, pencils, notebooks, art supplies, and writing materials.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Book Store</h6>
                                <p class="small mb-0">Textbooks, reference books, story books, and educational publications.</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Mixed</h6>
                                <p class="small mb-0">Combination of stationery, books, uniforms, and other educational supplies.</p>
                            </div>
                            <div>
                                <h6 class="small text-uppercase text-muted">School Affiliated</h6>
                                <p class="small mb-0">Official school shops selling uniforms, specific books, and school merchandise.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview for logo and banner
            const logoInput = document.getElementById('logo');
            const bannerInput = document.getElementById('banner');

            function previewImage(input, previewId) {
                const preview = document.getElementById(previewId);
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" alt="Preview">`;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Add preview containers if needed
            const mediaSection = document.querySelector('.media-uploads');
            if (mediaSection) {
                mediaSection.innerHTML += `
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <h6 class="small text-muted">Logo Preview</h6>
                                <div id="logoPreview" class="text-muted">
                                    <i class="fas fa-image fa-2x mb-2"></i>
                                    <p class="small mb-0">No logo selected</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center">
                                <h6 class="small text-muted">Banner Preview</h6>
                                <div id="bannerPreview" class="text-muted">
                                    <i class="fas fa-image fa-2x mb-2"></i>
                                    <p class="small mb-0">No banner selected</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            logoInput?.addEventListener('change', function() {
                previewImage(this, 'logoPreview');
            });

            bannerInput?.addEventListener('change', function() {
                previewImage(this, 'bannerPreview');
            });
        });
    </script>
</x-app-layout>