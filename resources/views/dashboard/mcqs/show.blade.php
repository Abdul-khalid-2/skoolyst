<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">MCQ Details</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('mcqs.index') }}">MCQs</a></li>
                                <li class="breadcrumb-item active">MCQ #{{ $mcq->id }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('mcqs.edit', $mcq) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <a href="{{ route('mcqs.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- MCQ Details -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- Question Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Question</h5>
                        </div>
                        <div class="card-body">
                            <div class="question-content mb-4">
                                {!! $mcq->question !!}
                            </div>
                            
                            <!-- Options -->
                            <h6 class="text-muted mb-3">Options:</h6>
                            <div class="list-group">
                                @php
                                $options = json_decode($mcq->options, true) ?? [];
                                $correctAnswers = json_decode($mcq->correct_answers, true) ?? [];
                                @endphp
                                
                                @foreach($options as $index => $option)
                                <div class="list-group-item {{ in_array($index, $correctAnswers) ? 'list-group-item-success' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="badge bg-light text-dark fs-6">{{ chr(65 + $index) }}</span>
                                        </div>
                                        <div class="flex-grow-1">
                                            {{ $option }}
                                        </div>
                                        @if(in_array($index, $correctAnswers))
                                        <div class="ms-3">
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i> Correct
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Question Info -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Question Type</label>
                                        <p>
                                            <span class="badge bg-{{ $mcq->question_type == 'single' ? 'primary' : 'info' }}">
                                                {{ $mcq->question_type == 'single' ? 'Single Choice' : 'Multiple Choice' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Difficulty Level</label>
                                        <p>
                                            <span class="badge bg-{{ $mcq->difficulty_level == 'easy' ? 'success' : ($mcq->difficulty_level == 'medium' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($mcq->difficulty_level) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Marks</label>
                                        <p>{{ $mcq->marks }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Negative Marks</label>
                                        <p>{{ $mcq->negative_marks }}</p>
                                    </div>
                                </div>
                                @if($mcq->time_limit_seconds)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Time Limit</label>
                                        <p>{{ $mcq->time_limit_seconds }} seconds</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Explanation & Hint -->
                    @if($mcq->explanation || $mcq->hint)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Additional Information</h5>
                        </div>
                        <div class="card-body">
                            @if($mcq->explanation)
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Explanation:</h6>
                                <div class="border rounded p-3 bg-light">
                                    {{ $mcq->explanation }}
                                </div>
                            </div>
                            @endif
                            
                            @if($mcq->hint)
                            <div>
                                <h6 class="text-muted mb-2">Hint:</h6>
                                <div class="alert alert-info">
                                    <i class="fas fa-lightbulb me-2"></i>{{ $mcq->hint }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- MCQ Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>MCQ Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <p>
                                    <span class="badge bg-{{ $mcq->status == 'published' ? 'success' : ($mcq->status == 'draft' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($mcq->status) }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Verification</label>
                                <p>
                                    @if($mcq->is_verified)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i> Verified
                                    </span>
                                    @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation me-1"></i> Unverified
                                    </span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Premium</label>
                                <p>
                                    @if($mcq->is_premium)
                                    <span class="badge bg-warning">
                                        <i class="fas fa-crown me-1"></i> Premium
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">Free</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Created</label>
                                <p>{{ $mcq->created_at->format('F j, Y g:i A') }}</p>
                            </div>
                            
                            @if($mcq->createdBy)
                            <div class="mb-3">
                                <label class="form-label text-muted">Created By</label>
                                <p>{{ $mcq->createdBy->name }}</p>
                            </div>
                            @endif
                            
                            @if($mcq->is_verified && $mcq->approvedBy)
                            <div class="mb-3">
                                <label class="form-label text-muted">Verified By</label>
                                <p>{{ $mcq->approvedBy->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Verified At</label>
                                <p>{{ $mcq->approved_at ? $mcq->approved_at->format('F j, Y g:i A') : 'N/A' }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Subject & Topic -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-book me-2"></i>Subject & Topic</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">Subject</label>
                                <div class="d-flex align-items-center">
                                    @if($mcq->subject)
                                    <div class="color-indicator me-2" 
                                         style="background-color: {{ $mcq->subject->color_code }};"></div>
                                    <div>
                                        <strong>{{ $mcq->subject->name }}</strong>
                                        @if($mcq->subject->testType)
                                        <div class="small text-muted">{{ $mcq->subject->testType->name }}</div>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-muted">No subject assigned</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Topic</label>
                                <p>
                                    @if($mcq->topic)
                                    <strong>{{ $mcq->topic->title }}</strong>
                                    <div class="small text-muted">
                                        <span class="badge bg-{{ $mcq->topic->difficulty_level == 'beginner' ? 'success' : ($mcq->topic->difficulty_level == 'intermediate' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($mcq->topic->difficulty_level) }}
                                        </span>
                                    </div>
                                    @else
                                    <span class="text-muted">No topic assigned</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Test Type</label>
                                <p>
                                    @if($mcq->testType)
                                    <span class="badge bg-light text-dark">
                                        <i class="{{ $mcq->testType->icon ?? 'fas fa-tag' }} me-1"></i>
                                        {{ $mcq->testType->name }}
                                    </span>
                                    @else
                                    <span class="text-muted">No test type</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reference -->
                    @if($mcq->reference_book || $mcq->reference_page || $mcq->tags)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-link me-2"></i>Reference</h5>
                        </div>
                        <div class="card-body">
                            @if($mcq->reference_book)
                            <div class="mb-3">
                                <label class="form-label text-muted">Reference Book</label>
                                <p>{{ $mcq->reference_book }}</p>
                            </div>
                            @endif
                            
                            @if($mcq->reference_page)
                            <div class="mb-3">
                                <label class="form-label text-muted">Reference Page</label>
                                <p>{{ $mcq->reference_page }}</p>
                            </div>
                            @endif
                            
                            @if($mcq->tags)
                            <div>
                                <label class="form-label text-muted">Tags</label>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach(json_decode($mcq->tags, true) as $tag)
                                    <span class="badge bg-secondary">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    @push('js')
    <style>
        .color-indicator {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            display: inline-block;
        }
        
        .question-content {
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .question-content img {
            max-width: 100%;
            height: auto;
        }
        
        .list-group-item-success {
            background-color: rgba(25, 135, 84, 0.1);
            border-color: rgba(25, 135, 84, 0.2);
        }
    </style>
    @endpush
</x-app-layout>