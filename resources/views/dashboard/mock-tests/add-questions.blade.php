<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Add Questions to Test</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('mock-tests.index') }}">Mock Tests</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('mock-tests.edit', $mockTest) }}">{{ $mockTest->title }}</a></li>
                                <li class="breadcrumb-item active">Add Questions</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('mock-tests.edit', $mockTest) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Test
                        </a>
                    </div>
                </div>
            </div>

            <!-- Test Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2">{{ $mockTest->title }}</h5>
                            <div class="d-flex flex-wrap gap-3">
                                <span class="badge bg-primary">
                                    <i class="fas fa-question-circle me-1"></i>
                                    {{ $mockTest->questions->count() }}/{{ $mockTest->total_questions }} questions
                                </span>
                                <span class="badge bg-success">
                                    <i class="fas fa-star me-1"></i>
                                    {{ $mockTest->questions->sum('marks') }}/{{ $mockTest->total_marks }} marks
                                </span>
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $mockTest->total_time_minutes }} min
                                </span>
                                <span class="badge bg-info">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ $mockTest->testType->name ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" id="save-selection">
                                    <i class="fas fa-save me-2"></i> Save Selection
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="select-all-questions">
                                    Select All
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Available Questions -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Available MCQs</h5>
                        </div>
                        <div class="card-body">
                            <!-- Filters -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label class="form-label">Subject</label>
                                    <select class="form-select" id="filter-subject">
                                        <option value="">All Subjects</option>
                                        @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Topic</label>
                                    <select class="form-select" id="filter-topic">
                                        <option value="">All Topics</option>
                                        @foreach($topics as $topic)
                                        <option value="{{ $topic->id }}" data-subject="{{ $topic->subject_id }}">
                                            {{ $topic->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label">Difficulty</label>
                                    <select class="form-select" id="filter-difficulty">
                                        <option value="">All Levels</option>
                                        <option value="easy">Easy</option>
                                        <option value="medium">Medium</option>
                                        <option value="hard">Hard</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label">Type</label>
                                    <select class="form-select" id="filter-type">
                                        <option value="">All Types</option>
                                        <option value="single">Single</option>
                                        <option value="multiple">Multiple</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-primary w-100" id="apply-filters">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>
                                
                                <div class="col-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="filter-search" 
                                               placeholder="Search questions...">
                                        <button class="btn btn-outline-secondary" type="button" id="clear-filters">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Questions List -->
                            <div id="questions-container">
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Loading questions...</p>
                                </div>
                            </div>
                            
                            <!-- Pagination -->
                            <div id="pagination-container" class="d-none">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        <!-- Pagination will be loaded dynamically -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Selected Questions -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Selected Questions</h5>
                            <span class="badge bg-primary" id="selected-count">0</span>
                        </div>
                        <div class="card-body">
                            <div id="selected-questions-container">
                                <div class="text-center py-4">
                                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No questions selected yet</p>
                                    <p class="small text-muted">Select questions from the list on the left</p>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Default Marks</span>
                                    <input type="number" class="form-control" id="default-marks" value="1" min="1">
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-success" id="add-selected-questions">
                                        <i class="fas fa-plus me-2"></i> Add Selected to Test
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" id="clear-selection">
                                        <i class="fas fa-times me-2"></i> Clear Selection
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Test Questions Preview -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Current Test Questions</h5>
                        </div>
                        <div class="card-body">
                            @if($mockTest->questions->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($mockTest->questions->sortBy('question_number')->take(10) as $question)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="badge bg-light text-dark me-2">{{ $question->question_number }}</span>
                                            <div class="small question-preview">
                                                {!! Str::limit(strip_tags($question->mcq->question), 40) !!}
                                            </div>
                                        </div>
                                        <span class="badge bg-primary">{{ $question->marks }}M</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if($mockTest->questions->count() > 10)
                            <div class="text-center mt-2">
                                <a href="{{ route('mock-tests.edit', $mockTest) }}" class="btn btn-sm btn-outline-primary">
                                    View All {{ $mockTest->questions->count() }} Questions
                                </a>
                            </div>
                            @endif
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-question fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No questions added yet</p>
                            </div>
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
            let selectedQuestions = new Set();
            let currentPage = 1;
            let totalPages = 1;
            
            // DOM Elements
            const questionsContainer = document.getElementById('questions-container');
            const selectedContainer = document.getElementById('selected-questions-container');
            const selectedCount = document.getElementById('selected-count');
            const paginationContainer = document.getElementById('pagination-container');
            
            // Filter elements
            const filterSubject = document.getElementById('filter-subject');
            const filterTopic = document.getElementById('filter-topic');
            const filterDifficulty = document.getElementById('filter-difficulty');
            const filterType = document.getElementById('filter-type');
            const filterSearch = document.getElementById('filter-search');
            const applyFiltersBtn = document.getElementById('apply-filters');
            const clearFiltersBtn = document.getElementById('clear-filters');
            
            // Action buttons
            const selectAllBtn = document.getElementById('select-all-questions');
            const clearSelectionBtn = document.getElementById('clear-selection');
            const addSelectedBtn = document.getElementById('add-selected-questions');
            const defaultMarksInput = document.getElementById('default-marks');
            
            // Filter topics by subject
            filterSubject.addEventListener('change', function() {
                const subjectId = this.value;
                const topicOptions = filterTopic.querySelectorAll('option');
                
                topicOptions.forEach(option => {
                    if (!subjectId || option.value === '' || option.dataset.subject === subjectId) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
                
                filterTopic.value = '';
            });
            
            // Apply filters
            applyFiltersBtn.addEventListener('click', function() {
                currentPage = 1;
                loadQuestions();
            });
            
            // Clear filters
            clearFiltersBtn.addEventListener('click', function() {
                filterSubject.value = '';
                filterTopic.value = '';
                filterDifficulty.value = '';
                filterType.value = '';
                filterSearch.value = '';
                
                // Reset topic visibility
                const topicOptions = filterTopic.querySelectorAll('option');
                topicOptions.forEach(option => {
                    option.style.display = 'block';
                });
                
                currentPage = 1;
                loadQuestions();
            });
            
            // Search on Enter key
            filterSearch.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    currentPage = 1;
                    loadQuestions();
                }
            });
            
            // Load questions
            function loadQuestions() {
                const filters = {
                    subject_id: filterSubject.value,
                    topic_id: filterTopic.value,
                    difficulty_level: filterDifficulty.value,
                    question_type: filterType.value,
                    search: filterSearch.value,
                    page: currentPage
                };
                
                questionsContainer.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading questions...</p>
                    </div>
                `;
                
                fetch('{{ route("mock-tests.get-mcqs") }}?' + new URLSearchParams(filters))
                    .then(response => response.json())
                    .then(data => {
                        renderQuestions(data.mcqs.data);
                        renderPagination(data.pagination);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        questionsContainer.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Error loading questions. Please try again.
                            </div>
                        `;
                    });
            }
            
            // Render questions
            function renderQuestions(questions) {
                if (questions.length === 0) {
                    questionsContainer.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No questions found</p>
                            <p class="small text-muted">Try adjusting your filters</p>
                        </div>
                    `;
                    paginationContainer.classList.add('d-none');
                    return;
                }
                
                let html = '<div class="row g-3">';
                
                questions.forEach(mcq => {
                    const isSelected = selectedQuestions.has(mcq.id.toString());
                    const questionPreview = mcq.question.replace(/<[^>]*>/g, '').substring(0, 80) + '...';
                    
                    html += `
                        <div class="col-md-6">
                            <div class="card question-card ${isSelected ? 'border-primary' : ''}">
                                <div class="card-body">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input question-checkbox" 
                                               type="checkbox" 
                                               value="${mcq.id}"
                                               ${isSelected ? 'checked' : ''}>
                                        <label class="form-check-label">
                                            <strong>Question #${mcq.id}</strong>
                                        </label>
                                    </div>
                                    
                                    <p class="card-text small mb-2">${questionPreview}</p>
                                    
                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                        <span class="badge bg-${mcq.question_type == 'single' ? 'primary' : 'info'}">
                                            ${mcq.question_type == 'single' ? 'Single' : 'Multiple'}
                                        </span>
                                        <span class="badge bg-${mcq.difficulty_level == 'easy' ? 'success' : (mcq.difficulty_level == 'medium' ? 'warning' : 'danger')}">
                                            ${mcq.difficulty_level}
                                        </span>
                                        <span class="badge bg-secondary">
                                            ${mcq.subject?.name || 'No Subject'}
                                        </span>
                                        ${mcq.is_premium ? '<span class="badge bg-warning">Premium</span>' : ''}
                                        ${mcq.is_verified ? '<span class="badge bg-success">Verified</span>' : ''}
                                    </div>
                                    
                                    <div class="small text-muted">
                                        <i class="fas fa-book me-1"></i>${mcq.topic?.title || 'No Topic'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                questionsContainer.innerHTML = html;
                paginationContainer.classList.remove('d-none');
                
                // Add event listeners to checkboxes
                document.querySelectorAll('.question-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const mcqId = this.value;
                        
                        if (this.checked) {
                            selectedQuestions.add(mcqId);
                        } else {
                            selectedQuestions.delete(mcqId);
                        }
                        
                        updateSelectedCount();
                        renderSelectedQuestions();
                        
                        // Update card border
                        const card = this.closest('.question-card');
                        if (this.checked) {
                            card.classList.add('border-primary');
                        } else {
                            card.classList.remove('border-primary');
                        }
                    });
                });
            }
            
            // Render pagination
            function renderPagination(pagination) {
                totalPages = pagination.last_page;
                
                let html = '';
                const maxVisiblePages = 5;
                
                // Previous button
                html += `
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage - 1}">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                `;
                
                // Page numbers
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
                
                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    html += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                }
                
                // Next button
                html += `
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage + 1}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                `;
                
                paginationContainer.querySelector('.pagination').innerHTML = html;
                
                // Add event listeners to pagination links
                paginationContainer.querySelectorAll('.page-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = parseInt(this.dataset.page);
                        if (page >= 1 && page <= totalPages && page !== currentPage) {
                            currentPage = page;
                            loadQuestions();
                            window.scrollTo({ top: questionsContainer.offsetTop - 100, behavior: 'smooth' });
                        }
                    });
                });
            }
            
            // Render selected questions
            function renderSelectedQuestions() {
                if (selectedQuestions.size === 0) {
                    selectedContainer.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No questions selected yet</p>
                            <p class="small text-muted">Select questions from the list on the left</p>
                        </div>
                    `;
                    return;
                }
                
                // In a real implementation, you would fetch details for selected questions
                selectedContainer.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        ${selectedQuestions.size} question${selectedQuestions.size === 1 ? '' : 's'} selected
                    </div>
                    <div class="text-center">
                        <p>Questions will be added with <strong>${defaultMarksInput.value} marks</strong> each</p>
                    </div>
                `;
            }
            
            // Update selected count
            function updateSelectedCount() {
                selectedCount.textContent = selectedQuestions.size;
            }
            
            // Select all questions on current page
            selectAllBtn.addEventListener('click', function() {
                document.querySelectorAll('.question-checkbox').forEach(checkbox => {
                    checkbox.checked = true;
                    selectedQuestions.add(checkbox.value);
                    
                    // Update card border
                    const card = checkbox.closest('.question-card');
                    card.classList.add('border-primary');
                });
                
                updateSelectedCount();
                renderSelectedQuestions();
            });
            
            // Clear selection
            clearSelectionBtn.addEventListener('click', function() {
                selectedQuestions.clear();
                
                // Uncheck all checkboxes
                document.querySelectorAll('.question-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                    
                    // Update card border
                    const card = checkbox.closest('.question-card');
                    card.classList.remove('border-primary');
                });
                
                updateSelectedCount();
                renderSelectedQuestions();
            });
            
            // Add selected questions to test
            addSelectedBtn.addEventListener('click', function() {
                if (selectedQuestions.size === 0) {
                    alert('Please select at least one question');
                    return;
                }
                
                const marks = parseInt(defaultMarksInput.value) || 1;
                
                fetch('{{ route("mock-tests.bulk-add-questions", $mockTest) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        mcq_ids: Array.from(selectedQuestions)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        
                        // Clear selection
                        selectedQuestions.clear();
                        updateSelectedCount();
                        renderSelectedQuestions();
                        
                        // Reload questions to update availability
                        loadQuestions();
                        
                        // Uncheck all checkboxes
                        document.querySelectorAll('.question-checkbox').forEach(checkbox => {
                            checkbox.checked = false;
                            const card = checkbox.closest('.question-card');
                            card.classList.remove('border-primary');
                        });
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding questions');
                });
            });
            
            // Initial load
            loadQuestions();
        });
    </script>
    @endpush

    <style>
        .question-card {
            height: 100%;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .question-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .question-card.border-primary {
            border-width: 2px;
        }
        
        .question-checkbox {
            transform: scale(1.2);
        }
        
        .card-text {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        #selected-questions-container {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</x-app-layout>