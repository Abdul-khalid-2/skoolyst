<section id="announcements" class="content-section">
    <h2 class="section-title">Latest Announcements</h2>
    <div class="section-content">
        @if($school->announcements && $school->announcements->where('status', 'published')->count() > 0)
            @php
                $publishedAnnouncements = $school->announcements->where('status', 'published')
                    ->filter(function($announcement) {
                        return $announcement->isPublished();
                    })
                    ->sortByDesc('created_at');
            @endphp

            @if($publishedAnnouncements->count() > 0)
                <div class="announcements-list">
                    @foreach($publishedAnnouncements->take(5) as $announcement)
                        <div class="announcement-item card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    @if($announcement->feature_image)
                                    <div class="col-md-3">
                                        <img src="{{ $announcement->feature_image_url }}"
                                            alt="{{ $announcement->title }}"
                                            class="announcement-image img-fluid rounded">
                                    </div>
                                    @endif
                                    <div class="{{ $announcement->feature_image ? 'col-md-9' : 'col-12' }}">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h4 class="announcement-title mb-0">
                                                <a href="{{ route('website.announcements.show', $announcement->uuid) }}"
                                                class="text-decoration-none">
                                                    {{ $announcement->title }}
                                                </a>
                                            </h4>
                                            @if($announcement->created_at->gt(now()->subDays(7)))
                                                <span class="badge bg-danger ms-2">New</span>
                                            @endif
                                        </div>

                                        <div class="announcement-meta mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt"></i>
                                                Posted {{ $announcement->created_at->diffForHumans() }}
                                                @if($announcement->branch)
                                                    • <i class="fas fa-building"></i>
                                                    {{ $announcement->branch->name }}
                                                @endif
                                                • <i class="fas fa-eye"></i>
                                                {{ $announcement->view_count }} views
                                                • <i class="fas fa-comments"></i>
                                                {{ $announcement->comments->count() }} comments
                                            </small>
                                        </div>

                                        <p class="announcement-description mb-3">
                                            {{ Str::limit(strip_tags($announcement->content), 200) }}
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('website.announcements.show', $announcement->uuid) }}"
                                            class="btn btn-sm btn-outline-primary">
                                                Read More <i class="fas fa-arrow-right ms-1"></i>
                                            </a>

                                            @if($announcement->publish_at || $announcement->expire_at)
                                            <div class="announcement-dates">
                                                @if($announcement->publish_at)
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock"></i>
                                                        Published: {{ $announcement->publish_at->format('M d, Y') }}
                                                    </small>
                                                @endif
                                                @if($announcement->expire_at)
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-times"></i>
                                                        Expires: {{ $announcement->expire_at->format('M d, Y') }}
                                                    </small>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($publishedAnnouncements->count() > 5)
                    <div class="text-center mt-4">
                        <a href="{{ route('schools.announcements', $school->uuid) }}"
                        class="btn btn-primary">
                            View All Announcements ({{ $publishedAnnouncements->count() }})
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                    <p class="no-content">No active announcements at the moment.</p>
                    <p class="text-muted">Check back later for updates from this school.</p>
                </div>
            @endif
        @else
            <div class="text-center py-4">
                <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                <p class="no-content">No announcements available.</p>
                <p class="text-muted">This school hasn't posted any announcements yet.</p>
            </div>
        @endif
    </div>
</section>
