<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        @if($announcement->feature_image)
                        <img src="{{ $announcement->feature_image_url }}" class="card-img-top" alt="{{ $announcement->title }}">
                        @endif
                        <div class="card-body">
                            <h1 class="card-title">{{ $announcement->title }}</h1>

                            <div class="text-muted mb-3">
                                <small>
                                    <i class="fas fa-calendar"></i>
                                    {{ $announcement->created_at->format('F d, Y') }}
                                    @if($announcement->branch)
                                    | <i class="fas fa-building"></i> {{ $announcement->branch->name }}
                                    @endif
                                    | <i class="fas fa-eye"></i> {{ $announcement->view_count }} views
                                </small>
                            </div>

                            <div class="card-text">
                                {!! $announcement->content !!}
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Comments ({{ $announcement->comments->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($announcement->comments->count() > 0)
                            @foreach($announcement->comments as $comment)
                            <div class="media mb-4">
                                <div class="media-body">
                                    <h6 class="mt-0">{{ $comment->getCommenterNameAttribute() }}</h6>
                                    <p class="text-muted small">
                                        {{ $comment->created_at->format('M d, Y \a\t h:i A') }}
                                    </p>
                                    <p>{{ $comment->comment }}</p>
                                </div>
                            </div>
                            @if(!$loop->last)
                            <hr>
                            @endif
                            @endforeach
                            @else
                            <p class="text-muted">No comments yet. Be the first to comment!</p>
                            @endif
                        </div>
                    </div>

                    <!-- Add Comment Form -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Add Comment</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('announcements.comments.store', $announcement->uuid) }}" method="POST">
                                @csrf

                                @if(!auth()->check())
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name *</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email *</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group">
                                    <label for="comment">Comment *</label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror"
                                        id="comment" name="comment" rows="4" required>{{ old('comment') }}</textarea>
                                    @error('comment')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Submit Comment</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Announcement Meta Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Announcement Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $announcement->status === 'published' ? 'success' : ($announcement->status === 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($announcement->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>School:</strong></td>
                                    <td>{{ $announcement->school->name }}</td>
                                </tr>
                                @if($announcement->branch)
                                <tr>
                                    <td><strong>Branch:</strong></td>
                                    <td>{{ $announcement->branch->name }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                                </tr>
                                @if($announcement->publish_at)
                                <tr>
                                    <td><strong>Publish Date:</strong></td>
                                    <td>{{ $announcement->publish_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endif
                                @if($announcement->expire_at)
                                <tr>
                                    <td><strong>Expire Date:</strong></td>
                                    <td>{{ $announcement->expire_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>

                            @if(auth()->check() && auth()->user()->school_id === $announcement->school_id)
                            <div class="mt-3">
                                <a href="{{ route('announcements.edit', $announcement->uuid) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('announcements.destroy', $announcement->uuid) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>