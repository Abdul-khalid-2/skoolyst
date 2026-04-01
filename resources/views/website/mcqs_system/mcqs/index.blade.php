@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/mcqs.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== MCQS HERO SECTION (compact, unified) ==================== -->
<section class="mcqs-hero-section" id="mcqs-hero">
    <div class="mcqs-hero-content">
        <h1 class="mcqs-hero-title">Practice MCQs for Competitive Exams</h1>
        <p class="mcqs-hero-subheading">
            Master your subjects with practice questions for NTS, PPSC, FPSC, MDCAT, ECAT and more.
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
                            <div class="stats-number">{{ \App\Models\Mcq::where('status', 'published')->count() }}</div>
                            <div class="stats-label">Total MCQs</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-number">{{ \App\Models\Subject::where('status', 'active')->count() }}</div>
                            <div class="stats-label">Subjects</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-number">{{ \App\Models\TestType::where('status', 'active')->count() }}</div>
                            <div class="stats-label">Test Types</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-number">{{ \App\Models\Topic::where('status', 'active')->count() }}</div>
                            <div class="stats-label">Topics</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== TOP PERFORMERS SECTION ==================== -->
<section class="py-5">
    <div class="container">
        <div class="section-title-wrapper text-center mb-5">
            <h2 class="section-title">Top Performers</h2>
            <p class="section-subtitle">Meet our top MCQs test takers! <a href="{{ route('login') }}" class="text-decoration-none">Login</a> to see your ranking and compete with the best.</p>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse($topUsers as $performer)
            <div class="col-6 col-md-2">
                <div class="performer-card text-center">
                    <div class="performer-avatar mb-3">
                        <img src="{{ asset('website/school/default/student_icon.png') }}"
                             alt="{{ $performer['user']->name }}"
                             class="rounded-circle"
                             style="width: 35px; height: 35px; object-fit: cover;">
                    </div>
                    <small class="performer-name mb-2 d-block">{{ $performer['user']->name }}</small>
                    <div class="performer-stars mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($performer['stars']))
                                <i class="fas fa-star text-warning"></i>
                            @elseif($i == ceil($performer['stars']) && ($performer['stars'] % 1) >= 0.5)
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @else
                                <i class="far fa-star text-muted"></i>
                            @endif
                        @endfor
                    </div>
                    <div class="performer-stats">
                       
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">No users have attempted MCQs yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ==================== TEST TYPES SECTION ==================== -->
<section id="test-types-section" class="py-5 bg-light">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="section-title">Choose Your Test Type</h2>
            <p class="section-subtitle">Select from various test categories to start your preparation</p>
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
                        <span><i class="fas fa-book me-1"></i>{{ $testType->subjects_count }} Subjects</span>
                        <span><i class="fas fa-question-circle me-1"></i>{{ $testType->mcqs_count }} MCQs</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ==================== POPULAR SUBJECTS SECTION ==================== -->
<section id="subjects-section" class="py-5">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="section-title">Popular Subjects</h2>
            <p class="section-subtitle">Start practicing with our most popular subjects</p>
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
                            <span class="badge">{{ $subject->mcqs_count }} Qs</span>
                            <span class="badge">{{ $subject->topics_count }} Topics</span>
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

                    <!-- <div class="subject-test-types">
                        <span class="subject-test-types-label">Available for:</span>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($subject->testTypes->take(3) as $testType)
                            <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug, $subject->slug]) }}"
                                class="badge">
                                {{ $testType->name }}
                            </a>
                            @endforeach
                            @if($subject->testTypes->count() > 3)
                            <span class="badge">+{{ $subject->testTypes->count() - 3 }}</span>
                            @endif
                        </div>
                    </div> -->

                    <div class="mt-4">
                        <a href="{{ route('website.mcqs.subject', $subject->slug) }}" class="btn btn-sm btn-outline-primary">
                            View Topics <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="#" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i>Browse All Subjects
            </a>
        </div>
    </div>
</section>

<!-- ==================== HOW IT WORKS SECTION ==================== -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="section-title-wrapper">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Three simple steps to improve your preparation</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-item">
                    <div class="step-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h4>1. Choose Test Type or Subject</h4>
                    <p>Select from various test types like NTS, PPSC, FPSC or browse by subject.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="step-item">
                    <div class="step-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4>2. Select Topic</h4>
                    <p>Choose specific topics within your subject for focused practice.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="step-item">
                    <div class="step-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h4>3. Practice Questions</h4>
                    <p>Practice with detailed explanations and track your progress.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== RECENT MCQS SECTION ==================== -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-0">Recent Practice Questions</h2>
                <p class="text-muted">Latest MCQs added to our database</p>
            </div>
            <a href="{{ route('website.mcqs.mock-tests') }}" class="btn btn-primary">
                <i class="fas fa-clipboard-list me-2"></i>View All Mock Tests
            </a>
        </div>

        <div class="row">
            @foreach($recentMcqs as $mcq)
            <div class="col-md-6">
                <div class="mcq-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="mcq-difficulty difficulty-{{ $mcq->difficulty_level }}">
                            {{ ucfirst($mcq->difficulty_level) }}
                        </span>
                        <span class="badge bg-light text-dark">
                            {{ $mcq->marks }} Mark{{ $mcq->marks > 1 ? 's' : '' }}
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
                            Practice <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="#" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i>Browse All Questions
            </a>
        </div>
    </div>
</section>

<!-- ==================== CALL TO ACTION SECTION ==================== -->
<section class="cta-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2>Ready to test your knowledge?</h2>
                <p class="mb-md-0">Take our comprehensive mock tests and track your progress over time.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('website.mcqs.mock-tests') }}" class="btn btn-light btn-lg">
                    Start Mock Test <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Option selection for practice questions
    document.addEventListener('DOMContentLoaded', function() {
        
        const optionCards = document.querySelectorAll('.option-card:not(.correct):not(.incorrect)');
        
        optionCards.forEach(card => {
            card.addEventListener('click', function() {
                const isSingle = this.closest('.mcq-card').dataset.questionType === 'single';
                const isSelected = this.classList.contains('selected');
                
                if (isSingle) {
                    optionCards.forEach(c => c.classList.remove('selected'));
                }
                
                if (!isSingle || !isSelected) {
                    this.classList.toggle('selected');
                }
            });
        });
    });
</script>
@endpush