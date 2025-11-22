<x-app-layout>
    <main class="main-content">
        <section id="shop-edit" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Edit Shop</h2>
                    <p class="mb-0 text-muted">Update shop information</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.shops.show', $shop) }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-2"></i> View Shop
                    </a>
                    <a href="{{ route('admin.shops.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Shops
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.shops.update', $shop) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

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
                                                       value="{{ old('name', $shop->name) }}" required>
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
                                                    <option value="stationery" {{ old('shop_type', $shop->shop_type) == 'stationery' ? 'selected' : '' }}>Stationery</option>
                                                    <option value="book_store" {{ old('shop_type', $shop->shop_type) == 'book_store' ? 'selected' : '' }}>Book Store</option>
                                                    <option value="mixed" {{ old('shop_type', $shop->shop_type) == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                                    <option value="school_affiliated" {{ old('shop_type', $shop->shop_type) == 'school_affiliated' ? 'selected' : '' }}>School Affiliated</option>
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
                                                  placeholder="Enter shop description">{{ old('description', $shop->description) }}</textarea>
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
                                                       value="{{ old('email', $shop->email) }}">
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
                                                       value="{{ old('phone', $shop->phone) }}">
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
                                                  placeholder="Enter complete address">{{ old('address', $shop->address) }}</textarea>
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
                                                       value="{{ old('city', $shop->city) }}">
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
                                                       value="{{ old('state', $shop->state) }}">
                                                @error('state')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="country" class="form-label">Country</label>
                                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                                       id="country" name="country" value="{{ old('country', $shop->country) }}">
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
                                                
                                                @if($shop->logo_url)
                                                <div class="mt-2">
                                                    <p class="small text-muted mb-1">Current Logo:</p>
                                                    <img src="{{ asset('storage/' . $shop->logo_url) }}" alt="Current Logo" 
                                                         class="img-thumbnail" style="max-height: 100px;">
                                                </div>
                                                @endif
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
                                                
                                                @if($shop->banner_url)
                                                <div class="mt-2">
                                                    <p class="small text-muted mb-1">Current Banner:</p>
                                                    <img src="{{ asset('storage/' . $shop->banner_url) }}" alt="Current Banner" 
                                                         class="img-thumbnail" style="max-height: 100px;">
                                                </div>
                                                @endif
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
                                                    <option value="1" {{ old('is_active', $shop->is_active) ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ !old('is_active', $shop->is_active) ? 'selected' : '' }}>Inactive</option>
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
                                                    <option value="1" {{ old('is_verified', $shop->is_verified) ? 'selected' : '' }}>Verified</option>
                                                    <option value="0" {{ !old('is_verified', $shop->is_verified) ? 'selected' : '' }}>Unverified</option>
                                                </select>
                                                @error('is_verified')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- School Association -->
                                    @if(auth()->user()->hasRole('super_admin'))
                                        <div class="mb-3">
                                            <label for="school_id" class="form-label">Associated School (Optional)</label>
                                            <select class="form-select @error('school_id') is-invalid @enderror" 
                                                    id="school_id" name="school_id">
                                                <option value="">No School Association</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->id }}" {{ old('school_id', $shop->school_id) == $school->id ? 'selected' : '' }}>
                                                        {{ $school->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('school_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @else
                                        <input type="hidden" name="school_id" value="{{ $shop->school_id }}">
                                    @endif
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Shop
                                    </button>
                                    <a href="{{ route('admin.shops.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                    
                                    @if(auth()->user()->hasRole('super_admin'))
                                    <button type="button" class="btn btn-outline-danger ms-auto" 
                                            onclick="confirmDelete()">
                                        <i class="fas fa-trash me-2"></i>Delete Shop
                                    </button>
                                    @endif
                                </div>
                            </form>

                            <!-- Delete Form -->
                            @if(auth()->user()->hasRole('super_admin'))
                            <form id="deleteForm" action="{{ route('admin.shops.destroy', $shop) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Shop Summary -->
                    <div class="card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-store text-primary me-2"></i>Shop Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Created</h6>
                                <p class="mb-0">{{ $shop->created_at->format('M j, Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Last Updated</h6>
                                <p class="mb-0">{{ $shop->updated_at->format('M j, Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Products</h6>
                                <p class="mb-0">{{ $shop->products_count ?? 0 }} products</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-uppercase text-muted">Rating</h6>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span>{{ number_format($shop->rating, 1) }}</span>
                                    <small class="text-muted ms-1">({{ $shop->total_reviews }} reviews)</small>
                                </div>
                            </div>
                            <div>
                                <h6 class="small text-uppercase text-muted">Shop URL</h6>
                                <p class="mb-0">
                                    <a href="{{ route('admin.products.show', $shop->slug) }}" target="_blank" class="text-decoration-none">
                                        {{ route('admin.products.show', $shop->slug) }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mt-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.products.create', ['shop_id' => $shop->id]) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Add Product
                                </a>
                                <a href="{{ route('admin.products.index', ['shop_id' => $shop->id]) }}" 
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-box me-2"></i>Manage Products
                                </a>
                                <a href="{{ route('admin.products.show', $shop->slug) }}" target="_blank" 
                                   class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-external-link-alt me-2"></i>View Public Page
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this shop? This will also delete all associated products and cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        // Image preview functionality
        document.addEventListener('DOMContentLoaded', function() {
            const logoInput = document.getElementById('logo');
            const bannerInput = document.getElementById('banner');

            function previewImage(input, previewContainerId) {
                const previewContainer = document.getElementById(previewContainerId);
                if (!previewContainer) return;

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.innerHTML = `
                            <p class="small text-muted mb-1">New Preview:</p>
                            <img src="${e.target.result}" class="img-thumbnail" style="max-height: 100px;" alt="New Preview">
                        `;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Create preview containers
            const logoContainer = document.querySelector('input[name="logo"]').closest('.mb-3');
            const bannerContainer = document.querySelector('input[name="banner"]').closest('.mb-3');

            logoContainer.innerHTML += '<div id="logoPreview"></div>';
            bannerContainer.innerHTML += '<div id="bannerPreview"></div>';

            logoInput.addEventListener('change', function() {
                previewImage(this, 'logoPreview');
            });

            bannerInput.addEventListener('change', function() {
                previewImage(this, 'bannerPreview');
            });
        });
    </script>
</x-app-layout>