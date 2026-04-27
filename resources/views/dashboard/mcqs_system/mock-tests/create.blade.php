<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <x-page-header class="mb-4 px-3 px-md-0">
                <x-slot name="heading">
                    <h1 class="h3 mb-2">Create Mock Test</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('mock-tests.index') }}">Mock Tests</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </nav>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('mock-tests.index') }}" variant="outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </x-button>
                </x-slot>
            </x-page-header>

            <!-- Form -->
            <div class="row">
                <div class="col-lg-8">
                    <x-card class="mx-3 mx-md-0">
                        <div class="card-header">
                            <h5 class="mb-0">Test Details</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('mock-tests.store') }}" method="POST" id="mockTestForm">
                                @csrf
                                
                                <!-- Basic Information -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-8">
                                        <label for="title" class="form-label">Test Title *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title') }}" 
                                               placeholder="e.g., Mathematics Practice Test, Physics Final Exam" required>
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
                                            <option value="{{ $type->id }}" {{ old('test_type_id') == $type->id ? 'selected' : '' }}>
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
                                               value="{{ old('total_questions', 20) }}" min="1" required>
                                        @error('total_questions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="total_marks" class="form-label">Total Marks *</label>
                                        <input type="number" class="form-control @error('total_marks') is-invalid @enderror" 
                                               id="total_marks" name="total_marks" 
                                               value="{{ old('total_marks', 20) }}" min="1" required>
                                        @error('total_marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="total_time_minutes" class="form-label">Time (minutes) *</label>
                                        <input type="number" class="form-control @error('total_time_minutes') is-invalid @enderror" 
                                               id="total_time_minutes" name="total_time_minutes" 
                                               value="{{ old('total_time_minutes', 30) }}" min="1" required>
                                        @error('total_time_minutes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="passing_marks" class="form-label">Passing Marks *</label>
                                        <input type="number" class="form-control @error('passing_marks') is-invalid @enderror" 
                                               id="passing_marks" name="passing_marks" 
                                               value="{{ old('passing_marks', 33) }}" min="0" required>
                                        @error('passing_marks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Minimum marks required to pass</small>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="test_mode" class="form-label">Test Mode *</label>
                                        <select class="form-select @error('test_mode') is-invalid @enderror" 
                                                id="test_mode" name="test_mode" required>
                                            <option value="">Select Mode</option>
                                            <option value="practice" {{ old('test_mode') == 'practice' ? 'selected' : '' }}>Practice</option>
                                            <option value="timed" {{ old('test_mode') == 'timed' ? 'selected' : '' }}>Timed</option>
                                            <option value="exam" {{ old('test_mode') == 'exam' ? 'selected' : '' }}>Exam</option>
                                        </select>
                                        @error('test_mode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
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
                                
                                <!-- Description & Instructions -->
                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="3"
                                                  placeholder="Brief description of the test...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="instructions" class="form-label">Instructions</label>
                                        <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                                  id="instructions" name="instructions" rows="4"
                                                  placeholder="Test instructions for students...">{{ old('instructions') }}</textarea>
                                        @error('instructions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Instructions shown to students before starting the test</small>
                                    </div>
                                </div>
                                
                                <!-- Test Settings -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="shuffle_questions" name="shuffle_questions" value="1"
                                                   {{ old('shuffle_questions') ? 'checked' : 'checked' }}>
                                            <label class="form-check-label" for="shuffle_questions">
                                                Shuffle Questions
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="show_result_immediately" name="show_result_immediately" value="1"
                                                   {{ old('show_result_immediately') ? 'checked' : 'checked' }}>
                                            <label class="form-check-label" for="show_result_immediately">
                                                Show Result Immediately
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="allow_retake" name="allow_retake" value="1"
                                                   {{ old('allow_retake') ? 'checked' : 'checked' }}>
                                            <label class="form-check-label" for="allow_retake">
                                                Allow Retake
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="max_attempts" class="form-label">Max Attempts</label>
                                        <input type="number" class="form-control @error('max_attempts') is-invalid @enderror" 
                                               id="max_attempts" name="max_attempts" 
                                               value="{{ old('max_attempts') }}" min="1">
                                        @error('max_attempts')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Leave empty for unlimited attempts</small>
                                    </div>
                                </div>
                                
                                <!-- Pricing -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   id="is_free" name="is_free" value="1"
                                                   {{ old('is_free', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_free">
                                                Free Test
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6" id="price-field" style="display: none;">
                                        <label for="price" class="form-label">Price ({{ config('app.currency', 'PKR') }})</label>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" step="0.01"
                                               value="{{ old('price') }}" min="0">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Subject Breakdown (Optional) -->
                                <div class="mb-4">
                                    <label class="form-label">Subject Breakdown (Optional)</label>
                                    <div id="subject-breakdown-container">
                                        <div class="subject-breakdown-item row g-2 mb-2">
                                            <div class="col-md-6">
                                                <select class="form-select subject-select" name="subject_breakdown[0][subject_id]">
                                                    <option value="">Select Subject</option>
                                                    @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="number" class="form-control" 
                                                       name="subject_breakdown[0][question_count]" 
                                                       placeholder="Question Count" min="1">
                                            </div>
                                            <div class="col-md-2">
                                                <x-button type="button" variant="outline-danger" class="w-100 remove-subject" disabled>
                                                    <i class="fas fa-times"></i>
                                                </x-button>
                                            </div>
                                        </div>
                                    </div>
                                    <x-button type="button" variant="outline-primary" class="btn-sm mt-2" id="add-subject">
                                        <i class="fas fa-plus me-1"></i> Add Subject
                                    </x-button>
                                    <small class="text-muted d-block mt-1">Define how many questions from each subject (optional)</small>
                                </div>
                                
                                <!-- Submit -->
                                <div class="mt-4">
                                    <x-button type="submit" variant="primary">
                                        <i class="fas fa-save me-2"></i> Create Test
                                    </x-button>
                                    <x-button href="{{ route('mock-tests.index') }}" variant="outline-secondary">
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
                                <h6 class="mb-2"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Create test structure first, then add questions</li>
                                    <li>Set appropriate time limits based on difficulty</li>
                                    <li>Define passing marks according to test standards</li>
                                    <li>Choose test mode based on purpose</li>
                                </ul>
                            </x-alert>
                            
                            <div class="mt-3">
                                <h6 class="mb-2">Test Modes:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Practice</span>
                                        <x-badge variant="primary">Unlimited time</x-badge>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Timed</span>
                                        <x-badge variant="warning">Time limited</x-badge>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Exam</span>
                                        <x-badge variant="danger">Strict rules</x-badge>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="mt-3">
                                <h6 class="mb-2">Example Tests:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Mathematics Quiz</span>
                                        <small class="text-muted">20 Q • 30 min</small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Physics Final Exam</span>
                                        <small class="text-muted">50 Q • 60 min</small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>English Practice Test</span>
                                        <small class="text-muted">40 Q • 45 min</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </x-card>
                    
                    <!-- Test Preview -->
                    <x-card class="mx-3 mx-md-0">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Test Preview</h5>
                        </div>
                        <div class="card-body">
                            <div id="test-preview">
                                <p class="text-muted">Test preview will appear here</p>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </main>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isFreeCheckbox = document.getElementById('is_free');
            const priceField = document.getElementById('price-field');
            const subjectBreakdownContainer = document.getElementById('subject-breakdown-container');
            let subjectCounter = 1;
            
            // Toggle price field
            isFreeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    priceField.style.display = 'none';
                    document.getElementById('price').value = '';
                } else {
                    priceField.style.display = 'block';
                }
            });
            
            // Initialize price field
            if (!isFreeCheckbox.checked) {
                priceField.style.display = 'block';
            }
            
            // Add subject breakdown
            document.getElementById('add-subject').addEventListener('click', function() {
                if (subjectCounter >= 10) {
                    alert('Maximum 10 subjects allowed');
                    return;
                }
                
                const subjectItem = document.createElement('div');
                subjectItem.className = 'subject-breakdown-item row g-2 mb-2';
                subjectItem.innerHTML = `
                    <div class="col-md-6">
                        <select class="form-select subject-select" name="subject_breakdown[${subjectCounter}][subject_id]">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" class="form-control" 
                               name="subject_breakdown[${subjectCounter}][question_count]" 
                               placeholder="Question Count" min="1">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger w-100 remove-subject">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                subjectBreakdownContainer.appendChild(subjectItem);
                subjectCounter++;
            });
            
            // Remove subject breakdown
            subjectBreakdownContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-subject')) {
                    const subjectItem = e.target.closest('.subject-breakdown-item');
                    if (subjectBreakdownContainer.children.length <= 1) {
                        alert('Minimum 1 subject required');
                        return;
                    }
                    
                    subjectItem.remove();
                    subjectCounter--;
                    updateSubjectIndexes();
                }
            });
            
            // Update subject indexes after removal
            function updateSubjectIndexes() {
                const subjectItems = subjectBreakdownContainer.querySelectorAll('.subject-breakdown-item');
                subjectItems.forEach((item, index) => {
                    const select = item.querySelector('.subject-select');
                    const input = item.querySelector('input[type="number"]');
                    
                    select.name = `subject_breakdown[${index}][subject_id]`;
                    input.name = `subject_breakdown[${index}][question_count]`;
                    
                    // Update remove button state
                    const removeBtn = item.querySelector('.remove-subject');
                    removeBtn.disabled = subjectItems.length <= 1;
                });
            }
            
            // Test preview
            const titleInput = document.getElementById('title');
            const totalQuestionsInput = document.getElementById('total_questions');
            const totalMarksInput = document.getElementById('total_marks');
            const totalTimeInput = document.getElementById('total_time_minutes');
            const testPreview = document.getElementById('test-preview');
            
            function updateTestPreview() {
                const title = titleInput.value || 'Test Title';
                const questions = totalQuestionsInput.value || '20';
                const marks = totalMarksInput.value || '20';
                const time = totalTimeInput.value || '30';
                
                testPreview.innerHTML = `
                    <h6 class="mb-2">${title}</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="text-center p-2 border rounded">
                                <div class="text-primary fw-bold">${questions}</div>
                                <small class="text-muted">Questions</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-2 border rounded">
                                <div class="text-success fw-bold">${marks}</div>
                                <small class="text-muted">Marks</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-2 border rounded">
                                <div class="text-warning fw-bold">${time}</div>
                                <small class="text-muted">Minutes</small>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Update preview on input
            [titleInput, totalQuestionsInput, totalMarksInput, totalTimeInput].forEach(input => {
                input.addEventListener('input', updateTestPreview);
            });
            
            // Initial preview
            updateTestPreview();
        });
    </script>
    @endpush

    <style>
        .subject-breakdown-item .form-control,
        .subject-breakdown-item .form-select {
            height: 38px;
        }
        
        .remove-subject {
            height: 38px;
        }
        
        #test-preview .row > div {
            padding: 0 5px;
        }
        
        #test-preview .border {
            border-radius: 6px;
        }
    </style>
</x-app-layout>