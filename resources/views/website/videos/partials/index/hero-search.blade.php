<!-- ==================== VIDEOS HERO SECTION ==================== -->
<section class="videos-hero-section" id="videos-hero">
    <div class="videos-hero-content">
        <h1 class="videos-hero-title">SKOOLYST EduVideos</h1>
        <p class="videos-hero-subheading">
            Explore our collection of educational videos from schools and shops.
            Learn, discover, and get inspired with quality content.
        </p>
    </div>
</section>

<!-- ==================== VIDEOS SEARCH BAR ==================== -->
<section class="videos-search-section">
    <div class="container">
        <div class="videos-search-container">
            <form action="{{ route('website.videos.index') }}" method="GET" class="videos-search-form">
                @if(request('category') && request('category') != 'all')
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('school') && request('school') != 'all')
                    <input type="hidden" name="school" value="{{ request('school') }}">
                @endif
                @if(request('shop') && request('shop') != 'all')
                    <input type="hidden" name="shop" value="{{ request('shop') }}">
                @endif
                @if(request('filter') && request('filter') != 'all')
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif
                <div class="videos-search-box">
                    <input
                        type="text"
                        name="search"
                        class="videos-search-input"
                        placeholder="Search videos by title or description..."
                        value="{{ request('search') }}"
                    >
                    <button class="videos-search-btn" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
