<div class="filters-container">
    <form action="{{ route('website.videos.index') }}" method="GET" id="video-filters-form">
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        <div class="row">
            <div class="col-md-4 filter-group">
                <label for="category">Category</label>
                <select name="category" id="category" class="form-control filter-select">
                    <option value="all">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 filter-group">
                <label for="school">School</label>
                <select name="school" id="school" class="form-control filter-select">
                    <option value="all">All Schools</option>
                    @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ request('school') == $school->id ? 'selected' : '' }}>
                        {{ $school->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 filter-group">
                <label for="shop">Shop</label>
                <select name="shop" id="shop" class="form-control filter-select">
                    <option value="all">All Shops</option>
                    @foreach($shops as $shop)
                    <option value="{{ $shop->id }}" {{ request('shop') == $shop->id ? 'selected' : '' }}>
                        {{ $shop->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4">
            <label class="d-block mb-2">Quick Filters:</label>
            <div class="quick-filters">
                <button type="button" class="quick-filter-btn {{ !request('filter') || request('filter') == 'all' ? 'active' : '' }}"
                        data-filter="all">
                    All Videos
                </button>
                <button type="button" class="quick-filter-btn {{ request('filter') == 'featured' ? 'active' : '' }}"
                        data-filter="featured">
                    <i class="fas fa-star me-1"></i> Featured
                </button>
                <button type="button" class="quick-filter-btn {{ request('filter') == 'popular' ? 'active' : '' }}"
                        data-filter="popular">
                    <i class="fas fa-fire me-1"></i> Popular
                </button>
                <button type="button" class="quick-filter-btn {{ request('filter') == 'recent' ? 'active' : '' }}"
                        data-filter="recent">
                    <i class="fas fa-clock me-1"></i> Recent
                </button>
            </div>
            <input type="hidden" name="filter" id="filter" value="{{ request('filter', 'all') }}">
        </div>

        @if(request()->hasAny(['category', 'school', 'shop', 'filter', 'search']))
        <div class="mt-3 text-end">
            <a href="{{ route('website.videos.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-times me-1"></i> Clear Filters
            </a>
        </div>
        @endif
    </form>
</div>
