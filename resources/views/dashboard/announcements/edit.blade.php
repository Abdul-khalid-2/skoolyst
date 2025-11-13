<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit Announcement</h3>
                            <div class="card-tools">
                                <a href="{{ route('announcements.show', $announcement->uuid) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('announcements.index') }}" class="btn btn-default btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                        <form action="{{ route('announcements.update', $announcement->uuid) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="title">Title *</label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                id="title" name="title" value="{{ old('title', $announcement->title) }}" required>
                                            @error('title')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="content">Content *</label>
                                            <textarea class="form-control @error('content') is-invalid @enderror"
                                                id="content" name="content" rows="10" required>{{ old('content', $announcement->content) }}</textarea>
                                            @error('content')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Current Feature Image Preview -->
                                        @if($announcement->feature_image)
                                        <div class="form-group">
                                            <label>Current Feature Image</label>
                                            <div>
                                                <img src="{{ $announcement->feature_image_url }}" alt="Current feature image"
                                                    class="img-fluid rounded" style="max-height: 200px;">
                                                <div class="mt-2">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="remove_feature_image" name="remove_feature_image" value="1">
                                                        <label class="form-check-label text-danger" for="remove_feature_image">
                                                            Remove current image
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">Status *</label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status" required>
                                                <option value="draft" {{ old('status', $announcement->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status', $announcement->status) == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="archived" {{ old('status', $announcement->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                            </select>
                                            @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="branch_id">Branch</label>
                                            <select class="form-control @error('branch_id') is-invalid @enderror"
                                                id="branch_id" name="branch_id">
                                                <option value="">All Branches</option>
                                                @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ old('branch_id', $announcement->branch_id) == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="feature_image">New Feature Image</label>
                                            <input type="file" class="form-control-file @error('feature_image') is-invalid @enderror"
                                                id="feature_image" name="feature_image" accept="image/*">
                                            <small class="form-text text-muted">
                                                Max file size: 2MB. Supported formats: JPEG, PNG, JPG, GIF
                                            </small>
                                            @error('feature_image')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="publish_at">Publish Date & Time</label>
                                            <input type="datetime-local" class="form-control @error('publish_at') is-invalid @enderror"
                                                id="publish_at" name="publish_at"
                                                value="{{ old('publish_at', $announcement->publish_at ? $announcement->publish_at->format('Y-m-d\TH:i') : '') }}">
                                            @error('publish_at')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="expire_at">Expire Date & Time</label>
                                            <input type="datetime-local" class="form-control @error('expire_at') is-invalid @enderror"
                                                id="expire_at" name="expire_at"
                                                value="{{ old('expire_at', $announcement->expire_at ? $announcement->expire_at->format('Y-m-d\TH:i') : '') }}">
                                            @error('expire_at')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Announcement Statistics -->
                                        <div class="card bg-light">
                                            <div class="card-header">
                                                <h6 class="card-title mb-0">Statistics</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <div class="col-6">
                                                        <div class="border-right">
                                                            <h4 class="text-primary mb-0">{{ $announcement->view_count }}</h4>
                                                            <small class="text-muted">Views</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div>
                                                            <h4 class="text-success mb-0">{{ $announcement->comments->count() }}</h4>
                                                            <small class="text-muted">Comments</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        Created: {{ $announcement->created_at->format('M d, Y') }}<br>
                                                        Updated: {{ $announcement->updated_at->format('M d, Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                                id="meta_title" name="meta_title"
                                                value="{{ old('meta_title', $announcement->meta_title) }}"
                                                placeholder="Optional meta title for SEO">
                                            @error('meta_title')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                                id="meta_description" name="meta_description"
                                                rows="3" placeholder="Optional meta description for SEO">{{ old('meta_description', $announcement->meta_description) }}</textarea>
                                            @error('meta_description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-8">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Announcement
                                        </button>
                                        <a href="{{ route('announcements.index') }}" class="btn btn-default">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a href="{{ route('announcements.show', $announcement->uuid) }}"
                                            class="btn btn-info" target="_blank">
                                            <i class="fas fa-external-link-alt"></i> Preview
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Comments Management Section -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Comments Management ({{ $announcement->allComments->count() }})</h3>
                        </div>
                        <div class="card-body">
                            @if($announcement->allComments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Commenter</th>
                                            <th>Comment</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($announcement->allComments as $comment)
                                        <tr>
                                            <td>
                                                <strong>{{ $comment->getCommenterNameAttribute() }}</strong><br>
                                                <small class="text-muted">{{ $comment->getCommenterEmailAttribute() }}</small>
                                            </td>
                                            <td>{{ Str::limit($comment->comment, 100) }}</td>
                                            <td>
                                                <span class="badge badge-{{ $comment->status === 'approved' ? 'success' : ($comment->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($comment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $comment->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($comment->status === 'pending')
                                                <form action="{{ route('announcements.comments.update', [$announcement->uuid, $comment->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="btn btn-success btn-sm" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('announcements.comments.update', [$announcement->uuid, $comment->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                <form action="{{ route('announcements.comments.destroy', [$announcement->uuid, $comment->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this comment?')" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-muted text-center">No comments yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @push('js')
        <style>
            .border-right {
                border-right: 1px solid #dee2e6 !important;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle remove feature image checkbox
                const removeImageCheckbox = document.getElementById('remove_feature_image');
                const featureImageInput = document.getElementById('feature_image');

                if (removeImageCheckbox && featureImageInput) {
                    removeImageCheckbox.addEventListener('change', function() {
                        if (this.checked) {
                            featureImageInput.disabled = false;
                        }
                    });

                    featureImageInput.addEventListener('change', function() {
                        if (this.files.length > 0) {
                            removeImageCheckbox.checked = false;
                        }
                    });
                }

                // Form validation
                const form = document.querySelector('form');
                form.addEventListener('submit', function(e) {
                    const publishAt = document.getElementById('publish_at').value;
                    const expireAt = document.getElementById('expire_at').value;

                    if (publishAt && expireAt && new Date(publishAt) >= new Date(expireAt)) {
                        e.preventDefault();
                        alert('Expire date must be after publish date.');
                        return false;
                    }
                });

                // Auto-generate meta title and description if empty
                const titleInput = document.getElementById('title');
                const contentInput = document.getElementById('content');
                const metaTitleInput = document.getElementById('meta_title');
                const metaDescriptionInput = document.getElementById('meta_description');

                function generateMetaData() {
                    if (!metaTitleInput.value && titleInput.value) {
                        metaTitleInput.value = titleInput.value;
                    }

                    if (!metaDescriptionInput.value && contentInput.value) {
                        const content = contentInput.value.replace(/(<([^>]+)>)/gi, "");
                        metaDescriptionInput.value = content.substring(0, 160) + (content.length > 160 ? '...' : '');
                    }
                }

                titleInput.addEventListener('blur', generateMetaData);
                contentInput.addEventListener('blur', generateMetaData);
            });
        </script>
        @endpush
    </main>
</x-app-layout>