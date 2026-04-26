<!-- ==================== RECENT MCQS SECTION ==================== -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
            <div>
                <h2 class="section-title mb-0">{{ __('mcqs.recent.title') }}</h2>
                <p class="text-muted">{{ __('mcqs.recent.subtitle') }}</p>
            </div>
            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl(route('website.mcqs.mock-tests', [], false)) }}" class="btn btn-primary">
                <i class="fas fa-clipboard-list me-2"></i>{{ __('mcqs.recent.view_all_mock_tests') }}
            </a>
        </div>

        <div class="row">
            @foreach($recentMcqs as $mcq)
            <div class="col-md-6">
                <div class="mcq-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="mcq-difficulty difficulty-{{ $mcq->difficulty_value }}">
                            {{ $mcq->difficulty_label }}
                        </span>
                        <span class="badge bg-light text-dark">
                            {{ $mcq->marks }} {{ $mcq->marks > 1 ? __('mcqs.recent.marks') : __('mcqs.recent.mark') }}
                        </span>
                    </div>

                    <div class="mcq-question">
                        <a href="{{ route('website.mcqs.practice', $mcq->uuid) }}">
                            {!! Str::limit(strip_tags($mcq->question), 150) !!}
                        </a>
                    </div>

                    <div class="mcq-meta">
                        <span class="mcq-meta-item">
                            <i class="fas fa-book"></i>
                            {{ $mcq->subject->name }}
                        </span>
                        @if($mcq->topic)
                        <span class="mcq-meta-item">
                            <i class="fas fa-folder"></i>
                            {{ $mcq->topic->title }}
                        </span>
                        @endif
                    </div>

                    <div class="text-end">
                        <a href="{{ route('website.mcqs.practice', $mcq->uuid) }}" class="btn btn-sm btn-outline-primary">
                            {{ __('mcqs.recent.practice') }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="#" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i>{{ __('mcqs.recent.browse_all') }}
            </a>
        </div>
    </div>
</section>
