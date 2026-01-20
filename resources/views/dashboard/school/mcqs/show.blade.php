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
                                <li class="breadcrumb-item"><a href="{{ route('school.mcqs.index') }}">School MCQs</a></li>
                                <li class="breadcrumb-item active">MCQ #{{ $mcq->id }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('school.mcqs.edit', $mcq) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <a href="{{ route('school.mcqs.index') }}" class="btn btn-outline-secondary">
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
                            
                            <h6 class="mb-3">Options:</h6>
                            <div class="row g-3">
                                @php
                                $options = json_decode($mcq->options, true);
                                $correctAnswers = explode(',', $mcq->correct_answer);
                                @endphp
                                
                                @foreach($options as $key => $value)
                                <div class="col-md-6">
                                    <div class="card option-card {{ in_array($key, $correctAnswers) ? 'border-success' : 'border-light' }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                @if(in_array($key, $correctAnswers))
                                                <span class="badge bg-success me-2">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                                @endif
                                                <div>
                                                    <strong>{{ $key }}.</strong> {{ $value }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            @if($mcq->explanation)
                            <div class="mt-4">
                                <h6 class="mb-2">Explanation:</h6>
                                <div class="alert alert-light border">
                                    {!! $mcq->explanation !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- MCQ Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>MCQ Information</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Subject</span>
                                    <span class="badge bg-light text-dark">
                                        {{ $mcq->subject->name ?? 'N/A' }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Topic</span>
                                    <span class="badge bg-light text-dark">
                                        {{ $mcq->topic->title ?? 'N/A' }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Question Type</span>
                                    <span class="badge bg-{{ $mcq->question_type == 'single' ? 'primary' : 'info' }}">
                                        {{ ucfirst($mcq->question_type) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Difficulty</span>
                                    <span class="badge bg-{{ $mcq->difficulty_level == 'easy' ? 'success' : ($mcq->difficulty_level == 'medium' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($mcq->difficulty_level) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Marks</span>
                                    <span class="badge bg-primary">{{ $mcq->marks }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Status</span>
                                    <span class="badge bg-{{ $mcq->status == 'published' ? 'success' : ($mcq->status == 'draft' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($mcq->status) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Premium</span>
                                    <span class="badge bg-{{ $mcq->is_premium ? 'warning' : 'secondary' }}">
                                        {{ $mcq->is_premium ? 'Yes' : 'No' }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Verified</span>
                                    <span class="badge bg-{{ $mcq->is_verified ? 'success' : 'secondary' }}">
                                        {{ $mcq->is_verified ? 'Yes' : 'No' }}
                                    </span>
                                </li>
                                @if($mcq->testType)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Test Type</span>
                                    <span>{{ $mcq->testType->name }}</span>
                                </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Created</span>
                                    <span>{{ $mcq->created_at->format('M d, Y') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Created By</span>
                                    <span>{{ $mcq->createdBy->name ?? 'N/A' }}</span>
                                </li>
                                @if($mcq->updated_at != $mcq->created_at)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>Last Updated</span>
                                    <span>{{ $mcq->updated_at->format('M d, Y') }}</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('school.mcqs.edit', $mcq) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i> Edit MCQ
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

    <style>
        .option-card {
            transition: all 0.2s ease;
        }
        
        .option-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .option-card.border-success {
            background-color: rgba(25, 135, 84, 0.05);
        }
        
        .question-content {
            font-size: 1.1rem;
            line-height: 1.6;
        }
    </style>
</x-app-layout>