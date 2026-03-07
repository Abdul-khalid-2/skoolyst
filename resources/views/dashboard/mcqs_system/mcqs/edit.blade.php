<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Edit MCQ</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('mcqs.index') }}">MCQs</a></li>
                                <li class="breadcrumb-item active">Edit MCQ #{{ $mcq->id }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('mcqs.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Edit MCQ</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('mcqs.update', $mcq) }}" method="POST" id="mcqForm">
                                @csrf
                                @method('PUT')
                                
                                <!-- Basic Information -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="subject_id" class="form-label">Subject *</label>
                                        <select class="form-select @error('subject_id') is-invalid @enderror" 
                                                id="subject_id" name="subject_id" required>
                                            <option value="">Select Subject</option>
                                            @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                {{ old('subject_id', $mcq->subject_id) == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                                @if($subject->testType)
                                                ({{ $subject->testType->name }})
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('subject_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="topic_id" class="form-label">Topic *</label>
                                        <select class="form-select @error('topic_id') is-invalid @enderror" 
                                                id="topic_id" name="topic_id" required>
                                            <option value="">Select Topic</option>
                                            @foreach($topics->where('subject_id', $mcq->subject_id) as $topic)
                                            <option value="{{ $topic->id }}" 
                                                {{ old('topic_id', $mcq->topic_id) == $topic->id ? 'selected' : '' }}>
                                                {{ $topic->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('topic_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="question_type" class="form-label">Question Type *</label>
                                        <select class="form-select @error('question_type') is-invalid @enderror" 
                                                id="question_type" name="question_type" required>
                                            <option value="">Select Type</option>
                                            <option value="single" {{ old('question_type', $mcq->question_type) == 'single' ? 'selected' : '' }}>Single Choice</option>
                                            <option value="multiple" {{ old('question_type', $mcq->question_type) == 'multiple' ? 'selected' : '' }}>Multiple Choice</option>
                                        </select>
                                        @error('question_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="difficulty_level" class="form-label">Difficulty Level *</label>
                                        <select class="form-select @error('difficulty_level') is-invalid @enderror" 
                                                id="difficulty_level" name="difficulty_level" required>
                                            <option value="">Select Difficulty</option>
                                            <option value="easy" {{ old('difficulty_level', $mcq->difficulty_level) == 'easy' ? 'selected' : '' }}>Easy</option>
                                            <option value="medium" {{ old('difficulty_level', $mcq->difficulty_level) == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="hard" {{ old('difficulty_level', $mcq->difficulty_level) == 'hard' ? 'selected' : '' }}>Hard</option>
                                        </select>
                                        @error('difficulty_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Replace the test_type_id select with this checkbox group -->
                                    <div class="col-12">
                                        <label class="form-label">Test Types</label>
                                        <div class="border rounded p-3 @error('test_type_ids') border-danger @enderror">
                                            <div id="test-types-container" class="row g-2">
                                                @if($mcq->subject_id)
                                                    @php
                                                        $subject = \App\Models\Subject::with('testTypes')->find($mcq->subject_id);
                                                    @endphp
                                                    @if($subject && $subject->testTypes->count() > 0)
                                                        @foreach($subject->testTypes as $type)
                                                            <div class="col-md-4 col-sm-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" 
                                                                        name="test_type_ids[]" 
                                                                        value="{{ $type->id }}" 
                                                                        id="test_type_{{ $type->id }}"
                                                                        {{ in_array($type->id, $selectedTestTypeIds) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="test_type_{{ $type->id }}">
                                                                        @if($type->icon)
                                                                            <i class="{{ $type->icon }} me-1"></i>
                                                                        @endif
                                                                        {{ $type->name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <p class="text-muted mb-0">No test types available for this subject.</p>
                                                    @endif
                                                @else
                                                    <p class="text-muted mb-0">Select a subject to see available test types.</p>
                                                @endif
                                            </div>
                                            @error('test_type_ids')
                                                <div class="text-danger small mt-2">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted d-block mt-2">Select multiple test types for this question</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Question -->
                                <div class="mb-4">
                                    <label for="question" class="form-label">Question *</label>
                                    <textarea class="form-control @error('question') is-invalid @enderror" 
                                              id="question" name="question" rows="4" required
                                              placeholder="Enter your question here...">{{ old('question', $mcq->question) }}</textarea>
                                    @error('question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Options -->
                                <div class="mb-4">
                                    <label class="form-label">Options *</label>
                                    <div id="options-container">
                                        @foreach($options as $index => $option)
                                        <div class="option-item mb-2">
                                            <div class="input-group">
                                                <span class="input-group-text">{{ chr(65 + $index) }}</span>
                                                <input type="text" class="form-control" name="options[{{ $index }}]" 
                                                       value="{{ old('options.' . $index, $option) }}" required>
                                                <button type="button" class="btn btn-outline-danger remove-option" 
                                                        {{ $index < 2 ? 'disabled' : '' }}>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('options')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-option">
                                        <i class="fas fa-plus me-1"></i> Add Option
                                    </button>
                                    <small class="text-muted d-block mt-1">Minimum 2 options required</small>
                                </div>
                                
                                <!-- Correct Answers -->
                                <div class="mb-4">
                                    <label class="form-label">Correct Answer(s) *</label>
                                    <div id="correct-answers-container" class="d-flex flex-wrap gap-2">
                                        @foreach($options as $index => $option)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" 
                                                   type="{{ $mcq->question_type == 'single' ? 'radio' : 'checkbox' }}" 
                                                   name="correct_answers[]" 
                                                   id="correct_{{ $index }}" 
                                                   value="{{ $index}}"
                                                   {{ in_array($index , $correctAnswers) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="correct_{{ $index }}">
                                                <span class="badge bg-light text-dark">{{ chr(65 + $index) }}</span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('correct_answers')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted" id="correct-answer-hint">
                                        {{ $mcq->question_type == 'single' ? 'Select one correct answer' : 'Select one or more correct answers' }}
                                    </small>
                                </div>
                                
                                <!-- Additional Information -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label for="marks" class="form-label">Marks *</label>
                                        <input type="number" class="form-control @error('marks') is-invalid @enderror" 
                                               id="marks" name="marks" value="{{ old('marks', $mcq->marks) }}" min="1" required>
                                        @error('marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="negative_marks" class="form-label">Negative Marks</label>
                                        <input type="number" class="form-control @error('negative_marks') is-invalid @enderror" 
                                               id="negative_marks" name="negative_marks" value="{{ old('negative_marks', $mcq->negative_marks) }}" min="0">
                                        @error('negative_marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="time_limit_seconds" class="form-label">Time Limit (seconds)</label>
                                        <input type="number" class="form-control @error('time_limit_seconds') is-invalid @enderror" 
                                               id="time_limit_seconds" name="time_limit_seconds" 
                                               value="{{ old('time_limit_seconds', $mcq->time_limit_seconds) }}" min="10">
                                        @error('time_limit_seconds')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="draft" {{ old('status', $mcq->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published" {{ old('status', $mcq->status) == 'published' ? 'selected' : '' }}>Published</option>
                                            <option value="archived" {{ old('status', $mcq->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="tags" class="form-label">Tags</label>
                                        <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                               id="tags" name="tags" value="{{ old('tags', $tags) }}"
                                               placeholder="e.g., algebra, calculus, geometry">
                                        @error('tags')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-check mt-4 pt-2">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="is_premium" name="is_premium" value="1"
                                                   {{ old('is_premium', $mcq->is_premium) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_premium">
                                                Premium Question
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Explanation & Hint -->
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <label for="explanation" class="form-label">Explanation</label>
                                        <textarea class="form-control @error('explanation') is-invalid @enderror" 
                                                  id="explanation" name="explanation" rows="3">{{ old('explanation', $mcq->explanation) }}</textarea>
                                        @error('explanation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="hint" class="form-label">Hint</label>
                                        <textarea class="form-control @error('hint') is-invalid @enderror" 
                                                  id="hint" name="hint" rows="2">{{ old('hint', $mcq->hint) }}</textarea>
                                        @error('hint')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Reference -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="reference_book" class="form-label">Reference Book</label>
                                        <input type="text" class="form-control @error('reference_book') is-invalid @enderror" 
                                               id="reference_book" name="reference_book" 
                                               value="{{ old('reference_book', $mcq->reference_book) }}">
                                        @error('reference_book')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="reference_page" class="form-label">Reference Page</label>
                                        <input type="text" class="form-control @error('reference_page') is-invalid @enderror" 
                                               id="reference_page" name="reference_page" 
                                               value="{{ old('reference_page', $mcq->reference_page) }}">
                                        @error('reference_page')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Submit -->
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Update MCQ
                                    </button>
                                    <a href="{{ route('mcqs.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- MCQ Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>MCQ Information</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Created</span>
                                    <span>{{ $mcq->created_at->format('M d, Y') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Last Updated</span>
                                    <span>{{ $mcq->updated_at->format('M d, Y') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Created By</span>
                                    <span>{{ $mcq->createdBy->name ?? 'System' }}</span>
                                </li>
                                @if($mcq->is_verified)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Verified By</span>
                                    <span>{{ $mcq->approvedBy->name ?? 'System' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Verified At</span>
                                    <span>{{ $mcq->approved_at ? $mcq->approved_at->format('M d, Y') : 'N/A' }}</span>
                                </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Used in Mock Tests</span>
                                    <span class="badge bg-primary">{{ $mcq->mock_tests_count }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Verification Status -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Verification Status</h5>
                        </div>
                        <div class="card-body">
                            @if($mcq->is_verified)
                            <div class="alert alert-success">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Verified</h6>
                                        <p class="mb-0">This MCQ has been verified and approved.</p>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('mcqs.unverify', $mcq) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-times me-2"></i> Remove Verification
                                </button>
                            </form>
                            @else
                            <div class="alert alert-warning">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Not Verified</h6>
                                        <p class="mb-0">This MCQ needs verification before publishing.</p>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('mcqs.verify', $mcq) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="fas fa-check me-2"></i> Verify MCQ
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let optionCounter = {{ count($options) }};
            
            // Get DOM elements
            const optionsContainer = document.getElementById('options-container');
            const correctAnswersContainer = document.getElementById('correct-answers-container');
            const correctAnswerHint = document.getElementById('correct-answer-hint');
            const questionTypeSelect = document.getElementById('question_type');
            const subjectSelect = document.getElementById('subject_id');
            const topicSelect = document.getElementById('topic_id');
            const testTypesContainer = document.getElementById('test-types-container');
            
            // Initialize correct answers from existing data
            let selectedCorrectAnswers = @json($correctAnswers).map(String);
            
            // Add option
            document.getElementById('add-option').addEventListener('click', function() {
                if (optionCounter >= 10) {
                    alert('Maximum 10 options allowed');
                    return;
                }
                
                const optionLetter = String.fromCharCode(65 + optionCounter);
                const optionItem = document.createElement('div');
                optionItem.className = 'option-item mb-2';
                optionItem.innerHTML = `
                    <div class="input-group">
                        <span class="input-group-text">${optionLetter}</span>
                        <input type="text" class="form-control" name="options[${optionCounter}]" 
                               placeholder="Option ${optionLetter}" required>
                        <button type="button" class="btn btn-outline-danger remove-option">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                optionsContainer.appendChild(optionItem);
                updateCorrectAnswers();
                optionCounter++;
            });
            
            // Remove option
            optionsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-option')) {
                    const optionItem = e.target.closest('.option-item');
                    if (optionsContainer.children.length <= 2) {
                        alert('Minimum 2 options required');
                        return;
                    }
                    
                    // Check if this option is selected as correct answer
                    const optionIndex = Array.from(optionsContainer.children).indexOf(optionItem);
                    const optionNumber = optionIndex; // 1-based index
                    const isCorrect = selectedCorrectAnswers.includes(optionNumber.toString());
                    
                    if (isCorrect) {
                        alert('Cannot remove an option that is marked as correct answer');
                        return;
                    }
                    
                    optionItem.remove();
                    updateOptionIndexes();
                    updateCorrectAnswers();
                    optionCounter--;
                }
            });
            
            // Update option indexes after removal
            function updateOptionIndexes() {
                const optionItems = optionsContainer.querySelectorAll('.option-item');
                optionItems.forEach((item, index) => {
                    const input = item.querySelector('input');
                    const span = item.querySelector('.input-group-text');
                    const optionLetter = String.fromCharCode(65 + index);
                    
                    span.textContent = optionLetter;
                    input.name = `options[${index}]`;
                    input.placeholder = `Option ${optionLetter}`;
                });
            }
            
            // Update correct answers checkboxes
            function updateCorrectAnswers() {
                const optionInputs = optionsContainer.querySelectorAll('input[name^="options"]');
                correctAnswersContainer.innerHTML = '';
                
                optionInputs.forEach((input, index) => {
                    const optionLetter = String.fromCharCode(65 + index);
                    const optionNumber = index; 
                    const checkboxId = `correct_${optionNumber}`;
                    const isSelected = selectedCorrectAnswers.includes(optionNumber.toString());
                    
                    const div = document.createElement('div');
                    div.className = 'form-check form-check-inline';
                    div.innerHTML = `
                        <input class="form-check-input" type="${questionTypeSelect.value === 'single' ? 'radio' : 'checkbox'}" 
                            name="correct_answers[]" id="${checkboxId}" value="${optionNumber}"
                            ${isSelected ? 'checked' : ''}>
                        <label class="form-check-label" for="${checkboxId}">
                            <span class="badge bg-light text-dark">${optionLetter}</span>
                        </label>
                    `;
                    
                    correctAnswersContainer.appendChild(div);
                    
                    // Add event listener to the checkbox/radio
                    const checkbox = div.querySelector('input');
                    checkbox.addEventListener('change', function() {
                        if (questionTypeSelect.value === 'single') {
                            // For single choice, clear and add selected one
                            selectedCorrectAnswers = [this.value];
                            // Uncheck others
                            correctAnswersContainer.querySelectorAll('input[name="correct_answers[]"]').forEach(cb => {
                                if (cb !== this) cb.checked = false;
                            });
                        } else {
                            // For multiple choice
                            if (this.checked) {
                                if (!selectedCorrectAnswers.includes(this.value)) {
                                    selectedCorrectAnswers.push(this.value);
                                }
                            } else {
                                selectedCorrectAnswers = selectedCorrectAnswers.filter(val => val !== this.value);
                            }
                        }
                    });
                });
            }
            
            // Handle question type change
            questionTypeSelect.addEventListener('change', function() {
                updateCorrectAnswers();
                correctAnswerHint.textContent = this.value === 'single' 
                    ? 'Select one correct answer' 
                    : 'Select one or more correct answers';
            });
            
            // Load topics and test types when subject changes
            subjectSelect.addEventListener('change', function() {
                const subjectId = this.value;
                
                // Load topics
                loadTopics(subjectId);
                
                // Load test types
                loadTestTypes(subjectId);
            });
            
            // Function to load topics
            function loadTopics(subjectId) {
                if (!subjectId) {
                    topicSelect.innerHTML = '<option value="">Select Topic</option>';
                    return;
                }
                
                // Show loading state
                topicSelect.innerHTML = '<option value="">Loading topics...</option>';
                
                fetch(`/dashboard/mcqs/get-topics?subject_id=${subjectId}`)
                    .then(response => response.json())
                    .then(topics => {
                        let options = '<option value="">Select Topic</option>';
                        topics.forEach(topic => {
                            const selected = (topic.id == {{ $mcq->topic_id }}) ? 'selected' : '';
                            options += `<option value="${topic.id}" ${selected}>${topic.title}</option>`;
                        });
                        topicSelect.innerHTML = options;
                    })
                    .catch(error => {
                        console.error('Error loading topics:', error);
                        topicSelect.innerHTML = '<option value="">Error loading topics</option>';
                    });
            }
            
            // Function to load test types
            function loadTestTypes(subjectId) {
                if (!subjectId) {
                    testTypesContainer.innerHTML = '<div class="row g-2"><div class="col-12"><p class="text-muted mb-0">Select a subject to see available test types.</p></div></div>';
                    return;
                }
                
                // Show loading state
                testTypesContainer.innerHTML = `
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="d-flex align-items-center text-muted">
                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span>Loading test types...</span>
                            </div>
                        </div>
                    </div>
                `;
                
                fetch(`/dashboard/mcqs/get-test-types?subject_id=${subjectId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(testTypes => {
                        if (!testTypes || testTypes.length === 0) {
                            testTypesContainer.innerHTML = '<div class="row g-2"><div class="col-12"><p class="text-muted mb-0">No test types available for this subject.</p></div></div>';
                            return;
                        }
                        
                        let html = '<div class="row g-2">';
                        const selectedTestTypeIds = @json($selectedTestTypeIds);
                        
                        testTypes.forEach((type) => {
                            const isChecked = selectedTestTypeIds.includes(type.id);
                            
                            html += `
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                            name="test_type_ids[]" 
                                            value="${type.id}" 
                                            id="test_type_${type.id}"
                                            ${isChecked ? 'checked' : ''}>
                                        <label class="form-check-label" for="test_type_${type.id}">
                                            ${type.icon ? `<i class="${type.icon} me-1"></i>` : ''}
                                            ${type.name}
                                        </label>
                                    </div>
                                </div>
                            `;
                        });
                        
                        html += '</div>';
                        testTypesContainer.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error loading test types:', error);
                        testTypesContainer.innerHTML = `
                            <div class="row g-2">
                                <div class="col-12">
                                    <p class="text-danger mb-0">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Error loading test types. Please try again.
                                    </p>
                                </div>
                            </div>
                        `;
                    });
            }
            
            // Initialize correct answers
            updateCorrectAnswers();
        });
    </script>
    @endpush

    <style>
        .option-item .input-group-text {
            min-width: 40px;
            justify-content: center;
        }
        
        .remove-option {
            width: 40px;
        }
        
        .form-check-inline {
            margin-right: 0.5rem;
        }
        
        .form-check-inline .form-check-input {
            margin-right: 0.25rem;
        }
        
        #test-types-container {
            min-height: 100px;
        }
    </style>
</x-app-layout>