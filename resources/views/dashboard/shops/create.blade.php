<x-app-layout>
    <main class="main-content">
        <section id="create-shop" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Create New Shop</h2>
                    <p class="mb-0 text-muted">Add a new shop with owner account</p>
                </div>
                <a href="{{ route('shops.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Shops
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('shops.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Shop Owner Information -->
                        <div class="mb-4">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-user me-2"></i>Shop Owner Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="owner_name" class="form-label">Owner Name *</label>
                                        <input type="text" class="form-control @error('owner_name') is-invalid @enderror" 
                                               id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required>
                                        @error('owner_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="owner_email" class="form-label">Owner Email *</label>
                                        <input type="email" class="form-control @error('owner_email') is-invalid @enderror" 
                                               id="owner_email" name="owner_email" value="{{ old('owner_email') }}" required>
                                        @error('owner_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="owner_phone" class="form-label">Owner Phone</label>
                                        <input type="text" class="form-control @error('owner_phone') is-invalid @enderror" 
                                               id="owner_phone" name="owner_phone" value="{{ old('owner_phone') }}">
                                        @error('owner_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="owner_password" class="form-label">Owner Password *</label>
                                        <input type="password" class="form-control @error('owner_password') is-invalid @enderror" 
                                               id="owner_password" name="owner_password" required>
                                        @error('owner_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="owner_password_confirmation" class="form-label">Confirm Password *</label>
                                        <input type="password" class="form-control" 
                                               id="owner_password_confirmation" name="owner_password_confirmation" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shop Information -->
                        <div class="mb-4">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-store me-2"></i>Shop Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Shop Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="shop_type" class="form-label">Shop Type *</label>
                                        <select class="form-select @error('shop_type') is-invalid @enderror" 
                                                id="shop_type" name="shop_type" required>
                                            <option value="">Select Type</option>
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

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Shop Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Shop Phone</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="city" class="form-label">City *</label>
                                        <select class="form-select @error('city') is-invalid @enderror" 
                                                id="city" name="city">
                                            <option value="">Select City</option>
                                            <option value="Karachi" {{ old('city') == 'Karachi' ? 'selected' : '' }}>Karachi</option>
                                            <option value="Hyderabad" {{ old('city') == 'Hyderabad' ? 'selected' : '' }}>Hyderabad</option>
                                        </select>

                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="state" class="form-label">State *</label>
                                        <select class="form-select @error('state') is-invalid @enderror" 
                                                id="state" name="state">
                                            <option value="">Select State</option>
                                            <option value="Sindh" {{ old('state') == 'Sindh' ? 'selected' : '' }}>Sindh</option>
                                        </select>

                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                               id="country" name="country" value="{{ old('country', 'Pakistan') }}" disabled>
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        {{-- <label for="latitude" class="form-label">Latitude</label> --}}
                                        <input type="hidden" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                               id="latitude" name="latitude" value="{{ old('latitude') }}" placeholder="e.g., 33.6844">
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        {{-- <label for="longitude" class="form-label">Longitude</label> --}}
                                        <input type="hidden" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                               id="longitude" name="longitude" value="{{ old('longitude') }}" placeholder="e.g., 73.0479">
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="logo_url" class="form-label">Shop Logo</label>
                                        <input type="file" class="form-control @error('logo_url') is-invalid @enderror" 
                                               id="logo_url" name="logo_url" accept="image/*">
                                        <div class="form-text">Upload shop logo (JPG, PNG, GIF, max 2MB)</div>
                                        @error('logo_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="banner_url" class="form-label">Shop Banner</label>
                                        <input type="file" class="form-control @error('banner_url') is-invalid @enderror" 
                                               id="banner_url" name="banner_url" accept="image/*">
                                        <div class="form-text">Upload shop banner (JPG, PNG, GIF, max 5MB)</div>
                                        @error('banner_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Active Shop</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" 
                                                   value="1" {{ old('is_verified') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_verified">Verified Shop</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Initial Rating</label>
                                        <input type="number" step="0.1" min="0" max="5" class="form-control @error('rating') is-invalid @enderror" 
                                               id="rating" name="rating" value="{{ old('rating', 0) }}">
                                        <div class="form-text">Initial rating between 0.0 and 5.0</div>
                                        @error('rating')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="total_reviews" class="form-label">Initial Reviews Count</label>
                                        <input type="number" min="0" class="form-control @error('total_reviews') is-invalid @enderror" 
                                               id="total_reviews" name="total_reviews" value="{{ old('total_reviews', 0) }}">
                                        <div class="form-text">Initial number of reviews</div>
                                        @error('total_reviews')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('shops.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Shop & Owner Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>