@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== MCQS HEADER SECTION ==================== */
    .mcqs-header {
        background: linear-gradient(135deg, #0f4077 0%, #1e56a0 100%);
        color: white;
        padding: 100px 0 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .mcqs-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        animation: float 20s linear infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(0px) translateX(0px);
        }
        100% {
            transform: translateY(-100px) translateX(-100px);
        }
    }

    .mcqs-hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    }

    .mcqs-hero-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ==================== TEST TYPE CARDS ==================== */
    .test-type-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #eaeaea;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .test-type-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: #000000;
    }

    .test-type-icon {
        font-size: 3rem;
        margin-bottom: 20px;
        color: #000000;
    }

    .test-type-count {
        background: #000000;
        color: white;
        border-radius: 20px;
        padding: 5px 15px;
        font-size: 0.9rem;
        position: absolute;
        top: 15px;
        right: 15px;
        font-weight: bold;
    }

    /* ==================== SUBJECT CARDS ==================== */
    .subject-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
        border: 1px solid #eaeaea;
        height: 100%;
    }

    .subject-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        border-color: #000000;
    }

    .subject-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        font-size: 1.8rem;
    }

    /* ==================== MCQ CARDS ==================== */
    .mcq-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
        border: 1px solid #eaeaea;
        margin-bottom: 20px;
    }

    .mcq-card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        border-color: #000000;
    }

    .mcq-difficulty {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .difficulty-easy {
        background: #d4edda;
        color: #155724;
    }

    .difficulty-medium {
        background: #fff3cd;
        color: #856404;
    }

    .difficulty-hard {
        background: #f8d7da;
        color: #721c24;
    }

    /* ==================== STATS CARDS ==================== */
    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        border: 1px solid #eaeaea;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #000000;
        margin-bottom: 10px;
    }

    /* ==================== PRACTICE MODE ==================== */
    .practice-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .question-text {
        font-size: 1.2rem;
        line-height: 1.6;
        margin-bottom: 25px;
        color: #333;
    }

    .option-card {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .option-card:hover {
        background: #e9ecef;
        border-color: #000000;
    }

    .option-card.selected {
        background: #000000;
        color: white;
        border-color: #000000;
    }

    .option-card.correct {
        background: #d4edda;
        border-color: #28a745;
        color: #155724;
    }

    .option-card.incorrect {
        background: #f8d7da;
        border-color: #dc3545;
        color: #721c24;
    }

    .stats-card .text-muted {
        font-weight: bolder;
        --bs-text-opacity: 1;
        color: rgb(255 255 255) !important;
    }

    /* ==================== RESPONSIVE DESIGN ==================== */
    @media (max-width: 768px) {
        .mcqs-hero-title {
            font-size: 2.5rem;
        }

        .mcqs-hero-subtitle {
            font-size: 1.1rem;
        }

        .test-type-card {
            padding: 20px;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<section class="mcqs-header">
    <div class="container">
        <h1 class="mcqs-hero-title">Practice MCQs for Competitive Exams</h1>
        <p class="mcqs-hero-subtitle">
            Master your subjects with practice questions for NTS, PPSC, FPSC, MDCAT, ECAT and more.
        </p>

        <!-- Quick Stats -->
        <div class="row justify-content-center mt-5">
            <div class="col-md-10">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number">{{ \App\Models\Mcq::where('status', 'published')->count() }}</div>
                            <div class="text-muted">Total MCQs</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number">{{ \App\Models\Subject::where('status', 'active')->count() }}</div>
                            <div class="text-muted">Subjects</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number">{{ \App\Models\TestType::where('status', 'active')->count() }}</div>
                            <div class="text-muted">Test Types</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number">{{ \App\Models\Topic::where('status', 'active')->count() }}</div>
                            <div class="text-muted">Topics</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== MAIN NAVIGATION SECTIONS ==================== -->
<section class="py-5">
    <div class="container">
        <!-- Three Main Options -->
        <div class="row g-4 mb-5">
            <!-- Test Type Wise -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-clipboard-list fa-3x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-3">Test Type Wise</h3>
                        <p class="text-muted mb-4">Browse MCQs by test types like NTS, GAT, PPSC, FPSC, MDCAT, ECAT</p>
                        <a href="#test-types-section" class="btn btn-primary btn-lg w-100">
                            Browse by Test Type <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Subject Wise -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-book fa-3x text-success"></i>
                        </div>
                        <h3 class="h4 mb-3">Subject Wise</h3>
                        <p class="text-muted mb-4">Select a subject and practice MCQs topic by topic</p>
                        <a href="#subjects-section" class="btn btn-success btn-lg w-100">
                            Browse by Subject <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mock Tests -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-tasks fa-3x text-warning"></i>
                        </div>
                        <h3 class="h4 mb-3">Mock Tests</h3>
                        <p class="text-muted mb-4">Take full-length mock tests with time limits and detailed results</p>
                        <a href="{{ route('website.mcqs.mock-tests') }}" class="btn btn-warning btn-lg w-100">
                            View Mock Tests <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== TEST TYPES SECTION ==================== -->
<section id="test-types-section" class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="h1 mb-3">Choose Your Test Type</h2>
                <p class="text-muted">Select from various test categories to start your preparation</p>
            </div>
        </div>

        <div class="row g-4">
            @foreach($testTypes as $testType)
            <div class="col-md-4 col-lg-3">
                <a href="{{ route('website.mcqs.test-type', $testType->slug) }}" class="text-decoration-none">
                    <div class="test-type-card">
                        <div class="test-type-icon">
                            <i class="{{ $testType->icon ?? 'fas fa-graduation-cap' }}"></i>
                        </div>
                        <h3 class="h5 mb-2">{{ $testType->name }}</h3>
                        <p class="text-muted small mb-2">{{ Str::limit($testType->description ?? '', 80) }}</p>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>{{ $testType->subjects_count }} Subjects</span>
                            <span>{{ $testType->mcqs_count }} MCQs</span>
                        </div>
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
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="h1 mb-3">Popular Subjects</h2>
                <p class="text-muted">Start practicing with our most popular subjects</p>
            </div>
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
                            <span class="badge bg-light text-dark">
                                {{ $subject->mcqs_count }} Questions
                            </span>
                            <span class="badge bg-light text-dark">
                                {{ $subject->topics_count }} Topics
                            </span>
                        </div>
                    </div>

                    <h4 class="h5 my-3">
                        <a href="{{ route('website.mcqs.subject', $subject->slug) }}" class="text-decoration-none text-dark">
                            {{ $subject->name }}
                        </a>
                    </h4>

                    @if($subject->description)
                    <p class="text-muted small mb-3">{{ Str::limit($subject->description, 100) }}</p>
                    @endif

                    <!-- Test Types for this subject -->
                    <!-- @if($subject->testTypes->count() > 0)
                    <div class="subject-test-types">
                        <span class="text-muted small">Available for:</span>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            @foreach($subject->testTypes->take(3) as $testType)
                            <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug, $subject->slug]) }}"
                                class="badge bg-light text-dark text-decoration-none">
                                {{ $testType->name }}
                            </a>
                            @endforeach
                            @if($subject->testTypes->count() > 3)
                            <span class="badge bg-secondary">+{{ $subject->testTypes->count() - 3 }}</span>
                            @endif
                        </div>
                    </div>
                    @endif -->

                    <div class="mt-3">
                        <a href="{{ route('website.mcqs.subject', $subject->slug) }}" class="btn btn-sm btn-outline-primary">
                            View Topics <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="#" class="btn btn-lg btn-primary">
                    <i class="fas fa-search me-2"></i>Browse All Subjects
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== HOW IT WORKS SECTION ==================== -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="h1 mb-3">How It Works</h2>
                <p class="text-muted">Three simple steps to improve your preparation</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="step-icon mb-3">
                        <i class="fas fa-book-open fa-2x text-primary"></i>
                    </div>
                    <h4 class="h5 mb-3">1. Choose Test Type or Subject</h4>
                    <p class="text-muted">Select from various test types like NTS, PPSC, FPSC or browse by subject.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="text-center">
                    <div class="step-icon mb-3">
                        <i class="fas fa-folder-open fa-2x text-primary"></i>
                    </div>
                    <h4 class="h5 mb-3">2. Select Topic</h4>
                    <p class="text-muted">Choose specific topics within your subject for focused practice.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="text-center">
                    <div class="step-icon mb-3">
                        <i class="fas fa-question-circle fa-2x text-primary"></i>
                    </div>
                    <h4 class="h5 mb-3">3. Practice Questions</h4>
                    <p class="text-muted">Practice with detailed explanations and track your progress.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== RECENT MCQS SECTION ==================== -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2 class="h1 mb-0">Recent Practice Questions</h2>
                <a href="{{ route('website.mcqs.mock-tests') }}" class="btn btn-primary">
                    <i class="fas fa-clipboard-list me-2"></i>View All Mock Tests
                </a>
            </div>
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
                    
                    <h5 class="mb-3">
                        <a href="{{ route('website.mcqs.practice', $mcq->uuid) }}" class="text-decoration-none text-dark">
                            {!! Str::limit(strip_tags($mcq->question), 150) !!}
                        </a>
                    </h5>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-light text-dark me-2">
                                <i class="fas fa-book me-1"></i>{{ $mcq->subject->name }}
                            </span>
                            @if($mcq->topic)
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-folder me-1"></i>{{ $mcq->topic->title }}
                            </span>
                            @endif
                        </div>
                        <a href="{{ route('website.mcqs.practice', $mcq->uuid) }}" class="btn btn-sm btn-outline-primary">
                            Practice <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="#" class="btn btn-lg btn-primary">
                    <i class="fas fa-search me-2"></i>Browse All Questions
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CALL TO ACTION SECTION ==================== -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="h1 mb-3">Ready to test your knowledge?</h2>
                <p class="mb-0">Take our comprehensive mock tests and track your progress over time.</p>
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

@push('styles')
<style>
    /* Hero Section */
    .mcqs-header {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: white;
        padding: 100px 0;
        text-align: center;
    }

    .mcqs-hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .mcqs-hero-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Stats Cards */
    .stats-card {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        backdrop-filter: blur(10px);
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 750;
        margin-bottom: 0.1rem;
        /* color: white; */
    }

    /* Test Type Cards */
    .test-type-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #eee;
        height: 100%;
    }

    .test-type-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .test-type-icon {
        width: 70px;
        height: 70px;
        background: #000000;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 1.8rem;
    }

    /* Subject Cards */
    .subject-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #eee;
        height: 100%;
    }

    .subject-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .subject-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
    }

    .subject-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .subject-meta {
        display: flex;
        gap: 5px;
    }

    .subject-test-types {
        border-top: 1px solid #eee;
        padding-top: 15px;
        margin-top: 15px;
    }

    /* Step Icons */
    .step-icon {
        width: 80px;
        height: 80px;
        background: #f8f9fa;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .mcqs-hero-title {
            font-size: 2.5rem;
        }
        
        .mcqs-hero-subtitle {
            font-size: 1rem;
        }
        
        .stats-number {
            font-size: 2rem;
        }
    }
    a {
        color: rgb(0 0 0);
        text-decoration: underline;
    }
</style>
@endpush
@endpush