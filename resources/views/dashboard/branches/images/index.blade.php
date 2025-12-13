<x-app-layout>
    <main class="main-content">
        <section id="branch-images" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Image Gallery: {{ $branch->name }}</h2>
                    <p class="mb-0 text-muted">Manage images for {{ $branch->name }} branch</p>
                </div>
                <div>
                    <a href="{{ route('schools.branches.edit', [$school, $branch]) }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-edit me-2"></i>Edit Branch
                    </a>
                    <a href="{{ route('schools.branches.index', $school) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Branches
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-start border-primary border-4 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small">Total Images</div>
                                    <div class="h5 mb-0" id="totalImages">{{ $images->count() }}</div>
                                </div>
                                <div class="text-primary">
                                    <i class="fas fa-images fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-start border-success border-4 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small">Featured</div>
                                    <div class="h5 mb-0" id="featuredImages">{{ $images->where('is_featured', true)->count() }}</div>
                                </div>
                                <div class="text-success">
                                    <i class="fas fa-star fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-start border-info border-4 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small">Main Banner</div>
                                    <div class="h5 mb-0" id="bannerImages">{{ $images->where('is_main_banner', true)->count() }}</div>
                                </div>
                                <div class="text-info">
                                    <i class="fas fa-image fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-start border-warning border-4 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small">Types</div>
                                    <div class="h5 mb-0" id="imageTypes">{{ $images->pluck('type')->unique()->count() }}</div>
                                </div>
                                <div class="text-warning">
                                    <i class="fas fa-tags fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Upload Images</h5>
                </div>
                <div class="card-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="images" class="form-label">Select Images *</label>
                                <input type="file" 
                                       class="form-control" 
                                       id="images" 
                                       name="images[]" 
                                       multiple 
                                       accept="image/*"
                                       required>
                                <small class="text-muted">You can select multiple images (Max 20 files, 10MB each)</small>
                                <div id="filePreview" class="mt-3"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="uploadType" class="form-label">Image Type</label>
                                <select class="form-select" id="uploadType" name="type">
                                    @foreach($imageTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="progress mb-3 d-none" id="uploadProgress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: 0%" 
                                 aria-valuenow="0" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                0%
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="uploadBtn">
                                <i class="fas fa-upload me-2"></i>Upload Images
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearSelection()">
                                <i class="fas fa-times me-2"></i>Clear Selection
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Image Gallery -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Image Gallery</h5>
                    <div class="d-flex align-items-center gap-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="sortableToggle">
                            <label class="form-check-label" for="sortableToggle">Drag to Reorder</label>
                        </div>
                        <select class="form-select form-select-sm w-auto" id="filterType">
                            <option value="all">All Types</option>
                            @foreach($imageTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    @if($images->count() > 0)
                        <div id="imageGallery" class="row sortable-gallery">
                            @foreach($images as $image)
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4 image-item" 
                                 data-id="{{ $image->id }}" 
                                 data-type="{{ $image->type }}">
                                <div class="card h-100 shadow-sm">
                                    <div class="position-relative">
                                        <img src="{{ asset('website/'. $image->image_path) }}" 
                                             class="card-img-top gallery-image" 
                                             alt="{{ $image->title }}"
                                             data-id="{{ $image->id }}"
                                             style="height: 180px; object-fit: cover; cursor: pointer;">
                                        
                                        <!-- Image Badges -->
                                        <div class="position-absolute top-0 start-0 m-2">
                                            @if($image->is_featured)
                                            <span class="badge bg-success" title="Featured Image">
                                                <i class="fas fa-star"></i>
                                            </span>
                                            @endif
                                            @if($image->is_main_banner)
                                            <span class="badge bg-primary ms-1" title="Main Banner">
                                                <i class="fas fa-image"></i>
                                            </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Type Badge -->
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-info">
                                                {{ $imageTypes[$image->type] ?? $image->type }}
                                            </span>
                                        </div>
                                        
                                        <!-- Sort Handle -->
                                        <div class="position-absolute bottom-0 start-0 m-2 sort-handle d-none">
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-arrows-alt"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-1 text-truncate" title="{{ $image->title }}">
                                            {{ $image->title }}
                                        </h6>
                                        @if($image->caption)
                                        <p class="card-text small text-muted mb-2 text-truncate" title="{{ $image->caption }}">
                                            {{ $image->caption }}
                                        </p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                {{ $image->created_at->format('M d, Y') }}
                                            </small>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                        type="button" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item edit-image" 
                                                           href="#"
                                                           data-id="{{ $image->id }}">
                                                            <i class="fas fa-edit me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item set-featured" 
                                                           href="#"
                                                           data-id="{{ $image->id }}">
                                                            @if($image->is_featured)
                                                            <i class="fas fa-star me-2"></i>Unset Featured
                                                            @else
                                                            <i class="far fa-star me-2"></i>Set as Featured
                                                            @endif
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item set-banner" 
                                                           href="#"
                                                           data-id="{{ $image->id }}">
                                                            @if($image->is_main_banner)
                                                            <i class="fas fa-image me-2"></i>Unset Banner
                                                            @else
                                                            <i class="far fa-image me-2"></i>Set as Banner
                                                            @endif
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger delete-image" 
                                                           href="#"
                                                           data-id="{{ $image->id }}">
                                                            <i class="fas fa-trash me-2"></i>Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Empty State -->
                        <div id="emptyGallery" class="text-center py-5 d-none">
                            <div class="mb-3">
                                <i class="fas fa-images fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted">No images found</h5>
                            <p class="text-muted">Try changing your filter or upload new images</p>
                        </div>
                    @else
                        <!-- Initial Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-images fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted">No images uploaded yet</h5>
                            <p class="text-muted">Start by uploading some images using the form above</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <!-- Edit Image Modal -->
    <div class="modal fade" id="editImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Image Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editImageForm">
                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <img id="editImagePreview" 
                                 src="" 
                                 alt="Preview" 
                                 class="img-fluid rounded mb-3"
                                 style="max-height: 200px;">
                        </div>
                        
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editTitle" name="title">
                        </div>
                        
                        <div class="mb-3">
                            <label for="editCaption" class="form-label">Caption</label>
                            <textarea class="form-control" id="editCaption" name="caption" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editType" class="form-label">Type</label>
                            <select class="form-select" id="editType" name="type">
                                @foreach($imageTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="editFeatured" name="is_featured">
                                    <label class="form-check-label" for="editFeatured">Featured Image</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="editBanner" name="is_main_banner">
                                    <label class="form-check-label" for="editBanner">Main Banner</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveImageBtn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewImageTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewImage" src="" alt="" class="img-fluid rounded">
                    <p id="previewImageCaption" class="mt-3 text-muted"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <style>
        .sortable-gallery {
            min-height: 200px;
        }
        .image-item {
            cursor: move;
            transition: transform 0.2s;
        }
        .image-item.sortable-chosen {
            opacity: 0.7;
            transform: scale(1.02);
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .image-item.sortable-ghost {
            opacity: 0.3;
        }
        .sort-handle {
            cursor: move;
        }
        .gallery-image {
            transition: transform 0.3s;
        }
        .gallery-image:hover {
            transform: scale(1.05);
        }
        .file-preview-item {
            position: relative;
            margin: 5px;
            display: inline-block;
        }
        .file-preview-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        .file-preview-item .remove-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
        }
        .dragging {
            opacity: 0.5;
        }
    </style>


    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        let currentImageId = null;
        let sortable = null;
        let selectedFiles = [];
        
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded');
            initializeSortable();
            initializeEventListeners();
            updateStatistics();
        });

        function initializeSortable() {
            const gallery = document.getElementById('imageGallery');
            if (gallery) {
                sortable = Sortable.create(gallery, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    handle: '.sort-handle',
                    onEnd: function(evt) {
                        saveNewOrder();
                    }
                });
                
                // Initially hide sort handles
                document.querySelectorAll('.sort-handle').forEach(el => {
                    el.classList.add('d-none');
                });
            }
        }
        
        function initializeEventListeners() {
            // Upload form submission
            document.getElementById('uploadForm').addEventListener('submit', handleUpload);
            
            // File input change
            document.getElementById('images').addEventListener('change', handleFileSelect);
            
            // Sortable toggle
            document.getElementById('sortableToggle').addEventListener('change', toggleSortable);
            
            // Filter type change
            document.getElementById('filterType').addEventListener('change', filterImages);
            
            // Edit image modal
            document.getElementById('editImageForm').addEventListener('submit', handleEditImage);
            
            // Image click for preview
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('gallery-image')) {
                    showImagePreview(e.target.dataset.id);
                }
                if (e.target.classList.contains('edit-image')) {
                    e.preventDefault();
                    editImage(e.target.dataset.id);
                }
                if (e.target.classList.contains('set-featured')) {
                    e.preventDefault();
                    toggleFeatured(e.target.dataset.id);
                }
                if (e.target.classList.contains('set-banner')) {
                    e.preventDefault();
                    toggleBanner(e.target.dataset.id);
                }
                if (e.target.classList.contains('delete-image')) {
                    e.preventDefault();
                    deleteImage(e.target.dataset.id);
                }
            });
        }
        
        function handleFileSelect(e) {
            const files = Array.from(e.target.files);
            selectedFiles = files;
            displayFilePreviews(files);
        }
        
        function displayFilePreviews(files) {
            const previewContainer = document.getElementById('filePreview');
            previewContainer.innerHTML = '';
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'file-preview-item';
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}">
                        <div class="remove-btn" onclick="removeFile(${index})">
                            <i class="fas fa-times"></i>
                        </div>
                    `;
                    previewContainer.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            });
        }
        
        function removeFile(index) {
            selectedFiles.splice(index, 1);
            
            // Update file input
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            document.getElementById('images').files = dataTransfer.files;
            
            // Update preview
            displayFilePreviews(selectedFiles);
        }
        
        function clearSelection() {
            selectedFiles = [];
            document.getElementById('images').value = '';
            document.getElementById('filePreview').innerHTML = '';
        }
        
        async function handleUpload(e) {
            e.preventDefault();
            
            if (selectedFiles.length === 0) {
                alert('Please select at least one image to upload.');
                return;
            }
            
            const uploadBtn = document.getElementById('uploadBtn');
            const progressBar = document.getElementById('uploadProgress');
            const formData = new FormData();
            
            // Add files
            selectedFiles.forEach(file => {
                formData.append('images[]', file);
            });
            
            // Add other data
            formData.append('type', document.getElementById('uploadType').value);
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            
            // Show progress
            progressBar.classList.remove('d-none');
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
            
            try {
                const response = await fetch('{{ route("schools.branches.images.store", [$school, $branch]) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Clear form
                    clearSelection();
                    
                    // Add new images to gallery
                    result.images.forEach(image => {
                        addImageToGallery(image);
                    });
                    
                    // Show success message
                    showToast('success', 'Images uploaded successfully!');
                    
                    // Update statistics
                    updateStatistics();
                } else {
                    throw new Error(result.message || 'Upload failed');
                }
            } catch (error) {
                console.error('Upload error:', error);
                showToast('error', error.message || 'Failed to upload images');
            } finally {
                // Reset UI
                progressBar.classList.add('d-none');
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Images';
            }
        }
        
        function addImageToGallery(imageData) {
            const gallery = document.getElementById('imageGallery');
            const emptyState = document.getElementById('emptyGallery');
            
            // Remove empty state if it exists
            if (emptyState) {
                emptyState.classList.add('d-none');
            }
            
            // If gallery doesn't exist (first image), create it
            if (!gallery) {
                location.reload(); // Reload for simplicity
                return;
            }
            
            // Create image card HTML
            const imageHtml = `
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4 image-item" 
                     data-id="${imageData.id}" 
                     data-type="${imageData.type}">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            <img src="{{asset('website/${imageData.image_path}')}}" 
                                 class="card-img-top gallery-image" 
                                 alt="${imageData.title}"
                                 data-id="${imageData.id}"
                                 style="height: 180px; object-fit: cover; cursor: pointer;">
                            
                            <div class="position-absolute top-0 start-0 m-2">
                                ${imageData.is_featured ? '<span class="badge bg-success"><i class="fas fa-star"></i></span>' : ''}
                                ${imageData.is_main_banner ? '<span class="badge bg-primary ms-1"><i class="fas fa-image"></i></span>' : ''}
                            </div>
                            
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-info">
                                    ${getTypeLabel(imageData.type)}
                                </span>
                            </div>
                            
                            <div class="position-absolute bottom-0 start-0 m-2 sort-handle d-none">
                                <span class="badge bg-secondary">
                                    <i class="fas fa-arrows-alt"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body p-3">
                            <h6 class="card-title mb-1 text-truncate" title="${imageData.title}">
                                ${imageData.title}
                            </h6>
                            ${imageData.caption ? `<p class="card-text small text-muted mb-2 text-truncate" title="${imageData.caption}">${imageData.caption}</p>` : ''}
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    ${new Date(imageData.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                </small>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                            type="button" 
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item edit-image" 
                                               href="#"
                                               data-id="${imageData.id}">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item set-featured" 
                                               href="#"
                                               data-id="${imageData.id}">
                                                ${imageData.is_featured ? 
                                                    '<i class="fas fa-star me-2"></i>Unset Featured' : 
                                                    '<i class="far fa-star me-2"></i>Set as Featured'}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item set-banner" 
                                               href="#"
                                               data-id="${imageData.id}">
                                                ${imageData.is_main_banner ? 
                                                    '<i class="fas fa-image me-2"></i>Unset Banner' : 
                                                    '<i class="far fa-image me-2"></i>Set as Banner'}
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger delete-image" 
                                               href="#"
                                               data-id="${imageData.id}">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Add to beginning of gallery
            gallery.insertAdjacentHTML('afterbegin', imageHtml);
            
            // Initialize Sortable on new element if needed
            if (sortable) {
                sortable.option("sort", document.getElementById('sortableToggle').checked);
            }
        }
        
        function getTypeLabel(type) {
            const typeLabels = {
                @foreach($imageTypes as $key => $label)
                '{{ $key }}': '{{ $label }}',
                @endforeach
            };
            return typeLabels[type] || type;
        }
        
        function toggleSortable(e) {
            const isEnabled = e.target.checked;
            const handles = document.querySelectorAll('.sort-handle');
            
            handles.forEach(handle => {
                if (isEnabled) {
                    handle.classList.remove('d-none');
                } else {
                    handle.classList.add('d-none');
                }
            });
            
            if (sortable) {
                sortable.option("disabled", !isEnabled);
                sortable.option("handle", isEnabled ? '.sort-handle' : null);
            }
        }
        
        async function saveNewOrder() {
            const imageItems = document.querySelectorAll('.image-item');
            const order = Array.from(imageItems).map(item => item.dataset.id);
            
            try {
                const response = await fetch('{{ route("schools.branches.images.reorder", [$school, $branch]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order: order })
                });
                
                const result = await response.json();
                
                if (!result.success) {
                    throw new Error('Failed to save order');
                }
                
                showToast('success', 'Images reordered successfully');
            } catch (error) {
                console.error('Reorder error:', error);
                showToast('error', 'Failed to save image order');
                location.reload(); // Reload to restore original order
            }
        }
        
        function filterImages() {
            const filterType = document.getElementById('filterType').value;
            const imageItems = document.querySelectorAll('.image-item');
            let visibleCount = 0;
            
            imageItems.forEach(item => {
                if (filterType === 'all' || item.dataset.type === filterType) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show/hide empty state
            const emptyState = document.getElementById('emptyGallery');
            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.classList.remove('d-none');
                } else {
                    emptyState.classList.add('d-none');
                }
            }
        }
        
        async function editImage(imageId) {
            currentImageId = imageId;
            
            try {
                // Find image in gallery
                const imageItem = document.querySelector(`.image-item[data-id="${imageId}"]`);
                const imgElement = imageItem.querySelector('.gallery-image');
                const titleElement = imageItem.querySelector('.card-title');
                const captionElement = imageItem.querySelector('.card-text');
                const typeBadge = imageItem.querySelector('.badge.bg-info');
                const featuredBadge = imageItem.querySelector('.badge.bg-success');
                const bannerBadge = imageItem.querySelector('.badge.bg-primary');
                
                // Set modal values
                document.getElementById('editImagePreview').src = imgElement.src;
                document.getElementById('editTitle').value = titleElement.title;
                document.getElementById('editCaption').value = captionElement ? captionElement.title : '';
                document.getElementById('editType').value = imageItem.dataset.type;
                document.getElementById('editFeatured').checked = featuredBadge !== null;
                document.getElementById('editBanner').checked = bannerBadge !== null;
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('editImageModal'));
                modal.show();
                
            } catch (error) {
                console.error('Error loading image:', error);
                showToast('error', 'Failed to load image details');
            }
        }
        
        async function handleEditImage(e) {
            e.preventDefault();
            
            const saveBtn = document.getElementById('saveImageBtn');
            const originalText = saveBtn.innerHTML;
            
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            
            const formData = {
                title: document.getElementById('editTitle').value,
                caption: document.getElementById('editCaption').value,
                type: document.getElementById('editType').value,
                is_featured: document.getElementById('editFeatured').checked,
                is_main_banner: document.getElementById('editBanner').checked
            };
            
            try {
                // ✅ FIXED: Construct URL correctly
                const url = `{{ route('schools.branches.images.update', ['school' => $school, 'branch' => $branch, 'image' => ':imageId']) }}`.replace(':imageId', currentImageId);
                
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Update gallery item
                    updateImageInGallery(currentImageId, result.image);
                    
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('editImageModal')).hide();
                    
                    // Show success
                    showToast('success', 'Image updated successfully');
                    
                    // Update statistics
                    updateStatistics();
                } else {
                    throw new Error(result.message || 'Update failed');
                }
            } catch (error) {
                console.error('Update error:', error);
                showToast('error', error.message || 'Failed to update image');
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            }
        }
        
        function updateImageInGallery(imageId, imageData) {
            const imageItem = document.querySelector(`.image-item[data-id="${imageId}"]`);
            if (!imageItem) return;
            
            // Update data attributes
            imageItem.dataset.type = imageData.type;
            
            // Update badges
            const featuredBadge = imageItem.querySelector('.badge.bg-success');
            const bannerBadge = imageItem.querySelector('.badge.bg-primary');
            const typeBadge = imageItem.querySelector('.badge.bg-info');
            
            // Update featured badge
            if (imageData.is_featured && !featuredBadge) {
                const badgeContainer = imageItem.querySelector('.position-absolute.top-0.start-0');
                badgeContainer.insertAdjacentHTML('beforeend', 
                    '<span class="badge bg-success"><i class="fas fa-star"></i></span>');
            } else if (!imageData.is_featured && featuredBadge) {
                featuredBadge.remove();
            }
            
            // Update banner badge
            if (imageData.is_main_banner && !bannerBadge) {
                const badgeContainer = imageItem.querySelector('.position-absolute.top-0.start-0');
                badgeContainer.insertAdjacentHTML('beforeend', 
                    '<span class="badge bg-primary ms-1"><i class="fas fa-image"></i></span>');
            } else if (!imageData.is_main_banner && bannerBadge) {
                bannerBadge.remove();
            }
            
            // Update type badge
            if (typeBadge) {
                typeBadge.textContent = getTypeLabel(imageData.type);
            }
            
            // Update title and caption
            const titleElement = imageItem.querySelector('.card-title');
            const captionElement = imageItem.querySelector('.card-text');
            
            if (titleElement) {
                titleElement.textContent = imageData.title;
                titleElement.title = imageData.title;
            }
            
            if (captionElement) {
                captionElement.textContent = imageData.caption || '';
                captionElement.title = imageData.caption || '';
            }
            
            // Update dropdown text
            const featuredLink = imageItem.querySelector('.set-featured');
            const bannerLink = imageItem.querySelector('.set-banner');
            
            if (featuredLink) {
                featuredLink.innerHTML = imageData.is_featured ? 
                    '<i class="fas fa-star me-2"></i>Unset Featured' : 
                    '<i class="far fa-star me-2"></i>Set as Featured';
            }
            
            if (bannerLink) {
                bannerLink.innerHTML = imageData.is_main_banner ? 
                    '<i class="fas fa-image me-2"></i>Unset Banner' : 
                    '<i class="far fa-image me-2"></i>Set as Banner';
            }
        }
        
        async function toggleFeatured(imageId) {
            try {
                const imageItem = document.querySelector(`.image-item[data-id="${imageId}"]`);
                const currentFeatured = imageItem.querySelector('.badge.bg-success') !== null;
                
                // ✅ FIXED: Construct URL correctly
                const url = `{{ route('schools.branches.images.update', ['school' => $school, 'branch' => $branch, 'image' => ':imageId']) }}`.replace(':imageId', imageId);
                
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        is_featured: !currentFeatured
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    updateImageInGallery(imageId, result.image);
                    showToast('success', `Image ${!currentFeatured ? 'set as' : 'removed from'} featured`);
                    updateStatistics();
                } else {
                    throw new Error('Operation failed');
                }
            } catch (error) {
                console.error('Toggle featured error:', error);
                showToast('error', 'Failed to update featured status');
            }
        }
        
        async function toggleBanner(imageId) {
            try {
                const imageItem = document.querySelector(`.image-item[data-id="${imageId}"]`);
                const currentBanner = imageItem.querySelector('.badge.bg-primary') !== null;
                
                // ✅ FIXED: Construct URL correctly
                const url = `{{ route('schools.branches.images.update', ['school' => $school, 'branch' => $branch, 'image' => ':imageId']) }}`.replace(':imageId', imageId);
                
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        is_main_banner: !currentBanner
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    updateImageInGallery(imageId, result.image);
                    showToast('success', `Image ${!currentBanner ? 'set as' : 'removed from'} main banner`);
                    updateStatistics();
                } else {
                    throw new Error('Operation failed');
                }
            } catch (error) {
                console.error('Toggle banner error:', error);
                showToast('error', 'Failed to update banner status');
            }
        }
        
        async function deleteImage(imageId) {
            if (!confirm('Are you sure you want to delete this image? This action cannot be undone.')) {
                return;
            }
            
            try {
                // ✅ FIXED: Construct URL correctly
                const url = `{{ route('schools.branches.images.destroy', ['school' => $school, 'branch' => $branch, 'image' => ':imageId']) }}`.replace(':imageId', imageId);
                
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Remove from gallery
                    const imageItem = document.querySelector(`.image-item[data-id="${imageId}"]`);
                    if (imageItem) {
                        imageItem.remove();
                    }
                    
                    // Check if gallery is empty
                    const gallery = document.getElementById('imageGallery');
                    const emptyState = document.getElementById('emptyGallery');
                    
                    if (gallery && gallery.children.length === 0 && emptyState) {
                        emptyState.classList.remove('d-none');
                    }
                    
                    showToast('success', 'Image deleted successfully');
                    updateStatistics();
                } else {
                    throw new Error('Delete failed');
                }
            } catch (error) {
                console.error('Delete error:', error);
                showToast('error', 'Failed to delete image');
            }
        }
        
        function showImagePreview(imageId) {
            const imageItem = document.querySelector(`.image-item[data-id="${imageId}"]`);
            const imgElement = imageItem.querySelector('.gallery-image');
            const titleElement = imageItem.querySelector('.card-title');
            const captionElement = imageItem.querySelector('.card-text');
            
            document.getElementById('previewImage').src = imgElement.src;
            document.getElementById('previewImageTitle').textContent = titleElement.title;
            document.getElementById('previewImageCaption').textContent = captionElement ? captionElement.title : '';
            
            const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
            modal.show();
        }
        
        async function updateStatistics() {
            try {
                const response = await fetch(`{{ route("schools.branches.getImageStats", [$school, $branch]) }}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('totalImages').textContent = result.stats.total;
                    document.getElementById('featuredImages').textContent = result.stats.featured;
                    document.getElementById('bannerImages').textContent = result.stats.main_banner;
                    document.getElementById('imageTypes').textContent = [
                        result.stats.gallery > 0 ? 1 : 0,
                        result.stats.banner > 0 ? 1 : 0,
                        result.stats.infrastructure > 0 ? 1 : 0,
                        result.stats.events > 0 ? 1 : 0,
                        result.stats.classroom > 0 ? 1 : 0
                    ].reduce((a, b) => a + b, 0);
                }
            } catch (error) {
                console.error('Failed to update statistics:', error);
            }
        }
        
        function showToast(type, message) {
            // Create toast container if it doesn't exist
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(toastContainer);
            }
            
            // Create toast
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            
            // Show toast
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
            toast.show();
            
            // Remove toast after it hides
            toastElement.addEventListener('hidden.bs.toast', function () {
                toastElement.remove();
            });
        }
    </script>
</x-app-layout>