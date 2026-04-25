{{-- School directory filters — centered bar --}}
<section class="filter-section" aria-label="Filter school listings">
    <div class="container">
        <header class="filter-section__header text-center">
            <p class="filter-section__eyebrow">Refine your search</p>
            <h2 class="filter-section__title h5 mb-0">Find schools that match you</h2>
        </header>

        <div class="filter-bar">
            <div class="filter-container">
                <div class="filter-group">
                    <label class="filter-label" for="locationFilter">
                        <i class="fas fa-location-dot" aria-hidden="true"></i>
                        Location
                    </label>
                    <div class="filter-select-wrap">
                        <select class="filter-select" id="locationFilter" onchange="applyFilters()">
                            <option value="">All locations</option>
                            @foreach($cities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
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
                        <select class="filter-select" id="typeFilter" onchange="applyFilters()">
                            <option value="">All types</option>
                            @foreach($schoolTypes as $type)
                            <option value="{{ $type }}">
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
                        <select class="filter-select" id="curriculumFilter" onchange="applyFilters()">
                            <option value="">All curriculums</option>
                            @foreach($curriculums as $curriculum)
                            <option value="{{ $curriculum->code }}">{{ $curriculum->name }}</option>
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
                        onclick="clearFilters()"
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
