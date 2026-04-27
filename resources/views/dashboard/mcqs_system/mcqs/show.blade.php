<x-app-layout>
    <main class="main-content">
        <div class="container-fluid px-0 px-md-3">
            <x-page-header class="mb-4 px-3 px-md-0">
                <x-slot name="heading">
                    <h1 class="h3 mb-2">MCQ Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('mcqs.index') }}">MCQs</a></li>
                            <li class="breadcrumb-item active">MCQ #{{ $mcq->id }}</li>
                        </ol>
                    </nav>
                </x-slot>
                <x-slot name="actions">
                    <div class="d-flex flex-wrap gap-2">
                        <x-button href="{{ route('mcqs.edit', $mcq) }}" variant="primary" class="me-0 me-sm-2">
                            <i class="fas fa-edit me-2"></i> Edit
                        </x-button>
                        <x-button href="{{ route('mcqs.index') }}" variant="outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </x-button>
                    </div>
                </x-slot>
            </x-page-header>

            <!-- MCQ Details -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- Question Card -->
                    <x-card class="mb-4 mx-3 mx-md-0">
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
                                            <x-badge variant="light" class="text-dark fs-6">{{ chr(65 + $index) }}</x-badge>
                                        </div>
                                        <div class="flex-grow-1">
                                            {{ $option }}
                                        </div>
                                        @if(in_array($index, $correctAnswers))
                                        <div class="ms-3">
                                            <x-badge variant="success">
                                                <i class="fas fa-check me-1"></i> Correct
                                            </x-badge>
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
                                            <x-badge :variant="$mcq->question_type == 'single' ? 'primary' : 'info'">
                                                {{ $mcq->question_type == 'single' ? 'Single Choice' : 'Multiple Choice' }}
                                            </x-badge>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Difficulty Level</label>
                                        <p>
                                            <x-badge :variant="$mcq->difficulty_badge_variant">
                                                {{ $mcq->difficulty_label }}
                                            </x-badge>
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
                    </x-card>

                    <!-- Explanation & Hint -->
                    @if($mcq->explanation || $mcq->hint)
                    <x-card class="mb-4 mx-3 mx-md-0">
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
                                <x-alert variant="info" :dismissible="false" :icon="false">
                                    <i class="fas fa-lightbulb me-2"></i>{{ $mcq->hint }}
                                </x-alert>
                            </div>
                            @endif
                        </div>
                    </x-card>
                    @endif
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- MCQ Info -->
                    <x-card class="mb-4 mx-3 mx-md-0">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>MCQ Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <p>
                                    <x-badge :variant="$mcq->status->value === 'published' ? 'success' : ($mcq->status->value === 'draft' ? 'warning' : 'secondary')">
                                        {{ ucfirst($mcq->status->value) }}
                                    </x-badge>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Verification</label>
                                <p>
                                    @if($mcq->is_verified)
                                    <x-badge variant="success">
                                        <i class="fas fa-check me-1"></i> Verified
                                    </x-badge>
                                    @else
                                    <x-badge variant="warning">
                                        <i class="fas fa-exclamation me-1"></i> Unverified
                                    </x-badge>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Premium</label>
                                <p>
                                    @if($mcq->is_premium)
                                    <x-badge variant="warning">
                                        <i class="fas fa-crown me-1"></i> Premium
                                    </x-badge>
                                    @else
                                    <x-badge variant="secondary">Free</x-badge>
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
                    </x-card>
                    
                    <!-- Subject & Topic -->
                    <x-card class="mb-4 mx-3 mx-md-0">
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
                                        <x-badge :variant="$mcq->topic->difficulty_badge_variant">
                                            {{ $mcq->topic->formatted_difficulty }}
                                        </x-badge>
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
                                    <x-badge variant="light" class="text-dark">
                                        <i class="{{ $mcq->testType->icon ?? 'fas fa-tag' }} me-1"></i>
                                        {{ $mcq->testType->name }}
                                    </x-badge>
                                    @else
                                    <span class="text-muted">No test type</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </x-card>
                    
                    <!-- Reference -->
                    @if($mcq->reference_book || $mcq->reference_page || $mcq->tags)
                    <x-card class="mx-3 mx-md-0">
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
                                    <x-badge variant="secondary">{{ $tag }}</x-badge>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </x-card>
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