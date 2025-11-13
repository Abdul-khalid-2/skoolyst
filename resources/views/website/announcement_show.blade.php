@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== ANNOUNCEMENT HERO SECTION ==================== */
    .announcement-hero {
        background: linear-gradient(135deg, #4361ee 0%, #3a86ff 100%);
        color: white;
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }

    .announcement-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        animation: float 20s linear infinite;
    }

    @keyframes float {
        0% { transform: translateY(0px) translateX(0px); }
        100% { transform: translateY(-100px) translateX(-100px); }
    }

    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .announcement-breadcrumb {
        margin-bottom: 2rem;
    }

    .breadcrumb-link {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color 0.3s ease;
        font-weight: 500;
    }

    .breadcrumb-link:hover {
        color: white;
        text-decoration: underline;
    }

    .announcement-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    }

    .announcement-meta {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2.5rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1rem;
        opacity: 0.9;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 25px;
        backdrop-filter: blur(10px);
    }

    .meta-item i {
        font-size: 1.1rem;
    }

    .new-badge {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* ==================== ANNOUNCEMENT CONTENT SECTION ==================== */
    .announcement-content-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .content-wrapper {
        max-width: 800px;
        margin: 0 auto;
    }

    .announcement-card {
        background: white;
        border-radius: 25px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 4rem;
        border: 1px solid rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
    }

    .featured-image {
        width: 100%;
        max-height: 450px;
        object-fit: cover;
        border-bottom: 3px solid #4361ee;
    }

    .announcement-body {
        padding: 4rem;
    }

    .announcement-content {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #2d3748;
        font-family: 'Inter', sans-serif;
    }

    .announcement-content h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 2.5rem 0 1.25rem;
        color: #1a202c;
        border-left: 4px solid #4361ee;
        padding-left: 1rem;
    }

    .announcement-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 2rem 0 1rem;
        color: #2d3748;
    }

    .announcement-content p {
        margin-bottom: 1.75rem;
    }

    .announcement-content ul, 
    .announcement-content ol {
        margin-bottom: 1.75rem;
        padding-left: 2.5rem;
    }

    .announcement-content li {
        margin-bottom: 0.75rem;
        position: relative;
    }

    .announcement-content ul li::before {
        content: '•';
        color: #4361ee;
        font-weight: bold;
        position: absolute;
        left: -1.5rem;
    }

    .announcement-content blockquote {
        border-left: 4px solid #4361ee;
        padding: 2rem;
        margin: 2.5rem 0;
        font-style: italic;
        color: #4a5568;
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        border-radius: 0 15px 15px 0;
        position: relative;
    }

    .announcement-content blockquote::before {
        content: '"';
        font-size: 4rem;
        color: #4361ee;
        position: absolute;
        top: -1rem;
        left: 1rem;
        opacity: 0.3;
        font-family: serif;
    }

    .announcement-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 2.5rem 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .announcement-content table th,
    .announcement-content table td {
        border: 1px solid #e2e8f0;
        padding: 1rem;
        text-align: left;
    }

    .announcement-content table th {
        background: linear-gradient(135deg, #4361ee, #3a86ff);
        color: white;
        font-weight: 600;
    }

    .announcement-content table tr:nth-child(even) {
        background: #f7fafc;
    }

    /* ==================== ANNOUNCEMENT META BAR ==================== */
    .announcement-meta-bar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem 0;
        margin: 2rem 0;
        border-radius: 15px;
    }

    .meta-bar-content {
        display: flex;
        justify-content: space-around;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .meta-bar-item {
        text-align: center;
        padding: 0 1rem;
    }

    .meta-bar-label {
        display: block;
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 0.25rem;
    }

    .meta-bar-value {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
    }

    /* ==================== SCHOOL INFO CARD ==================== */
    .school-info-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
        margin-bottom: 2.5rem;
        border: 1px solid #e2e8f0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .school-info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
    }

    .school-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f7fafc;
    }

    .school-logo {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        object-fit: cover;
        border: 3px solid #4361ee;
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
    }

    .school-details h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a202c;
    }

    .school-location {
        color: #718096;
        font-size: 1rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .school-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem 1rem;
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        border-radius: 15px;
        transition: transform 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-3px);
    }

    .stat-number {
        font-size: 1rem;
        font-weight: 500;
        color: #4361ee;
        display: block;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.6rem;
        color: #718096;
        margin-top: 0.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .view-school-btn {
        width: 100%;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #4361ee, #3a86ff);
        color: white;
        border: none;
        border-radius: 15px;
        font-weight: 700;
        text-decoration: none;
        text-align: center;
        display: block;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
    }

    .view-school-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.4);
        color: white;
    }

    /* ==================== COMMENTS SECTION ==================== */
    .comments-section {
        background: white;
        border-radius: 25px;
        padding: 4rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        margin-top: 3rem;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 2.5rem;
        color: #1a202c;
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #4361ee;
    }

    .comments-list {
        margin-bottom: 3rem;
    }

    .comment-item {
        padding: 2rem 0;
        border-bottom: 1px solid #e2e8f0;
        transition: background-color 0.3s ease;
    }

    .comment-item:hover {
        background: #f7fafc;
        margin: 0 -2rem;
        padding: 2rem;
        border-radius: 15px;
    }

    .comment-item:last-child {
        border-bottom: none;
    }

    .comment-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .comment-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #4361ee, #3a86ff);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
    }

    .comment-author {
        font-weight: 700;
        color: #1a202c;
        margin: 0;
        font-size: 1.1rem;
    }

    .comment-date {
        color: #718096;
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    .comment-text {
        color: #4a5568;
        line-height: 1.7;
        margin: 0;
        font-size: 1.05rem;
    }

    .no-comments {
        text-align: center;
        padding: 4rem 2rem;
        color: #718096;
    }

    .no-comments i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .no-comments h4 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #4a5568;
    }

    /* ==================== COMMENT FORM ==================== */
    .comment-form {
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        padding: 3rem;
        border-radius: 20px;
        border: 2px solid #e2e8f0;
    }

    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 2rem;
        color: #1a202c;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-group {
        margin-bottom: 2rem;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #2d3748;
        font-size: 1rem;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .submit-btn {
        background: linear-gradient(135deg, #4361ee, #3a86ff);
        color: white;
        border: none;
        padding: 1.25rem 3rem;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
        width: 100%;
        font-size: 1.1rem;
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.4);
    }

    /* ==================== RELATED ANNOUNCEMENTS ==================== */
    .related-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #ffffff 0%, #f7fafc 100%);
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2.5rem;
        margin-top: 3rem;
    }

    .related-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        position: relative;
    }

    .related-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #4361ee, #3a86ff);
    }

    .related-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
    }

    .related-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .related-card:hover .related-image {
        transform: scale(1.05);
    }

    .related-content {
        padding: 2rem;
    }

    .related-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a202c;
        line-height: 1.4;
    }

    .related-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .related-title a:hover {
        color: #4361ee;
    }

    .related-excerpt {
        color: #718096;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .related-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        color: #a0aec0;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    /* ==================== RESPONSIVE DESIGN ==================== */
    @media (max-width: 768px) {
        .announcement-title {
            font-size: 2.2rem;
        }

        .announcement-meta {
            gap: 1rem;
        }

        .meta-item {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }

        .announcement-body {
            padding: 2.5rem 1.5rem;
        }

        .announcement-content {
            font-size: 1.05rem;
        }

        .school-stats {
            grid-template-columns: 1fr;
        }

        .comments-section {
            padding: 2.5rem 1.5rem;
        }

        .comment-form {
            padding: 2rem 1.5rem;
        }

        .related-grid {
            grid-template-columns: 1fr;
        }

        .hero-content {
            text-align: left;
        }

        .announcement-meta {
            justify-content: flex-start;
        }
    }

    @media (max-width: 480px) {
        .announcement-hero {
            padding: 80px 0 60px;
        }

        .announcement-title {
            font-size: 1.8rem;
        }

        .meta-item {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== ANNOUNCEMENT HERO SECTION ==================== -->
<section class="announcement-hero">
    <div class="container">
        <div class="hero-content">
            <!-- Breadcrumb -->
            <nav class="announcement-breadcrumb">
                <a href="{{ route('website.home') }}" class="breadcrumb-link">Home</a>
                <span class="text-white mx-2">/</span>
                <a href="{{ route('browseSchools.show', $announcement->school->uuid) }}" class="breadcrumb-link">{{ $announcement->school->name }}</a>
                <span class="text-white mx-2">/</span>
                <span class="text-white">Announcement</span>
            </nav>

            <!-- Announcement Title -->
            <h1 class="announcement-title">{{ $announcement->title }}</h1>

            <!-- Announcement Meta -->
            <div class="announcement-meta">
                <div class="meta-item">
                    <i class="fas fa-school"></i>
                    <span>{{ $announcement->school->name }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ $announcement->created_at->format('F d, Y') }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-eye"></i>
                    <span>{{ $announcement->view_count }} views</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-comments"></i>
                    <span>{{ $announcement->comments->count() }} comments</span>
                </div>
                @if($announcement->branch)
                <div class="meta-item">
                    <i class="fas fa-building"></i>
                    <span>{{ $announcement->branch->name }}</span>
                </div>
                @endif
                @if($announcement->created_at->gt(now()->subDays(3)))
                <span class="new-badge">
                    <i class="fas fa-bolt me-1"></i> Just Posted
                </span>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- ==================== ANNOUNCEMENT CONTENT SECTION ==================== -->
<section class="announcement-content-section">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="content-wrapper">
                    <div class="announcement-card">
                        @if($announcement->feature_image)
                        <img src="{{ $announcement->feature_image_url }}" alt="{{ $announcement->title }}" class="featured-image">
                        @endif
                        
                        <div class="announcement-body">
                            <!-- Announcement Meta Bar -->
                            @if($announcement->publish_at || $announcement->expire_at)
                            <div class="announcement-meta-bar">
                                <div class="meta-bar-content">
                                    @if($announcement->publish_at)
                                    <div class="meta-bar-item">
                                        <span class="meta-bar-label">Published</span>
                                        <span class="meta-bar-value">{{ $announcement->publish_at->format('M d, Y') }}</span>
                                    </div>
                                    @endif
                                    @if($announcement->expire_at)
                                    <div class="meta-bar-item">
                                        <span class="meta-bar-label">Expires</span>
                                        <span class="meta-bar-value">{{ $announcement->expire_at->format('M d, Y') }}</span>
                                    </div>
                                    @endif
                                    <div class="meta-bar-item">
                                        <span class="meta-bar-label">Status</span>
                                        <span class="meta-bar-value">{{ ucfirst($announcement->status) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="announcement-content">
                                {!! $announcement->content !!}
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="comments-section">
                        <h3 class="section-title">
                            <i class="fas fa-comments"></i>
                            Community Discussion
                            <span class="badge bg-primary ms-2">{{ $announcement->comments->count() }}</span>
                        </h3>

                        <div class="comments-list">
                            @forelse($announcement->comments as $comment)
                            <div class="comment-item">
                                <div class="comment-header">
                                    <div class="comment-avatar">
                                        {{ strtoupper(substr($comment->getCommenterNameAttribute(), 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="comment-author">{{ $comment->getCommenterNameAttribute() }}</h4>
                                        <div class="comment-date">{{ $comment->created_at->format('M d, Y \\a\\t h:i A') }}</div>
                                    </div>
                                </div>
                                <p class="comment-text">{{ $comment->comment }}</p>
                            </div>
                            @empty
                            <div class="no-comments">
                                <i class="fas fa-comment-slash"></i>
                                <h4>No comments yet</h4>
                                <p>Be the first to share your thoughts on this announcement!</p>
                            </div>
                            @endforelse
                        </div>

                        <!-- Comment Form -->
                        <div class="comment-form">
                            <h4 class="form-title">
                                <i class="fas fa-edit"></i>
                                Share Your Thoughts
                            </h4>
                            <form action="{{ route('announcements.comments.store', $announcement->uuid) }}" method="POST">
                                @csrf
                                
                                @if(!auth()->check())
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Your Name *</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" 
                                                   placeholder="Enter your name" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email Address *</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email') }}" 
                                                   placeholder="Enter your email" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group">
                                    <label for="comment" class="form-label">Your Comment *</label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" 
                                              id="comment" name="comment" rows="6" 
                                              placeholder="Share your thoughts about this announcement..." required>{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-paper-plane me-2"></i> Post Comment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- School Info Card -->
                <div class="school-info-card">
                    <div class="school-header">
                        @if($announcement->school->logo)
                        <img src="{{ asset('storage/' . $announcement->school->logo) }}" 
                             alt="{{ $announcement->school->name }}" class="school-logo">
                        @else
                        <div class="school-logo bg-primary text-white d-flex align-items-center justify-content-center">
                            <i class="fas fa-school fa-2x"></i>
                        </div>
                        @endif
                        <div class="school-details">
                            <h3>{{ $announcement->school->name }}</h3>
                            <div class="school-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $announcement->school->city ?? 'Location not specified' }}
                            </div>
                        </div>
                    </div>

                    <div class="school-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $announcement->school->announcements()->where('status', 'published')->count() }}</span>
                            <span class="stat-label">Updates</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $announcement->school->reviews->count() }}</span>
                            <span class="stat-label">Reviews</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $announcement->school->branches->count() }}</span>
                            <span class="stat-label">Locations</span>
                        </div>
                    </div>

                    <a href="{{ route('browseSchools.show', $announcement->school->uuid) }}" class="view-school-btn">
                        <i class="fas fa-external-link-alt me-2"></i> Visit School Profile
                    </a>
                </div>

                <!-- Related Announcements -->
                @if($relatedAnnouncements->count() > 0)
                <div class="school-info-card">
                    <h4 class="mb-3">
                        <i class="fas fa-bullhorn text-primary me-2"></i>
                        More from {{ $announcement->school->name }}
                    </h4>
                    <div class="related-announcements-list">
                        @foreach($relatedAnnouncements->take(3) as $related)
                        <div class="related-announcement-item mb-3 p-3 bg-light rounded">
                            <h6 class="mb-1">
                                <a href="{{ route('announcements.show', $related->uuid) }}" 
                                   class="text-decoration-none text-dark fw-bold">{{ Str::limit($related->title, 50) }}</a>
                            </h6>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>{{ $related->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- ==================== RELATED ANNOUNCEMENTS SECTION ==================== -->
@if($relatedAnnouncements->count() > 0)
<section class="related-section">
    <div class="container">
        <h2 class="section-title text-center">More Updates from {{ $announcement->school->name }}</h2>
        <p class="text-center text-muted mb-5">Stay informed with the latest news and announcements</p>
        <div class="related-grid">
            @foreach($relatedAnnouncements->take(3) as $related)
            <div class="related-card">
                @if($related->feature_image)
                <img src="{{ $related->feature_image_url }}" alt="{{ $related->title }}" class="related-image">
                @endif
                <div class="related-content">
                    <h5 class="related-title">
                        <a href="{{ route('announcements.show', $related->uuid) }}">{{ $related->title }}</a>
                    </h5>
                    <p class="related-excerpt">{{ Str::limit(strip_tags($related->content), 120) }}</p>
                    <div class="related-meta">
                        <span><i class="fas fa-calendar me-1"></i>{{ $related->created_at->format('M d, Y') }}</span>
                        <span><i class="fas fa-eye me-1"></i>{{ $related->view_count }} views</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection