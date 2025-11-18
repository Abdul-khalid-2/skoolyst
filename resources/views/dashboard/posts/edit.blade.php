<x-app-layout>
    <main class="main-content">
        <section id="blog-edit" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">Edit Blog Post</h2>
                    <p class="mb-0 text-muted">Update blog post details</p>
                </div>
                <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Posts
                </a>
            </div>

            <form class="row" method="POST" action="{{ route('admin.blog-posts.update', $blogPost) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- Basic Information -->
                            <h5 class="mb-3">Basic Information</h5>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="title" class="form-label">Post Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter blog post title" required value="{{ old('title', $blogPost->title) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="blog_category_id" class="form-label">Category</label>
                                    <select class="form-control" id="blog_category_id" name="blog_category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('blog_category_id', $blogPost->blog_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="school_id" class="form-label">Associated School</label>
                                    <select class="form-control" id="school_id" name="school_id">
                                        <option value="">General Post</option>
                                        @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id', $blogPost->school_id) == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="draft" {{ old('status', $blogPost->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status', $blogPost->status) == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="archived" {{ old('status', $blogPost->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="excerpt" class="form-label">Excerpt</label>
                                <textarea class="form-control" id="excerpt" name="excerpt" rows="3" placeholder="Brief description of your blog post">{{ old('excerpt', $blogPost->excerpt) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="content" name="content" rows="12" placeholder="Write your blog post content here..." required>{{ old('content', $blogPost->content) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="tags" name="tags" placeholder="Enter tags separated by commas" value="{{ old('tags', $blogPost->tags ? implode(',', $blogPost->tags) : '') }}">
                                <div class="form-text">Separate tags with commas (e.g., education, learning, schools)</div>
                            </div>

                            <!-- SEO Information -->
                            <h5 class="mb-3 mt-4">SEO Information</h5>
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta title for SEO" value="{{ old('meta_title', $blogPost->meta_title) }}">
                            </div>

                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" rows="3" placeholder="Meta description for SEO">{{ old('meta_description', $blogPost->meta_description) }}</textarea>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Post
                                </button>
                                <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Featured Image -->
                    <div class="card mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">Featured Image</h5>
                        </div>
                        <div class="card-body">
                            @if($blogPost->featured_image)
                            <div class="mb-3 text-center">
                                <img src="{{ Storage::url($blogPost->featured_image) }}" alt="Current featured image" class="img-fluid rounded mb-2" style="max-height: 200px;">
                                <p class="text-muted small">Current featured image</p>
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <label for="featured_image" class="form-label">Upload new featured image</label>
                                <input type="file" class="form-control" id="featured_image" name="featured_image" accept=".webp,.jpg,.png">
                                <div class="form-text">Recommended size: 1200x630px. Max file size: 2MB.</div>
                            </div>
                            <div class="featured-image-preview mt-3 text-center" id="featured-image-preview" style="display: none;">
                                <p class="text-muted">New Image Preview</p>
                                <img id="featured-image-preview-img" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>

                    <!-- Post Settings -->
                    <div class="card mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">Post Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $blogPost->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Featured Post</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="published_at" class="form-label">Publish Date & Time</label>
                                <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="{{ old('published_at', $blogPost->published_at?->format('Y-m-d\TH:i')) }}">
                                <div class="form-text">Schedule publication for future date</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="card">
                        <div class="card-header bg-white border-0">
                            <h5 class="card-title mb-0">Quick Stats</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="mb-3">
                                    <small class="text-muted">READ TIME</small>
                                    <h4 id="read-time-display">{{ $blogPost->read_time }} min</h4>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">WORD COUNT</small>
                                    <h4 id="word-count-display">{{ str_word_count(strip_tags($blogPost->content)) }}</h4>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">TOTAL VIEWS</small>
                                    <h4>{{ $blogPost->view_count }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Featured image preview
            const featuredImageInput = document.getElementById('featured_image');
            const featuredImagePreview = document.getElementById('featured-image-preview');
            const featuredImagePreviewImg = document.getElementById('featured-image-preview-img');

            featuredImageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        featuredImagePreviewImg.src = e.target.result;
                        featuredImagePreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    featuredImagePreview.style.display = 'none';
                }
            });

            // Read time and word count calculation
            const contentTextarea = document.getElementById('content');
            const readTimeDisplay = document.getElementById('read-time-display');
            const wordCountDisplay = document.getElementById('word-count-display');

            function calculateReadTimeAndWordCount() {
                const text = contentTextarea.value;
                const wordCount = text.trim() ? text.trim().split(/\s+/).length : 0;
                const readTime = Math.ceil(wordCount / 200); // 200 words per minute
                
                wordCountDisplay.textContent = wordCount.toLocaleString();
                readTimeDisplay.textContent = readTime + ' min';
            }

            contentTextarea.addEventListener('input', calculateReadTimeAndWordCount);
        });
    </script>
</x-app-layout>