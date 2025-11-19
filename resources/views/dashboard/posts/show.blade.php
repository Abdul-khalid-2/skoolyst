<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blogPost->title }} - Skoolyst</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-school me-2"></i>Skoolyst
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fas fa-blog me-1"></i>Blog Posts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-users me-1"></i>Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog me-1"></i>Settings
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Main Content Area -->
            <div class="col-12">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h4 mb-0">{{ $blogPost->title }}</h2>
                        <p class="mb-0 text-muted">Blog post details and statistics</p>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('admin.blog-posts.show', [$blogPost->category?->slug, $blogPost->slug]) }}" 
                           class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-eye me-2"></i> View Live
                        </a>
                        <a href="{{ route('admin.blog-posts.edit', $blogPost) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Posts
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Blog Content Card -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Blog Content</h5>
                            </div>
                            <div class="card-body">
                                <!-- Featured Image -->
                                @if($blogPost->featured_image)
                                <div class="text-center mb-4">
                                    <img src="{{ asset('website/' . $blogPost->featured_image) }}" 
                                         alt="{{ $blogPost->title }}" 
                                         class="img-fluid rounded" style="max-height: 400px; object-fit: cover;">
                                </div>
                                @endif

                                <!-- Post Meta -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="d-flex flex-wrap gap-3 text-muted small">
                                            <div>
                                                <i class="fas fa-user me-1"></i>
                                                {{ $blogPost->user->name }}
                                            </div>
                                            <div>
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $blogPost->published_at?->format('M j, Y') ?? 'Draft' }}
                                            </div>
                                            <div>
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $blogPost->read_time }} min read
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <span class="badge bg-{{ $blogPost->status === 'published' ? 'success' : ($blogPost->status === 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($blogPost->status) }}
                                        </span>
                                        @if($blogPost->is_featured)
                                        <span class="badge bg-warning ms-1">
                                            <i class="fas fa-star me-1"></i>Featured
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Drag & Drop Content -->
                                <div id="blog-content">
                                    @if($blogPost->structure && isset($blogPost->structure['elements']))
                                        @foreach($blogPost->structure['elements'] as $index => $element)
                                            @php
                                                $elementType = $element['type'] ?? '';
                                                $elementContent = $element['content'] ?? [];
                                            @endphp

                                            @switch($elementType)
                                                @case('heading')
                                                    <{{ $elementContent['level'] ?? 'h2' }} class="mb-4" style="color: #2c5aa0; border-bottom: 2px solid #4f46e5; padding-bottom: 0.5rem;">
                                                        {{ $elementContent['text'] ?? 'Heading Text' }}
                                                    </{{ $elementContent['level'] ?? 'h2' }}>
                                                    @break

                                                @case('text')
                                                    <div class="ck-content rich-text-content mb-4">
                                                        {!! $elementContent['content'] ?? '<p>Content goes here...</p>' !!}
                                                    </div>
                                                    @break

                                                @case('image')
                                                    <div class="text-center mb-4">
                                                        @if(isset($elementContent['src']) && $elementContent['src'])
                                                            <img src="{{ $elementContent['src'] }}" 
                                                                 alt="{{ $elementContent['alt'] ?? 'Educational Content Image' }}" 
                                                                 class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                                                            @if(isset($elementContent['caption']) && $elementContent['caption'])
                                                                <div class="mt-2 text-muted fst-italic">
                                                                    {{ $elementContent['caption'] }}
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div class="text-center py-4 bg-light rounded text-muted">
                                                                <i class="fas fa-image fa-2x mb-2"></i>
                                                                <p>No Image Available</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @break

                                                @case('banner')
                                                    <div class="card bg-gradient mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                        <div class="card-body text-white text-center py-5">
                                                            @if(isset($elementContent['title']))
                                                                <h2 class="card-title">{{ $elementContent['title'] }}</h2>
                                                            @endif
                                                            @if(isset($elementContent['subtitle']))
                                                                <p class="card-text lead">{{ $elementContent['subtitle'] }}</p>
                                                            @endif
                                                            @if(isset($elementContent['src']) && $elementContent['src'])
                                                                <img src="{{ $elementContent['src'] }}" 
                                                                     alt="Banner" 
                                                                     class="img-fluid mt-3 rounded" style="max-height: 200px;">
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @break

                                                @case('columns')
                                                    <div class="row mb-4">
                                                        <div class="col-md-6">
                                                            <div class="ck-content rich-text-content">
                                                                {!! $elementContent['left'] ?? '<p>Left column content...</p>' !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="ck-content rich-text-content">
                                                                {!! $elementContent['right'] ?? '<p>Right column content...</p>' !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @break

                                                @default
                                                    <div class="alert alert-warning mb-4">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        This content element couldn't be displayed properly.
                                                    </div>
                                            @endswitch
                                        @endforeach
                                    @else
                                        <!-- Fallback to traditional content -->
                                        <div class="ck-content rich-text-content">
                                            {!! $blogPost->content !!}
                                        </div>
                                    @endif
                                </div>

                                <!-- Tags -->
                                @if($blogPost->tags && count($blogPost->tags) > 0)
                                <div class="mt-4 pt-4 border-top">
                                    <strong>Tags:</strong>
                                    @foreach($blogPost->tags as $tag)
                                    <span class="badge bg-secondary me-1">{{ $tag }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Comments Section -->
                        @if($blogPost->comments && $blogPost->comments->count() > 0)
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Comments ({{ $blogPost->comments->count() }})</h5>
                            </div>
                            <div class="card-body">
                                @forelse($blogPost->comments->where('parent_id', null) as $comment)
                                <div class="comment mb-4 pb-3 border-bottom">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                {{ substr($comment->name ?? $comment->user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">{{ $comment->name ?? $comment->user->name }}</h6>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-2 mt-2">{{ $comment->comment }}</p>
                                            <div class="d-flex gap-2">
                                                <span class="badge bg-{{ $comment->status === 'approved' ? 'success' : ($comment->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($comment->status) }}
                                                </span>
                                            </div>
                                            
                                            <!-- Replies -->
                                            @foreach($comment->replies as $reply)
                                            <div class="d-flex mt-3 pt-3 border-top">
                                                <div class="flex-shrink-0">
                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 30px; height: 30px;">
                                                        {{ substr($reply->name ?? $reply->user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0 small">{{ $reply->name ?? $reply->user->name }}</h6>
                                                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <p class="mb-0 small mt-1">{{ $reply->comment }}</p>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted text-center">No comments yet.</p>
                                @endforelse
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="col-lg-4">
                        <!-- Post Details Card -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Post Details</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <span class="badge bg-{{ $blogPost->status === 'published' ? 'success' : ($blogPost->status === 'draft' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($blogPost->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Category:</th>
                                        <td>{{ $blogPost->category->name ?? 'Uncategorized' }}</td>
                                    </tr>
                                    <tr>
                                        <th>School:</th>
                                        <td>{{ $blogPost->school->name ?? 'General' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Author:</th>
                                        <td>{{ $blogPost->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Published:</th>
                                        <td>{{ $blogPost->published_at?->format('M j, Y g:i A') ?? 'Not published' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Read Time:</th>
                                        <td>{{ $blogPost->read_time }} min</td>
                                    </tr>
                                    <tr>
                                        <th>Views:</th>
                                        <td>{{ $blogPost->view_count }}</td>
                                    </tr>
                                    <tr>
                                        <th>Featured:</th>
                                        <td>
                                            @if($blogPost->is_featured)
                                            <i class="fas fa-star text-warning"></i> Yes
                                            @else
                                            No
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ $blogPost->created_at->format('M j, Y g:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated:</th>
                                        <td>{{ $blogPost->updated_at->format('M j, Y g:i A') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        @if($blogPost->excerpt)
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-quote-left me-2"></i>Excerpt</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $blogPost->excerpt }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($blogPost->meta_title || $blogPost->meta_description)
                        <div class="card mb-4">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-search me-2"></i>SEO Meta</h5>
                            </div>
                            <div class="card-body">
                                @if($blogPost->meta_title)
                                <p><strong>Title:</strong> {{ $blogPost->meta_title }}</p>
                                @endif
                                @if($blogPost->meta_description)
                                <p><strong>Description:</strong> {{ $blogPost->meta_description }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Quick Actions -->
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.blog-posts.edit', $blogPost) }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-2"></i> Edit Post
                                    </a>
                                    <a href="{{ route('admin.blog-posts.show', [$blogPost->category?->slug, $blogPost->slug]) }}" 
                                       class="btn btn-outline-primary" target="_blank">
                                        <i class="fas fa-external-link-alt me-2"></i> View Live
                                    </a>
                                    <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Back to Posts
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Styles -->
    <style>
        .ck-content {
            line-height: 1.8;
            font-size: 1.1rem;
            color: #374151;
        }

        .ck-content h1,
        .ck-content h2,
        .ck-content h3,
        .ck-content h4,
        .ck-content h5,
        .ck-content h6 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #2c5aa0;
            font-weight: 600;
        }

        .ck-content h1 { 
            font-size: 2.2rem; 
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 0.5rem;
        }
        .ck-content h2 { font-size: 1.8rem; }
        .ck-content h3 { font-size: 1.5rem; }
        .ck-content h4 { font-size: 1.3rem; }
        .ck-content h5 { font-size: 1.1rem; }
        .ck-content h6 { font-size: 1rem; }

        .ck-content p {
            margin-bottom: 1.5rem;
        }

        .ck-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .ck-content blockquote {
            border-left: 4px solid #4f46e5;
            padding-left: 1.5rem;
            margin: 1.5rem 0;
            font-style: italic;
            color: #6b7280;
            background: #f9fafb;
            padding: 1rem 1.5rem;
            border-radius: 0 8px 8px 0;
        }

        .ck-content ul,
        .ck-content ol {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }

        .ck-content table {
            width: 100%;
            margin: 1.5rem 0;
            border-collapse: collapse;
        }

        .ck-content table th,
        .ck-content table td {
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
        }

        .ck-content table th {
            background-color: #4f46e5;
            color: white;
            font-weight: 600;
        }

        .rich-text-content {
            line-height: 1.8;
            font-size: 1.1rem;
        }

        /* Navbar styles */
        .navbar-brand {
            font-weight: 600;
        }

        /* Comment Styles */
        .comment {
            border-bottom: 1px solid #e5e7eb;
        }

        .comment:last-child {
            border-bottom: none;
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Make images in CKEditor content responsive
            document.querySelectorAll('.ck-content img').forEach(img => {
                img.classList.add('img-fluid');
            });

            // Add table responsive wrapper
            document.querySelectorAll('.ck-content table').forEach(table => {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            });
        });
    </script>
</body>
</html>