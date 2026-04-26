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
                                <li class="breadcrumb-item"><a href="{{ route('school.mcqs.index') }}">School MCQs</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('school.mcqs.show', $mcq) }}">MCQ #{{ $mcq->id }}</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('school.mcqs.show', $mcq) }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-eye me-2"></i> View
                        </a>
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
                            <h5 class="mb-0">Edit MCQ: #{{ $mcq->id }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('school.mcqs.update', $mcq) }}" method="POST" id="mcqForm">
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
                                            <option value="{{ $subject->id }}" {{ old('subject_id', $mcq->subject_id) == $subject->id ? 'selected' : '' }}>
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
                                                    {{ old('topic_id', $mcq->topic_id) == $topic->id ? 'selected' : '' }}>
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
                                            <option value="single" {{ old('question_type', $mcq->question_type) == 'single' ? 'selected' : '' }}>Single Correct</option>
                                            <option value="multiple" {{ old('question_type', $mcq->question_type) == 'multiple' ? 'selected' : '' }}>Multiple Correct</option>
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
                                            <option value="easy" {{ old('difficulty_level', $mcq->difficulty_value) == 'easy' ? 'selected' : '' }}>Easy</option>
                                            <option value="medium" {{ old('difficulty_level', $mcq->difficulty_value) == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="hard" {{ old('difficulty_level', $mcq->difficulty_value) == 'hard' ? 'selected' : '' }}>Hard</option>
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
                                            <option value="{{ $type->id }}" {{ old('test_type_id', $mcq->test_type_id) == $type->id ? 'selected' : '' }}>
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
                                               id="marks" name="marks" value="{{ old('marks', $mcq->marks) }}" min="1" required>
                                        @error('marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
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
                                </div>
                                
                                <!-- Question -->
                                <div class="mb-4">
                                    <label for="question" class="form-label">Question *</label>
                                    <textarea class="form-control @error('question') is-invalid @enderror" 
                                              id="question" name="question" rows="4" required>{{ old('question', $mcq->question) }}</textarea>
                                    @error('question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Options -->
                                <div class="mb-4">
                                    <label class="form-label">Options *</label>
                                    <div class="row g-3">
                                        @php
                                        $options = json_decode($mcq->options, true);
                                        $correctAnswers = explode(',', $mcq->correct_answer);
                                        @endphp
                                        
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">A</span>
                                                <input type="text" class="form-control @error('option_a') is-invalid @enderror" 
                                                       id="option_a" name="option_a" value="{{ old('option_a', $options['A'] ?? '') }}" required>
                                                @error('option_a')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">B</span>
                                                <input type="text" class="form-control @error('option_b') is-invalid @enderror" 
                                                       id="option_b" name="option_b" value="{{ old('option_b', $options['B'] ?? '') }}" required>
                                                @error('option_b')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">C</span>
                                                <input type="text" class="form-control @error('option_c') is-invalid @enderror" 
                                                       id="option_c" name="option_c" value="{{ old('option_c', $options['C'] ?? '') }}">
                                                @error('option_c')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">D</span>
                                                <input type="text" class="form-control @error('option_d') is-invalid @enderror" 
                                                       id="option_d" name="option_d" value="{{ old('option_d', $options['D'] ?? '') }}">
                                                @error('option_d')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">E</span>
                                                <input type="text" class="form-control @error('option_e') is-invalid @enderror" 
                                                       id="option_e" name="option_e" value="{{ old('option_e', $options['E'] ?? '') }}">
                                                @error('option_e')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Correct Answer -->
                                <div class="mb-4">
                                    <label for="correct_answer" class="form-label">Correct Answer(s) *</label>
                                    <div id="correctAnswerContainer">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input correct-answer" type="checkbox" id="correct_a" name="correct_a" value="A" 
                                                   {{ in_array('A', $correctAnswers) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="correct_a">
                                                Option A
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input correct-answer" type="checkbox" id="correct_b" name="correct_b" value="B"
                                                   {{ in_array('B', $correctAnswers) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="correct_b">
                                                Option B
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input correct-answer" type="checkbox" id="correct_c" name="correct_c" value="C"
                                                   {{ in_array('C', $correctAnswers) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="correct_c">
                                                Option C
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input correct-answer" type="checkbox" id="correct_d" name="correct_d" value="D"
                                                   {{ in_array('D', $correctAnswers) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="correct_d">
                                                Option D
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input correct-answer" type="checkbox" id="correct_e" name="correct_e" value="E"
                                                   {{ in_array('E', $correctAnswers) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="correct_e">
                                                Option E
                                            </label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="correct_answer" id="correct_answer" value="{{ old('correct_answer', $mcq->correct_answer) }}" required>
                                    @error('correct_answer')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Explanation -->
                                <div class="mb-4">
                                    <label for="explanation" class="form-label">Explanation</label>
                                    <textarea class="form-control @error('explanation') is-invalid @enderror" 
                                              id="explanation" name="explanation" rows="3">{{ old('explanation', $mcq->explanation) }}</textarea>
                                    @error('explanation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Premium Checkbox -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" 
                                               id="is_premium" name="is_premium" value="1"
                                               {{ old('is_premium', $mcq->is_premium) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_premium">
                                            Premium Question
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Submit -->
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Update MCQ
                                    </button>
                                    <a href="{{ route('school.mcqs.show', $mcq) }}" class="btn btn-outline-secondary">
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
                                    <span>Created By</span>
                                    <span>{{ $mcq->createdBy->name ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Last Updated</span>
                                    <span>{{ $mcq->updated_at->format('M d, Y') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Verification Status</span>
                                    <span class="badge bg-{{ $mcq->is_verified ? 'success' : 'warning' }}">
                                        {{ $mcq->is_verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </li>
                            </ul>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Note:</strong> Updating this MCQ will reset its verification status.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('school.mcqs.show', $mcq) }}" class="btn btn-outline-info">
                                    <i class="fas fa-eye me-2"></i> View MCQ
                                </a>
                                <form action="{{ route('school.mcqs.destroy', $mcq) }}" method="POST" class="d-grid">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this MCQ?')">
                                        <i class="fas fa-trash me-2"></i> Delete MCQ
                                    </button>
                                </form>
                                <a href="{{ route('school.mcqs.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-list me-2"></i> All MCQs
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
            const subjectSelect = document.getElementById('subject_id');
            const topicSelect = document.getElementById('topic_id');
            const questionTypeSelect = document.getElementById('question_type');
            const correctAnswerCheckboxes = document.querySelectorAll('.correct-answer');
            const correctAnswerHidden = document.getElementById('correct_answer');
            
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
            
            // Initial setup
            updateCorrectAnswerUI();
        });
    </script>
    @endpush
</x-app-layout>