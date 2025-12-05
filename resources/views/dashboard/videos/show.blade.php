<x-app-layout>
    <main class="main-content">
        <section id="video-detail" class="page-section">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Video Player -->
                    <div class="card mb-4">
                        <div class="ratio ratio-16x9 bg-dark">
                            <iframe src="https://www.youtube.com/embed/{{ $video->video_id }}?rel=0" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen></iframe>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h1 class="h4 mb-0">{{ $video->title }}</h1>
                                @can('update', $video)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('videos.edit', $video) }}">
                                                <i class="fas fa-edit me-2"></i> Edit Video
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('videos.destroy', $video) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash me-2"></i> Delete Video
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                                @endcan
                            </div>
                            
                            <!-- Video Stats -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center me-4">
                                        <i class="fas fa-eye text-muted me-2"></i>
                                        <span class="text-muted">{{ number_format($video->views) }} views</span>
                                    </div>
                                    <div class="d-flex align-items-center me-4" id="likes-section">
                                        @auth
                                        <button class="btn btn-sm btn-outline-primary me-1" onclick="reactToVideo('like')">
                                            <i class="fas fa-thumbs-up"></i> <span id="likes-count">{{ number_format($video->likes_count) }}</span>
                                        </button>
                                        @else
                                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-thumbs-up"></i> {{ number_format($video->likes_count) }}
                                        </a>
                                        @endauth
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-comment text-muted me-2"></i>
                                        <span class="text-muted">{{ number_format($video->comments_count) }} comments</span>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i> {{ $video->created_at->format('F j, Y') }}
                                </small>
                            </div>
                            
                            <!-- Video Meta -->
                            <div class="row mb-4">
                                @if($video->category)
                                <div class="col-auto mb-2">
                                    <span class="badge bg-primary">{{ $video->category->name }}</span>
                                </div>
                                @endif
                                @if($video->school)
                                <div class="col-auto mb-2">
                                    <a href="{{ route('schools.show', $video->school->uuid) }}" class="badge bg-info text-decoration-none">
                                        <i class="fas fa-school me-1"></i> {{ $video->school->name }}
                                    </a>
                                </div>
                                @endif
                                @if($video->shop)
                                <div class="col-auto mb-2">
                                    <a href="{{ route('shops.show', $video->shop->uuid) }}" class="badge bg-success text-decoration-none">
                                        <i class="fas fa-store me-1"></i> {{ $video->shop->name }}
                                    </a>
                                </div>
                                @endif
                                @if($video->is_featured)
                                <div class="col-auto mb-2">
                                    <span class="badge bg-warning">
                                        <i class="fas fa-star me-1"></i> Featured
                                    </span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Video Description -->
                            <div class="mb-4">
                                <h6 class="mb-2">Description</h6>
                                <p class="mb-0">{{ $video->description ?: 'No description provided.' }}</p>
                            </div>
                            
                            <!-- Uploader Info -->
                            <div class="d-flex align-items-center border-top pt-3">
                                <div class="flex-shrink-0">
                                    @if($video->user->profile_picture)
                                    <img src="{{ asset('storage/' . $video->user->profile_picture) }}" 
                                         class="rounded-circle" width="50" height="50" alt="{{ $video->user->name }}">
                                    @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">{{ $video->user->name }}</h6>
                                    <small class="text-muted">Uploaded {{ $video->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comments Section -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Comments ({{ $video->comments_count }})</h5>
                        </div>
                        <div class="card-body">
                            <!-- Comment Form -->
                            @auth
                            <form action="{{ route('videos.comments.store', $video) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="message" id="comment-message" rows="3" 
                                              class="form-control" placeholder="Add a comment..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i> Post Comment
                                </button>
                            </form>
                            @else
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <a href="{{ route('login') }}" class="fw-bold">Login</a> or 
                                <a href="{{ route('register') }}" class="fw-bold">register</a> to post comments.
                            </div>
                            @endauth
                            
                            <!-- Comments List -->
                            <div id="comments-container">
                                @foreach($video->comments as $comment)
                                <div class="comment-item mb-4 pb-3 border-bottom">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            @if($comment->user && $comment->user->profile_picture)
                                            <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" 
                                                 class="rounded-circle" width="40" height="40">
                                            @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white fa-sm"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-0">{{ $comment->user ? $comment->user->name : $comment->name }}</h6>
                                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                </div>
                                                @auth
                                                <button class="btn btn-sm btn-outline-secondary" onclick="likeComment({{ $comment->id }})">
                                                    <i class="fas fa-thumbs-up"></i> <span id="comment-like-count-{{ $comment->id }}">{{ $comment->likes_count }}</span>
                                                </button>
                                                @endauth
                                            </div>
                                            <p class="mt-2 mb-0">{{ $comment->message }}</p>
                                            
                                            <!-- Replies -->
                                            @foreach($comment->replies as $reply)
                                            <div class="reply-item mt-3 ms-4 ps-3 border-start">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        @if($reply->user && $reply->user->profile_picture)
                                                        <img src="{{ asset('storage/' . $reply->user->profile_picture) }}" 
                                                             class="rounded-circle" width="32" height="32">
                                                        @else
                                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                             style="width: 32px; height: 32px;">
                                                            <i class="fas fa-user text-white fa-sm"></i>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <h6 class="mb-0">{{ $reply->user ? $reply->user->name : $reply->name }}</h6>
                                                                <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                            </div>
                                                            @auth
                                                            <button class="btn btn-sm btn-outline-secondary" onclick="likeComment({{ $reply->id }})">
                                                                <i class="fas fa-thumbs-up"></i> <span id="comment-like-count-{{ $reply->id }}">{{ $reply->likes_count }}</span>
                                                            </button>
                                                            @endauth
                                                        </div>
                                                        <p class="mt-2 mb-0">{{ $reply->message }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                                @if($video->comments->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-comments fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">No comments yet. Be the first to comment!</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Related Videos -->
                    @if($relatedVideos->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Related Videos</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach($relatedVideos as $related)
                                <a href="{{ route('videos.show', $related->slug) }}" 
                                   class="list-group-item list-group-item-action border-0 px-0 py-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="{{ $related->thumbnail ? asset('storage/' . $related->thumbnail) : 'https://img.youtube.com/vi/' . $related->video_id . '/default.jpg' }}" 
                                                 class="rounded" width="80" height="60" style="object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 text-dark">{{ Str::limit($related->title, 50) }}</h6>
                                            <div class="d-flex align-items-center text-muted">
                                                <small class="me-3">
                                                    <i class="fas fa-eye fa-xs me-1"></i> {{ number_format($related->views) }}
                                                </small>
                                                <small>{{ $related->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Video Details -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Video Details</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <span class="text-muted">Uploaded:</span>
                                    <span class="float-end">{{ $video->created_at->format('F j, Y') }}</span>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <span class="text-muted">Duration:</span>
                                    <span class="float-end">{{ $video->duration ?? 'N/A' }}</span>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-tag text-primary me-2"></i>
                                    <span class="text-muted">Category:</span>
                                    <span class="float-end">{{ $video->category->name ?? 'Uncategorized' }}</span>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-globe text-primary me-2"></i>
                                    <span class="text-muted">Status:</span>
                                    <span class="float-end badge bg-{{ $video->status == 'published' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($video->status) }}
                                    </span>
                                </li>
                                @if($video->meta_title)
                                <li class="mt-4 pt-3 border-top">
                                    <h6 class="text-muted mb-2">SEO Information</h6>
                                    <small class="text-muted">Meta Title: {{ Str::limit($video->meta_title, 30) }}</small><br>
                                    <small class="text-muted">Meta Description: {{ Str::limit($video->meta_description, 40) }}</small>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Video reaction function
            window.reactToVideo = function(reaction) {
                fetch('{{ route("videos.reactions.store", $video) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ reaction: reaction })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        showToast(data.error, 'error');
                        return;
                    }
                    
                    const likesCount = document.getElementById('likes-count');
                    if (likesCount) {
                        likesCount.textContent = numberFormat(data.likes_count);
                    }
                    
                    const button = document.querySelector('#likes-section button');
                    if (button) {
                        if (data.reaction) {
                            button.classList.remove('btn-outline-primary');
                            button.classList.add('btn-primary');
                        } else {
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-outline-primary');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                });
            };
            
            // Comment like function
            window.likeComment = function(commentId) {
                fetch(`/video-comments/${commentId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const countElement = document.getElementById(`comment-like-count-${commentId}`);
                    if (countElement && data.likes_count !== undefined) {
                        countElement.textContent = data.likes_count;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                });
            };
            
            // Helper function for number formatting
            function numberFormat(num) {
                if (num >= 1000000) {
                    return (num / 1000000).toFixed(1) + 'M';
                } else if (num >= 1000) {
                    return (num / 1000).toFixed(1) + 'K';
                }
                return num.toString();
            }
            
            // Toast notification function
            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = `toast align-items-center text-bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
                toast.setAttribute('role', 'alert');
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
    
    <style>
        .comment-item:last-child {
            border-bottom: none !important;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        
        .reply-item {
            position: relative;
        }
        
        .reply-item:before {
            content: "";
            position: absolute;
            left: -1px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #dee2e6;
        }
        
        @media (max-width: 768px) {
            .ratio-16x9 {
                --bs-aspect-ratio: 56.25%;
            }
            
            .comment-item .d-flex {
                flex-direction: column;
            }
            
            .comment-item .flex-shrink-0 {
                margin-bottom: 10px;
            }
            
            .reply-item {
                margin-left: 0 !important;
                padding-left: 20px !important;
            }
        }
    </style>
</x-app-layout>