<!-- ==================== HERO SECTION (compact) ==================== -->
<section class="hero-section" id="home">
    <div class="hero-content">
        <img class="hero-image" src="{{ asset('assets/assets/hero1.png') }}" alt="Hero Image">
        <p class="hero-subheading">Discover, compare, and connect with the best educational institutions</p>
    </div>
</section>

{{-- JSON endpoints: full grid search + typeahead (same filters apply to both) --}}
<div
    id="homeSearchConfig"
    hidden
    data-search-url="{{ route('search.schools') }}"
    data-suggest-url="{{ route('search.schools.suggest') }}"
    aria-hidden="true"
></div>

<!-- ==================== SEARCH BAR (Google-style live results) ==================== -->
<section class="search-section" aria-label="Search schools">
    <div class="container">
        <div class="search-container">
            <div class="search-box search-box--live">
                <div class="search-box__field" id="homeLiveSearchWrap">
                    <!-- <span class="search-box__icon" aria-hidden="true">
                        <i class="fas fa-search"></i>
                    </span> -->
                    <input
                        type="text"
                        class="search-input search-input--live"
                        id="mainSearch"
                        name="q"
                        autocomplete="off"
                        inputmode="search"
                        enterkeyhint="search"
                        placeholder="Search by name, email, phone, address, city, or description…"
                        aria-autocomplete="list"
                        aria-controls="homeLiveSearchResults"
                        aria-expanded="false"
                    >
                    <button
                        type="button"
                        class="search-box__clear"
                        id="homeLiveSearchClear"
                        title="Clear"
                        aria-label="Clear"
                        hidden
                    >
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                    <div
                        class="home-live-search-dropdown"
                        id="homeLiveSearchResults"
                        role="listbox"
                        hidden
                    ></div>
                </div>
                <button class="search-btn" type="button" id="mainSearchGoBtn">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <span>Search</span>
                </button>
            </div>
        </div>
    </div>
</section>
