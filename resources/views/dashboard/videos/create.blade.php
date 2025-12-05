<x-app-layout>
    <main class="main-content">
        <section id="edit-video" class="page-section">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-0">Edit Video</h4>
                                    <p class="mb-0 text-muted">Update your video information</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('videos.show', $video->slug) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-2"></i> View
                                    </a>
                                    <a href="{{ route('videos.index') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <form action="{{ route('videos.update', $video) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <!-- Basic Information -->
                                <div class="mb-4">
                                    <h6 class="mb-3 border-bottom pb-2">Basic Information</h6>
                                    
                                    <div class="mb-3">
                                        <label for="title" class="form-label">
                                            Video Title <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title', $video->title) }}" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Current YouTube Link</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="{{ $video->embed_video_link }}" readonly>
                                            <a href="{{ $video->embed_video_link }}" target="_blank" class="btn btn-outline-primary">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </div>
                                        <div class="form-text mt-1">
                                            <i class="fas fa-info-circle me-1"></i> 
                                            Video link cannot be changed for existing videos
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="4">{{ old('description', $video->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Categorization -->
                                <div class="mb-4">
                                    <h6 class="mb-3 border-bottom pb-2">Categorization</h6>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="category_id" class="form-label">Category</label>
                                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                                    id="category_id" name="category_id">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $video->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select @error('status') is-invalid @enderror" 
                                                    id="status" name="status">
                                                <option value="published" {{ old('status', $video->status) == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="draft" {{ old('status', $video->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="private" {{ old('status', $video->status) == 'private' ? 'selected' : '' }}>Private</option>
                                            </select>
                                            @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        @if(auth()->user()->school_id || auth()->user()->can('assign-school-videos'))
                                        <div class="col-md-6">
                                            <label for="school_id" class="form-label">School</label>
                                            <select class="form-select @error('school_id') is-invalid @enderror" 
                                                    id="school_id" name="school_id">
                                                <option value="">No School</option>
                                                @foreach($schools as $school)
                                                <option value="{{ $school->id }}" {{ old('school_id', $video->school_id) == $school->id ? 'selected' : '' }}>
                                                    {{ $school->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('school_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endif
                                        
                                        @if(auth()->user()->can('assign-shop-videos'))
                                        <div class="col-md-6">
                                            <label for="shop_id" class="form-label">Shop</label>
                                            <select class="form-select @error('shop_id') is-invalid @enderror" 
                                                    id="shop_id" name="shop_id">
                                                <option value="">No Shop</option>
                                                @foreach($shops as $shop)
                                                <option value="{{ $shop->id }}" {{ old('shop_id', $video->shop_id) == $shop->id ? 'selected' : '' }}>
                                                    {{ $shop->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('shop_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Video Settings -->
                                <div class="mb-4">
                                    <h6 class="mb-3 border-bottom pb-2">Video Settings</h6>
                                    
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                                                {{ old('is_featured', $video->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Mark as Featured Video
                                            </label>
                                        </div>
                                        @error('is_featured')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Video Stats -->
                                <div class="mb-4">
                                    <h6 class="mb-3 border-bottom pb-2">Video Statistics</h6>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Views</label>
                                                <input type="text" class="form-control" value="{{ number_format($video->views) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Likes</label>
                                                <input type="text" class="form-control" value="{{ number_format($video->likes_count) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Comments</label>
                                                <input type="text" class="form-control" value="{{ number_format($video->comments_count) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Created</label>
                                                <input type="text" class="form-control" value="{{ $video->created_at->format('M d, Y') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- SEO Settings -->
                                <div class="mb-4">
                                    <h6 class="mb-3 border-bottom pb-2">SEO Settings</h6>
                                    
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta Title</label>
                                        <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                               value="{{ old('meta_title', $video->meta_title) }}" maxlength="60">
                                        <div class="form-text">
                                            <span id="meta-title-count">{{ strlen($video->meta_title ?? '') }}</span>/60 characters
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <textarea class="form-control" id="meta_description" name="meta_description" 
                                                  rows="3" maxlength="160">{{ old('meta_description', $video->meta_description) }}</textarea>
                                        <div class="form-text">
                                            <span id="meta-desc-count">{{ strlen($video->meta_description ?? '') }}</span>/160 characters
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                                               value="{{ old('meta_keywords', $video->meta_keywords) }}" 
                                               placeholder="keyword1, keyword2, keyword3">
                                    </div>
                                </div>
                                
                                <!-- Submit Buttons -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('videos.show', $video->slug) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i> Cancel
                                    </a>
                                    <div class="d-flex gap-2">
                                        <button type="submit" name="save" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i> Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Character counters for SEO fields
            const metaTitleInput = document.getElementById('meta_title');
            const metaDescInput = document.getElementById('meta_description');
            const metaTitleCount = document.getElementById('meta-title-count');
            const metaDescCount = document.getElementById('meta-desc-count');
            
            function updateCharacterCount(input, counter) {
                if (input && counter) {
                    counter.textContent = input.value.length;
                }
            }
            
            if (metaTitleInput) {
                metaTitleInput.addEventListener('input', function() {
                    updateCharacterCount(this, metaTitleCount);
                });
            }
            
            if (metaDescInput) {
                metaDescInput.addEventListener('input', function() {
                    updateCharacterCount(this, metaDescCount);
                });
            }
            
            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                const title = document.getElementById('title').value.trim();
                
                if (!title) {
                    event.preventDefault();
                    showToast('Please enter a video title', 'error');
                }
            });
            
            // Toast notification
            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = `toast align-items-center text-bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
                toast.style.zIndex = '1060';
                
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-exclamation-circle me-2"></i> ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                `;
                
                document.body.appendChild(toast);
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
                
                toast.addEventListener('hidden.bs.toast', function() {
                    document.body.removeChild(toast);
                });
            }
        });
    </script>
</x-app-layout>