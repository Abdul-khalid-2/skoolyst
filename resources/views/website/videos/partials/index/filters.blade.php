{{-- Videos listing filters — same bar design as home / browse schools --}}
<section class="filter-section filter-section--videos" aria-label="Filter videos">
    <header class="filter-section__header text-center">
        <p class="filter-section__eyebrow">Refine results</p>
        <h2 class="filter-section__title h5 mb-0">Find the right videos</h2>
    </header>

    <div class="filter-bar filter-bar--videos">
        <form action="{{ route('website.videos.index') }}" method="GET" id="video-filters-form">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <div class="filter-container">
                <div class="filter-group">
                    <label class="filter-label" for="category">
                        <i class="fas fa-layer-group" aria-hidden="true"></i>
                        Category
                    </label>
                    <div class="filter-select-wrap">
                        <select name="category" id="category" class="filter-select">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="filter-group">
                    <label class="filter-label" for="school">
                        <i class="fas fa-school" aria-hidden="true"></i>
                        School
                    </label>
                    <div class="filter-select-wrap">
                        <select name="school" id="school" class="filter-select">
                            <option value="all">All Schools</option>
                            @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school') == $school->id ? 'selected' : '' }}>
                                {{ $school->localized('name') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="filter-group">
                    <label class="filter-label" for="shop">
                        <i class="fas fa-store" aria-hidden="true"></i>
                        Shop
                    </label>
                    <div class="filter-select-wrap">
                        <select name="shop" id="shop" class="filter-select">
                            <option value="all">All Shops</option>
                            @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ request('shop') == $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="filter-group filter-group--action">
                    <span class="filter-label filter-label--spacer" aria-hidden="true">
                        <i class="fas fa-school" aria-hidden="true"></i>
                        School
                    </span>
                    <a
                        href="{{ route('website.videos.index') }}"
                        class="clear-filters-btn"
                        title="Reset all filters"
                        aria-label="Reset all filters"
                    >
                        <i class="fas fa-arrow-rotate-left" aria-hidden="true"></i>
                        <span class="clear-filters-btn__text">Reset</span>
                    </a>
                </div>
            </div>

            <div class="videos-filters__quick" aria-label="Quick filters">
                <p class="videos-filters__quick-label">Quick filters</p>
                <div class="quick-filters" role="group">
                    <button type="button" class="quick-filter-btn {{ !request('filter') || request('filter') == 'all' ? 'active' : '' }}"
                            data-filter="all">
                        All Videos
                    </button>
                    <button type="button" class="quick-filter-btn {{ request('filter') == 'featured' ? 'active' : '' }}"
                            data-filter="featured">
                        <i class="fas fa-star" aria-hidden="true"></i> Featured
                    </button>
                    <button type="button" class="quick-filter-btn {{ request('filter') == 'popular' ? 'active' : '' }}"
                            data-filter="popular">
                        <i class="fas fa-fire" aria-hidden="true"></i> Popular
                    </button>
                    <button type="button" class="quick-filter-btn {{ request('filter') == 'recent' ? 'active' : '' }}"
                            data-filter="recent">
                        <i class="fas fa-clock" aria-hidden="true"></i> Recent
                    </button>
                </div>
                <input type="hidden" name="filter" id="filter" value="{{ request('filter', 'all') }}">
            </div>
        </form>
    </div>
</section>
