<!-- ==================== BLOG HERO SECTION ==================== -->
<section class="blog-header" id="blog-hero">
    <div class="container">
        <div class="blog-hero-content">
            <h1 class="blog-hero-title">Educational Insights & Articles</h1>
            <p class="blog-hero-subtitle">
                Discover the latest trends, insights, and stories from the world of education.
                Expert advice, school success stories, and educational innovations.
            </p>
        </div>
    </div>
</section>

<!-- ==================== BLOG SEARCH BAR ==================== -->
<section class="blog-search-section">
    <div class="container">
        <div class="blog-search-container">
            <form action="{{ route('website.blog.index') }}" method="GET" class="blog-search-form">
                <div class="blog-search-box">
                    <input
                        type="text"
                        name="search"
                        class="blog-search-input"
                        placeholder="Search articles by title or content..."
                        value="{{ request('search') }}"
                    >
                    <button class="blog-search-btn" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
