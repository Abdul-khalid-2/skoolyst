<div class="card comment-card" id="comment-{{ $comment->id }}">
    <div class="card-body">
        <div class="comment-header">
            <div class="d-flex align-items-center">
                @if($comment->user && $comment->user->profile_image)
                <img src="{{ asset('website/' . $comment->user->profile_image) }}"
                    alt="{{ $comment->user->name }}"
                    class="comment-avatar me-3">
                @else
                <div class="comment-avatar bg-primary text-white d-flex align-items-center justify-content-center me-3">
                    <i class="fas fa-user"></i>
                </div>
                @endif
                <div>
                    <h6 class="comment-author mb-1">
                        {{ $comment->user->name ?? $comment->name }}
                        @if($comment->user)
                        <small class="badge bg-primary ms-2">Verified</small>
                        @endif
                    </h6>
                    <div class="comment-date">
                        <i class="far fa-clock me-1"></i>
                        {{ $comment->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="comment-content">
            {{ $comment->comment }}
        </div>

        <div class="comment-actions">
            <button class="reply-btn" data-comment-id="{{ $comment->id }}">
                <i class="fas fa-reply me-1"></i>Reply
            </button>
            <!-- <button class="like-btn" data-comment-id="{{ $comment->id }}">
                <i class="far fa-thumbs-up me-1"></i>Like
                <span class="like-count">(0)</span>
            </button> -->
        </div>

        <!-- Reply Form -->
        @auth
        <div class="reply-form" id="reply-form-{{ $comment->id }}">
            <form action="{{ route('website.blog.comment.store', $post->slug) }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <div class="form-group">
                    <textarea class="form-control" name="comment" rows="3"
                        placeholder="Write your reply..." required></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-paper-plane me-1"></i>Post Reply
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        onclick="cancelReply({{ $comment->id }})">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        @endauth

        <!-- Replies -->
        @if($comment->replies->where('status', 'approved')->count() > 0)
        <div class="replies-section">
            @foreach($comment->replies->where('status', 'approved')->sortBy('created_at') as $reply)
            <div class="card comment-card">
                <div class="card-body">
                    <div class="comment-header">
                        <div class="d-flex align-items-center">
                            @if($reply->user && $reply->user->profile_image)
                            <img src="{{ asset('website/' . $reply->user->profile_image) }}"
                                alt="{{ $reply->user->name }}"
                                class="comment-avatar me-3">
                            @else
                            <div class="comment-avatar bg-secondary text-white d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-user"></i>
                            </div>
                            @endif
                            <div>
                                <h6 class="comment-author mb-1">
                                    {{ $reply->user->name ?? $reply->name }}
                                    @if($reply->user)
                                    <small class="badge bg-primary ms-2">Verified</small>
                                    @endif
                                </h6>
                                <div class="comment-date">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $reply->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="comment-content">
                        {{ $reply->comment }}
                    </div>
                    <!-- <div class="comment-actions">
                        <button class="like-btn" data-comment-id="{{ $reply->id }}">
                            <i class="far fa-thumbs-up me-1"></i>Like
                            <span class="like-count">(0)</span>
                        </button>
                    </div> -->
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>