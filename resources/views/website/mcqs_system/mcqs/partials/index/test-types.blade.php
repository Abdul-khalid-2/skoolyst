<!-- ==================== TEST TYPES SECTION ==================== -->
<section id="test-types-section" class="py-5 bg-light">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="section-title">{{ __('mcqs.test_types.title') }}</h2>
            <p class="section-subtitle">{{ __('mcqs.test_types.subtitle') }}</p>
        </div>

        <div class="row g-4">
            @foreach($testTypes as $testType)
            <div class="col-md-4 col-lg-3">
                <a href="{{ route('website.mcqs.test-type', $testType->slug) }}" class="test-type-card">
                    <div class="test-type-icon">
                        <i class="{{ $testType->icon ?? 'fas fa-graduation-cap' }}"></i>
                    </div>
                    <h3>{{ $testType->name }}</h3>
                    <p>{{ Str::limit($testType->description ?? '', 80) }}</p>
                    <div class="test-type-stats">
                        <span><i class="fas fa-book me-1"></i>{{ __('mcqs.test_types.subjects_n', ['count' => $testType->subjects_count]) }}</span>
                        <span><i class="fas fa-question-circle me-1"></i>{{ __('mcqs.test_types.mcqs_n', ['count' => $testType->mcqs_count]) }}</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
