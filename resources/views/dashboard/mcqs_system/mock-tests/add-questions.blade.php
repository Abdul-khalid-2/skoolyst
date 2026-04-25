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
                            <div class="d-flex flex-wrap gap-3" id="mock-test-stat-badges">
                                <span class="badge bg-primary" id="stat-questions-line">
                                    <i class="fas fa-question-circle me-1"></i>
                                    <span id="stat-questions-text">{{ $mockTest->questions->count() }}/{{ $mockTest->total_questions }} questions</span>
                                </span>
                                <span class="badge bg-success" id="stat-marks-line">
                                    <i class="fas fa-star me-1"></i>
                                    <span id="stat-marks-text">{{ $mockTest->questions->sum('marks') }}/{{ $mockTest->total_marks }} marks</span>
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
                        <div class="card-body" id="current-test-questions-body">
                            @if($mockTest->questions->count() > 0)
                            <div class="list-group list-group-flush" id="current-test-questions-list">
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
                            <div class="text-center mt-2 @if($mockTest->questions->count() <= 10) d-none @endif" id="current-test-questions-view-all-wrap">
                                <a href="{{ route('mock-tests.edit', $mockTest) }}" class="btn btn-sm btn-outline-primary" id="current-test-questions-view-all-link">
                                    View All {{ $mockTest->questions->count() }} Questions
                                </a>
                            </div>
                            @else
                            <div class="text-center py-3" id="current-test-questions-empty">
                                <i class="fas fa-question fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No questions added yet</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Full MCQ preview (card click) -->
        <div class="modal fade" id="mcqPreviewModal" tabindex="-1" aria-labelledby="mcqPreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mcqPreviewModalLabel">Question <span id="mcqPreviewId">—</span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="mcqPreviewMeta" class="mb-3"></div>
                        <div id="mcqPreviewQuestion" class="mb-3 text-break"></div>
                        <h6 class="text-secondary border-bottom pb-2">Options</h6>
                        <ul class="list-group" id="mcqPreviewOptions"></ul>
                        <div id="mcqPreviewExplanation" class="mt-3 p-3 bg-light rounded small text-break d-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @php
        $removeMcqPlaceholder = 900000001;
        $removeQuestionUrlTemplate = route('mock-tests.remove-question', ['mockTest' => $mockTest, 'mcq' => $removeMcqPlaceholder]);
    @endphp
    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush
    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pending to add to test. Already saved rows use `in_test` from the API and show checked.
            let pendingToAdd = new Set();
            let lastRenderedMcqs = [];
            let currentPage = 1;
            let totalPages = 1;
            const removeQuestionUrlTemplate = @json($removeQuestionUrlTemplate);
            const removeQuestionMcqPlaceholder = '{{ $removeMcqPlaceholder }}';
            const CSRF = '{{ csrf_token() }}';
            const mockTestIdForQuery = {{ $mockTest->id }};
            const editMockTestUrl = @json(route('mock-tests.edit', $mockTest));

            const Toast = (typeof Swal !== 'undefined')
                ? Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                })
                : null;

            function showToastMessage(icon, text) {
                if (Toast) {
                    Toast.fire({ icon: icon, text: text });
                } else {
                    alert(text);
                }
            }

            function showToastInfo(text) { showToastMessage('info', text); }
            function showToastSuccess(text) { showToastMessage('success', text); }
            function showToastError(text) { showToastMessage('error', text); }

            function confirmRemoveFromTest() {
                if (typeof Swal === 'undefined') {
                    return Promise.resolve(confirm('Remove this question from the test?'));
                }
                return Swal.fire({
                    title: 'Remove from test?',
                    text: 'This question will be removed from this mock test.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel',
                }).then((r) => r.isConfirmed === true);
            }

            function escapeHtml(str) {
                if (str == null || str === '') return '';
                const d = document.createElement('div');
                d.textContent = str;
                return d.innerHTML;
            }

            function applyTestStateFromResponse(data) {
                if (data.questions_count === undefined) return;
                const qEl = document.getElementById('stat-questions-text');
                const mEl = document.getElementById('stat-marks-text');
                const body = document.getElementById('current-test-questions-body');
                if (qEl) {
                    qEl.textContent = data.questions_count + '/' + data.total_questions + ' questions';
                }
                if (mEl) {
                    mEl.textContent = data.marks_sum + '/' + data.total_marks + ' marks';
                }
                if (!body) return;
                const preview = data.current_questions_preview || [];
                if (!data.questions_count) {
                    body.innerHTML = `
                        <div class="text-center py-3" id="current-test-questions-empty">
                            <i class="fas fa-question fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No questions added yet</p>
                        </div>`;
                    return;
                }
                let rows = '';
                preview.forEach(function(item) {
                    rows += `
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="badge bg-light text-dark me-2">${item.number}</span>
                                    <div class="small question-preview">${escapeHtml(item.preview)}</div>
                                </div>
                                <span class="badge bg-primary">${item.marks}M</span>
                            </div>
                        </div>`;
                });
                const viewAll = data.questions_count > 10
                    ? `<div class="text-center mt-2">
                        <a href="${editMockTestUrl}" class="btn btn-sm btn-outline-primary">
                            View All ${data.questions_count} Questions
                        </a>
                    </div>`
                    : '';
                body.innerHTML = `
                    <div class="list-group list-group-flush">${rows}</div>
                    ${viewAll}`;
            }

            function resolveBootstrap() {
                if (typeof window.bootstrap !== 'undefined' && window.bootstrap.Modal) {
                    return window.bootstrap;
                }
                try {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        return bootstrap;
                    }
                } catch (e) {
                    /* not in global scope (e.g. module) */
                }
                return null;
            }

            /**
             * correct_answers may arrive as a JSON array, object (numeric keys), or a JSON string.
             */
            function normalizeCorrectAnswers(value) {
                if (value == null || value === '') {
                    return [];
                }
                if (typeof value === 'number' && !Number.isNaN(value)) {
                    return [value];
                }
                if (Array.isArray(value)) {
                    return value;
                }
                if (typeof value === 'object') {
                    return Object.values(value);
                }
                if (typeof value === 'string') {
                    try {
                        const p = JSON.parse(value);
                        if (Array.isArray(p)) {
                            return p;
                        }
                        if (p && typeof p === 'object') {
                            return Object.values(p);
                        }
                    } catch (e) {
                        return [];
                    }
                }
                return [];
            }

            /**
             * Options in DB are often 1-based keys: {"1":"A","2":"B"}; may arrive as a JSON string.
             * Match server: sort numeric keys, then return ordered list.
             */
            function normalizeOptionsForPreview(value) {
                if (value == null || value === '') {
                    return [];
                }
                if (typeof value === 'string') {
                    try {
                        return normalizeOptionsForPreview(JSON.parse(value));
                    } catch (e) {
                        return [];
                    }
                }
                if (Array.isArray(value)) {
                    return value;
                }
                if (typeof value === 'object') {
                    const keys = Object.keys(value).map((k) => parseInt(k, 10)).filter((n) => !Number.isNaN(n));
                    if (keys.length && keys.length === Object.keys(value).length) {
                        keys.sort((a, b) => a - b);
                        return keys.map((k) => (value[k] !== undefined ? value[k] : value[String(k)]));
                    }
                    return Object.values(value);
                }
                return [];
            }

            function openMcqPreviewModal(mcq) {
                if (!mcq) {
                    return;
                }
                const el = document.getElementById('mcqPreviewModal');
                if (!el) {
                    return;
                }
                /* Avoid overflow/transform parents hiding fixed modal; Bootstrap expects this pattern */
                if (el.parentNode !== document.body) {
                    document.body.appendChild(el);
                }
                const idEl = document.getElementById('mcqPreviewId');
                const qEl = document.getElementById('mcqPreviewQuestion');
                const metaEl = document.getElementById('mcqPreviewMeta');
                const optList = document.getElementById('mcqPreviewOptions');
                const expEl = document.getElementById('mcqPreviewExplanation');
                if (idEl) {
                    idEl.textContent = '#' + mcq.id;
                }
                if (qEl) {
                    qEl.innerHTML = mcq.question || '<p class="text-muted mb-0">No question text</p>';
                }
                if (metaEl) {
                    const sub = (mcq.subject && mcq.subject.name) ? mcq.subject.name : '—';
                    const top = (mcq.topic && mcq.topic.title) ? mcq.topic.title : '—';
                    const qt = mcq.question_type === 'single' ? 'Single' : (mcq.question_type === 'multiple' ? 'Multiple' : (mcq.question_type || '—'));
                    const diff = mcq.difficulty_level || '—';
                    const diffClass = (diff === 'easy') ? 'success' : ((diff === 'medium') ? 'warning' : 'danger');
                    const inBadge = mcq.in_test
                        ? '<span class="badge bg-success">In this test</span>'
                        : '';
                    const typeBg = (mcq.question_type === 'single') ? 'primary' : 'info';
                    metaEl.innerHTML = `
                        <div class="d-flex flex-wrap gap-1 align-items-center">
                            <span class="badge bg-secondary">${sub}</span>
                            <span class="badge bg-info text-dark">${top}</span>
                            <span class="badge bg-${typeBg}">${qt}</span>
                            <span class="badge bg-${diffClass}">${diff}</span>
                            ${inBadge}
                        </div>`;
                }
                if (optList) {
                    optList.innerHTML = '';
                    const correct = new Set();
                    normalizeCorrectAnswers(mcq.correct_answers).forEach((a) => {
                        if (a === null || a === undefined) {
                            return;
                        }
                        const n = parseInt(a, 10);
                        if (!Number.isNaN(n)) {
                            correct.add(n);
                        }
                    });
                    const options = normalizeOptionsForPreview(mcq.options);
                    if (options.length === 0) {
                        const li = document.createElement('li');
                        li.className = 'list-group-item text-muted';
                        li.textContent = 'No options (check this MCQ in the MCQ library — options may be missing or invalid JSON).';
                        optList.appendChild(li);
                    } else {
                        options.forEach((opt, idx) => {
                            const isCorrect = correct.has(idx);
                            const letter = String.fromCharCode(65 + idx);
                            const li = document.createElement('li');
                            li.className = 'list-group-item d-flex align-items-start' + (isCorrect
                                ? ' list-group-item-success border border-success'
                                : '');
                            li.style.gap = '0.5rem';
                            li.innerHTML = `
                                <span class="badge ${isCorrect ? 'bg-success' : 'bg-secondary'} flex-shrink-0 align-self-start" style="min-width:1.9rem;">${letter}</span>
                                <div class="flex-grow-1 mcq-option-content"></div>
                                ${isCorrect
                                    ? '<span class="badge bg-success flex-shrink-0 align-self-center"><i class="fas fa-check me-1"></i>Correct</span>'
                                    : ''}`;
                            const content = li.querySelector('.mcq-option-content');
                            if (content) {
                                if (opt == null || opt === '') {
                                    content.innerHTML = '<em class="text-muted">Empty</em>';
                                } else {
                                    content.innerHTML = String(opt);
                                }
                            }
                            optList.appendChild(li);
                        });
                    }
                }
                if (expEl) {
                    if (mcq.explanation) {
                        expEl.classList.remove('d-none');
                        expEl.innerHTML = '<h6 class="text-muted">Explanation</h6><div class="mt-2 mcq-explanation-body">' + mcq.explanation + '</div>';
                    } else {
                        expEl.classList.add('d-none');
                        expEl.innerHTML = '';
                    }
                }
                const bs = resolveBootstrap();
                if (!bs || !bs.Modal) {
                    console.error('Bootstrap 5 Modal not found. Ensure bootstrap.bundle.js loads before this script.');
                    showToastError('Could not open the preview. Please refresh the page.');
                    return;
                }
                try {
                    bs.Modal.getOrCreateInstance(el).show();
                } catch (err) {
                    console.error('Bootstrap modal show error', err);
                }
            }
            
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

            /* One listener: works for every re-render, badge clicks, and "Click to view full question" */
            questionsContainer.addEventListener('click', function (e) {
                const card = e.target.closest('.question-card');
                if (!card || !this.contains(card)) {
                    return;
                }
                if (e.target.closest('.form-check')) {
                    return;
                }
                e.preventDefault();
                const id = card.getAttribute('data-mcq-id');
                const mcq = lastRenderedMcqs.find((m) => String(m.id) === String(id));
                if (mcq) {
                    openMcqPreviewModal(mcq);
                }
            });
            questionsContainer.addEventListener('keydown', function (e) {
                if (e.key !== 'Enter' && e.key !== ' ') {
                    return;
                }
                const card = e.target.closest('.question-card');
                if (!card || !this.contains(card)) {
                    return;
                }
                e.preventDefault();
                const id = card.getAttribute('data-mcq-id');
                const mcq = lastRenderedMcqs.find((m) => String(m.id) === String(id));
                if (mcq) {
                    openMcqPreviewModal(mcq);
                }
            });
            
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
                    page: currentPage,
                    mock_test_id: mockTestIdForQuery
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
                    const inTest = !!mcq.in_test;
                    const pending = pendingToAdd.has(String(mcq.id));
                    const isChecked = inTest || pending;
                    const cardClass = inTest
                        ? 'border-success'
                        : (pending ? 'border-primary' : '');
                    const questionPreview = mcq.question.replace(/<[^>]*>/g, '').substring(0, 80) + '...';
                    
                    html += `
                        <div class="col-md-6">
                            <div class="card question-card ${cardClass}" data-mcq-id="${mcq.id}" role="button" tabindex="0" title="View full question and answers">
                                <div class="card-body">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input question-checkbox" 
                                               type="checkbox" 
                                               value="${mcq.id}"
                                               data-in-test="${inTest ? '1' : '0'}"
                                               ${isChecked ? 'checked' : ''}>
                                        <label class="form-check-label">
                                            <strong>Question #${mcq.id}</strong>
                                            ${inTest ? '<span class="badge bg-success ms-1">In test</span>' : ''}
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
                                    <p class="small text-primary mb-0 mt-2"><i class="fas fa-expand-alt me-1"></i>Click to view full question</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                lastRenderedMcqs = questions;
                questionsContainer.innerHTML = html;
                paginationContainer.classList.remove('d-none');
                
                document.querySelectorAll('.question-card .form-check').forEach((fc) => {
                    fc.addEventListener('click', (e) => e.stopPropagation());
                });
                
                // Add event listeners to checkboxes
                document.querySelectorAll('.question-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const box = this;
                        const mcqId = box.value;
                        const inTest = box.dataset.inTest === '1';
                        const card = box.closest('.question-card');

                        if (box.checked) {
                            if (!inTest) {
                                pendingToAdd.add(mcqId);
                            }
                            if (inTest) {
                                card.classList.add('border-success');
                            } else {
                                card.classList.add('border-primary');
                            }
                            updateSelectedCount();
                            renderSelectedQuestions();
                            return;
                        }

                        // Unchecking
                        if (inTest) {
                            box.checked = true;
                            confirmRemoveFromTest().then(function (confirmed) {
                                if (!confirmed) {
                                    return;
                                }
                                const url = removeQuestionUrlTemplate.replace(
                                    new RegExp(removeQuestionMcqPlaceholder + '$'),
                                    String(mcqId)
                                );
                                fetch(url, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': CSRF,
                                        'Accept': 'application/json'
                                    }
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            showToastSuccess('Question removed from the test');
                                            applyTestStateFromResponse(data);
                                            loadQuestions();
                                        } else {
                                            showToastError(data.message || 'Could not remove question');
                                        }
                                    })
                                    .catch(function() {
                                        showToastError('Could not remove question');
                                    });
                            });
                            return;
                        }

                        pendingToAdd.delete(mcqId);
                        card.classList.remove('border-primary');
                        updateSelectedCount();
                        renderSelectedQuestions();
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
            
            // Render selected (pending) questions
            function renderSelectedQuestions() {
                if (pendingToAdd.size === 0) {
                    selectedContainer.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No new questions in queue</p>
                            <p class="small text-muted">Check the boxes (left) for questions not yet in the test. Already added ones show a green <strong>In test</strong> badge and stay checked.</p>
                        </div>
                    `;
                    return;
                }
                
                selectedContainer.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        ${pendingToAdd.size} new question${pendingToAdd.size === 1 ? '' : 's'} will be added
                    </div>
                    <div class="text-center">
                        <p>They will be added with <strong>${defaultMarksInput.value} marks</strong> each (you can change marks on the test edit page)</p>
                    </div>
                `;
            }
            
            // Update selected count
            function updateSelectedCount() {
                selectedCount.textContent = pendingToAdd.size;
            }
            
            // Select all questions on current page (only pending, not "already in test")
            selectAllBtn.addEventListener('click', function() {
                document.querySelectorAll('.question-checkbox').forEach(checkbox => {
                    checkbox.checked = true;
                    if (checkbox.dataset.inTest !== '1') {
                        pendingToAdd.add(checkbox.value);
                    }
                    const card = checkbox.closest('.question-card');
                    if (checkbox.dataset.inTest === '1') {
                        card.classList.add('border-success');
                    } else {
                        card.classList.add('border-primary');
                    }
                });
                updateSelectedCount();
                renderSelectedQuestions();
            });
            
            // Clear only pending (queue), keep in-test checkboxes on
            clearSelectionBtn.addEventListener('click', function() {
                pendingToAdd.clear();
                document.querySelectorAll('.question-checkbox').forEach(checkbox => {
                    const inTest = checkbox.dataset.inTest === '1';
                    checkbox.checked = inTest;
                    const card = checkbox.closest('.question-card');
                    card.classList.remove('border-primary');
                    if (inTest) {
                        card.classList.add('border-success');
                    } else {
                        card.classList.remove('border-success');
                    }
                });
                updateSelectedCount();
                renderSelectedQuestions();
            });
            
            // Add pending questions to test
            addSelectedBtn.addEventListener('click', function() {
                if (pendingToAdd.size === 0) {
                    showToastInfo('Select one or more questions that are not already in the test (no green "In test" badge), or clear your pending selection.');
                    return;
                }
                
                fetch('{{ route("mock-tests.bulk-add-questions", $mockTest) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        mcq_ids: Array.from(pendingToAdd)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToastSuccess(data.message || 'Questions added to the test');
                        pendingToAdd.clear();
                        updateSelectedCount();
                        renderSelectedQuestions();
                        applyTestStateFromResponse(data);
                        loadQuestions();
                    } else {
                        showToastError(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToastError('An error occurred while adding questions');
                });
            });
            
            // Initial state for "Selected Questions" (pending only)
            updateSelectedCount();
            renderSelectedQuestions();
            // Initial load
            loadQuestions();
        });
    </script>
    @endpush

    <style>
        .question-card[role="button"] {
            cursor: pointer;
        }
        
        .question-card[role="button"]:focus-visible {
            outline: 2px solid var(--bs-primary);
            outline-offset: 2px;
        }
        
        #mcqPreviewModal #mcqPreviewQuestion img,
        #mcqPreviewModal .mcq-option-content img {
            max-width: 100%;
            height: auto;
        }
        
        .question-card {
            height: 100%;
            transition: all 0.2s ease;
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