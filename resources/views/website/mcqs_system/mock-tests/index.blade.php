@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* Mock Tests Page Styles */
    .mock-tests-hero {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        padding: 80px 0;
        text-align: center;
    }
    
    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .mock-test-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        border: 1px solid #eaeaea;
        height: 100%;
    }
    
    .mock-test-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-color: #4361ee;
    }
    
    .test-type-badge {
        background: #e9ecef;
        color: #495057;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    
    .test-mode-badge {
        background: #4361ee;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.75rem;
    }
    
    .free-badge {
        background: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.75rem;
    }
    
    .premium-badge {
        background: #ffc107;
        color: #212529;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.75rem;
    }
    
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: #4361ee;
    }
    
    .test-requirements {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- Hero Section -->
<section class="mock-tests-hero">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">Practice Mock Tests</h1>
        <p class="lead mb-4">Take comprehensive mock tests to evaluate your preparation level</p>
        
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <form action="{{ route('website.mcqs.mock-tests') }}" method="GET" class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control form-control-lg" 
                                   placeholder="Search mock tests..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="test_type" class="form-select form-select-lg">
                            <option value="">All Test Types</option>
                            @foreach($testTypes as $type)
                                <option value="{{ $type->slug }}" {{ request('test_type') == $type->slug ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-light btn-lg w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
@if(Auth::check() && $userStats)
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-4">Your Test Statistics</h4>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number">{{ $userStats['total_attempts'] }}</div>
                            <p class="text-muted mb-0">Total Attempts</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number">{{ $userStats['total_passed'] }}</div>
                            <p class="text-muted mb-0">Tests Passed</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number">{{ number_format($userStats['average_score'], 1) }}%</div>
                            <p class="text-muted mb-0">Average Score</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number">{{ $mockTests->total() }}</div>
                            <p class="text-muted mb-0">Available Tests</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                <div class="filter-card">
                    <h5 class="mb-4">Filter Tests</h5>
                    
                    <div class="mb-4">
                        <h6 class="mb-3">Test Type</h6>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('website.mcqs.mock-tests') }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ !request('test_type') ? 'active' : '' }}">
                                All Types
                                <span class="badge bg-primary rounded-pill">{{ \App\Models\MockTest::where('status', 'published')->count() }}</span>
                            </a>
                            @foreach($testTypes as $type)
                            <a href="{{ route('website.mcqs.mock-tests', ['test_type' => $type->slug]) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request('test_type') == $type->slug ? 'active' : '' }}">
                                {{ $type->name }}
                                <span class="badge bg-primary rounded-pill">{{ $type->mock_tests_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="mb-3">Test Mode</h6>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('website.mcqs.mock-tests', array_merge(request()->all(), ['mode' => ''])) }}" 
                               class="list-group-item list-group-item-action {{ !request('mode') ? 'active' : '' }}">
                                All Modes
                            </a>
                            <a href="{{ route('website.mcqs.mock-tests', array_merge(request()->all(), ['mode' => 'practice'])) }}" 
                               class="list-group-item list-group-item-action {{ request('mode') == 'practice' ? 'active' : '' }}">
                                Practice
                            </a>
                            <a href="{{ route('website.mcqs.mock-tests', array_merge(request()->all(), ['mode' => 'timed'])) }}" 
                               class="list-group-item list-group-item-action {{ request('mode') == 'timed' ? 'active' : '' }}">
                                Timed
                            </a>
                            <a href="{{ route('website.mcqs.mock-tests', array_merge(request()->all(), ['mode' => 'exam'])) }}" 
                               class="list-group-item list-group-item-action {{ request('mode') == 'exam' ? 'active' : '' }}">
                                Exam
                            </a>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="mb-3">Access Type</h6>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('website.mcqs.mock-tests', array_merge(request()->all(), ['is_free' => ''])) }}" 
                               class="list-group-item list-group-item-action {{ !request()->has('is_free') ? 'active' : '' }}">
                                All Tests
                            </a>
                            <a href="{{ route('website.mcqs.mock-tests', array_merge(request()->all(), ['is_free' => '1'])) }}" 
                               class="list-group-item list-group-item-action {{ request('is_free') == '1' ? 'active' : '' }}">
                                Free Tests
                            </a>
                            <a href="{{ route('website.mcqs.mock-tests', array_merge(request()->all(), ['is_free' => '0'])) }}" 
                               class="list-group-item list-group-item-action {{ request('is_free') == '0' ? 'active' : '' }}">
                                Premium Tests
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="filter-card">
                    <h5 class="mb-4">Quick Stats</h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="fs-4 fw-bold text-primary">{{ \App\Models\MockTest::where('status', 'published')->count() }}</div>
                                <small class="text-muted">Total Tests</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="fs-4 fw-bold text-success">{{ \App\Models\MockTest::where('status', 'published')->where('is_free', true)->count() }}</div>
                                <small class="text-muted">Free Tests</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tests Grid -->
            <div class="col-lg-9">
                @if($mockTests->count() > 0)
                    <div class="row g-4">
                        @foreach($mockTests as $test)
                        <div class="col-md-6">
                            <div class="mock-test-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="test-type-badge">{{ $test->testType->name }}</span>
                                    @if($test->is_free)
                                        <span class="free-badge">Free</span>
                                    @else
                                        <span class="premium-badge">Premium</span>
                                    @endif
                                </div>
                                
                                <h4 class="h5 mb-3">
                                    <a href="{{ route('website.mcqs.mock-test-detail', $test->slug) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ $test->title }}
                                    </a>
                                </h4>
                                
                                <p class="text-muted mb-4">{{ Str::limit($test->description, 100) }}</p>
                                
                                <div class="test-requirements">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Questions</small>
                                            <strong>{{ $test->total_questions }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Time</small>
                                            <strong>{{ floor($test->total_time_minutes / 60) }}h {{ $test->total_time_minutes % 60 }}m</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Marks</small>
                                            <strong>{{ $test->total_marks }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Passing</small>
                                            <strong>{{ $test->passing_marks }}%</strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <span class="test-mode-badge">{{ ucfirst($test->test_mode) }}</span>
                                    <a href="{{ route('website.mcqs.mock-test-detail', $test->slug) }}" 
                                       class="btn btn-primary btn-sm">
                                        View Details <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-5">
                        {{ $mockTests->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list fa-4x text-muted mb-4"></i>
                            <h4>No mock tests found</h4>
                            <p class="text-muted">Try adjusting your filters or check back later for new tests.</p>
                            <a href="{{ route('website.mcqs.mock-tests') }}" class="btn btn-primary">
                                Clear Filters
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Quick filter functionality
    document.querySelectorAll('.list-group-item-action').forEach(item => {
        item.addEventListener('click', function(e) {
            if (this.getAttribute('href') === '#') {
                e.preventDefault();
            }
        });
    });
</script>
@endpush