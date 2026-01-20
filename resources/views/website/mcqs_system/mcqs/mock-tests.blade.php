@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    .mock-test-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .mock-test-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: #4361ee;
    }

    .test-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .test-badge.free { background: #28a745; color: white; }
    .test-badge.premium { background: #ffc107; color: #212529; }

    .test-stats {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 1.2rem;
        font-weight: bold;
        color: #4361ee;
        display: block;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
        display: block;
    }

    .filter-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .filter-tag {
        display: inline-block;
        padding: 8px 15px;
        margin: 5px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .filter-tag:hover {
        background: #e9ecef;
        border-color: #4361ee;
    }

    .filter-tag.active {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    .featured-test {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 40px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .featured-test::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
    }

    .mode-badge {
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: 5px;
    }

    .mode-practice { background: #28a745; color: white; }
    .mode-timed { background: #17a2b8; color: white; }
    .mode-exam { background: #dc3545; color: white; }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')
<section class="py-5">
    <div class="container">
        <!-- Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="h1 mb-3">Mock Tests</h1>
                <p class="text-muted mb-0">Test your knowledge with comprehensive mock tests and track your progress</p>
            </div>
        </div>

        <!-- Featured Test -->
        @php $featuredTest = $mockTests->first(); @endphp
        @if($featuredTest)
        <div class="featured-test position-relative">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="position-relative">
                        <span class="badge bg-warning text-dark mb-3">FEATURED TEST</span>
                        <h2 class="h1 mb-3">{{ $featuredTest->title }}</h2>
                        <p class="mb-4">{{ $featuredTest->description }}</p>
                        <div class="d-flex flex-wrap gap-3">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-question-circle me-1"></i>
                                {{ $featuredTest->total_questions }} Questions
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="far fa-clock me-1"></i>
                                {{ $featuredTest->total_time_minutes }} Minutes
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-star me-1"></i>
                                {{ $featuredTest->total_marks }} Marks
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('website.mcqs.mock-test-detail', $featuredTest->slug) }}" class="btn btn-light btn-lg">
                        Start Test <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Filters -->
        <div class="filter-card">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="mb-3">Test Types</h5>
                    <div>
                        <a href="{{ request()->fullUrlWithQuery(['test_type' => null]) }}" 
                           class="filter-tag {{ !request('test_type') ? 'active' : '' }}">
                            All Types
                        </a>
                        @foreach($testTypes as $type)
                        <a href="{{ request()->fullUrlWithQuery(['test_type' => $type->slug]) }}" 
                           class="filter-tag {{ request('test_type') == $type->slug ? 'active' : '' }}">
                            {{ $type->name }}
                            <span class="badge bg-light text-dark ms-1">{{ $type->mock_tests_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="mb-3">Test Mode</h5>
                    <div>
                        <a href="{{ request()->fullUrlWithQuery(['mode' => null]) }}" 
                           class="filter-tag {{ !request('mode') ? 'active' : '' }}">
                            All Modes
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['mode' => 'practice']) }}" 
                           class="filter-tag {{ request('mode') == 'practice' ? 'active' : '' }}">
                            Practice
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['mode' => 'timed']) }}" 
                           class="filter-tag {{ request('mode') == 'timed' ? 'active' : '' }}">
                            Timed
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['mode' => 'exam']) }}" 
                           class="filter-tag {{ request('mode') == 'exam' ? 'active' : '' }}">
                            Exam
                        </a>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="mb-3">Price</h5>
                    <div>
                        <a href="{{ request()->fullUrlWithQuery(['is_free' => null]) }}" 
                           class="filter-tag {{ !request()->has('is_free') ? 'active' : '' }}">
                            All Tests
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['is_free' => 1]) }}" 
                           class="filter-tag {{ request('is_free') == '1' ? 'active' : '' }}">
                            Free Only
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['is_free' => 0]) }}" 
                           class="filter-tag {{ request('is_free') == '0' ? 'active' : '' }}">
                            Premium
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mock Tests Grid -->
        <div class="row g-4">
            @forelse($mockTests as $test)
            <div class="col-lg-4 col-md-6">
                <div class="mock-test-card">
                    <!-- Test Type Badge -->
                    <span class="test-badge {{ $test->is_free ? 'free' : 'premium' }}">
                        {{ $test->is_free ? 'FREE' : 'PREMIUM' }}
                    </span>

                    <!-- Test Type -->
                    <div class="mb-3">
                        <span class="badge bg-light text-dark">
                            {{ $test->testType->name }}
                        </span>
                        <span class="mode-badge mode-{{ $test->test_mode }}">
                            {{ ucfirst($test->test_mode) }}
                        </span>
                    </div>

                    <!-- Test Title & Description -->
                    <h4 class="h5 mb-3">
                        <a href="{{ route('website.mcqs.mock-test-detail', $test->slug) }}" class="text-decoration-none text-dark">
                            {{ $test->title }}
                        </a>
                    </h4>
                    <p class="text-muted small mb-4">{{ Str::limit($test->description, 100) }}</p>

                    <!-- Test Stats -->
                    <div class="test-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $test->total_questions }}</span>
                            <span class="stat-label">Questions</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $test->total_time_minutes }}</span>
                            <span class="stat-label">Minutes</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $test->total_marks }}</span>
                            <span class="stat-label">Marks</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('website.mcqs.mock-test-detail', $test->slug) }}" class="btn btn-primary w-100 mt-4">
                        @if($test->is_free)
                        Start Test <i class="fas fa-play ms-2"></i>
                        @else
                        View Details <i class="fas fa-arrow-right ms-2"></i>
                        @endif
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No mock tests found</h4>
                <p class="text-muted">Try adjusting your filters</p>
                <a href="{{ route('website.mcqs.mock-tests') }}" class="btn btn-primary">
                    Clear Filters
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($mockTests->hasPages())
        <div class="mt-5">
            {{ $mockTests->links('pagination::bootstrap-5') }}
        </div>
        @endif

        <!-- Call to Action -->
        <div class="text-center mt-5 pt-5">
            <h3 class="h2 mb-4">Ready to test your skills?</h3>
            <p class="text-muted mb-4">Take a mock test and see where you stand</p>
            <a href="{{ route('website.mcqs.mock-tests') }}" class="btn btn-primary btn-lg">
                Browse All Tests <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endsection