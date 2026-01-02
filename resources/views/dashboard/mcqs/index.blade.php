<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <!-- Page Header -->
            <div class="page-header mb-4 px-3 px-md-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h3 mb-1 mb-md-2">MCQs Management</h1>
                        <p class="text-muted mb-0 d-none d-md-block">Create and manage multiple choice questions</p>
                        <p class="text-muted mb-0 d-block d-md-none">Manage questions</p>
                    </div>
                    <div class="w-100 w-md-auto">
                        <a href="{{ route('mcqs.create') }}" class="btn btn-primary w-100 w-md-auto d-flex align-items-center justify-content-center">
                            <i class="fas fa-plus me-2"></i> 
                            <span class="d-none d-sm-inline">Add MCQ</span>
                            <span class="d-inline d-sm-none">Add</span>
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mx-md-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div class="flex-grow-1">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-3 mx-md-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div class="flex-grow-1">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filters - Collapsible on Mobile -->
            <div class="card mb-4 mx-3 mx-md-0">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3 d-md-none" 
                     data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-filter me-2"></i>
                        <span>Filters</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
                
                <div class="collapse d-md-block" style="visibility: visible;" id="filterCollapse">
                    <div class="card-body">
                        <form action="{{ route('mcqs.index') }}" method="GET" class="row g-3">
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Subject</label>
                                <select name="subject_id" class="form-select form-select-sm">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Topic</label>
                                <select name="topic_id" class="form-select form-select-sm">
                                    <option value="">All Topics</option>
                                    @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                        {{ Str::limit($topic->title, 20) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Test Type</label>
                                <select name="test_type_id" class="form-select form-select-sm">
                                    <option value="">All Types</option>
                                    @foreach($testTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('test_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ Str::limit($type->name, 15) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Difficulty</label>
                                <select name="difficulty_level" class="form-select form-select-sm">
                                    <option value="">All Levels</option>
                                    <option value="easy" {{ request('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy</option>
                                    <option value="medium" {{ request('difficulty_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="hard" {{ request('difficulty_level') == 'hard' ? 'selected' : '' }}>Hard</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Type</label>
                                <select name="question_type" class="form-select form-select-sm">
                                    <option value="">All Types</option>
                                    <option value="single" {{ request('question_type') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="multiple" {{ request('question_type') == 'multiple' ? 'selected' : '' }}>Multiple</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Premium</label>
                                <select name="is_premium" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="1" {{ request('is_premium') == '1' ? 'selected' : '' }}>Premium</option>
                                    <option value="0" {{ request('is_premium') == '0' ? 'selected' : '' }}>Free</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Verified</label>
                                <select name="is_verified" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>Verified</option>
                                    <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>Unverified</option>
                                </select>
                            </div>
                            
                            <!-- Search Row -->
                            <div class="col-12 col-lg-8">
                                <label class="form-label small fw-bold">Search</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search questions..." 
                                           value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="button" onclick="this.closest('form').reset()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-12 col-lg-4 d-flex align-items-end">
                                <div class="d-flex w-100 gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-filter me-2"></i> 
                                        <span class="d-none d-md-inline">Apply Filters</span>
                                        <span class="d-inline d-md-none">Filter</span>
                                    </button>
                                    <a href="{{ route('mcqs.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center" style="width: 45px;">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Stats Cards - Mobile Optimized -->
            <div class="row mb-4 g-3 px-3 px-md-0">
                <div class="col-6 col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Total MCQs</h6>
                                    <h4 class="mb-0">{{ $mcqs->total() }}</h4>
                                    <small class="text-success d-none d-md-block">
                                        {{ \App\Models\Mcq::whereDate('created_at', today())->count() }} new today
                                    </small>
                                    <small class="text-success d-block d-md-none">
                                        +{{ \App\Models\Mcq::whereDate('created_at', today())->count() }} today
                                    </small>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-question-circle text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Published</h6>
                                    <h4 class="mb-0">{{ \App\Models\Mcq::where('status', 'published')->count() }}</h4>
                                    <small class="text-success d-none d-md-block">
                                        {{ \App\Models\Mcq::where('status', 'published')->where('is_verified', true)->count() }} verified
                                    </small>
                                    <small class="text-success d-block d-md-none">
                                        ✓{{ \App\Models\Mcq::where('status', 'published')->where('is_verified', true)->count() }}
                                    </small>
                                </div>
                                <div class="bg-success bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Premium</h6>
                                    <h4 class="mb-0">{{ \App\Models\Mcq::where('is_premium', true)->count() }}</h4>
                                    <small class="text-warning d-none d-md-block">
                                        {{ \App\Models\Mcq::where('is_premium', true)->where('status', 'published')->count() }} published
                                    </small>
                                    <small class="text-warning d-block d-md-none">
                                        ⭐{{ \App\Models\Mcq::where('is_premium', true)->where('status', 'published')->count() }}
                                    </small>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-crown text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Needs Review</h6>
                                    <h4 class="mb-0">{{ \App\Models\Mcq::where('is_verified', false)->count() }}</h4>
                                    <small class="text-danger d-none d-md-block">
                                        {{ \App\Models\Mcq::where('is_verified', false)->where('status', 'published')->count() }} published
                                    </small>
                                    <small class="text-danger d-block d-md-none">
                                        ⚠{{ \App\Models\Mcq::where('is_verified', false)->where('status', 'published')->count() }}
                                    </small>
                                </div>
                                <div class="bg-danger bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-exclamation-circle text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions - Responsive -->
            <div class="card mb-3 mx-3 mx-md-0 d-none" id="bulkActionsCard">
                <div class="card-body p-3">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                        <span class="me-md-3 mb-2 mb-md-0 text-center text-md-start" id="selectedCount">0 MCQs selected</span>
                        <div class="d-flex flex-column flex-md-row align-items-center gap-2 w-100 w-md-auto">
                            <select class="form-select form-select-sm me-md-2 flex-grow-1" id="bulkActionSelect">
                                <option value="">Bulk Actions</option>
                                <option value="publish">Publish</option>
                                <option value="draft">Move to Draft</option>
                                <option value="archive">Archive</option>
                                <option value="verify">Verify</option>
                                <option value="unverify">Unverify</option>
                                <option value="delete">Delete</option>
                            </select>
                            <div class="d-flex gap-2 mt-2 mt-md-0">
                                <button class="btn btn-sm btn-primary flex-grow-1" id="applyBulkAction">
                                    <span class="d-none d-md-inline">Apply</span>
                                    <i class="fas fa-check d-inline d-md-none"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" id="clearSelection">
                                    <span class="d-none d-md-inline">Clear</span>
                                    <i class="fas fa-times d-inline d-md-none"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MCQs Table - Responsive -->
            <div class="card mx-3 mx-md-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0" id="mcqsTable">
                            <thead class="d-none d-md-table-header-group">
                                <tr>
                                    <th width="40">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th width="40">#</th>
                                    <th>Question</th>
                                    <th>Subject/Topic</th>
                                    <th>Type/Level</th>
                                    <th width="70">Marks</th>
                                    <th width="100">Status</th>
                                    <th width="150" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mcqs as $mcq)
                                <tr data-id="{{ $mcq->id }}" class="d-none d-md-table-row {{ $mcq->status == 'draft' ? 'table-warning' : ($mcq->status == 'archived' ? 'table-secondary' : '') }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input mcq-checkbox" value="{{ $mcq->id }}">
                                    </td>
                                    <td>{{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="question-preview">
                                            <div class="fw-bold mb-1">
                                                {!! Str::limit(strip_tags($mcq->question), 60) !!}
                                            </div>
                                            <div class="small text-muted">
                                                <span class="badge bg-{{ $mcq->question_type == 'single' ? 'primary' : 'info' }}">
                                                    {{ $mcq->question_type == 'single' ? 'Single' : 'Multiple' }}
                                                </span>
                                                @if($mcq->is_premium)
                                                <span class="badge bg-warning ms-1">Premium</span>
                                                @endif
                                                @if($mcq->is_verified)
                                                <span class="badge bg-success ms-1">✓</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="fw-bold">{{ $mcq->subject->name ?? 'N/A' }}</div>
                                            <div class="text-muted">{{ Str::limit($mcq->topic->title ?? 'N/A', 20) }}</div>
                                            @if($mcq->testType)
                                            <div class="text-muted">
                                                <i class="fas fa-tag fa-xs me-1"></i>{{ Str::limit($mcq->testType->name, 15) }}
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>
                                                <span class="badge bg-{{ $mcq->difficulty_level == 'easy' ? 'success' : ($mcq->difficulty_level == 'medium' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($mcq->difficulty_level) }}
                                                </span>
                                            </div>
                                            @if($mcq->time_limit_seconds)
                                            <div class="text-muted mt-1">
                                                <i class="fas fa-clock fa-xs me-1"></i>{{ $mcq->time_limit_seconds }}s
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="fw-bold">{{ $mcq->marks }}</span>
                                            @if($mcq->negative_marks > 0)
                                            <div class="small text-danger">-{{ $mcq->negative_marks }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $mcq->status == 'published' ? 'success' : ($mcq->status == 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($mcq->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('mcqs.show', $mcq) }}" 
                                               class="btn btn-sm btn-outline-info"
                                               data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('mcqs.edit', $mcq) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!$mcq->is_verified)
                                            <form action="{{ route('mcqs.verify', $mcq) }}" 
                                                  method="POST" class="d-inline"
                                                  data-bs-toggle="tooltip" title="Verify">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{ route('mcqs.unverify', $mcq) }}" 
                                                  method="POST" class="d-inline"
                                                  data-bs-toggle="tooltip" title="Unverify">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif
                                            @if($mcq->mock_tests_count == 0)
                                            <form action="{{ route('mcqs.destroy', $mcq) }}" 
                                                  method="POST" class="d-inline"
                                                  data-bs-toggle="tooltip" title="Delete">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this MCQ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete MCQ used in mock tests">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Mobile View Card -->
                                <div class="card mb-3 d-block d-md-none mx-3 {{ $mcq->status == 'draft' ? 'border-warning' : ($mcq->status == 'archived' ? 'border-secondary' : '') }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input me-2 mcq-checkbox" value="{{ $mcq->id }}">
                                                <strong class="me-2">#{{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + $loop->iteration }}</strong>
                                                <span class="badge bg-{{ $mcq->status == 'published' ? 'success' : ($mcq->status == 'draft' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($mcq->status) }}
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-{{ $mcq->difficulty_level == 'easy' ? 'success' : ($mcq->difficulty_level == 'medium' ? 'warning' : 'danger') }} me-1">
                                                    {{ ucfirst($mcq->difficulty_level) }}
                                                </span>
                                                <span class="badge bg-{{ $mcq->question_type == 'single' ? 'primary' : 'info' }}">
                                                    {{ $mcq->question_type == 'single' ? 'S' : 'M' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="fw-bold mb-1">
                                                {!! Str::limit(strip_tags($mcq->question), 80) !!}
                                            </div>
                                            <div class="small text-muted">
                                                <div>
                                                    <i class="fas fa-book me-1"></i>
                                                    {{ $mcq->subject->name ?? 'N/A' }}
                                                </div>
                                                @if($mcq->topic)
                                                <div>
                                                    <i class="fas fa-folder me-1"></i>
                                                    {{ Str::limit($mcq->topic->title, 30) }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex flex-wrap gap-1">
                                                @if($mcq->is_premium)
                                                <span class="badge bg-warning">Premium</span>
                                                @endif
                                                @if($mcq->is_verified)
                                                <span class="badge bg-success">Verified</span>
                                                @endif
                                                @if($mcq->time_limit_seconds)
                                                <span class="badge bg-info">
                                                    <i class="fas fa-clock me-1"></i>{{ $mcq->time_limit_seconds }}s
                                                </span>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold">{{ $mcq->marks }} marks</div>
                                                @if($mcq->negative_marks > 0)
                                                <div class="small text-danger">-{{ $mcq->negative_marks }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('mcqs.show', $mcq) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('mcqs.edit', $mcq) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                            <div class="btn-group" role="group">
                                                @if(!$mcq->is_verified)
                                                <form action="{{ route('mcqs.verify', $mcq) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                @else
                                                <form action="{{ route('mcqs.unverify', $mcq) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                @if($mcq->mock_tests_count == 0)
                                                <form action="{{ route('mcqs.destroy', $mcq) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Delete this MCQ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Cannot delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                        
                        @if($mcqs->count() == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No MCQs found</h5>
                            <p class="text-muted">Try adjusting your filters or add a new MCQ</p>
                            <a href="{{ route('mcqs.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-2"></i> Add MCQ
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Pagination - Responsive -->
                    @if($mcqs->hasPages())
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top">
                        <div class="text-muted small mb-2 mb-md-0 text-center text-md-start">
                            Showing {{ $mcqs->firstItem() }} to {{ $mcqs->lastItem() }} of {{ $mcqs->total() }}
                        </div>
                        <nav>
                            {{ $mcqs->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
            
            // Bulk selection functionality
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.mcq-checkbox');
            const bulkActionsCard = document.getElementById('bulkActionsCard');
            const selectedCount = document.getElementById('selectedCount');
            const clearSelectionBtn = document.getElementById('clearSelection');
            
            // Select all checkbox
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActions();
            });
            
            // Individual checkbox change
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });
            
            // Clear selection
            clearSelectionBtn.addEventListener('click', function() {
                checkboxes.forEach(checkbox => checkbox.checked = false);
                if (selectAll) selectAll.checked = false;
                updateBulkActions();
            });
            
            // Apply bulk action
            document.getElementById('applyBulkAction').addEventListener('click', function() {
                const action = document.getElementById('bulkActionSelect').value;
                const selectedIds = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                
                if (!action) {
                    alert('Please select an action');
                    return;
                }
                
                if (selectedIds.length === 0) {
                    alert('Please select at least one MCQ');
                    return;
                }
                
                if (action === 'delete') {
                    if (!confirm(`Are you sure you want to delete ${selectedIds.length} MCQ(s)?`)) {
                        return;
                    }
                }
                
                // Submit bulk action
                fetch('{{ route("mcqs.bulk.action") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        action: action,
                        ids: selectedIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            
            function updateBulkActions() {
                const selected = Array.from(checkboxes).filter(cb => cb.checked).length;
                
                if (selected > 0) {
                    if (bulkActionsCard) {
                        bulkActionsCard.classList.remove('d-none');
                        selectedCount.textContent = `${selected} MCQ${selected === 1 ? '' : 's'} selected`;
                    }
                } else {
                    if (bulkActionsCard) {
                        bulkActionsCard.classList.add('d-none');
                        if (selectAll) selectAll.checked = false;
                    }
                }
            }
            
            // Filter collapse icon toggle
            const filterCollapse = document.getElementById('filterCollapse');
            if (filterCollapse) {
                filterCollapse.addEventListener('show.bs.collapse', function() {
                    this.previousElementSibling.querySelector('.fa-chevron-down').className = 'fas fa-chevron-up';
                });
                filterCollapse.addEventListener('hide.bs.collapse', function() {
                    this.previousElementSibling.querySelector('.fa-chevron-up').className = 'fas fa-chevron-down';
                });
            }
            
            // Mobile responsive table initialization
            function initMobileTable() {
                if (window.innerWidth < 768) {
                    // Hide desktop table, show mobile cards
                    document.querySelectorAll('#mcqsTable .d-none.d-md-table-row').forEach(row => {
                        row.style.display = 'none';
                    });
                } else {
                    // Show desktop table, hide mobile cards
                    document.querySelectorAll('#mcqsTable .d-block.d-md-none').forEach(card => {
                        card.style.display = 'none';
                    });
                }
            }
            
            // Initialize and add resize listener
            initMobileTable();
            window.addEventListener('resize', initMobileTable);
            
            // Filter form reset
            document.querySelectorAll('button[type="button"]').forEach(button => {
                if (button.textContent.includes('fa-times') && button.closest('.input-group')) {
                    button.addEventListener('click', function() {
                        const form = this.closest('form');
                        form.reset();
                        form.submit();
                    });
                }
            });
        });
    </script>
    @endpush

    <style>
        /* Responsive Container */
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }
        
        @media (min-width: 768px) {
            .container-fluid {
                padding-left: var(--bs-gutter-x, 0.75rem);
                padding-right: var(--bs-gutter-x, 0.75rem);
            }
        }
        
        /* Card Hover Effect */
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        /* Question Preview */
        .question-preview {
            max-width: 100%;
        }
        
        @media (min-width: 768px) {
            .question-preview {
                max-width: 400px;
            }
        }
        
        /* Table Responsiveness */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        @media (max-width: 767.98px) {
            .table-responsive {
                border: 0;
                background: transparent;
            }
            
            #mcqsTable {
                min-width: auto;
            }
            
            .card.mb-3.d-block.d-md-none {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            .card.mb-3.d-block.d-md-none:first-child {
                margin-top: 1rem;
            }
        }
        
        /* Badge Adjustments */
        .badge {
            font-size: 0.75em;
        }
        
        @media (max-width: 767.98px) {
            .badge {
                font-size: 0.7em;
                padding: 0.25em 0.5em;
            }
        }
        
        /* Button Group Responsiveness */
        @media (max-width: 575.98px) {
            .btn-group .btn {
                padding: 0.25rem 0.4rem;
                font-size: 0.75rem;
            }
            
            .btn-group .btn i {
                margin: 0;
            }
        }
        
        /* Filter Collapse */
        .card-header[data-bs-toggle="collapse"] {
            cursor: pointer;
            user-select: none;
        }
        
        .card-header[data-bs-toggle="collapse"] .fa-chevron-down,
        .card-header[data-bs-toggle="collapse"] .fa-chevron-up {
            transition: transform 0.3s ease;
        }
        
        /* Pagination for Mobile */
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .pagination .page-item {
            margin: 0.125rem;
        }
        
        /* Status Colors */
        .table-warning {
            background-color: rgba(255, 243, 205, 0.3) !important;
        }
        
        .table-secondary {
            background-color: rgba(108, 117, 125, 0.1) !important;
        }
        
        .border-warning {
            border-color: #ffc107 !important;
        }
        
        .border-secondary {
            border-color: #6c757d !important;
        }
        
        /* Form Controls */
        @media (max-width: 767.98px) {
            .form-select-sm {
                font-size: 0.875rem;
                padding: 0.25rem 0.5rem;
            }
            
            .input-group-sm > .form-control,
            .input-group-sm > .form-select {
                font-size: 0.875rem;
            }
        }
        
        /* Action Buttons */
        .btn-group .form {
            display: inline;
        }
        
        /* Mobile Card Layout */
        @media (max-width: 767.98px) {
            .card.mb-3.d-block.d-md-none .card-body {
                padding: 1rem;
            }
            
            .card.mb-3.d-block.d-md-none .btn-group {
                flex-wrap: nowrap;
            }
        }
        
        /* Ensure proper spacing on mobile */
        @media (max-width: 767.98px) {
            .main-content {
                padding-top: 0.5rem;
            }
            
            .page-header,
            .alert,
            .card.mb-4 {
                margin-left: 0.75rem;
                margin-right: 0.75rem;
            }
        }
        
        /* Stats Cards on Mobile */
        @media (max-width: 767.98px) {
            .col-6 {
                padding-left: 0.375rem;
                padding-right: 0.375rem;
            }
            
            .card-hover .card-body {
                padding: 0.75rem !important;
            }
            
            .card-hover h4 {
                font-size: 1.25rem;
            }
        }
    </style>
</x-app-layout>