<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Test Type Details</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('test-types.index') }}">Test Types</a></li>
                                <li class="breadcrumb-item active">{{ $testType->name }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('test-types.edit', $testType) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <a href="{{ route('test-types.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Details Card -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $testType->name }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Name</label>
                                        <p class="fw-bold">{{ $testType->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Slug</label>
                                        <p><code>{{ $testType->slug }}</code></p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Icon</label>
                                        <div>
                                            @if($testType->icon)
                                            <i class="{{ $testType->icon }} fa-2x text-primary me-2"></i>
                                            <span>{{ $testType->icon }}</span>
                                            @else
                                            <span class="text-muted">No icon</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Status</label>
                                        <div>
                                            <span class="badge bg-{{ $testType->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($testType->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Sort Order</label>
                                        <p>{{ $testType->sort_order }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Created</label>
                                        <p>{{ $testType->created_at->format('F j, Y g:i A') }}</p>
                                    </div>
                                </div>
                                
                                @if($testType->description)
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Description</label>
                                        <div class="border rounded p-3 bg-light">
                                            {{ $testType->description }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Subjects Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-book me-2"></i>Subjects</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <h1 class="display-4 text-primary">{{ $testType->subjects_count ?? 0 }}</h1>
                                <p class="text-muted">Subjects in this test type</p>
                            </div>
                            <a href="{{ route('subjects.index', ['test_type_id' => $testType->id]) }}" 
                               class="btn btn-outline-primary w-100">
                                <i class="fas fa-eye me-2"></i> View Subjects
                            </a>
                        </div>
                    </div>
                    
                    <!-- MCQs Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>MCQs</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <h1 class="display-4 text-success">{{ $testType->mcqs_count ?? 0 }}</h1>
                                <p class="text-muted">Total MCQs</p>
                            </div>
                            <a href="{{ route('mcqs.index', ['test_type_id' => $testType->id]) }}" 
                               class="btn btn-outline-success w-100">
                                <i class="fas fa-search me-2"></i> View MCQs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>