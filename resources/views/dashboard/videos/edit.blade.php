<x-app-layout>
    <main class="main-content">
        <section id="edit-video" class="page-section">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-0">Edit Video</h4>
                                    <p class="mb-0 text-muted">Update video information</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.videos.show', $video->slug) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye me-2"></i> View
                                    </a>
                                    <a href="{{ route('admin.videos.index') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <form action="{{ route('admin.videos.update', $video) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Basic Information -->
                                <div class="mb-4">
                                    <h6 class="mb-3 border-bottom pb-2">
                                        <i class="fas fa-info-circle me-2"></i> Basic Information
                                    </h6>

                                    <div class="mb-3">
                                        <label for="title" class="form-label">
                                            Video Title <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $video->title) }}"
                                            placeholder="Enter video title" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="embed_video_link" class="form-label">
                                            YouTube Embed Code <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control @error('embed_video_link') is-invalid @enderror"
                                            id="embed_video_link" name="embed_video_link" rows="3"
                                            placeholder="Paste YouTube iframe embed code here..." required>{{ old('embed_video_link', $video->embed_video_link) }}</textarea>
                                        @error('embed_video_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i> 
                                            Paste the iframe embed code from YouTube's "Share → Embed" option
                                        </div>
                                    </div>

                                    <!-- Current Video Preview -->
                                    <div class="alert alert-info mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-play-circle me-2 mt-1"></i>
                                            <div class="flex-grow-1">
                                                <h6 class="alert-heading mb-2">Current Video Preview:</h6>
                                                <div class="ratio ratio-16x9 mt-3">
                                                    {!! $video->embed_video_link !!}
                                                </div>
                                                <small class="d-block mt-2">
                                                    Video ID: {{ $video->video_id ?? 'Not detected' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                            id="description" name="description" rows="4"
                                            placeholder="Describe your video content">{{ old('description', $video->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Categorization -->
                                <div class="mb-4">
                                    <h6 class="mb-3 border-bottom pb-2">
                                        <i class="fas fa-tags me-2"></i> Categorization
                                    </h6>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="category_id" class="form-label">Category</label>
                                            <select class="form-select @error('category_id') is-invalid @enderror"
                                                id="category_id" name="category_id">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $video->category_id) == $category->id ? 'selected' : '' }}>
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
                                            <label for="school_id" class="form-label">School (Optional)</label>
                                            <select class="form-select @error('school_id') is-invalid @enderror"
                                                id="school_id" name="school_id">
                                                <option value="">Select School</option>
                                                @foreach($schools as $school)
                                                <option value="{{ $school->id }}" {{ old('school_id', $video->school_id) == $school->id || auth()->user()->school_id == $school->id ? 'selected' : '' }}>
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
                                            <label for="shop_id" class="form-label">Shop (Optional)</label>
                                            <select class="form-select @error('shop_id') is-invalid @enderror"
                                                id="shop_id" name="shop_id">
                                                <option value="">Select Shop</option>
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
                                    <h6 class="mb-3 border-bottom pb-2">
                                        <i class="fas fa-cog me-2"></i> Video Settings
                                    </h6>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1"
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
                                <!-- SEO Settings -->
                                <div class="mb-4">
                                    <h6 class="mb-3 border-bottom pb-2">
                                        <i class="fas fa-search me-2"></i> SEO Settings (Optional)
                                    </h6>

                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta Title</label>
                                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                                            value="{{ old('meta_title', $video->meta_title) }}"
                                            placeholder="Enter meta title for SEO" maxlength="60">
                                        <div class="form-text">
                                            <span id="meta-title-count">{{ Str::length(old('meta_title', $video->meta_title)) }}</span>/60 characters recommended
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <textarea class="form-control" id="meta_description" name="meta_description"
                                            rows="3" placeholder="Enter meta description for SEO"
                                            maxlength="160">{{ old('meta_description', $video->meta_description) }}</textarea>
                                        <div class="form-text">
                                            <span id="meta-desc-count">{{ Str::length(old('meta_description', $video->meta_description)) }}</span>/160 characters recommended
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                            value="{{ old('meta_keywords', $video->meta_keywords) }}"
                                            placeholder="keyword1, keyword2, keyword3">
                                        <div class="form-text">Separate keywords with commas</div>
                                    </div>
                                </div>

                                <!-- Video Stats -->
                                <div class="mb-4">
                                    <h6 class="mb-3 border-bottom pb-2">
                                        <i class="fas fa-chart-bar me-2"></i> Video Statistics
                                    </h6>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="text-center p-3 bg-light rounded">
                                                <div class="h4 mb-1">{{ number_format($video->views) }}</div>
                                                <div class="small text-muted">Views</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 bg-light rounded">
                                                <div class="h4 mb-1">{{ number_format($video->likes_count) }}</div>
                                                <div class="small text-muted">Likes</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 bg-light rounded">
                                                <div class="h4 mb-1">{{ number_format($video->comments_count) }}</div>
                                                <div class="small text-muted">Comments</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 bg-light rounded">
                                                <div class="h4 mb-1">{{ $video->created_at->format('M d') }}</div>
                                                <div class="small text-muted">Uploaded</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Submit Buttons -->
                                <div class="d-flex justify-content-between border-top pt-4">
                                    <div>
                                        @can('delete', $video)
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="confirmDelete()">
                                            <i class="fas fa-trash me-2"></i> Delete Video
                                        </button>
                                        @endcan
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.videos.show', $video->slug) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
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
                updateCharacterCount(metaTitleInput, metaTitleCount);
            }
            
            if (metaDescInput) {
                metaDescInput.addEventListener('input', function() {
                    updateCharacterCount(this, metaDescCount);
                });
                updateCharacterCount(metaDescInput, metaDescCount);
            }

            // Delete confirmation
            window.confirmDelete = function() {
                if (confirm('Are you sure you want to delete this video? This action cannot be undone.')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.videos.destroy", $video) }}';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    form.appendChild(csrfToken);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            };

            // Preview YouTube video from iframe
            const embedInput = document.getElementById('embed_video_link');
            const previewContainer = document.createElement('div');
            previewContainer.className = 'mt-3';

            function extractYouTubeIdFromIframe(iframeCode) {
                // Extract src attribute from iframe
                const srcMatch = iframeCode.match(/src=["']([^"']+)["']/);
                if (!srcMatch) return null;
                
                const src = srcMatch[1];
                // Extract video ID from YouTube URL
                const videoIdMatch = src.match(/(?:youtube\.com\/embed\/|youtu\.be\/|youtube\.com\/v\/|youtube\.com\/watch\?v=)([^&\?\/\s]+)/);
                return videoIdMatch ? videoIdMatch[1] : null;
            }

            function updatePreview() {
                const iframeCode = embedInput.value.trim();
                const videoId = extractYouTubeIdFromIframe(iframeCode);
                
                if (videoId) {
                    previewContainer.innerHTML = `
                        <div class="alert alert-success">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                                <div>
                                    <h6 class="mb-1">New Video Preview:</h6>
                                    <p class="mb-0">This will replace the current video</p>
                                </div>
                            </div>
                            <div class="ratio ratio-16x9 mt-3">
                                <iframe src="https://www.youtube.com/embed/${videoId}" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen></iframe>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">New Video ID: ${videoId}</small>
                            </div>
                        </div>
                    `;
                } else if (iframeCode) {
                    // Check if it's already a valid iframe
                    if (iframeCode.includes('<iframe') && iframeCode.includes('src=')) {
                        previewContainer.innerHTML = `
                            <div class="alert alert-warning">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Embed Code Found</h6>
                                        <p class="mb-0">Unable to extract video ID. The code will be saved as-is.</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        previewContainer.innerHTML = `
                            <div class="alert alert-danger">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-times-circle me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Invalid Embed Code</h6>
                                        <p class="mb-0">Please paste a valid YouTube iframe embed code.</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                } else {
                    previewContainer.innerHTML = '';
                }
            }

            if (embedInput) {
                embedInput.addEventListener('input', updatePreview);
                // Insert preview after input
                embedInput.parentNode.appendChild(previewContainer);
                updatePreview();
            }
            
            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                const title = document.getElementById('title').value.trim();
                const embedCode = document.getElementById('embed_video_link').value.trim();

                if (!title) {
                    event.preventDefault();
                    showToast('Please enter a video title', 'error');
                    return;
                }

                if (!embedCode) {
                    event.preventDefault();
                    showToast('Please enter a YouTube embed code', 'error');
                    return;
                }

                // Validate if it's an iframe
                if (!embedCode.includes('<iframe') || !embedCode.includes('src=')) {
                    event.preventDefault();
                    showToast('Please enter a valid YouTube iframe embed code', 'error');
                    return;
                }

                // Extract and validate video ID
                const videoId = extractYouTubeIdFromIframe(embedCode);
                if (!videoId) {
                    if (!confirm('Unable to detect YouTube video ID. Are you sure this is a valid YouTube embed code?')) {
                        event.preventDefault();
                    }
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

            // Auto-fix common embed code issues
            embedInput.addEventListener('blur', function() {
                let code = this.value.trim();
                
                if (code) {
                    // Fix common issues
                    if (code.includes('youtube.com/watch?v=')) {
                        // Convert watch URL to embed URL
                        const videoIdMatch = code.match(/watch\?v=([^&\s]+)/);
                        if (videoIdMatch) {
                            const videoId = videoIdMatch[1];
                            code = `<iframe width="560" height="315" src="https://www.youtube.com/embed/${videoId}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>`;
                            this.value = code;
                            showToast('Converted YouTube URL to embed code', 'info');
                        }
                    } else if (code.includes('youtu.be/')) {
                        // Convert short URL to embed URL
                        const videoIdMatch = code.match(/youtu\.be\/([^\s]+)/);
                        if (videoIdMatch) {
                            const videoId = videoIdMatch[1];
                            code = `<iframe width="560" height="315" src="https://www.youtube.com/embed/${videoId}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>`;
                            this.value = code;
                            showToast('Converted YouTube short URL to embed code', 'info');
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>