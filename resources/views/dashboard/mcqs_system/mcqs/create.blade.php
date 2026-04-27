<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <x-page-header class="mb-4 px-3 px-md-0">
                <x-slot name="heading">
                    <h1 class="h3 mb-2">Create MCQ</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('mcqs.index') }}">MCQs</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </nav>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('mcqs.index') }}" variant="outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </x-button>
                </x-slot>
            </x-page-header>

            <!-- Form -->
            <div class="row">
                <div class="col-lg-8">
                    <x-card class="mx-3 mx-md-0">
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
                                    

                                    <!-- With this: -->
                                    <div class="col-12">
                                        <label class="form-label">Test Types</label>
                                        <div class="border rounded p-3 @error('test_type_ids') border-danger @enderror">
                                            <div id="test-types-container" class="row g-2">
                                                <!-- Test types will be loaded via AJAX -->
                                                @if(isset($selectedSubject) && $selectedSubject)
                                                    @php
                                                        $subject = \App\Models\Subject::with('testTypes')->find($selectedSubject);
                                                    @endphp
                                                    @if($subject && $subject->testTypes->count() > 0)
                                                        @foreach($subject->testTypes as $type)
                                                            <div class="col-md-4 col-sm-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" 
                                                                        name="test_type_ids[]" 
                                                                        value="{{ $type->id }}" 
                                                                        id="test_type_{{ $type->id }}"
                                                                        {{ is_array(old('test_type_ids')) && in_array($type->id, old('test_type_ids')) ? 'checked' : '' }}>
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
                                    <x-button type="submit" variant="primary">
                                        <i class="fas fa-save me-2"></i> Create MCQ
                                    </x-button>
                                    <x-button type="button" variant="outline-info" id="preview-btn">
                                        <i class="fas fa-eye me-2"></i> Preview
                                    </x-button>
                                    <x-button href="{{ route('mcqs.index') }}" variant="outline-secondary">
                                        Cancel
                                    </x-button>
                                </div>
                            </form>
                        </div>
                    </x-card>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Information Card -->
                    <x-card class="mb-4 mx-3 mx-md-0">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Information</h5>
                        </div>
                        <div class="card-body">
                            <x-alert variant="info" :dismissible="false">
                                <h6 class="mb-2"><i class="fas fa-lightbulb me-2"></i>Tips for creating MCQs</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Questions should be clear and unambiguous</li>
                                    <li>Options should be plausible but distinct</li>
                                    <li>Add explanations for better learning</li>
                                    <li>Set appropriate difficulty levels</li>
                                    <li>Use tags for better organization</li>
                                </ul>
                            </x-alert>
                            
                            <div class="mt-3">
                                <h6 class="mb-2">Question Types:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Single Choice</span>
                                        <x-badge variant="primary">One correct answer</x-badge>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Multiple Choice</span>
                                        <x-badge variant="info">Multiple correct answers</x-badge>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="mt-3">
                                <h6 class="mb-2">Difficulty Levels:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Easy</span>
                                        <x-badge variant="success">Basic concepts</x-badge>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Medium</span>
                                        <x-badge variant="warning">Application level</x-badge>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Hard</span>
                                        <x-badge variant="danger">Analytical skills</x-badge>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </x-card>
                    
                    <!-- Preview Card -->
                    <x-card class="mx-3 mx-md-0">
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
                    </x-card>
                </div>
            </div>
        </div>
    </main>

    <x-bs-modal id="previewModal" title="MCQ Preview" size="lg" labelledBy="previewModalLabel">
        <div id="previewModalContent">
            {{-- Preview content will be inserted here --}}
        </div>
    </x-bs-modal>

    @push('js')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let optionCounter = 2; // Starting from C (0=A, 1=B, 2=C)
        
        // Get DOM elements
        const optionsContainer = document.getElementById('options-container');
        const correctAnswersContainer = document.getElementById('correct-answers-container');
        const correctAnswerHint = document.getElementById('correct-answer-hint');
        const questionTypeSelect = document.getElementById('question_type');
        const subjectSelect = document.getElementById('subject_id');
        const topicSelect = document.getElementById('topic_id');
        const testTypesContainer = document.getElementById('test-types-container');
        const previewBtn = document.getElementById('preview-btn');
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        
        // Initialize correct answers from old input
        let selectedCorrectAnswers = [];
        @if(old('correct_answers'))
            selectedCorrectAnswers = @json(old('correct_answers'));
        @endif
        
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
                const optionNumber = optionIndex + 1; // 1-based index
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
                const optionNumber = index + 1; // 1-based index
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
                    const oldTestTypeIds = @json(old('test_type_ids', []));
                    
                    testTypes.forEach((type) => {
                        const isChecked = oldTestTypeIds.includes(type.id.toString());
                        
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
            const optionInputs = optionsContainer.querySelectorAll('input[name^="options"]');
            optionInputs.forEach((input, index) => {
                const optionValue = input.value;
                if (optionValue) {
                    const optionLetter = String.fromCharCode(65 + index);
                    const optionNumber = index + 1;
                    const isCorrect = selectedCorrectAnswers.includes(optionNumber.toString());
                    
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
            });
            
            // Get selected test types
            const selectedTestTypes = [];
            const testTypeCheckboxes = testTypesContainer.querySelectorAll('input[name="test_type_ids[]"]:checked');
            testTypeCheckboxes.forEach(checkbox => {
                const label = document.querySelector(`label[for="${checkbox.id}"]`);
                if (label) {
                    selectedTestTypes.push(label.textContent.trim());
                }
            });
            
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
            `;
            
            if (selectedTestTypes.length > 0) {
                previewHTML += `
                    <div class="col-12">
                        <h6 class="text-muted">Test Types:</h6>
                        <p>${selectedTestTypes.join(', ')}</p>
                    </div>
                `;
            }
            
            previewHTML += `
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
        
        if (questionInput && questionPreview) {
            questionInput.addEventListener('input', function() {
                questionPreview.innerHTML = `
                    <h6 class="text-muted mb-2">Question:</h6>
                    <div class="border rounded p-2 bg-light">
                        ${this.value || 'Question preview will appear here'}
                    </div>
                `;
            });
        }
        
        // Initialize correct answers
        updateCorrectAnswers();
        
        // If there's a pre-selected subject on page load, load its test types
        const initialSubjectId = subjectSelect.value;
        if (initialSubjectId) {
            loadTestTypes(initialSubjectId);
        }
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