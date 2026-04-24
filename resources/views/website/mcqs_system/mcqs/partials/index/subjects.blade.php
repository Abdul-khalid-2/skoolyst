<!-- ==================== POPULAR SUBJECTS SECTION ==================== -->
<section id="subjects-section" class="py-5">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="section-title">{{ __('mcqs.subjects.title') }}</h2>
            <p class="section-subtitle">{{ __('mcqs.subjects.subtitle') }}</p>
        </div>

        <div class="row g-4">
            @foreach($subjects as $subject)
            <div class="col-md-4 col-lg-3">
                <div class="subject-card">
                    <div class="subject-header">
                        <div class="subject-icon" style="background: {{ $subject->color_code ?? '#4361ee' }};">
                            <i class="{{ $subject->icon ?? 'fas fa-book' }}"></i>
                        </div>
                        <div class="subject-meta">
                            <span class="badge">{{ $subject->mcqs_count }} {{ __('mcqs.subjects.badge_qs') }}</span>
                            <span class="badge">{{ $subject->topics_count }} {{ __('mcqs.subjects.badge_topics') }}</span>
                        </div>
                    </div>

                    <h4>
                        <a href="{{ route('website.mcqs.subject', $subject->slug) }}">
                            {{ $subject->name }}
                        </a>
                    </h4>

                    @if($subject->description)
                    <p class="subject-description">{{ Str::limit($subject->description, 100) }}</p>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('website.mcqs.subject', $subject->slug) }}" class="btn btn-sm btn-outline-primary">
                            {{ __('mcqs.subjects.view_topics') }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="#" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i>{{ __('mcqs.subjects.browse_all') }}
            </a>
        </div>
    </div>
</section>
