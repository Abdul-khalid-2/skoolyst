<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <!-- Page Header -->
            <div class="page-header mb-4 px-3 px-md-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h3 mb-1 mb-md-2">MCQs Management</h1>
                        <p class="text-muted mb-0 d-none d-md-block">Create and manage multiple choice questions</p>
                        <p class="text-muted mb-0 d-block d-md-none">Manage questions</p>
                    </div>  
                    <div class="w-100 w-md-auto d-flex flex-column flex-sm-row gap-2">
                        <button type="button"
                                class="btn btn-outline-secondary w-100 w-md-auto d-flex align-items-center justify-content-center"
                                data-bs-toggle="modal"
                                data-bs-target="#exportTemplateModal">
                            <i class="fas fa-file-download me-2"></i>
                            <span class="d-none d-sm-inline">Download Template</span>
                            <span class="d-inline d-sm-none">Template</span>
                        </button>
                        <button type="button"
                                class="btn btn-outline-primary w-100 w-md-auto d-flex align-items-center justify-content-center"
                                data-bs-toggle="modal"
                                data-bs-target="#bulkImportMcqModal">
                            <i class="fas fa-file-import me-2"></i>
                            <span class="d-none d-sm-inline">Import MCQs</span>
                            <span class="d-inline d-sm-none">Import</span>
                        </button>
                        <a href="{{ route('mcqs.create') }}" class="btn btn-primary w-100 w-md-auto d-flex align-items-center justify-content-center">
                            <i class="fas fa-plus me-2"></i>
                            <span class="d-none d-sm-inline">Add MCQ</span>
                            <span class="d-inline d-sm-none">Add</span>
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mx-md-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div class="flex-grow-1">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-3 mx-md-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div class="flex-grow-1">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Live search (always visible, outside filter collapse) -->
            <div class="card mb-4 mx-3 mx-md-0 border-0 shadow-sm" id="mcq-live-search-card">
                <div class="card-body py-3">
                    <label for="mcqLiveSearchInput" class="form-label small fw-bold mb-2 text-uppercase text-muted d-flex align-items-center">
                        <i class="fas fa-search me-2"></i> Find MCQ
                    </label>
                    <div class="position-relative mcq-live-search-wrap" id="mcqLiveSearchWrap">
                        <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted" aria-hidden="true" style="z-index: 2;">
                            <i class="fas fa-search"></i>
                        </span>
                        <input
                            type="search"
                            name="mcq_live_q"
                            id="mcqLiveSearchInput"
                            class="form-control form-control-lg ps-5 pe-5 border rounded-3 shadow-sm"
                            placeholder="Search questions, subject, topic…"
                            autocomplete="off"
                            aria-autocomplete="list"
                            aria-controls="mcqLiveSearchResults"
                            aria-expanded="false"
                        />
                        <button
                            type="button"
                            class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-decoration-none text-muted p-0 pe-2 d-none"
                            id="mcqLiveSearchClear"
                            title="Clear"
                            aria-label="Clear"
                            style="z-index: 2; width: 2.5rem;">
                            <i class="fas fa-times"></i>
                        </button>
                        <div
                            class="dropdown-menu show border-0 shadow p-0 mt-1 w-100 rounded-3 overflow-hidden d-none mcq-live-search-dropdown"
                            id="mcqLiveSearchResults"
                            role="listbox"
                            style="max-height: 22rem; z-index: 1050;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters - Collapsible on Mobile -->
            <div class="card mb-4 mx-3 mx-md-0">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3 d-md-none" 
                     data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-filter me-2"></i>
                        <span>Filters</span>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
                
                <div class="collapse d-md-block" style="visibility: visible;" id="filterCollapse">
                    <div class="card-body">
                        <form action="{{ route('mcqs.index') }}" method="GET" class="row g-3" id="mcqIndexFiltersForm">
                            <div class="col-12 col-lg-8">
                                <label class="form-label small fw-bold" for="mcqListSearchInput">Search in list</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fas fa-search" aria-hidden="true"></i></span>
                                    <input
                                        type="text"
                                        name="search"
                                        id="mcqListSearchInput"
                                        class="form-control"
                                        value="{{ request('search') }}"
                                        placeholder="Filter table: question, explanation, tags…"
                                        autocomplete="off"
                                    >
                                    @if (request()->filled('search'))
                                        <a
                                            class="btn btn-outline-secondary d-flex align-items-center"
                                            href="{{ route('mcqs.index', request()->except('search')) }}"
                                            title="Clear text filter"
                                        >
                                            <i class="fas fa-times" aria-hidden="true"></i>
                                        </a>
                                    @else
                                        <span class="btn btn-outline-secondary disabled d-flex align-items-center" tabindex="-1" aria-disabled="true" title="No text filter to clear">
                                            <i class="fas fa-times" aria-hidden="true"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Subject</label>
                                <select name="subject_id" class="form-select form-select-sm js-select2">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Topic</label>
                                <select name="topic_id" class="form-select form-select-sm js-select2">
                                    <option value="">All Topics</option>
                                    @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                        {{ Str::limit($topic->title, 20) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Test Type</label>
                                <select name="test_type_id" class="form-select form-select-sm js-select2">
                                    <option value="">All Types</option>
                                    @foreach($testTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('test_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ Str::limit($type->name, 15) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Difficulty</label>
                                <select name="difficulty_level" class="form-select form-select-sm js-select2">
                                    <option value="">All Levels</option>
                                    <option value="easy" {{ request('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy</option>
                                    <option value="medium" {{ request('difficulty_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="hard" {{ request('difficulty_level') == 'hard' ? 'selected' : '' }}>Hard</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Type</label>
                                <select name="question_type" class="form-select form-select-sm js-select2">
                                    <option value="">All Types</option>
                                    <option value="single" {{ request('question_type') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="multiple" {{ request('question_type') == 'multiple' ? 'selected' : '' }}>Multiple</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Status</label>
                                <select name="status" class="form-select form-select-sm js-select2">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Premium</label>
                                <select name="is_premium" class="form-select form-select-sm js-select2">
                                    <option value="">All</option>
                                    <option value="1" {{ request('is_premium') == '1' ? 'selected' : '' }}>Premium</option>
                                    <option value="0" {{ request('is_premium') == '0' ? 'selected' : '' }}>Free</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label class="form-label small fw-bold">Verified</label>
                                <select name="is_verified" class="form-select form-select-sm js-select2">
                                    <option value="">All</option>
                                    <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>Verified</option>
                                    <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>Unverified</option>
                                </select>
                            </div>
                            
                            <div class="col-12 d-flex align-items-end">
                                <div class="d-flex w-100 flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-filter me-2"></i> 
                                        <span class="d-none d-md-inline">Apply Filters</span>
                                        <span class="d-inline d-md-none">Filter</span>
                                    </button>
                                    <a href="{{ route('mcqs.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center" style="min-width: 45px;">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Stats Cards - Mobile Optimized -->
            <div class="row mb-4 g-3 px-3 px-md-0">
                <div class="col-6 col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small">Total MCQs</h6>
                                    <h4 class="mb-0">{{ $mcqs->total() }}</h4>
                                    <small class="text-success d-none d-md-block">
                                        {{ \App\Models\Mcq::whereDate('created_at', today())->count() }} new today
                                    </small>
                                    <small class="text-success d-block d-md-none">
                                        +{{ \App\Models\Mcq::whereDate('created_at', today())->count() }} today
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
                                    <h6 class="text-muted mb-1 small">Published</h6>
                                    <h4 class="mb-0">{{ \App\Models\Mcq::where('status', 'published')->count() }}</h4>
                                    <small class="text-success d-none d-md-block">
                                        {{ \App\Models\Mcq::where('status', 'published')->where('is_verified', true)->count() }} verified
                                    </small>
                                    <small class="text-success d-block d-md-none">
                                        ✓{{ \App\Models\Mcq::where('status', 'published')->where('is_verified', true)->count() }}
                                    </small>
                                </div>
                                <div class="bg-success bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-check-circle text-success"></i>
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
                                    <h6 class="text-muted mb-1 small">Premium</h6>
                                    <h4 class="mb-0">{{ \App\Models\Mcq::where('is_premium', true)->count() }}</h4>
                                    <small class="text-warning d-none d-md-block">
                                        {{ \App\Models\Mcq::where('is_premium', true)->where('status', 'published')->count() }} published
                                    </small>
                                    <small class="text-warning d-block d-md-none">
                                        ⭐{{ \App\Models\Mcq::where('is_premium', true)->where('status', 'published')->count() }}
                                    </small>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-crown text-warning"></i>
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
                                    <h6 class="text-muted mb-1 small">Needs Review</h6>
                                    <h4 class="mb-0">{{ \App\Models\Mcq::where('is_verified', false)->count() }}</h4>
                                    <small class="text-danger d-none d-md-block">
                                        {{ \App\Models\Mcq::where('is_verified', false)->where('status', 'published')->count() }} published
                                    </small>
                                    <small class="text-danger d-block d-md-none">
                                        ⚠{{ \App\Models\Mcq::where('is_verified', false)->where('status', 'published')->count() }}
                                    </small>
                                </div>
                                <div class="bg-danger bg-opacity-10 p-2 rounded">
                                    <i class="fas fa-exclamation-circle text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions - Responsive -->
            <div class="card mb-3 mx-3 mx-md-0 d-none" id="bulkActionsCard">
                <div class="card-body p-3">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                        <span class="me-md-3 mb-2 mb-md-0 text-center text-md-start" id="selectedCount">0 MCQs selected</span>
                        <div class="d-flex flex-column flex-md-row align-items-center gap-2 w-100 w-md-auto">
                            <select class="form-select form-select-sm me-md-2 flex-grow-1" id="bulkActionSelect">
                                <option value="">Bulk Actions</option>
                                <option value="publish">Publish</option>
                                <option value="draft">Move to Draft</option>
                                <option value="archive">Archive</option>
                                <option value="verify">Verify</option>
                                <option value="unverify">Unverify</option>
                                <option value="delete">Delete</option>
                            </select>
                            <div class="d-flex gap-2 mt-2 mt-md-0">
                                <button class="btn btn-sm btn-primary flex-grow-1" id="applyBulkAction">
                                    <span class="d-none d-md-inline">Apply</span>
                                    <i class="fas fa-check d-inline d-md-none"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" id="clearSelection">
                                    <span class="d-none d-md-inline">Clear</span>
                                    <i class="fas fa-times d-inline d-md-none"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Smart Export Template Modal -->
            @include('dashboard.mcqs_system.mcqs._export_template_modal')

            <!-- Bulk Import MCQs Modal -->
            <div class="modal fade" id="bulkImportMcqModal" tabindex="-1" aria-labelledby="bulkImportMcqModalLabel" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bulkImportMcqModalLabel">
                                <i class="fas fa-file-import me-2"></i> Bulk Import MCQs
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="bulkImportClose"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info d-flex flex-column flex-md-row align-items-start">
                                <i class="fas fa-info-circle me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <strong>Before you start:</strong> Subjects and topics in your file must already exist in the system (matched by name).
                                    Use one of the templates below to see all required and optional columns.
                                    <div class="mt-2 d-flex flex-wrap gap-2">
                                        <a href="{{ route('mcqs.bulk-import.template') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i> Download Sample Template
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#exportTemplateModal"
                                                data-bs-dismiss="modal">
                                            <i class="fas fa-file-download me-1"></i> Download Pre-filled Template
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 1: Upload area --}}
                            <div id="bulkImportStepUpload">
                                <label for="bulkImportFileInput"
                                       id="bulkImportDropzone"
                                       class="d-flex flex-column align-items-center justify-content-center text-center w-100 mb-3"
                                       style="border: 2px dashed #cbd5e1; border-radius: 0.5rem; padding: 2.5rem 1rem; cursor: pointer; background: #f8fafc; transition: background-color .2s, border-color .2s;">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                    <h6 class="mb-1">Drag &amp; drop your file here</h6>
                                    <p class="text-muted mb-2">or click to browse from your computer</p>
                                    <small class="text-muted">Supported formats: <strong>.csv</strong>, <strong>.xlsx</strong>, <strong>.xls</strong> (max 5 MB)</small>
                                    <input type="file"
                                           id="bulkImportFileInput"
                                           class="d-none"
                                           accept=".csv,.xlsx,.xls,text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                </label>

                                <div id="bulkImportFileSummary" class="d-none align-items-center justify-content-between p-3 border rounded bg-white mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-csv fa-2x text-success me-3"></i>
                                        <div>
                                            <div class="fw-bold" id="bulkImportFileName"></div>
                                            <small class="text-muted" id="bulkImportFileSize"></small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="bulkImportRemoveFile">
                                        <i class="fas fa-times me-1"></i> Remove
                                    </button>
                                </div>

                                <div id="bulkImportProgressWrapper" class="d-none mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <small class="text-muted" id="bulkImportProgressLabel">Parsing file...</small>
                                        <small class="text-muted" id="bulkImportProgressPercent">0%</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div id="bulkImportProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>

                                <div id="bulkImportInlineError" class="alert alert-danger d-none mb-0" role="alert"></div>
                            </div>

                            {{-- Step 2: Preview table --}}
                            <div id="bulkImportStepPreview" class="d-none">
                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 gap-2">
                                    <div>
                                        <h6 class="mb-0">Preview &amp; validation</h6>
                                        <small class="text-muted">Review the rows below. Only valid rows will be imported.</small>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-secondary">Total: <span id="bulkImportTotalCount">0</span></span>
                                        <span class="badge bg-success">Valid: <span id="bulkImportValidCount">0</span></span>
                                        <span class="badge bg-danger">Invalid: <span id="bulkImportInvalidCount">0</span></span>
                                    </div>
                                </div>

                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="bulkImportShowOnlyInvalid">
                                    <label class="form-check-label small text-muted" for="bulkImportShowOnlyInvalid">
                                        Show only invalid rows
                                    </label>
                                </div>

                                <div class="table-responsive border rounded" style="max-height: 360px;">
                                    <table class="table table-sm table-hover mb-0 align-middle" id="bulkImportPreviewTable">
                                        <thead class="table-light position-sticky top-0">
                                            <tr>
                                                <th style="width: 60px;">Row</th>
                                                <th style="width: 90px;">Status</th>
                                                <th>Subject / Topic</th>
                                                <th>Question</th>
                                                <th style="width: 100px;">Difficulty</th>
                                                <th style="width: 100px;">Type</th>
                                                <th style="width: 80px;">Correct</th>
                                                <th style="width: 70px;">Marks</th>
                                                <th>Errors</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bulkImportPreviewBody"></tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Step 3: Result summary --}}
                            <div id="bulkImportStepResult" class="d-none">
                                <div class="text-center py-4">
                                    <div id="bulkImportResultIcon" class="mb-3">
                                        <i class="fas fa-check-circle fa-4x text-success"></i>
                                    </div>
                                    <h5 id="bulkImportResultTitle" class="mb-2">Import complete</h5>
                                    <p class="text-muted mb-3" id="bulkImportResultMessage"></p>

                                    <div class="row justify-content-center g-3 mb-3">
                                        <div class="col-6 col-md-4">
                                            <div class="card border-success">
                                                <div class="card-body py-2">
                                                    <div class="text-success fw-bold h4 mb-0" id="bulkImportResultImported">0</div>
                                                    <small class="text-muted">Imported</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <div class="card border-danger">
                                                <div class="card-body py-2">
                                                    <div class="text-danger fw-bold h4 mb-0" id="bulkImportResultFailed">0</div>
                                                    <small class="text-muted">Failed</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="bulkImportResultErrorsWrapper" class="d-none text-start">
                                        <h6 class="mb-2">Errors</h6>
                                        <div class="table-responsive border rounded" style="max-height: 240px;">
                                            <table class="table table-sm mb-0">
                                                <thead class="table-light position-sticky top-0">
                                                    <tr>
                                                        <th style="width: 60px;">Row</th>
                                                        <th style="width: 140px;">Field</th>
                                                        <th>Message</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bulkImportResultErrorsBody"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex flex-wrap gap-2 justify-content-between">
                            <div class="text-muted small" id="bulkImportFooterHint">Choose a file to begin.</div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" id="bulkImportCancelBtn" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-secondary d-none" id="bulkImportBackBtn">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </button>
                                <button type="button" class="btn btn-primary d-none" id="bulkImportConfirmBtn">
                                    <i class="fas fa-cloud-upload-alt me-1"></i> Import <span id="bulkImportConfirmCount"></span>
                                </button>
                                <button type="button" class="btn btn-primary d-none" id="bulkImportDoneBtn" data-bs-dismiss="modal">
                                    Done
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MCQs Table - Responsive -->
            <div class="card mx-3 mx-md-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0" id="mcqsTable">
                            <thead class="d-none d-md-table-header-group">
                                <tr>
                                    <th width="40">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th width="40">#</th>
                                    <th>Question</th>
                                    <th>Subject/Topic</th>
                                    <th>Type/Level</th>
                                    <th width="70">Marks</th>
                                    <th width="100">Status</th>
                                    <th width="150" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mcqs as $mcq)
                                <tr data-id="{{ $mcq->id }}" class="d-none d-md-table-row {{ $mcq->status == 'draft' ? 'table-warning' : ($mcq->status == 'archived' ? 'table-secondary' : '') }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input mcq-checkbox" value="{{ $mcq->id }}">
                                    </td>
                                    <td>{{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="question-preview">
                                            <div class="fw-bold mb-1">
                                                {!! Str::limit(strip_tags($mcq->question), 60) !!}
                                            </div>
                                            <div class="small text-muted">
                                                <span class="badge bg-{{ $mcq->question_type == 'single' ? 'primary' : 'info' }}">
                                                    {{ $mcq->question_type == 'single' ? 'Single' : 'Multiple' }}
                                                </span>
                                                @if($mcq->is_premium)
                                                <span class="badge bg-warning ms-1">Premium</span>
                                                @endif
                                                @if($mcq->is_verified)
                                                <span class="badge bg-success ms-1">✓</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="fw-bold">{{ $mcq->subject->name ?? 'N/A' }}</div>
                                            <div class="text-muted">{{ Str::limit($mcq->topic->title ?? 'N/A', 20) }}</div>
                                            @if($mcq->testType)
                                            <div class="text-muted">
                                                <i class="fas fa-tag fa-xs me-1"></i>{{ Str::limit($mcq->testType->name, 15) }}
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>
                                                <span class="badge bg-{{ $mcq->difficulty_level == 'easy' ? 'success' : ($mcq->difficulty_level == 'medium' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($mcq->difficulty_level) }}
                                                </span>
                                            </div>
                                            @if($mcq->time_limit_seconds)
                                            <div class="text-muted mt-1">
                                                <i class="fas fa-clock fa-xs me-1"></i>{{ $mcq->time_limit_seconds }}s
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="fw-bold">{{ $mcq->marks }}</span>
                                            @if($mcq->negative_marks > 0)
                                            <div class="small text-danger">-{{ $mcq->negative_marks }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $mcq->status == 'published' ? 'success' : ($mcq->status == 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($mcq->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('mcqs.show', $mcq) }}" 
                                               class="btn btn-sm btn-outline-info"
                                               data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('mcqs.edit', $mcq) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!$mcq->is_verified)
                                            <form action="{{ route('mcqs.verify', $mcq) }}" 
                                                  method="POST" class="d-inline"
                                                  data-bs-toggle="tooltip" title="Verify">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{ route('mcqs.unverify', $mcq) }}" 
                                                  method="POST" class="d-inline"
                                                  data-bs-toggle="tooltip" title="Unverify">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif
                                            @if($mcq->mock_tests_count == 0)
                                            <form action="{{ route('mcqs.destroy', $mcq) }}" 
                                                  method="POST" class="d-inline"
                                                  data-bs-toggle="tooltip" title="Delete">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this MCQ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cannot delete MCQ used in mock tests">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Mobile View Card -->
                                <div class="card mb-3 d-block d-md-none mx-3 {{ $mcq->status == 'draft' ? 'border-warning' : ($mcq->status == 'archived' ? 'border-secondary' : '') }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input me-2 mcq-checkbox" value="{{ $mcq->id }}">
                                                <strong class="me-2">#{{ ($mcqs->currentPage() - 1) * $mcqs->perPage() + $loop->iteration }}</strong>
                                                <span class="badge bg-{{ $mcq->status == 'published' ? 'success' : ($mcq->status == 'draft' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($mcq->status) }}
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-{{ $mcq->difficulty_level == 'easy' ? 'success' : ($mcq->difficulty_level == 'medium' ? 'warning' : 'danger') }} me-1">
                                                    {{ ucfirst($mcq->difficulty_level) }}
                                                </span>
                                                <span class="badge bg-{{ $mcq->question_type == 'single' ? 'primary' : 'info' }}">
                                                    {{ $mcq->question_type == 'single' ? 'S' : 'M' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="fw-bold mb-1">
                                                {!! Str::limit(strip_tags($mcq->question), 80) !!}
                                            </div>
                                            <div class="small text-muted">
                                                <div>
                                                    <i class="fas fa-book me-1"></i>
                                                    {{ $mcq->subject->name ?? 'N/A' }}
                                                </div>
                                                @if($mcq->topic)
                                                <div>
                                                    <i class="fas fa-folder me-1"></i>
                                                    {{ Str::limit($mcq->topic->title, 30) }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex flex-wrap gap-1">
                                                @if($mcq->is_premium)
                                                <span class="badge bg-warning">Premium</span>
                                                @endif
                                                @if($mcq->is_verified)
                                                <span class="badge bg-success">Verified</span>
                                                @endif
                                                @if($mcq->time_limit_seconds)
                                                <span class="badge bg-info">
                                                    <i class="fas fa-clock me-1"></i>{{ $mcq->time_limit_seconds }}s
                                                </span>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold">{{ $mcq->marks }} marks</div>
                                                @if($mcq->negative_marks > 0)
                                                <div class="small text-danger">-{{ $mcq->negative_marks }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('mcqs.show', $mcq) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('mcqs.edit', $mcq) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                            <div class="btn-group" role="group">
                                                @if(!$mcq->is_verified)
                                                <form action="{{ route('mcqs.verify', $mcq) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                @else
                                                <form action="{{ route('mcqs.unverify', $mcq) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                @if($mcq->mock_tests_count == 0)
                                                <form action="{{ route('mcqs.destroy', $mcq) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Delete this MCQ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Cannot delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                        
                        @if($mcqs->count() == 0)
                        <div class="text-center py-5">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No MCQs found</h5>
                            <p class="text-muted">Try adjusting your filters or add a new MCQ</p>
                            <a href="{{ route('mcqs.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-2"></i> Add MCQ
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Pagination - Responsive -->
                    @if($mcqs->hasPages())
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top">
                        <div class="text-muted small mb-2 mb-md-0 text-center text-md-start">
                            Showing {{ $mcqs->firstItem() }} to {{ $mcqs->lastItem() }} of {{ $mcqs->total() }}
                        </div>
                        <nav>
                            {{ $mcqs->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
            
            // Bulk selection functionality
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.mcq-checkbox');
            const bulkActionsCard = document.getElementById('bulkActionsCard');
            const selectedCount = document.getElementById('selectedCount');
            const clearSelectionBtn = document.getElementById('clearSelection');
            
            // Select all checkbox
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActions();
            });
            
            // Individual checkbox change
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });
            
            // Clear selection
            clearSelectionBtn.addEventListener('click', function() {
                checkboxes.forEach(checkbox => checkbox.checked = false);
                if (selectAll) selectAll.checked = false;
                updateBulkActions();
            });
            
            // Apply bulk action
            document.getElementById('applyBulkAction').addEventListener('click', function() {
                const action = document.getElementById('bulkActionSelect').value;
                const selectedIds = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                
                if (!action) {
                    alert('Please select an action');
                    return;
                }
                
                if (selectedIds.length === 0) {
                    alert('Please select at least one MCQ');
                    return;
                }
                
                if (action === 'delete') {
                    if (!confirm(`Are you sure you want to delete ${selectedIds.length} MCQ(s)?`)) {
                        return;
                    }
                }
                
                // Submit bulk action
                fetch('{{ route("mcqs.bulk.action") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        action: action,
                        ids: selectedIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            
            function updateBulkActions() {
                const selected = Array.from(checkboxes).filter(cb => cb.checked).length;
                
                if (selected > 0) {
                    if (bulkActionsCard) {
                        bulkActionsCard.classList.remove('d-none');
                        selectedCount.textContent = `${selected} MCQ${selected === 1 ? '' : 's'} selected`;
                    }
                } else {
                    if (bulkActionsCard) {
                        bulkActionsCard.classList.add('d-none');
                        if (selectAll) selectAll.checked = false;
                    }
                }
            }
            
            // Filter collapse icon toggle
            const filterCollapse = document.getElementById('filterCollapse');
            if (filterCollapse) {
                filterCollapse.addEventListener('show.bs.collapse', function() {
                    this.previousElementSibling.querySelector('.fa-chevron-down').className = 'fas fa-chevron-up';
                });
                filterCollapse.addEventListener('hide.bs.collapse', function() {
                    this.previousElementSibling.querySelector('.fa-chevron-up').className = 'fas fa-chevron-down';
                });
            }
            
            // Mobile responsive table initialization
            function initMobileTable() {
                if (window.innerWidth < 768) {
                    // Hide desktop table, show mobile cards
                    document.querySelectorAll('#mcqsTable .d-none.d-md-table-row').forEach(row => {
                        row.style.display = 'none';
                    });
                } else {
                    // Show desktop table, hide mobile cards
                    document.querySelectorAll('#mcqsTable .d-block.d-md-none').forEach(card => {
                        card.style.display = 'none';
                    });
                }
            }
            
            // Initialize and add resize listener
            initMobileTable();
            window.addEventListener('resize', initMobileTable);
            
            initMcqLiveSearch();
            initBulkMcqImport();
        });

        function initMcqLiveSearch() {
            const searchUrl = @json(route('mcqs.search'));
            const showUrlTmpl = @json(route('mcqs.show', ['mcq' => 999999999]));
            const input = document.getElementById('mcqLiveSearchInput');
            const clearBtn = document.getElementById('mcqLiveSearchClear');
            const resultsEl = document.getElementById('mcqLiveSearchResults');
            const wrap = document.getElementById('mcqLiveSearchWrap');
            if (!input || !resultsEl || !wrap) {
                return;
            }

            let debounceT = null;
            let aborter = new AbortController();
            let items = [];
            let active = -1;

            function showUrlFor(id) {
                return String(showUrlTmpl).split('999999999').join(String(id));
            }

            function closeDropdown() {
                resultsEl.classList.add('d-none');
                resultsEl.innerHTML = '';
                input.setAttribute('aria-expanded', 'false');
                items = [];
                active = -1;
            }

            function setActive(i) {
                items.forEach((el, j) => {
                    el.classList.toggle('active', j === i);
                    el.setAttribute('aria-selected', j === i ? 'true' : 'false');
                });
                active = i;
            }

            function escapeMcqText(s) {
                const d = document.createElement('div');
                d.textContent = s == null ? '' : String(s);
                return d.innerHTML;
            }

            function buildRowHtml(row, i) {
                const u = showUrlFor(row.id);
                const snippet = row.highlight ? row.highlight : escapeMcqText(row.excerpt);
                const sub = row.subject
                    ? '<span class="badge bg-secondary bg-opacity-10 text-dark border">' +
                      escapeMcqText(row.subject) +
                      '</span>'
                    : '';
                const cat = row.category
                    ? '<span class="badge bg-info bg-opacity-10 text-dark border">' +
                      escapeMcqText(row.category) +
                      '</span>'
                    : '';
                return (
                    '<a href="' +
                    u +
                    '" class="list-group-item list-group-item-action border-0 border-bottom py-2 px-3 d-flex flex-column align-items-start text-decoration-none text-dark mcq-live-search-item" ' +
                    'role="option" data-index="' +
                    i +
                    '" data-url="' +
                    u +
                    '">' +
                    '<div class="d-flex w-100 justify-content-between align-items-center gap-2 mb-1">' +
                    '<span class="small flex-grow-1 mcq-live-search-snippet">' +
                    snippet +
                    '</span></div>' +
                    '<div class="d-flex flex-wrap gap-1 w-100">' +
                    sub +
                    cat +
                    '</div></a>'
                );
            }

            function runSearch(q) {
                aborter.abort();
                aborter = new AbortController();
                if (!q || q.length < 2) {
                    closeDropdown();
                    return;
                }
                resultsEl.classList.remove('d-none');
                input.setAttribute('aria-expanded', 'true');
                resultsEl.innerHTML = `
                    <div class="px-3 py-4 text-center text-muted small">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <div class="mt-2">Searching…</div>
                    </div>`;
                fetch(searchUrl + '?q=' + encodeURIComponent(q), {
                    signal: aborter.signal,
                    headers: { 'Accept': 'application/json' },
                })
                    .then((r) => r.json())
                    .then((data) => {
                        const list = (data && data.results) ? data.results : [];
                        if (list.length === 0) {
                            resultsEl.innerHTML =
                                '<div class="px-3 py-4 text-center text-muted small border-0">No results found. Try different keywords.</div>';
                            items = [];
                            return;
                        }
                        resultsEl.innerHTML = list.map((row, i) => buildRowHtml(row, i)).join('');
                        items = Array.from(resultsEl.querySelectorAll('.mcq-live-search-item'));
                        items.forEach((a, j) => {
                            a.addEventListener('mouseenter', () => setActive(j));
                            a.addEventListener('click', (e) => {
                                e.preventDefault();
                                window.location.href = a.getAttribute('data-url');
                            });
                        });
                        setActive(0);
                    })
                    .catch((e) => {
                        if (e.name === 'AbortError') {
                            return;
                        }
                        resultsEl.innerHTML =
                            '<div class="px-3 py-2 text-danger small">Search failed. Try again.</div>';
                    });
            }

            function scheduleSearch() {
                clearTimeout(debounceT);
                const v = String(input.value || '').trim();
                clearBtn.classList.toggle('d-none', v.length === 0);
                if (v.length < 2) {
                    closeDropdown();
                    return;
                }
                debounceT = setTimeout(() => runSearch(v), 300);
            }

            input.addEventListener('input', scheduleSearch);
            input.addEventListener('focus', () => {
                if (String(input.value || '').trim().length >= 2) {
                    scheduleSearch();
                }
            });
            clearBtn.addEventListener('click', () => {
                input.value = '';
                clearBtn.classList.add('d-none');
                closeDropdown();
            });
            document.addEventListener('click', (e) => {
                if (!wrap.contains(e.target)) {
                    closeDropdown();
                }
            });
            input.addEventListener('keydown', (e) => {
                if (resultsEl.classList.contains('d-none') || items.length === 0) {
                    if (e.key === 'Enter' && String(input.value || '').trim().length >= 2) {
                        scheduleSearch();
                    }
                    return;
                }
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    setActive(Math.min(items.length - 1, active + 1));
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    setActive(Math.max(0, active - 1));
                } else if (e.key === 'Enter' && active >= 0 && items[active]) {
                    e.preventDefault();
                    window.location.href = items[active].getAttribute('data-url');
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    closeDropdown();
                }
            });
        }

        function initBulkMcqImport() {
            const modalEl = document.getElementById('bulkImportMcqModal');
            if (!modalEl) return;

            const PREVIEW_URL = @json(route('mcqs.bulk-import.preview'));
            const STORE_URL = @json(route('mcqs.bulk-import.store'));
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || @json(csrf_token());

            const dropzone = document.getElementById('bulkImportDropzone');
            const fileInput = document.getElementById('bulkImportFileInput');
            const fileSummary = document.getElementById('bulkImportFileSummary');
            const fileNameEl = document.getElementById('bulkImportFileName');
            const fileSizeEl = document.getElementById('bulkImportFileSize');
            const removeFileBtn = document.getElementById('bulkImportRemoveFile');

            const stepUpload = document.getElementById('bulkImportStepUpload');
            const stepPreview = document.getElementById('bulkImportStepPreview');
            const stepResult = document.getElementById('bulkImportStepResult');

            const totalCountEl = document.getElementById('bulkImportTotalCount');
            const validCountEl = document.getElementById('bulkImportValidCount');
            const invalidCountEl = document.getElementById('bulkImportInvalidCount');
            const previewBody = document.getElementById('bulkImportPreviewBody');
            const showOnlyInvalidEl = document.getElementById('bulkImportShowOnlyInvalid');

            const resultIcon = document.getElementById('bulkImportResultIcon');
            const resultTitle = document.getElementById('bulkImportResultTitle');
            const resultMessage = document.getElementById('bulkImportResultMessage');
            const resultImported = document.getElementById('bulkImportResultImported');
            const resultFailed = document.getElementById('bulkImportResultFailed');
            const resultErrorsWrapper = document.getElementById('bulkImportResultErrorsWrapper');
            const resultErrorsBody = document.getElementById('bulkImportResultErrorsBody');

            const progressWrapper = document.getElementById('bulkImportProgressWrapper');
            const progressBar = document.getElementById('bulkImportProgressBar');
            const progressLabel = document.getElementById('bulkImportProgressLabel');
            const progressPercent = document.getElementById('bulkImportProgressPercent');
            const inlineError = document.getElementById('bulkImportInlineError');

            const cancelBtn = document.getElementById('bulkImportCancelBtn');
            const backBtn = document.getElementById('bulkImportBackBtn');
            const confirmBtn = document.getElementById('bulkImportConfirmBtn');
            const confirmCountEl = document.getElementById('bulkImportConfirmCount');
            const doneBtn = document.getElementById('bulkImportDoneBtn');
            const footerHint = document.getElementById('bulkImportFooterHint');

            let currentFile = null;
            let parsedRows = [];
            let validCount = 0;
            let invalidCount = 0;
            let didImport = false;

            modalEl.addEventListener('hidden.bs.modal', function () {
                resetAll();
                if (didImport) {
                    didImport = false;
                    window.location.reload();
                }
            });

            ['dragenter', 'dragover'].forEach(evt => {
                dropzone.addEventListener(evt, e => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.style.borderColor = '#0d6efd';
                    dropzone.style.background = '#eff6ff';
                });
            });
            ['dragleave', 'drop'].forEach(evt => {
                dropzone.addEventListener(evt, e => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.style.borderColor = '#cbd5e1';
                    dropzone.style.background = '#f8fafc';
                });
            });
            dropzone.addEventListener('drop', e => {
                const files = e.dataTransfer?.files;
                if (files && files.length > 0) handleFileSelected(files[0]);
            });
            fileInput.addEventListener('change', function () {
                if (this.files && this.files.length > 0) handleFileSelected(this.files[0]);
            });

            removeFileBtn.addEventListener('click', () => {
                resetAll();
            });

            backBtn.addEventListener('click', () => {
                showStep('upload');
                confirmBtn.classList.add('d-none');
                backBtn.classList.add('d-none');
            });

            confirmBtn.addEventListener('click', () => {
                if (!currentFile || validCount === 0) return;
                uploadFile(STORE_URL, currentFile, true);
            });

            function handleFileSelected(file) {
                hideError();
                if (!isAcceptedFile(file)) {
                    showError('Unsupported file type. Please upload a .csv, .xlsx or .xls file.');
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    showError('File is too large. Maximum allowed size is 5 MB.');
                    return;
                }
                currentFile = file;
                fileNameEl.textContent = file.name;
                fileSizeEl.textContent = formatBytes(file.size);
                fileSummary.classList.remove('d-none');
                fileSummary.classList.add('d-flex');
                dropzone.classList.add('d-none');
                footerHint.textContent = 'Validating file with the server...';

                uploadFile(PREVIEW_URL, file, false);
            }

            function uploadFile(url, file, isFinalImport) {
                showProgress(isFinalImport ? 'Importing...' : 'Parsing file...');

                const formData = new FormData();
                formData.append('file', file);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', url);
                xhr.setRequestHeader('X-CSRF-TOKEN', CSRF_TOKEN);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                xhr.upload.addEventListener('progress', e => {
                    if (e.lengthComputable) {
                        const pct = Math.round((e.loaded / e.total) * 100);
                        setProgress(pct, isFinalImport ? 'Uploading...' : 'Uploading file...');
                    }
                });

                xhr.onload = function () {
                    setProgress(100, isFinalImport ? 'Processing on server...' : 'Validating rows...');
                    let payload = null;
                    try {
                        payload = JSON.parse(xhr.responseText);
                    } catch (e) {
                        hideProgress();
                        showError('Server returned an unexpected response.');
                        return;
                    }

                    if (xhr.status >= 200 && xhr.status < 300 && payload?.success) {
                        hideProgress();
                        if (isFinalImport) {
                            renderResult(payload);
                        } else {
                            renderPreview(payload);
                        }
                        return;
                    }

                    hideProgress();
                    if (payload && payload.errors && typeof payload.errors === 'object') {
                        const messages = Object.values(payload.errors).flat();
                        showError(messages.join(' ') || (payload.message || 'Validation failed.'));
                    } else {
                        showError(payload?.message || 'Something went wrong while processing the file.');
                    }
                };

                xhr.onerror = function () {
                    hideProgress();
                    showError('Network error. Please check your connection and try again.');
                };

                xhr.send(formData);
            }

            function renderPreview(payload) {
                parsedRows = payload.rows || [];
                validCount = payload.valid || 0;
                invalidCount = payload.invalid || 0;
                totalCountEl.textContent = payload.total || 0;
                validCountEl.textContent = validCount;
                invalidCountEl.textContent = invalidCount;

                renderPreviewRows();

                showStep('preview');
                backBtn.classList.remove('d-none');
                if (validCount > 0) {
                    confirmBtn.classList.remove('d-none');
                    confirmBtn.disabled = false;
                    confirmCountEl.textContent = `(${validCount})`;
                    footerHint.textContent = `${validCount} valid row(s) ready to import.`;
                } else {
                    confirmBtn.classList.add('d-none');
                    footerHint.textContent = 'No valid rows to import. Please fix the errors and try again.';
                }
            }

            function renderPreviewRows() {
                const onlyInvalid = showOnlyInvalidEl.checked;
                previewBody.innerHTML = '';
                let renderedAny = false;
                parsedRows.forEach(row => {
                    if (onlyInvalid && row.valid) return;
                    renderedAny = true;
                    const tr = document.createElement('tr');
                    if (!row.valid) tr.classList.add('table-danger');

                    const errorList = (row.errors || []).map(e => {
                        const field = e.field ? `<strong>${escapeHtml(e.field)}:</strong> ` : '';
                        return `<div class="small text-danger mb-1">${field}${escapeHtml(e.message)}</div>`;
                    }).join('') || '<span class="text-success small"><i class="fas fa-check"></i> OK</span>';

                    const p = row.preview || {};
                    tr.innerHTML = `
                        <td>${row.row_number}</td>
                        <td>
                            <span class="badge bg-${row.valid ? 'success' : 'danger'}">
                                ${row.valid ? 'Valid' : 'Invalid'}
                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold small">${escapeHtml(p.subject || '')}</div>
                            <div class="text-muted small">${escapeHtml(p.topic || '')}</div>
                        </td>
                        <td><div class="small">${escapeHtml(p.question || '')}</div></td>
                        <td><span class="small text-capitalize">${escapeHtml(p.difficulty || '')}</span></td>
                        <td><span class="small">${escapeHtml(p.test_type || '')}</span></td>
                        <td><span class="small">${escapeHtml(p.correct_option || '')}</span></td>
                        <td><span class="small">${escapeHtml(String(p.marks ?? ''))}</span></td>
                        <td>${errorList}</td>
                    `;
                    previewBody.appendChild(tr);
                });

                if (!renderedAny) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td colspan="9" class="text-center text-muted py-3">No rows to display.</td>`;
                    previewBody.appendChild(tr);
                }
            }

            showOnlyInvalidEl.addEventListener('change', renderPreviewRows);

            function renderResult(payload) {
                didImport = (payload.imported || 0) > 0;
                showStep('result');
                backBtn.classList.add('d-none');
                confirmBtn.classList.add('d-none');
                doneBtn.classList.remove('d-none');
                cancelBtn.classList.add('d-none');

                resultImported.textContent = payload.imported || 0;
                resultFailed.textContent = payload.failed || 0;

                if ((payload.imported || 0) > 0 && (payload.failed || 0) === 0) {
                    resultIcon.innerHTML = '<i class="fas fa-check-circle fa-4x text-success"></i>';
                    resultTitle.textContent = 'Import complete';
                    resultMessage.textContent = `Successfully imported ${payload.imported} MCQ(s).`;
                } else if ((payload.imported || 0) > 0 && (payload.failed || 0) > 0) {
                    resultIcon.innerHTML = '<i class="fas fa-exclamation-triangle fa-4x text-warning"></i>';
                    resultTitle.textContent = 'Import partially complete';
                    resultMessage.textContent = `Imported ${payload.imported} MCQ(s); ${payload.failed} row(s) failed.`;
                } else {
                    resultIcon.innerHTML = '<i class="fas fa-times-circle fa-4x text-danger"></i>';
                    resultTitle.textContent = 'Import failed';
                    resultMessage.textContent = 'No MCQs were imported. Please review the errors below.';
                }

                const errors = payload.errors || [];
                if (errors.length > 0) {
                    resultErrorsWrapper.classList.remove('d-none');
                    resultErrorsBody.innerHTML = errors.map(e => `
                        <tr>
                            <td>${e.row ?? ''}</td>
                            <td>${escapeHtml(e.field ?? '-')}</td>
                            <td class="small">${escapeHtml(e.message ?? '')}</td>
                        </tr>
                    `).join('');
                } else {
                    resultErrorsWrapper.classList.add('d-none');
                    resultErrorsBody.innerHTML = '';
                }

                footerHint.textContent = '';
            }

            function showStep(step) {
                stepUpload.classList.toggle('d-none', step !== 'upload');
                stepPreview.classList.toggle('d-none', step !== 'preview');
                stepResult.classList.toggle('d-none', step !== 'result');
            }

            function resetAll() {
                currentFile = null;
                parsedRows = [];
                validCount = 0;
                invalidCount = 0;
                fileInput.value = '';
                fileSummary.classList.add('d-none');
                fileSummary.classList.remove('d-flex');
                dropzone.classList.remove('d-none');
                hideError();
                hideProgress();
                showStep('upload');
                backBtn.classList.add('d-none');
                confirmBtn.classList.add('d-none');
                doneBtn.classList.add('d-none');
                cancelBtn.classList.remove('d-none');
                showOnlyInvalidEl.checked = false;
                footerHint.textContent = 'Choose a file to begin.';
            }

            function showProgress(label) {
                progressWrapper.classList.remove('d-none');
                setProgress(0, label);
            }

            function setProgress(pct, label) {
                progressBar.style.width = pct + '%';
                progressPercent.textContent = pct + '%';
                if (label) progressLabel.textContent = label;
            }

            function hideProgress() {
                progressWrapper.classList.add('d-none');
                setProgress(0, '');
            }

            function showError(msg) {
                inlineError.textContent = msg;
                inlineError.classList.remove('d-none');
            }

            function hideError() {
                inlineError.textContent = '';
                inlineError.classList.add('d-none');
            }

            function isAcceptedFile(file) {
                const name = (file.name || '').toLowerCase();
                if (name.endsWith('.csv') || name.endsWith('.xlsx') || name.endsWith('.xls')) return true;
                const acceptedTypes = [
                    'text/csv', 'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ];
                return acceptedTypes.includes(file.type);
            }

            function formatBytes(bytes) {
                if (!bytes) return '0 B';
                const units = ['B', 'KB', 'MB', 'GB'];
                let i = 0;
                while (bytes >= 1024 && i < units.length - 1) {
                    bytes /= 1024;
                    i++;
                }
                return bytes.toFixed(bytes < 10 && i > 0 ? 1 : 0) + ' ' + units[i];
            }

            function escapeHtml(value) {
                if (value === null || value === undefined) return '';
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }
        }
    </script>
    @endpush

    <style>
        /* Responsive Container */
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }
        
        @media (min-width: 768px) {
            .container-fluid {
                padding-left: var(--bs-gutter-x, 0.75rem);
                padding-right: var(--bs-gutter-x, 0.75rem);
            }
        }
        
        /* Card Hover Effect */
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        /* Question Preview */
        .question-preview {
            max-width: 100%;
        }
        
        @media (min-width: 768px) {
            .question-preview {
                max-width: 400px;
            }
        }
        
        /* Table Responsiveness */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        @media (max-width: 767.98px) {
            .table-responsive {
                border: 0;
                background: transparent;
            }
            
            #mcqsTable {
                min-width: auto;
            }
            
            .card.mb-3.d-block.d-md-none {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            .card.mb-3.d-block.d-md-none:first-child {
                margin-top: 1rem;
            }
        }
        
        /* Badge Adjustments */
        .badge {
            font-size: 0.75em;
        }
        
        @media (max-width: 767.98px) {
            .badge {
                font-size: 0.7em;
                padding: 0.25em 0.5em;
            }
        }
        
        /* Button Group Responsiveness */
        @media (max-width: 575.98px) {
            .btn-group .btn {
                padding: 0.25rem 0.4rem;
                font-size: 0.75rem;
            }
            
            .btn-group .btn i {
                margin: 0;
            }
        }
        
        /* Filter Collapse */
        .card-header[data-bs-toggle="collapse"] {
            cursor: pointer;
            user-select: none;
        }
        
        .card-header[data-bs-toggle="collapse"] .fa-chevron-down,
        .card-header[data-bs-toggle="collapse"] .fa-chevron-up {
            transition: transform 0.3s ease;
        }
        
        /* Pagination for Mobile */
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .pagination .page-item {
            margin: 0.125rem;
        }
        
        /* Status Colors */
        .table-warning {
            background-color: rgba(255, 243, 205, 0.3) !important;
        }
        
        .table-secondary {
            background-color: rgba(108, 117, 125, 0.1) !important;
        }
        
        .border-warning {
            border-color: #ffc107 !important;
        }
        
        .border-secondary {
            border-color: #6c757d !important;
        }
        
        /* Form Controls */
        @media (max-width: 767.98px) {
            .form-select-sm {
                font-size: 0.875rem;
                padding: 0.25rem 0.5rem;
            }
            
            .input-group-sm > .form-control,
            .input-group-sm > .form-select {
                font-size: 0.875rem;
            }
        }
        
        /* Action Buttons */
        .btn-group .form {
            display: inline;
        }
        
        /* Mobile Card Layout */
        @media (max-width: 767.98px) {
            .card.mb-3.d-block.d-md-none .card-body {
                padding: 1rem;
            }
            
            .card.mb-3.d-block.d-md-none .btn-group {
                flex-wrap: nowrap;
            }
        }
        
        /* Ensure proper spacing on mobile */
        @media (max-width: 767.98px) {
            .main-content {
                padding-top: 0.5rem;
            }
            
            .page-header,
            .alert,
            .card.mb-4 {
                margin-left: 0.75rem;
                margin-right: 0.75rem;
            }
        }
        
        /* Stats Cards on Mobile */
        @media (max-width: 767.98px) {
            .col-6 {
                padding-left: 0.375rem;
                padding-right: 0.375rem;
            }
            
            .card-hover .card-body {
                padding: 0.75rem !important;
            }
            
            .card-hover h4 {
                font-size: 1.25rem;
            }
        }

        /* MCQ live search */
        #mcqLiveSearchInput:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        .mcq-live-search-dropdown {
            animation: mcqSearchFade 0.18s ease-out;
        }
        @keyframes mcqSearchFade {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .mcq-live-search-item.active {
            background-color: rgba(13, 110, 253, 0.08) !important;
        }
        .mcq-live-search-snippet .mcq-search-mark {
            padding: 0 0.1em;
            border-radius: 0.2rem;
        }
    </style>
</x-app-layout>

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"></noscript>
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet"></noscript>
@endpush

@push('vendor-js')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js" defer></script>
    <script src="{{ asset('assets/dashboard/js/select2-init.js') }}" defer></script>
@endpush