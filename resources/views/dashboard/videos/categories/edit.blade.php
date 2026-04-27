<x-app-layout>
    <main class="main-content">
        <section id="video-categories-edit" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Edit Video Category</h2>
                    <p class="mb-0 text-muted">Update category information</p>
                </div>
                <a href="{{ route('video-categories.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Categories
                </a>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Edit Category Form -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('video-categories.update', $videoCategory) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name *</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $videoCategory->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">The name of the category as it appears on your site.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" 
                                           class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" 
                                           name="slug" 
                                           value="{{ old('slug', $videoCategory->slug) }}" 
                                           readonly>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">URL-friendly version of the name. Auto-generated from the name field.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4">{{ old('description', $videoCategory->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional description for this category.</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Category Settings</h5>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status *</label>
                                            <select class="form-select @error('status') is-invalid @enderror" 
                                                    id="status" 
                                                    name="status" 
                                                    required>
                                                <option value="active" {{ old('status', $videoCategory->status->value) == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status', $videoCategory->status->value) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="sort_order" class="form-label">Sort Order</label>
                                            <input type="number" 
                                                   class="form-control @error('sort_order') is-invalid @enderror" 
                                                   id="sort_order" 
                                                   name="sort_order" 
                                                   value="{{ old('sort_order', $videoCategory->sort_order) }}">
                                            @error('sort_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Lower numbers appear first. Default is 0.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="parent_id" class="form-label">Parent Category</label>
                                            <select class="form-select @error('parent_id') is-invalid @enderror" 
                                                    id="parent_id" 
                                                    name="parent_id">
                                                <option value="">No Parent</option>
                                                @foreach($categories as $category)
                                                    @if($category->id != $videoCategory->id)
                                                        <option value="{{ $category->id }}" 
                                                                {{ old('parent_id', $videoCategory->parent_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Category Stats -->
                        {{-- <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Category Statistics</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle p-3 me-3">
                                                <i class="fas fa-video text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $videoCategory->videos_count ?? 0 }}</h6>
                                                <small class="text-muted">Total Videos</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success rounded-circle p-3 me-3">
                                                <i class="fas fa-eye text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $videoCategory->views_count ?? 0 }}</h6>
                                                <small class="text-muted">Total Views</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning rounded-circle p-3 me-3">
                                                <i class="fas fa-thumbs-up text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $videoCategory->likes_count ?? 0 }}</h6>
                                                <small class="text-muted">Total Likes</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info rounded-circle p-3 me-3">
                                                <i class="fas fa-comment text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $videoCategory->comments_count ?? 0 }}</h6>
                                                <small class="text-muted">Total Comments</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update Category
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                                    Cancel
                                </button>
                            </div>
                            
                            @if($videoCategory->videos_count == 0)
                                <button type="button" 
                                        class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal">
                                    <i class="fas fa-trash me-2"></i> Delete Category
                                </button>
                            @else
                                <button type="button" 
                                        class="btn btn-outline-secondary" 
                                        disabled
                                        title="Cannot delete category with videos">
                                    <i class="fas fa-trash me-2"></i> Delete Category
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            @if($videoCategory->videos_count == 0)
            <div class="modal fade" id="deleteModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete the category <strong>"{{ $videoCategory->name }}"</strong>?</p>
                            <p class="text-danger"><small>This action cannot be undone.</small></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('video-categories.destroy', $videoCategory) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Category</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </section>
    </main>

    @push('scripts')
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-generate slug from name
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            nameInput.addEventListener('blur', function() {
                if (!slugInput.readOnly) {
                    generateSlug();
                }
            });

            // Enable slug editing
            slugInput.addEventListener('click', function() {
                this.readOnly = false;
            });

            slugInput.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    generateSlug();
                    this.readOnly = true;
                }
            });

            function generateSlug() {
                const name = nameInput.value;
                if (name.trim() === '') return;

                fetch('{{ route("api.generate-slug") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        text: name,
                        model: 'VideoCategory',
                        exclude_id: '{{ $videoCategory->id }}'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.slug) {
                        slugInput.value = data.slug;
                    }
                })
                .catch(error => {
                    console.error('Error generating slug:', error);
                    // Fallback to simple slug generation
                    const slug = name.toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                    slugInput.value = slug;
                });
            }

            // Form submission confirmation for critical changes
            const form = document.querySelector('form');
            const originalName = nameInput.value;
            const originalStatus = document.getElementById('status').value;

            form.addEventListener('submit', function(e) {
                const currentName = nameInput.value;
                const currentStatus = document.getElementById('status').value;
                
                if (originalName !== currentName || originalStatus !== currentStatus) {
                    if (!confirm('Are you sure you want to save these changes?')) {
                        e.preventDefault();
                    }
                }
            });
        });
    </script> --}}
    @endpush

    <style>
        .main-content {
            padding: 20px;
        }

        .page-section {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card-body {
            padding: 1.5rem;
        }

        .rounded-circle {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 10px;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .d-flex {
                flex-direction: column;
                gap: 10px;
            }
            
            .d-flex > * {
                width: 100%;
            }
        }
    </style>
</x-app-layout>