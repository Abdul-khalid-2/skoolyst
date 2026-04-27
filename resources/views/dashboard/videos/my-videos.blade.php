<x-app-layout>
    <main class="main-content">
        <section id="my-videos" class="page-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0">My Videos</h2>
                    <p class="mb-0 text-muted">Manage your uploaded videos</p>
                </div>
                <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Upload New Video
                </a>
            </div>

            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Videos</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $videos->total() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-video fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Views</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalViews) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-eye fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Published</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $publishedCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Drafts</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $draftCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-save fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter and Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-2 g-md-3">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="private" {{ request('status') == 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Featured</label>
                            <select name="featured" class="form-select">
                                <option value="">All Videos</option>
                                <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                                <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Not Featured</option>
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Most Views</option>
                                <option value="likes" {{ request('sort') == 'likes' ? 'selected' : '' }}>Most Likes</option>
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search videos..." value="{{ request('search') }}">
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-2"></i> Apply Filters
                                </button>
                                <a href="{{ route('admin.videos.my-videos') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Videos Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="table-wrapper">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="min-width-200">Video</th>
                                        <th class="min-width-100">Status</th>
                                        <th class="min-width-100">Views</th>
                                        <th class="min-width-100">Likes</th>
                                        <th class="min-width-100">Comments</th>
                                        <th class="min-width-150">Created</th>
                                        <th class="min-width-150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($videos as $video)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <img src="{{ $video->thumbnail ? asset('storage/' . $video->thumbnail) : 'https://img.youtube.com/vi/' . $video->video_id . '/default.jpg' }}" 
                                                         class="rounded" width="60" height="45" style="object-fit: cover;">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">
                                                        <a href="{{ route('admin.videos.show', $video->slug) }}" class="text-decoration-none">
                                                            {{ Str::limit($video->title, 50) }}
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted">
                                                        @if($video->category)
                                                        <span class="badge bg-secondary me-2">{{ $video->category->name }}</span>
                                                        @endif
                                                        @if($video->is_featured)
                                                        <span class="badge bg-warning">Featured</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $video->status === \App\Enums\VideoPublishStatus::Published ? 'success' : ($video->status === \App\Enums\VideoPublishStatus::Draft ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($video->status->value) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-eye text-muted me-2"></i>
                                                {{ number_format($video->views) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-thumbs-up text-muted me-2"></i>
                                                {{ number_format($video->likes_count) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-comment text-muted me-2"></i>
                                                {{ number_format($video->comments_count) }}
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted d-block">{{ $video->created_at->format('M j, Y') }}</small>
                                            <small class="text-muted">{{ $video->created_at->format('g:i A') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.videos.show', $video->slug) }}"
                                                   class="btn btn-sm btn-outline-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.videos.edit', $video) }}"
                                                   class="btn btn-sm btn-outline-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmDelete({{ $video->id }})" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            
                                            <form id="delete-form-{{ $video->id }}" 
                                                  action="{{ route('admin.videos.destroy', $video) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-video fa-2x mb-3"></i>
                                                <p>You haven't uploaded any videos yet.</p>
                                                <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i> Upload Your First Video
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($videos->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $videos->withQueryString()->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <style>
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-wrapper {
            min-width: 768px;
        }

        .min-width-100 { min-width: 100px; }
        .min-width-150 { min-width: 150px; }
        .min-width-200 { min-width: 200px; }

        @media (max-width: 768px) {
            .table {
                font-size: 0.875rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .table thead th:nth-child(4),
            .table tbody td:nth-child(4),
            .table thead th:nth-child(5),
            .table tbody td:nth-child(5) {
                display: none;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation
            window.confirmDelete = function(videoId) {
                if (confirm('Are you sure you want to delete this video? This action cannot be undone.')) {
                    document.getElementById('delete-form-' + videoId).submit();
                }
            };
            
            // Quick status update
            const statusButtons = document.querySelectorAll('.quick-status');
            statusButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const videoId = this.dataset.videoId;
                    const status = this.dataset.status;
                    
                    fetch(`/videos/${videoId}/quick-update`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: status })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Video status updated successfully!', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred', 'error');
                    });
                });
            });
            
            // Toast notification
            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = `toast align-items-center text-bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
                toast.style.zIndex = '1060';
                
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
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