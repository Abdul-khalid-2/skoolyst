<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Create MCQ</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('mcqs.index') }}">MCQs</a></li>
                                <li class="breadcrumb-item active">Create</li>
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
                            <h5 class="mb-0">MCQ Details</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('mcqs.store') }}" method="POST" id="mcqForm">
                                @csrf
                                
                                <!-- Basic Information -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="subject_id" class="form-label">Subject *</label>
                                        <select class="form-select @error('subject_id') is-invalid @enderror" 
                                                id="subject_id" name="subject_id" required>
                                            <option value="">Select Subject</option>
                                            @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                {{ old('subject_id', $selectedSubject) == $subject->id ? 'selected' : '' }}>
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
                                            @if($selectedSubject)
                                                @foreach($topics->where('subject_id', $selectedSubject) as $topic)
                                                <option value="{{ $topic->id }}" 
                                                    {{ old('topic_id', $selectedTopic) == $topic->id ? 'selected' : '' }}>
                                                    {{ $topic->title }}
                                                </option>
                                                @endforeach
                                            @endif
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
                                            <option value="single" {{ old('question_type') == 'single' ? 'selected' : '' }}>Single Choice</option>
                                            <option value="multiple" {{ old('question_type') == 'multiple' ? 'selected' : '' }}>Multiple Choice</option>
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
                                            <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy</option>
                                            <option value="medium" {{ old('difficulty_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="hard" {{ old('difficulty_level') == 'hard' ? 'selected' : '' }}>Hard</option>
                                        </select>
                                        @error('difficulty_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="test_type_id" class="form-label">Test Type</label>
                                        <select class="form-select @error('test_type_id') is-invalid @enderror" 
                                                id="test_type_id" name="test_type_id">
                                            <option value="">Select Test Type (Optional)</option>
                                            @foreach($testTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('test_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('test_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Question -->
                                <div class="mb-4">
                                    <label for="question" class="form-label">Question *</label>
                                    <textarea class="form-control @error('question') is-invalid @enderror" 
                                              id="question" name="question" rows="4" required
                                              placeholder="Enter your question here...">{{ old('question') }}</textarea>
                                    @error('question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">You can use HTML for formatting</small>
                                </div>
                                
                                <!-- Options -->
                                <div class="mb-4">
                                    <label class="form-label">Options *</label>
                                    <div id="options-container">
                                        <div class="option-item mb-2">
                                            <div class="input-group">
                                                <span class="input-group-text">A</span>
                                                <input type="text" class="form-control" name="options[0]" 
                                                       placeholder="Option A" value="{{ old('options.0') }}" required>
                                                <button type="button" class="btn btn-outline-danger remove-option" disabled>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="option-item mb-2">
                                            <div class="input-group">
                                                <span class="input-group-text">B</span>
                                                <input type="text" class="form-control" name="options[1]" 
                                                       placeholder="Option B" value="{{ old('options.1') }}" required>
                                                <button type="button" class="btn btn-outline-danger remove-option">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
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
                                        <!-- Will be populated by JavaScript -->
                                    </div>
                                    @error('correct_answers')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted" id="correct-answer-hint">Select correct answer(s) from options</small>
                                </div>
                                
                                <!-- Additional Information -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label for="marks" class="form-label">Marks *</label>
                                        <input type="number" class="form-control @error('marks') is-invalid @enderror" 
                                               id="marks" name="marks" value="{{ old('marks', 1) }}" min="1" required>
                                        @error('marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="negative_marks" class="form-label">Negative Marks</label>
                                        <input type="number" class="form-control @error('negative_marks') is-invalid @enderror" 
                                               id="negative_marks" name="negative_marks" value="{{ old('negative_marks', 0) }}" min="0">
                                        @error('negative_marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="time_limit_seconds" class="form-label">Time Limit (seconds)</label>
                                        <input type="number" class="form-control @error('time_limit_seconds') is-invalid @enderror" 
                                               id="time_limit_seconds" name="time_limit_seconds" 
                                               value="{{ old('time_limit_seconds') }}" min="10">
                                        @error('time_limit_seconds')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Leave empty for no limit</small>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="tags" class="form-label">Tags</label>
                                        <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                               id="tags" name="tags" value="{{ old('tags') }}"
                                               placeholder="e.g., algebra, calculus, geometry">
                                        @error('tags')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Separate tags with commas</small>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-check mt-4 pt-2">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="is_premium" name="is_premium" value="1"
                                                   {{ old('is_premium') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_premium">
                                                Premium Question
                                            </label>
                                            <small class="text-muted d-block">Premium questions require subscription</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Explanation & Hint -->
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <label for="explanation" class="form-label">Explanation</label>
                                        <textarea class="form-control @error('explanation') is-invalid @enderror" 
                                                  id="explanation" name="explanation" rows="3"
                                                  placeholder="Detailed explanation of the correct answer...">{{ old('explanation') }}</textarea>
                                        @error('explanation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="hint" class="form-label">Hint</label>
                                        <textarea class="form-control @error('hint') is-invalid @enderror" 
                                                  id="hint" name="hint" rows="2"
                                                  placeholder="Hint for students...">{{ old('hint') }}</textarea>
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
                                               id="reference_book" name="reference_book" value="{{ old('reference_book') }}"
                                               placeholder="e.g., Calculus by James Stewart">
                                        @error('reference_book')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="reference_page" class="form-label">Reference Page</label>
                                        <input type="text" class="form-control @error('reference_page') is-invalid @enderror" 
                                               id="reference_page" name="reference_page" value="{{ old('reference_page') }}"
                                               placeholder="e.g., Page 45">
                                        @error('reference_page')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Submit -->
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Create MCQ
                                    </button>
                                    <button type="button" class="btn btn-outline-info" id="preview-btn">
                                        <i class="fas fa-eye me-2"></i> Preview
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
                    <!-- Information Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-lightbulb me-2"></i>Tips for creating MCQs</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Questions should be clear and unambiguous</li>
                                    <li>Options should be plausible but distinct</li>
                                    <li>Add explanations for better learning</li>
                                    <li>Set appropriate difficulty levels</li>
                                    <li>Use tags for better organization</li>
                                </ul>
                            </div>
                            
                            <div class="mt-3">
                                <h6 class="mb-2">Question Types:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Single Choice</span>
                                        <span class="badge bg-primary">One correct answer</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Multiple Choice</span>
                                        <span class="badge bg-info">Multiple correct answers</span>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="mt-3">
                                <h6 class="mb-2">Difficulty Levels:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Easy</span>
                                        <span class="badge bg-success">Basic concepts</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Medium</span>
                                        <span class="badge bg-warning">Application level</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Hard</span>
                                        <span class="badge bg-danger">Analytical skills</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preview Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Preview</h5>
                        </div>
                        <div class="card-body">
                            <div id="question-preview" class="mb-3">
                                <p class="text-muted">Question preview will appear here</p>
                            </div>
                            <div id="options-preview">
                                <!-- Options preview -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">MCQ Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="previewModalContent">
                    <!-- Preview content will be inserted here -->
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            alert('testing');
            let optionCounter = 2; // Starting from C (0=A, 1=B, 2=C)
            
            // Get DOM elements
            const optionsContainer = document.getElementById('options-container');
            const correctAnswersContainer = document.getElementById('correct-answers-container');
            const correctAnswerHint = document.getElementById('correct-answer-hint');
            const questionTypeSelect = document.getElementById('question_type');
            const subjectSelect = document.getElementById('subject_id');
            const topicSelect = document.getElementById('topic_id');
            const previewBtn = document.getElementById('preview-btn');
            const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
            
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
                    const optionLetter = String.fromCharCode(65 + optionIndex);
                    const correctCheckbox = document.querySelector(`input[name="correct_answers[]"][value="${optionIndex}"]`);
                    
                    if (correctCheckbox && correctCheckbox.checked) {
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
                    const checkboxId = `correct_${index}`;
                    
                    const div = document.createElement('div');
                    div.className = 'form-check form-check-inline';
                    div.innerHTML = `
                        <input class="form-check-input" type="${questionTypeSelect.value === 'single' ? 'radio' : 'checkbox'}" 
                               name="correct_answers[]" id="${checkboxId}" value="${index}">
                        <label class="form-check-label" for="${checkboxId}">
                            <span class="badge bg-light text-dark">${optionLetter}</span>
                        </label>
                    `;
                    
                    correctAnswersContainer.appendChild(div);
                    
                    // Add event listener to the checkbox/radio
                    const checkbox = div.querySelector('input');
                    checkbox.addEventListener('change', function() {
                        if (questionTypeSelect.value === 'single' && this.type === 'radio') {
                            // For radio buttons, uncheck others
                            correctAnswersContainer.querySelectorAll('input[type="radio"]').forEach(cb => {
                                if (cb !== this) cb.checked = false;
                            });
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
            
            // Load topics when subject changes
            subjectSelect.addEventListener('change', function() {
                const subjectId = this.value;
                
                if (!subjectId) {
                    topicSelect.innerHTML = '<option value="">Select Topic</option>';
                    return;
                }
                
                fetch(`/dashboard/mcqs/get-topics?subject_id=${subjectId}`)
                    .then(response => response.json())
                    .then(topics => {
                        let options = '<option value="">Select Topic</option>';
                        topics.forEach(topic => {
                            options += `<option value="${topic.id}">${topic.title}</option>`;
                        });
                        topicSelect.innerHTML = options;
                    })
                    .catch(error => console.error('Error:', error));
            });
            
            // Preview functionality
            previewBtn.addEventListener('click', function() {
                const form = document.getElementById('mcqForm');
                const formData = new FormData(form);
                
                // Build preview HTML
                let previewHTML = `
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Question:</h6>
                        <div class="border rounded p-3 bg-light">
                            ${formData.get('question') || 'No question entered'}
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Options:</h6>
                        <div class="list-group">
                `;
                
                // Add options
                for (let i = 0; i < optionCounter; i++) {
                    const optionValue = formData.get(`options[${i}]`);
                    if (optionValue) {
                        const optionLetter = String.fromCharCode(65 + i);
                        const isCorrect = formData.getAll('correct_answers[]').includes(i.toString());
                        
                        previewHTML += `
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-light text-dark me-2">${optionLetter}</span>
                                    ${optionValue}
                                </div>
                                ${isCorrect ? '<span class="badge bg-success"><i class="fas fa-check"></i> Correct</span>' : ''}
                            </div>
                        `;
                    }
                }
                
                previewHTML += `
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Subject:</h6>
                            <p>${subjectSelect.options[subjectSelect.selectedIndex]?.text || 'Not selected'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Topic:</h6>
                            <p>${topicSelect.options[topicSelect.selectedIndex]?.text || 'Not selected'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Difficulty:</h6>
                            <p>${document.getElementById('difficulty_level').value || 'Not selected'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Marks:</h6>
                            <p>${formData.get('marks') || '1'}</p>
                        </div>
                    </div>
                `;
                
                document.getElementById('previewModalContent').innerHTML = previewHTML;
                previewModal.show();
            });
            
            // Real-time preview
            const questionInput = document.getElementById('question');
            const questionPreview = document.getElementById('question-preview');
            
            questionInput.addEventListener('input', function() {
                questionPreview.innerHTML = `
                    <h6 class="text-muted mb-2">Question:</h6>
                    <div class="border rounded p-2 bg-light">
                        ${this.value || 'Question preview will appear here'}
                    </div>
                `;
            });
            
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
        
        #previewModalContent .list-group-item {
            border-left: none;
            border-right: none;
        }
        
        #previewModalContent .list-group-item:first-child {
            border-top: none;
        }
        
        #previewModalContent .list-group-item:last-child {
            border-bottom: none;
        }
    </style>
</x-app-layout>