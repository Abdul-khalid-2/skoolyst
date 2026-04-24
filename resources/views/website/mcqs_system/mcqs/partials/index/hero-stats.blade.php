<!-- ==================== MCQS HERO SECTION (compact, unified) ==================== -->
<section class="mcqs-hero-section" id="mcqs-hero">
    <div class="mcqs-hero-content">
        <h1 class="mcqs-hero-title">{{ __('mcqs.hero.title') }}</h1>
        <p class="mcqs-hero-subheading">
            {{ __('mcqs.hero.subheading') }}
        </p>
    </div>
</section>

<!-- ==================== STATS SECTION ==================== -->
<section class="stats-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row g-4">
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-number">{{ $stats['totalMcqs'] }}</div>
                            <div class="stats-label">{{ __('mcqs.stats.total_mcqs') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-number">{{ $stats['subjectsCount'] }}</div>
                            <div class="stats-label">{{ __('mcqs.stats.subjects') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-number">{{ $stats['testTypesCount'] }}</div>
                            <div class="stats-label">{{ __('mcqs.stats.test_types') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-number">{{ $stats['topicsCount'] }}</div>
                            <div class="stats-label">{{ __('mcqs.stats.topics') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
