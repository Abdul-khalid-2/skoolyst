<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <!-- Page Header -->
            <div class="page-header mb-4 px-3 px-md-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h3 mb-1 mb-md-2">Edit Mock Test</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('mock-tests.index') }}">Mock Tests</a></li>
                                <li class="breadcrumb-item active">Edit {{ Str::limit($mockTest->title, 20) }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-success d-flex align-items-center">
                            <i class="fas fa-question me-1 me-md-2"></i> 
                            <span class="d-none d-sm-inline">Manage Questions</span>
                            <span class="d-inline d-sm-none">Questions</span>
                        </a>
                        <a href="{{ route('mock-tests.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                            <i class="fas fa-arrow-left me-1 me-md-2"></i> 
                            <span class="d-none d-md-inline">Back</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Test Stats - Mobile Optimized -->
            <div class="row mb-4 g-3 px-3 px-md-0">
                <div class="col-6 col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Questions</h6>
                                    <h4 class="mb-0">{{ $mockTest->questions_count }}</h4>
                                    <small class="text-muted d-block">
                                        of {{ $mockTest->total_questions }}
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
                                    <h6 class="text-muted mb-1 small">Marks</h6>
                                    <h4 class="mb-0">{{ $mockTest->questions->sum('marks') }}</h4>
                                    <small class="text-muted d-block">
                                        of {{ $mockTest->total_marks }}
                                    </small>
                                </div>
                                <div class="bg-success bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-star text-success"></i>
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
                                    <h6 class="text-muted mb-1 small">Attempts</h6>
                                    <h4 class="mb-0">{{ $mockTest->attempts_count }}</h4>
                                    <small class="text-success d-block">
                                        {{ $mockTest->attempts()->where('result_status', 'passed')->count() }} passed
                                    </small>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-users text-warning"></i>
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
                                    <h6 class="text-muted mb-1 small">Avg. Score</h6>
                                    <h4 class="mb-0">
                                        {{ $mockTest->attempts_count > 0 ? round($mockTest->attempts()->avg('percentage'), 1) : 0 }}%
                                    </h4>
                                    <small class="text-muted d-block">Average</small>
                                </div>
                                <div class="bg-info bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-chart-line text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="row">
                <!-- Form Section -->
                <div class="col-lg-8">
                    <div class="card mx-3 mx-md-0 mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Edit Test: {{ Str::limit($mockTest->title, 30) }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('mock-tests.update', $mockTest) }}" method="POST" id="mockTestForm">
                                @csrf
                                @method('PUT')
                                
                                <!-- Basic Information -->
                                <div class="row g-3 mb-4">
                                    <div class="col-12 col-md-8">
                                        <label for="title" class="form-label">Test Title *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title', $mockTest->title) }}" 
                                               placeholder="e.g., Mathematics Practice Test" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 col-md-4">
                                        <label for="test_type_id" class="form-label">Test Type *</label>
                                        <select class="form-select @error('test_type_id') is-invalid @enderror" 
                                                id="test_type_id" name="test_type_id" required>
                                            <option value="">Select Type</option>
                                            @foreach($testTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('test_type_id', $mockTest->test_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('test_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 col-md-4">
                                        <label for="total_questions" class="form-label">Total Questions *</label>
                                        <input type="number" class="form-control @error('total_questions') is-invalid @enderror" 
                                               id="total_questions" name="total_questions" 
                                               value="{{ old('total_questions', $mockTest->total_questions) }}" min="1" required>
                                        @error('total_questions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 col-md-4">
                                        <label for="total_marks" class="form-label">Total Marks *</label>
                                        <input type="number" class="form-control @error('total_marks') is-invalid @enderror" 
                                               id="total_marks" name="total_marks" 
                                               value="{{ old('total_marks', $mockTest->total_marks) }}" min="1" required>
                                        @error('total_marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 col-md-4">
                                        <label for="total_time_minutes" class="form-label">Time (minutes) *</label>
                                        <input type="number" class="form-control @error('total_time_minutes') is-invalid @enderror" 
                                               id="total_time_minutes" name="total_time_minutes" 
                                               value="{{ old('total_time_minutes', $mockTest->total_time_minutes) }}" min="1" required>
                                        @error('total_time_minutes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <label for="passing_marks" class="form-label">Passing Marks *</label>
                                        <input type="number" class="form-control @error('passing_marks') is-invalid @enderror" 
                                               id="passing_marks" name="passing_marks" 
                                               value="{{ old('passing_marks', $mockTest->passing_marks) }}" min="0" required>
                                        @error('passing_marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <label for="test_mode" class="form-label">Test Mode *</label>
                                        <select class="form-select @error('test_mode') is-invalid @enderror" 
                                                id="test_mode" name="test_mode" required>
                                            <option value="">Select Mode</option>
                                            <option value="practice" {{ old('test_mode', $mockTest->test_mode) == 'practice' ? 'selected' : '' }}>Practice</option>
                                            <option value="timed" {{ old('test_mode', $mockTest->test_mode) == 'timed' ? 'selected' : '' }}>Timed</option>
                                            <option value="exam" {{ old('test_mode', $mockTest->test_mode) == 'exam' ? 'selected' : '' }}>Exam</option>
                                        </select>
                                        @error('test_mode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="draft" {{ old('status', $mockTest->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published" {{ old('status', $mockTest->status) == 'published' ? 'selected' : '' }}>Published</option>
                                            <option value="archived" {{ old('status', $mockTest->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Description & Instructions -->
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="3">{{ old('description', $mockTest->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="instructions" class="form-label">Instructions</label>
                                        <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                                  id="instructions" name="instructions" rows="4">{{ old('instructions', $mockTest->instructions) }}</textarea>
                                        @error('instructions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Test Settings -->
                                <div class="row g-3 mb-4">
                                    <div class="col-12 col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="shuffle_questions" name="shuffle_questions" value="1"
                                                   {{ old('shuffle_questions', $mockTest->shuffle_questions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="shuffle_questions">
                                                Shuffle Questions
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="show_result_immediately" name="show_result_immediately" value="1"
                                                   {{ old('show_result_immediately', $mockTest->show_result_immediately) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_result_immediately">
                                                Show Result Immediately
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="allow_retake" name="allow_retake" value="1"
                                                   {{ old('allow_retake', $mockTest->allow_retake) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="allow_retake">
                                                Allow Retake
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <label for="max_attempts" class="form-label">Max Attempts</label>
                                        <input type="number" class="form-control @error('max_attempts') is-invalid @enderror" 
                                               id="max_attempts" name="max_attempts" 
                                               value="{{ old('max_attempts', $mockTest->max_attempts) }}" min="1">
                                        @error('max_attempts')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Pricing -->
                                <div class="row g-3 mb-4">
                                    <div class="col-12 col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="is_free" name="is_free" value="1"
                                                   {{ old('is_free', $mockTest->is_free) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_free">
                                                Free Test
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6" id="price-field" style="{{ $mockTest->is_free ? 'display: none;' : '' }}">
                                        <label for="price" class="form-label">Price ({{ config('app.currency', 'PKR') }})</label>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" step="0.01"
                                               value="{{ old('price', $mockTest->price) }}" min="0">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Submit -->
                                <div class="mt-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                                        <i class="fas fa-save me-2"></i> 
                                        <span class="d-none d-sm-inline">Update Test</span>
                                        <span class="d-inline d-sm-none">Update</span>
                                    </button>
                                    <a href="{{ route('mock-tests.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Questions List -->
                    <div class="card mx-3 mx-md-0 mt-4">
                        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h5 class="mb-2 mb-md-0">Test Questions ({{ $mockTest->questions->count() }})</h5>
                            <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-sm btn-success d-flex align-items-center">
                                <i class="fas fa-plus me-1"></i> 
                                <span class="d-none d-sm-inline">Add Questions</span>
                                <span class="d-inline d-sm-none">Add</span>
                            </a>
                        </div>
                        <div class="card-body">
                            @if($mockTest->questions->count() > 0)
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th width="80">#</th>
                                            <th>Question</th>
                                            <th>Subject/Topic</th>
                                            <th width="100">Difficulty</th>
                                            <th width="120">Marks</th>
                                            <th width="80">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sortable-questions">
                                        @foreach($mockTest->questions->sortBy('question_number') as $question)
                                        <tr data-id="{{ $question->id }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-light text-dark me-2 question-number">
                                                        {{ $question->question_number }}
                                                    </span>
                                                    <button type="button" class="btn btn-sm btn-link handle">
                                                        <i class="fas fa-arrows-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="question-preview">
                                                    {!! Str::limit(strip_tags($question->mcq->question), 50) !!}
                                                </div>
                                                <div class="small text-muted mt-1">
                                                    <span class="badge bg-{{ $question->mcq->question_type == 'single' ? 'primary' : 'info' }}">
                                                        {{ $question->mcq->question_type == 'single' ? 'Single' : 'Multiple' }}
                                                    </span>
                                                    @if($question->mcq->is_premium)
                                                    <span class="badge bg-warning ms-1">Premium</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <div class="fw-bold">{{ Str::limit($question->mcq->subject->name ?? 'N/A', 15) }}</div>
                                                    <div class="text-muted">{{ Str::limit($question->mcq->topic->title ?? 'N/A', 15) }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $question->mcq->difficulty_badge_variant }}">
                                                    {{ $question->mcq->difficulty_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" class="form-control marks-input" 
                                                           value="{{ $question->marks }}" min="1" 
                                                           data-id="{{ $question->id }}">
                                                    <span class="input-group-text">M</span>
                                                </div>
                                                @if($question->negative_marks > 0)
                                                <small class="text-danger d-block mt-1">-{{ $question->negative_marks }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-question"
                                                        data-id="{{ $question->id }}"
                                                        data-mcq-id="{{ $question->mcq_id }}"
                                                        data-title="{{ Str::limit(strip_tags($question->mcq->question), 50) }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Mobile Questions List -->
                            <div class="d-block d-md-none">
                                <div class="list-group" id="mobile-sortable-questions">
                                    @foreach($mockTest->questions->sortBy('question_number') as $question)
                                    <div class="list-group-item mb-3 border rounded" data-id="{{ $question->id }}">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <button type="button" class="btn btn-sm btn-link handle me-2">
                                                    <i class="fas fa-arrows-alt"></i>
                                                </button>
                                                <span class="badge bg-light text-dark question-number">
                                                    #{{ $question->question_number }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="badge bg-{{ $question->mcq->question_type == 'single' ? 'primary' : 'info' }} me-1">
                                                    {{ $question->mcq->question_type == 'single' ? 'S' : 'M' }}
                                                </span>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-question"
                                                        data-id="{{ $question->id }}"
                                                        data-mcq-id="{{ $question->mcq_id }}"
                                                        data-title="{{ Str::limit(strip_tags($question->mcq->question), 50) }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="small text-muted mb-1">
                                                {!! Str::limit(strip_tags($question->mcq->question), 80) !!}
                                            </div>
                                            <div class="small">
                                                <span class="badge bg-{{ $question->mcq->difficulty_badge_variant }}">
                                                    {{ $question->mcq->difficulty_label }}
                                                </span>
                                                @if($question->mcq->is_premium)
                                                <span class="badge bg-warning ms-1">Premium</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="small text-muted">Subject</div>
                                                <div class="small fw-bold">{{ $question->mcq->subject->name ?? 'N/A' }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="small text-muted">Topic</div>
                                                <div class="small fw-bold">{{ Str::limit($question->mcq->topic->title ?? 'N/A', 15) }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="small text-muted">Marks</div>
                                                <div class="input-group input-group-sm mt-1">
                                                    <input type="number" class="form-control marks-input" 
                                                           value="{{ $question->marks }}" min="1" 
                                                           data-id="{{ $question->id }}">
                                                    <span class="input-group-text">M</span>
                                                </div>
                                                @if($question->negative_marks > 0)
                                                <small class="text-danger d-block mt-1">-{{ $question->negative_marks }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No questions added yet</p>
                                <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-primary d-flex align-items-center justify-content-center mx-auto" style="max-width: 200px;">
                                    <i class="fas fa-plus me-2"></i> Add Questions
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <!-- Test Info -->
                    <div class="card mb-4 mx-3 mx-md-0">
                        <div class="card-header">
                            <h5 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>Test Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="small">Created</span>
                                    <span class="small">{{ $mockTest->created_at->format('M d, Y') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="small">Created By</span>
                                    <span class="small">{{ $mockTest->createdBy->name ?? 'System' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="small">Last Updated</span>
                                    <span class="small">{{ $mockTest->updated_at->format('M d, Y') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="small">Slug</span>
                                    <span class="small"><code>{{ Str::limit($mockTest->slug, 15) }}</code></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="small">Test Mode</span>
                                    <span class="badge bg-{{ $mockTest->test_mode == 'exam' ? 'danger' : ($mockTest->test_mode == 'timed' ? 'warning' : 'primary') }}">
                                        {{ ucfirst($mockTest->test_mode) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="small">Access</span>
                                    <span class="badge bg-{{ $mockTest->is_free ? 'success' : 'warning' }}">
                                        {{ $mockTest->is_free ? 'Free' : 'Premium' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Subject Breakdown -->
                    @if($mockTest->subject_breakdown)
                    <div class="card mb-4 mx-3 mx-md-0">
                        <div class="card-header">
                            <h5 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-chart-pie me-2"></i>Subject Breakdown
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                            $breakdown = json_decode($mockTest->subject_breakdown, true);
                            @endphp
                            <div class="list-group list-group-flush">
                                @foreach($breakdown as $item)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="small">
                                        @php
                                        $subject = \App\Models\Subject::find($item['subject_id']);
                                        @endphp
                                        {{ Str::limit($subject->name ?? 'Unknown Subject', 20) }}
                                    </span>
                                    <span class="badge bg-primary">{{ $item['question_count'] }} Qs</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Quick Actions -->
                    <div class="card mx-3 mx-md-0">
                        <div class="card-header">
                            <h5 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-bolt me-2"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-success d-flex align-items-center justify-content-center">
                                    <i class="fas fa-plus me-2"></i> 
                                    <span>Add Questions</span>
                                </a>
                                <a href="{{ route('mock-tests.show', $mockTest) }}" class="btn btn-outline-info d-flex align-items-center justify-content-center">
                                    <i class="fas fa-eye me-2"></i> 
                                    <span>View Details</span>
                                </a>
                                <a href="{{ route('mock-tests.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center">
                                    <i class="fas fa-list me-2"></i> 
                                    <span>All Tests</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isFreeCheckbox = document.getElementById('is_free');
            const priceField = document.getElementById('price-field');
            
            // Toggle price field
            isFreeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    priceField.style.display = 'none';
                    document.getElementById('price').value = '';
                } else {
                    priceField.style.display = 'block';
                }
            });
            
            // Initialize Sortable for questions (Desktop)
            const sortableContainer = document.getElementById('sortable-questions');
            if (sortableContainer) {
                new Sortable(sortableContainer, {
                    animation: 150,
                    handle: '.handle',
                    onEnd: function(evt) {
                        updateQuestionNumbers();
                    }
                });
            }
            
            // Initialize Sortable for questions (Mobile)
            const mobileSortableContainer = document.getElementById('mobile-sortable-questions');
            if (mobileSortableContainer) {
                new Sortable(mobileSortableContainer, {
                    animation: 150,
                    handle: '.handle',
                    onEnd: function(evt) {
                        updateMobileQuestionNumbers();
                    }
                });
            }
            
            // Update question numbers (Desktop)
            function updateQuestionNumbers() {
                const rows = sortableContainer.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    const questionNumber = index + 1;
                    row.querySelector('.question-number').textContent = questionNumber;
                    
                    // Update in database
                    const questionId = row.dataset.id;
                    updateQuestionNumberInDatabase(questionId, questionNumber);
                });
            }
            
            // Update question numbers (Mobile)
            function updateMobileQuestionNumbers() {
                const items = mobileSortableContainer.querySelectorAll('.list-group-item');
                items.forEach((item, index) => {
                    const questionNumber = index + 1;
                    item.querySelector('.question-number').textContent = `#${questionNumber}`;
                    
                    // Update in database
                    const questionId = item.dataset.id;
                    updateQuestionNumberInDatabase(questionId, questionNumber);
                });
            }
            
            // Update question number in database
            function updateQuestionNumberInDatabase(questionId, questionNumber) {
                fetch('{{ route("mock-tests.update-question-order", $mockTest) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        questions: [{
                            id: questionId,
                            question_number: questionNumber
                        }]
                    })
                });
            }
            
            // Update marks
            document.querySelectorAll('.marks-input').forEach(input => {
                input.addEventListener('change', function() {
                    const questionId = this.dataset.id;
                    const marks = this.value;
                    
                    fetch(`/dashboard/mock-tests/{{ $mockTest->id }}/questions/${questionId}/update-details`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            marks: marks
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Marks updated successfully', 'success');
                        } else {
                            showToast('Failed to update marks', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Error updating marks', 'error');
                    });
                });
            });
            
            // Remove question (both desktop and mobile)
            document.querySelectorAll('.remove-question').forEach(button => {
                button.addEventListener('click', function() {
                    const questionId = this.dataset.id;
                    const mcqId = this.dataset.mcqId;
                    const questionTitle = this.dataset.title;
                    
                    if (confirm(`Are you sure you want to remove: "${questionTitle}" from this test?`)) {
                        fetch(`/dashboard/mock-tests/{{ $mockTest->id }}/remove-question/${mcqId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast('Question removed successfully', 'success');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                showToast(data.message || 'Failed to remove question', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Error removing question', 'error');
                        });
                    }
                });
            });
            
            // Toast notification function
            function showToast(message, type = 'info') {
                // Create toast element
                const toastId = 'toast-' + Date.now();
                const toastHtml = `
                    <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;
                
                // Add to toast container
                let toastContainer = document.querySelector('.toast-container');
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                    document.body.appendChild(toastContainer);
                }
                
                toastContainer.insertAdjacentHTML('beforeend', toastHtml);
                
                // Show toast
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
                toast.show();
                
                // Remove toast after hiding
                toastElement.addEventListener('hidden.bs.toast', function () {
                    this.remove();
                });
            }
        });
    </script>
    @endpush

    <style>
        .handle {
            cursor: move;
            color: #6c757d;
        }
        
        .handle:hover {
            color: #0d6efd;
        }
        
        .question-preview {
            max-width: 100%;
        }
        
        @media (min-width: 768px) {
            .question-preview {
                max-width: 250px;
            }
        }
        
        .marks-input {
            min-width: 50px;
        }
        
        .sortable-ghost {
            opacity: 0.4;
            background-color: #f8f9fa;
        }
        
        .sortable-chosen {
            background-color: #e9ecef;
        }
        
        /* Mobile specific styles */
        @media (max-width: 767.98px) {
            .list-group-item {
                padding: 1rem;
            }
            
            .handle {
                font-size: 1rem;
            }
            
            .question-number {
                font-size: 0.875rem;
            }
            
            .input-group-sm {
                width: 100px;
            }
            
            .toast-container {
                bottom: 1rem;
                right: 1rem;
                left: 1rem;
            }
        }
        
        /* Form spacing adjustments */
        @media (max-width: 767.98px) {
            .card-body {
                padding: 1rem;
            }
            
            .row.g-3 {
                margin-bottom: 1rem;
            }
            
            .col-12 {
                margin-bottom: 0.5rem;
            }
        }
        
        /* Button adjustments for mobile */
        @media (max-width: 767.98px) {
            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
        
        /* Card hover effect */
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        /* Responsive sidebar */
        @media (max-width: 991.98px) {
            .col-lg-4 {
                margin-top: 2rem;
            }
            
            .card.mx-3.mx-md-0 {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }
        
        /* Empty state responsiveness */
        .text-center.py-4 i.fa-3x {
            font-size: 3rem;
        }
        
        @media (max-width: 767.98px) {
            .text-center.py-4 i.fa-3x {
                font-size: 2.5rem;
            }
            
            .text-center.py-4 p {
                font-size: 0.875rem;
            }
        }
    </style>
</x-app-layout>