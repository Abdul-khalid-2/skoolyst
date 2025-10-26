@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<style>
    /* ==================== EVENTS HERO SECTION ==================== */
    .events-hero {
        background: linear-gradient(135deg, #4361ee 0%, #38b000 50%, #ff9e00 100%);
        color: white;
        padding: 80px 0 60px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .events-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        animation: float 20s linear infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(0px) translateX(0px);
        }

        100% {
            transform: translateY(-100px) translateX(-100px);
        }
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    }

    .hero-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ==================== EVENTS STATS SECTION ==================== */
    .events-stats-section {
        padding: 4rem 0;
        background: white;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        text-align: center;
        padding: 2.5rem 2rem;
        background: #f8f9fa;
        border-radius: 20px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        border-color: #4361ee;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: #4361ee;
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 1.1rem;
        color: #666;
        font-weight: 600;
    }

    /* ==================== EVENTS GRID SECTION ==================== */
    .events-grid-section {
        padding: 4rem 0;
        background: #f8f9fa;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 3rem;
        color: #1a1a1a;
    }

    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 2.5rem;
        margin-bottom: 3rem;
    }

    .event-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid #e0e0e0;
        position: relative;
        overflow: hidden;
    }

    .event-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        /* background: linear-gradient(135deg, #4361ee, #38b000); */
    }

    .event-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }

    .event-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-bottom: 1px solid #e0e0e0;
    }

    .event-content {
        padding: 2rem;
    }

    .event-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #4361ee, #38b000);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: white;
        font-size: 1.5rem;
    }

    .event-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a1a;
        line-height: 1.3;
    }

    .event-description {
        font-size: 1rem;
        line-height: 1.6;
        color: #555;
        margin-bottom: 1.5rem;
    }

    .event-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid #e0e0e0;
        font-size: 0.9rem;
        color: #666;
    }

    .event-date {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
    }

    .event-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-view {
        background: linear-gradient(135deg, #4361ee, #38b000);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
    }

    /* ==================== FEATURED EVENTS ==================== */
    .featured-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #ff9e00, #ff6b00);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
        box-shadow: 0 4px 15px rgba(255, 158, 0, 0.3);
    }

    /* ==================== EMPTY STATE ==================== */
    .empty-state {
        text-align: center;
        padding: 6rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .empty-icon {
        font-size: 5rem;
        color: #6c757d;
        margin-bottom: 2rem;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .empty-description {
        color: #6c757d;
        margin-bottom: 2rem;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    /* ==================== RESPONSIVE DESIGN ==================== */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.2rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .events-grid {
            grid-template-columns: 1fr;
        }

        .event-content {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .event-meta {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .event-actions {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .hero-title {
            font-size: 1.8rem;
        }

        .section-title {
            font-size: 2rem;
        }
    }

    /* ==================== FILTER SECTION ==================== */
    .filter-section {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 3rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
    }

    .filter-select {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
</style>
@endpush

@section('content')

<!-- ==================== HERO SECTION ==================== -->
<section class="events-hero">
    <div class="container">
        <h1 class="hero-title">{{ $event->event_name??'Events' }}</h1>
        <p class="hero-subtitle">
            Discover the latest events, announcements, and important updates from our educational community. Stay informed and never miss an opportunity.
        </p>
    </div>
</section>

<!-- ==================== EVENTS GRID SECTION ==================== -->
<section class="events-grid-section">
    <div class="container">
        <h2 class="section-title">Latest Events</h2>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-grid">

                <div class="filter-group">
                    <label for="status-filter">Filter by Status</label>
                    <select id="status-filter" class="filter-select">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="past">Past Events</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="sort-filter">Sort By</label>
                    <select id="sort-filter" class="filter-select">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="featured">Featured First</option>
                    </select>
                </div>
            </div>
        </div>

        @if($pages->count() > 0)
        <div class="events-grid" id="events-container">
            @foreach($pages as $page)
            <div class="event-card" data-category="{{ $page->category ?? 'event' }}" data-status="{{ $page->status }}" data-featured="{{ $page->is_featured ? 'true' : 'false' }}">
                @if($page->is_featured)
                <div class="featured-badge">
                    <i class="fas fa-star me-1"></i> Featured
                </div>
                @endif
                <!-- 
                @if($page->featured_image)
                <img src="{{ asset('website/' . $page->featured_image) }}" alt="{{ $page->name }}" class="event-image">
                @else
                <div style="height: 200px; background: linear-gradient(135deg, #4361ee, #38b000); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                @endif -->

                <div class="event-content">
                    <!-- <div class="event-icon">
                        <i class="fas fa-{{ $page->icon ?? 'calendar-alt' }}"></i>
                    </div> -->

                    <h3 class="event-title">{{ $page->name }}</h3>

                    <p class="event-description">
                        {{ Str::limit($page->description ?? 'Discover this amazing event and be part of our educational community. Join us for an unforgettable experience.', 150) }}
                    </p>

                    <div class="event-meta">
                        <div class="event-date">
                            <i class="fas fa-clock"></i>
                            {{ $page->created_at->format('M d, Y') }}
                        </div>

                        <div class="event-actions">
                            <a href="{{ route('advertisement_pages.show', [$page->slug, $page->uuid]) }}"
                                class="btn-view" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3 class="empty-title">No Events Available</h3>
            <p class="empty-description">
                There are currently no events or announcements scheduled. Please check back later for updates, or contact us for more information about upcoming activities.
            </p>
            <a href="{{ route('home') }}" class="btn-view">
                <i class="fas fa-home me-2"></i> Return to Home
            </a>
        </div>
        @endif

        <!-- Pagination -->
        @if($pages->hasPages())
        <div class="d-flex justify-content-center mt-5">
            <nav aria-label="Events pagination">
                {{ $pages->links('pagination::bootstrap-5') }}
            </nav>
        </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth animations for cards
        const eventCards = document.querySelectorAll('.event-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        eventCards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });

        // Simple filter functionality
        const statusFilter = document.getElementById('status-filter');
        const sortFilter = document.getElementById('sort-filter');
        const eventsContainer = document.getElementById('events-container');

        function filterEvents() {
            const categoryValue = categoryFilter.value;
            const statusValue = statusFilter.value;
            const sortValue = sortFilter.value;

            eventCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                const cardStatus = card.getAttribute('data-status');
                const isFeatured = card.getAttribute('data-featured') === 'true';

                let showCard = true;

                // Category filter
                if (categoryValue !== 'all' && cardCategory !== categoryValue) {
                    showCard = false;
                }

                // Status filter
                if (statusValue !== 'all') {
                    if (statusValue === 'active' && cardStatus !== 'active') {
                        showCard = false;
                    } else if (statusValue === 'featured' && !isFeatured) {
                        showCard = false;
                    }
                }

                // Show/hide card
                if (showCard) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Simple sort (you can enhance this with more complex sorting)
            if (sortValue === 'featured') {
                const featuredCards = Array.from(eventCards).filter(card =>
                    card.getAttribute('data-featured') === 'true' &&
                    card.style.display !== 'none'
                );
                const nonFeaturedCards = Array.from(eventCards).filter(card =>
                    card.getAttribute('data-featured') === 'false' &&
                    card.style.display !== 'none'
                );

                eventsContainer.innerHTML = '';
                featuredCards.forEach(card => eventsContainer.appendChild(card));
                nonFeaturedCards.forEach(card => eventsContainer.appendChild(card));
            }
        }

        // Add event listeners for filters
        if (categoryFilter) categoryFilter.addEventListener('change', filterEvents);
        if (statusFilter) statusFilter.addEventListener('change', filterEvents);
        if (sortFilter) sortFilter.addEventListener('change', filterEvents);
    });
</script>
@endpush