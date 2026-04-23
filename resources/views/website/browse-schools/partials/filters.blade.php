<!-- ==================== FILTER SECTION ==================== -->
<section class="filter-section">
    <div class="container">
        <div class="filter-container" data-base-url="{{ route('browseSchools.index') }}">
            <div class="filter-group">
                <label class="filter-label">Search</label>
                <input
                    type="text"
                    class="filter-search"
                    id="searchInput"
                    placeholder="Search by name, location, or curriculum..."
                    value="{{ request('search', '') }}"
                >
            </div>

            <div class="filter-group">
                <label class="filter-label">Location</label>
                <select class="filter-select" id="locationFilter">
                    <option value="">All Locations</option>
                    @foreach($cities as $city)
                    <option value="{{ $city }}" {{ request('location') == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">School Type</label>
                <select class="filter-select" id="typeFilter">
                    <option value="">All Types</option>
                    @foreach($schoolTypes as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                        {{ $type === 'Separate' ? 'Girls And Boys Separate Campuses' : $type }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Curriculum</label>
                <select class="filter-select" id="curriculumFilter">
                    <option value="">All Curriculums</option>
                    @foreach($curriculums as $curriculum)
                    <option value="{{ $curriculum->code }}" {{ request('curriculum') == $curriculum->code ? 'selected' : '' }}>{{ $curriculum->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <button class="clear-filters-btn" id="clearFiltersBtn">
                    <i class="fas fa-redo"></i> Clear All
                </button>
            </div>
        </div>
    </div>
</section>
