<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <x-page-header class="mb-4">
                <x-slot name="heading">
                    <h1 class="h3 mb-2">Test Type Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('test-types.index') }}">Test Types</a></li>
                            <li class="breadcrumb-item active">{{ $testType->name }}</li>
                        </ol>
                    </nav>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('test-types.edit', $testType) }}" variant="primary" class="me-2">
                        <i class="fas fa-edit me-2"></i> Edit
                    </x-button>
                    <x-button href="{{ route('test-types.index') }}" variant="outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </x-button>
                </x-slot>
            </x-page-header>

            <div class="row">
                <div class="col-lg-8">
                    <x-card>
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
                                            <x-badge :variant="$testType->status === \App\Enums\ActiveStatus::Active ? 'success' : 'secondary'">
                                                {{ ucfirst($testType->status->value) }}
                                            </x-badge>
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
                    </x-card>
                </div>

                <div class="col-lg-4">
                    <x-card class="mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-book me-2"></i>Subjects</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <h1 class="display-4 text-primary">{{ $testType->subjects_count ?? 0 }}</h1>
                                <p class="text-muted">Subjects in this test type</p>
                            </div>
                            <x-button href="{{ route('subjects.index', ['test_type_id' => $testType->id]) }}"
                               variant="outline-primary" class="w-100">
                                <i class="fas fa-eye me-2"></i> View Subjects
                            </x-button>
                        </div>
                    </x-card>

                    <x-card>
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>MCQs</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <h1 class="display-4 text-success">{{ $testType->mcqs_count ?? 0 }}</h1>
                                <p class="text-muted">Total MCQs</p>
                            </div>
                            <x-button href="{{ route('mcqs.index', ['test_type_id' => $testType->id]) }}"
                               variant="outline-success" class="w-100">
                                <i class="fas fa-search me-2"></i> View MCQs
                            </x-button>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
