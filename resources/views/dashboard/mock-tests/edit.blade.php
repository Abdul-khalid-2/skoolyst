<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Edit Mock Test</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('mock-tests.index') }}">Mock Tests</a></li>
                                <li class="breadcrumb-item active">Edit {{ $mockTest->title }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-success me-2">
                            <i class="fas fa-question me-2"></i> Manage Questions
                        </a>
                        <a href="{{ route('mock-tests.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Test Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Questions</h6>
                                    <h3 class="mb-0">{{ $mockTest->questions_count }}</h3>
                                    <small class="text-muted">of {{ $mockTest->total_questions }} target</small>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-question-circle fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Marks</h6>
                                    <h3 class="mb-0">{{ $mockTest->questions->sum('marks') }}</h3>
                                    <small class="text-muted">of {{ $mockTest->total_marks }} target</small>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-star fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Attempts</h6>
                                    <h3 class="mb-0">{{ $mockTest->attempts_count }}</h3>
                                    <small class="text-success">
                                        {{ $mockTest->attempts()->where('result_status', 'passed')->count() }} passed
                                    </small>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-users fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Avg. Score</h6>
                                    <h3 class="mb-0">
                                        {{ $mockTest->attempts_count > 0 ? round($mockTest->attempts()->avg('percentage'), 1) : 0 }}%
                                    </h3>
                                    <small class="text-muted">Average percentage</small>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-chart-line fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Edit Test: {{ $mockTest->title }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('mock-tests.update', $mockTest) }}" method="POST" id="mockTestForm">
                                @csrf
                                @method('PUT')
                                
                                <!-- Basic Information -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-8">
                                        <label for="title" class="form-label">Test Title *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title', $mockTest->title) }}" 
                                               placeholder="e.g., Mathematics Practice Test" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
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
                                    
                                    <div class="col-md-4">
                                        <label for="total_questions" class="form-label">Total Questions *</label>
                                        <input type="number" class="form-control @error('total_questions') is-invalid @enderror" 
                                               id="total_questions" name="total_questions" 
                                               value="{{ old('total_questions', $mockTest->total_questions) }}" min="1" required>
                                        @error('total_questions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="total_marks" class="form-label">Total Marks *</label>
                                        <input type="number" class="form-control @error('total_marks') is-invalid @enderror" 
                                               id="total_marks" name="total_marks" 
                                               value="{{ old('total_marks', $mockTest->total_marks) }}" min="1" required>
                                        @error('total_marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="total_time_minutes" class="form-label">Time (minutes) *</label>
                                        <input type="number" class="form-control @error('total_time_minutes') is-invalid @enderror" 
                                               id="total_time_minutes" name="total_time_minutes" 
                                               value="{{ old('total_time_minutes', $mockTest->total_time_minutes) }}" min="1" required>
                                        @error('total_time_minutes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="passing_marks" class="form-label">Passing Marks *</label>
                                        <input type="number" class="form-control @error('passing_marks') is-invalid @enderror" 
                                               id="passing_marks" name="passing_marks" 
                                               value="{{ old('passing_marks', $mockTest->passing_marks) }}" min="0" required>
                                        @error('passing_marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
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
                                    
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="shuffle_questions" name="shuffle_questions" value="1"
                                                   {{ old('shuffle_questions', $mockTest->shuffle_questions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="shuffle_questions">
                                                Shuffle Questions
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="show_result_immediately" name="show_result_immediately" value="1"
                                                   {{ old('show_result_immediately', $mockTest->show_result_immediately) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_result_immediately">
                                                Show Result Immediately
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="allow_retake" name="allow_retake" value="1"
                                                   {{ old('allow_retake', $mockTest->allow_retake) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="allow_retake">
                                                Allow Retake
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
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
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="is_free" name="is_free" value="1"
                                                   {{ old('is_free', $mockTest->is_free) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_free">
                                                Free Test
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6" id="price-field" style="{{ $mockTest->is_free ? 'display: none;' : '' }}">
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
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Update Test
                                    </button>
                                    <a href="{{ route('mock-tests.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Questions List -->
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Test Questions ({{ $mockTest->questions->count() }})</h5>
                            <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-plus me-1"></i> Add Questions
                            </a>
                        </div>
                        <div class="card-body">
                            @if($mockTest->questions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th width="60">#</th>
                                            <th>Question</th>
                                            <th>Subject/Topic</th>
                                            <th>Difficulty</th>
                                            <th>Marks</th>
                                            <th>Actions</th>
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
                                                    {!! Str::limit(strip_tags($question->mcq->question), 60) !!}
                                                </div>
                                                <div class="small text-muted">
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
                                                    <div>{{ $question->mcq->subject->name ?? 'N/A' }}</div>
                                                    <div class="text-muted">{{ $question->mcq->topic->title ?? 'N/A' }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $question->mcq->difficulty_level == 'easy' ? 'success' : ($question->mcq->difficulty_level == 'medium' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($question->mcq->difficulty_level) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm" style="width: 120px;">
                                                    <input type="number" class="form-control marks-input" 
                                                           value="{{ $question->marks }}" min="1" 
                                                           data-id="{{ $question->id }}">
                                                    <span class="input-group-text">M</span>
                                                </div>
                                                @if($question->negative_marks > 0)
                                                <small class="text-danger">-{{ $question->negative_marks }}</small>
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
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No questions added yet</p>
                                <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Add Questions
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Test Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Test Information</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Created</span>
                                    <span>{{ $mockTest->created_at->format('M d, Y') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Created By</span>
                                    <span>{{ $mockTest->createdBy->name ?? 'System' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Last Updated</span>
                                    <span>{{ $mockTest->updated_at->format('M d, Y') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Slug</span>
                                    <span><code>{{ $mockTest->slug }}</code></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Test Mode</span>
                                    <span class="badge bg-{{ $mockTest->test_mode == 'exam' ? 'danger' : ($mockTest->test_mode == 'timed' ? 'warning' : 'primary') }}">
                                        {{ ucfirst($mockTest->test_mode) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Access</span>
                                    <span class="badge bg-{{ $mockTest->is_free ? 'success' : 'warning' }}">
                                        {{ $mockTest->is_free ? 'Free' : 'Premium' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Subject Breakdown -->
                    @if($mockTest->subject_breakdown)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Subject Breakdown</h5>
                        </div>
                        <div class="card-body">
                            @php
                            $breakdown = json_decode($mockTest->subject_breakdown, true);
                            @endphp
                            <div class="list-group list-group-flush">
                                @foreach($breakdown as $item)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>
                                        @php
                                        $subject = \App\Models\Subject::find($item['subject_id']);
                                        @endphp
                                        {{ $subject->name ?? 'Unknown Subject' }}
                                    </span>
                                    <span class="badge bg-primary">{{ $item['question_count'] }} questions</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('mock-tests.add-questions', $mockTest) }}" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i> Add Questions
                                </a>
                                <a href="{{ route('mock-tests.show', $mockTest) }}" class="btn btn-outline-info">
                                    <i class="fas fa-eye me-2"></i> View Details
                                </a>
                                <a href="{{ route('mock-tests.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-list me-2"></i> All Tests
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
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
            
            // Initialize Sortable for questions
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
            
            // Update question numbers
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
                            // Update total marks display if needed
                            console.log('Marks updated');
                        }
                    });
                });
            });
            
            // Remove question
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
                                location.reload();
                            }
                        });
                    }
                });
            });
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
            max-width: 300px;
        }
        
        .marks-input {
            width: 60px;
        }
        
        .sortable-ghost {
            opacity: 0.4;
            background-color: #f8f9fa;
        }
        
        .sortable-chosen {
            background-color: #e9ecef;
        }
    </style>
</x-app-layout>