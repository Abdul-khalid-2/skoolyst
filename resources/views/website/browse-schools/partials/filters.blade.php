{{-- All schools page — same filter bar design as homepage --}}
<section class="filter-section" aria-label="Filter school listings">
    <div class="container">
        <header class="filter-section__header text-center">
            <p class="filter-section__eyebrow">Refine your search</p>
            <h2 class="filter-section__title h5 mb-0">Find schools that match you</h2>
        </header>

        <div class="filter-bar filter-bar--browse">
            <div class="filter-container" data-base-url="{{ route('browseSchools.index') }}">
                <div class="filter-group filter-group--search">
                    <label class="filter-label" for="searchInput">
                        <i class="fas fa-magnifying-glass" aria-hidden="true"></i>
                        Search
                    </label>
                    <div class="filter-input-wrap">
                        <input
                            type="search"
                            class="filter-search"
                            id="searchInput"
                            name="search"
                            placeholder="Name, city, curriculum…"
                            value="{{ request('search', '') }}"
                            autocomplete="off"
                            inputmode="search"
                        >
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="locationFilter">
                        <i class="fas fa-location-dot" aria-hidden="true"></i>
                        Location
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select" id="locationFilter">
                            <option value="">All locations</option>
                            @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('location') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="typeFilter">
                        <i class="fas fa-venus-mars" aria-hidden="true"></i>
                        School type
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select" id="typeFilter">
                            <option value="">All types</option>
                            @foreach($schoolTypes as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ $type === 'Separate' ? 'Girls and boys (separate campuses)' : $type }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label" for="curriculumFilter">
                        <i class="fas fa-book-open" aria-hidden="true"></i>
                        Curriculum
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select" id="curriculumFilter">
                            <option value="">All curriculums</option>
                            @foreach($curriculums as $curriculum)
                            <option value="{{ $curriculum->code }}" {{ request('curriculum') == $curriculum->code ? 'selected' : '' }}>
                                {{ $curriculum->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group filter-group--action">
                    <span class="filter-label filter-label--spacer" aria-hidden="true">
                        <i class="fas fa-location-dot" aria-hidden="true"></i>
                        Location
                    </span>
                    <button
                        type="button"
                        class="clear-filters-btn"
                        id="clearFiltersBtn"
                        title="Reset all filters"
                        aria-label="Reset all filters"
                    >
                        <i class="fas fa-arrow-rotate-left" aria-hidden="true"></i>
                        <span class="clear-filters-btn__text">Reset</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
