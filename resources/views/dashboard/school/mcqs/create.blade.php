<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Add New MCQ</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('school.mcqs.index') }}">School MCQs</a></li>
                                <li class="breadcrumb-item active">Add New</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('school.mcqs.index') }}" class="btn btn-outline-secondary">
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
                            <form action="{{ route('school.mcqs.store') }}" method="POST" id="mcqForm">
                                @csrf
                                
                                <!-- Basic Information -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="subject_id" class="form-label">Subject *</label>
                                        <select class="form-select @error('subject_id') is-invalid @enderror" 
                                                id="subject_id" name="subject_id" required>
                                            <option value="">Select Subject</option>
                                            @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
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
                                            @foreach($topics as $topic)
                                            <option value="{{ $topic->id }}" 
                                                    data-subject-id="{{ $topic->subject_id }}"
                                                    {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                                                {{ $topic->title }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('topic_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="question_type" class="form-label">Question Type *</label>
                                        <select class="form-select @error('question_type') is-invalid @enderror" 
                                                id="question_type" name="question_type" required>
                                            <option value="">Select Type</option>
                                            <option value="single" {{ old('question_type') == 'single' ? 'selected' : '' }}>Single Correct</option>
                                            <option value="multiple" {{ old('question_type') == 'multiple' ? 'selected' : '' }}>Multiple Correct</option>
                                        </select>
                                        @error('question_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
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
                                    
                                    <div class="col-md-6">
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
                                    
                                    <div class="col-md-6">
                                        <label for="marks" class="form-label">Marks *</label>
                                        <input type="number" class="form-control @error('marks') is-invalid @enderror" 
                                               id="marks" name="marks" value="{{ old('marks', 1) }}" min="1" required>
                                        @error('marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
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
                                </div>
                                
                                <!-- Question -->
                                <div class="mb-4">
                                    <label for="question" class="form-label">Question *</label>
                                    <textarea class="form-control @error('question') is-invalid @enderror" 
                                              id="question" name="question" rows="4" 
                                              placeholder="Enter your question here..." required>{{ old('question') }}</textarea>
                                    @error('question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">You can use HTML tags for formatting</small>
                                </div>
                                
                                <!-- Options -->
                                <div class="mb-4">
                                    <label class="form-label">Options *</label>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">A</span>
                                                <input type="text" class="form-control @error('option_a') is-invalid @enderror" 
                                                       id="option_a" name="option_a" value="{{ old('option_a') }}" required>
                                                @error('option_a')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">B</span>
                                                <input type="text" class="form-control @error('option_b') is-invalid @enderror" 
                                                       id="option_b" name="option_b" value="{{ old('option_b') }}" required>
                                                @error('option_b')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">C</span>
                                                <input type="text" class="form-control @error('option_c') is-invalid @enderror" 
                                                       id="option_c" name="option_c" value="{{ old('option_c') }}">
                                                @error('option_c')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">D</span>
                                                <input type="text" class="form-control @error('option_d') is-invalid @enderror" 
                                                       id="option_d" name="option_d" value="{{ old('option_d') }}">
                                                @error('option_d')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">E</span>
                                                <input type="text" class="form-control @error('option_e') is-invalid @enderror" 
                                                       id="option_e" name="option_e" value="{{ old('option_e') }}">
                                                @error('option_e')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">At least options A and B are required. C, D, and E are optional.</small>
                                </div>
                                
                                <!-- Correct Answer -->
                                <div class="mb-4">
                                    <label for="correct_answer" class="form-label">Correct Answer(s) *</label>
                                    <div id="correctAnswerContainer">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input correct-answer" type="checkbox" id="correct_a" name="correct_a" value="A">
                                            <label class="form-check-label" for="correct_a">
                                                Option A
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input correct-answer" type="checkbox" id="correct_b" name="correct_b" value="B">
                                            <label class="form-check-label" for="correct_b">
                                                Option B
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input correct-answer" type="checkbox" id="correct_c" name="correct_c" value="C">
                                            <label class="form-check-label" for="correct_c">
                                                Option C
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input correct-answer" type="checkbox" id="correct_d" name="correct_d" value="D">
                                            <label class="form-check-label" for="correct_d">
                                                Option D
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input correct-answer" type="checkbox" id="correct_e" name="correct_e" value="E">
                                            <label class="form-check-label" for="correct_e">
                                                Option E
                                            </label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="correct_answer" id="correct_answer" value="{{ old('correct_answer') }}" required>
                                    @error('correct_answer')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Explanation -->
                                <div class="mb-4">
                                    <label for="explanation" class="form-label">Explanation</label>
                                    <textarea class="form-control @error('explanation') is-invalid @enderror" 
                                              id="explanation" name="explanation" rows="3" 
                                              placeholder="Explain why the answer is correct...">{{ old('explanation') }}</textarea>
                                    @error('explanation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Premium Checkbox -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" 
                                               id="is_premium" name="is_premium" value="1"
                                               {{ old('is_premium') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_premium">
                                            Premium Question
                                        </label>
                                        <small class="text-muted d-block">Premium questions are only accessible to premium users</small>
                                    </div>
                                </div>
                                
                                <!-- Submit -->
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Save MCQ
                                    </button>
                                    <a href="{{ route('school.mcqs.index') }}" class="btn btn-outline-secondary">
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
                                <h6><i class="fas fa-lightbulb me-2"></i>Tips for creating MCQs:</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Questions should be clear and unambiguous</li>
                                    <li>Options should be distinct and not overlapping</li>
                                    <li>Correct answer(s) must be specified</li>
                                    <li>Provide explanation for better learning</li>
                                    <li>All MCQs will be reviewed by admin</li>
                                </ul>
                            </div>
                            
                            <div class="mt-3">
                                <h6 class="mb-2">Question Types:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Single Correct</span>
                                        <span class="badge bg-primary">Only one answer</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Multiple Correct</span>
                                        <span class="badge bg-info">Multiple answers</span>
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
                                        <span class="badge bg-warning">Application required</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Hard</span>
                                        <span class="badge bg-danger">Complex analysis</span>
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
                            <div id="mcq-preview">
                                <p class="text-muted">Question preview will appear here</p>
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
            const subjectSelect = document.getElementById('subject_id');
            const topicSelect = document.getElementById('topic_id');
            const questionTypeSelect = document.getElementById('question_type');
            const correctAnswerCheckboxes = document.querySelectorAll('.correct-answer');
            const correctAnswerHidden = document.getElementById('correct_answer');
            const questionInput = document.getElementById('question');
            const optionAInput = document.getElementById('option_a');
            const optionBInput = document.getElementById('option_b');
            const optionCInput = document.getElementById('option_c');
            const optionDInput = document.getElementById('option_d');
            const optionEInput = document.getElementById('option_e');
            const mcqPreview = document.getElementById('mcq-preview');
            
            // Filter topics based on selected subject
            subjectSelect.addEventListener('change', function() {
                const subjectId = this.value;
                
                // Reset and filter topics
                Array.from(topicSelect.options).forEach(option => {
                    if (option.value === '' || option.dataset.subjectId === subjectId) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
                
                // Reset selected topic if it doesn't belong to selected subject
                if (topicSelect.value && topicSelect.options[topicSelect.selectedIndex].dataset.subjectId !== subjectId) {
                    topicSelect.value = '';
                }
            });
            
            // Handle question type change
            questionTypeSelect.addEventListener('change', function() {
                updateCorrectAnswerUI();
            });
            
            // Update correct answer checkboxes
            function updateCorrectAnswerUI() {
                const isMultiple = questionTypeSelect.value === 'multiple';
                
                if (!isMultiple) {
                    // For single correct, use radio buttons
                    correctAnswerCheckboxes.forEach(cb => {
                        cb.type = 'radio';
                        cb.name = 'correct_answer_radio';
                    });
                } else {
                    // For multiple correct, use checkboxes
                    correctAnswerCheckboxes.forEach(cb => {
                        cb.type = 'checkbox';
                        cb.name = cb.value.toLowerCase();
                    });
                }
                
                updateCorrectAnswerValue();
            }
            
            // Update correct answer value when checkboxes change
            correctAnswerCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateCorrectAnswerValue);
            });
            
            function updateCorrectAnswerValue() {
                const selected = Array.from(correctAnswerCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                
                correctAnswerHidden.value = selected.join(',');
                
                // Validation
                if (selected.length === 0) {
                    correctAnswerHidden.setCustomValidity('Please select at least one correct answer');
                } else if (questionTypeSelect.value === 'single' && selected.length > 1) {
                    correctAnswerHidden.setCustomValidity('Single correct questions can only have one correct answer');
                } else {
                    correctAnswerHidden.setCustomValidity('');
                }
            }
            
            // Preview update
            function updatePreview() {
                const question = questionInput.value || 'Your question will appear here';
                const options = {
                    'A': optionAInput.value || 'Option A',
                    'B': optionBInput.value || 'Option B',
                    'C': optionCInput.value || '',
                    'D': optionDInput.value || '',
                    'E': optionEInput.value || ''
                };
                
                let previewHtml = `
                    <div class="mb-3">
                        <strong>${question}</strong>
                    </div>
                    <div class="list-group list-group-flush">
                `;
                
                Object.entries(options).forEach(([key, value]) => {
                    if (value) {
                        previewHtml += `
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="${questionTypeSelect.value === 'multiple' ? 'checkbox' : 'radio'}" disabled>
                                    </div>
                                    <div>
                                        <strong>${key}.</strong> ${value}
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                });
                
                previewHtml += `</div>`;
                mcqPreview.innerHTML = previewHtml;
            }
            
            // Update preview on input
            [questionInput, optionAInput, optionBInput, optionCInput, optionDInput, optionEInput, questionTypeSelect].forEach(input => {
                input.addEventListener('input', updatePreview);
                input.addEventListener('change', updatePreview);
            });
            
            // Initial preview
            updatePreview();
            updateCorrectAnswerUI();
        });
    </script>
    @endpush

    <style>
        .correct-answer {
            transform: scale(1.2);
        }
        
        #mcq-preview .list-group-item {
            border: none;
            border-bottom: 1px solid #dee2e6;
        }
        
        #mcq-preview .list-group-item:last-child {
            border-bottom: none;
        }
    </style>
</x-app-layout>