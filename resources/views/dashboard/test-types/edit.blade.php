<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Edit Test Type</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('test-types.index') }}">Test Types</a></li>
                                <li class="breadcrumb-item active">Edit {{ $testType->name }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('test-types.index') }}" class="btn btn-outline-secondary">
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
                            <h5 class="mb-0">Edit Test Type: {{ $testType->name }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('test-types.update', $testType) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label for="name" class="form-label">Test Type Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $testType->name) }}" 
                                               placeholder="e.g., Entry Test, Job Test, University Test" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Unique name for the test type</small>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="active" {{ old('status', $testType->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $testType->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="icon" class="form-label">Icon</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-icons"></i></span>
                                            <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                                   id="icon" name="icon" value="{{ old('icon', $testType->icon) }}"
                                                   placeholder="e.g., fas fa-graduation-cap">
                                        </div>
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Font Awesome icon class (optional)</small>
                                        
                                        <!-- Icon Preview -->
                                        @if(old('icon', $testType->icon))
                                        <div class="mt-2">
                                            <span class="badge bg-light text-dark">
                                                Preview: <i class="{{ old('icon', $testType->icon) }} me-2"></i>
                                                {{ old('icon', $testType->icon) }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="4"
                                                  placeholder="Describe the test type...">{{ old('description', $testType->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="sort_order" class="form-label">Sort Order</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                               id="sort_order" name="sort_order" value="{{ old('sort_order', $testType->sort_order) }}">
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Lower numbers appear first</small>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" value="{{ $testType->slug }}" readonly>
                                        <small class="text-muted">Auto-generated from name</small>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Update Test Type
                                    </button>
                                    <a href="{{ route('test-types.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Important</h6>
                                <p class="mb-0">Changing the name will update the slug. Make sure to update any references to this test type.</p>
                            </div>
                            
                            <div class="mt-3">
                                <h6 class="mb-2">Test Type Details:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Created</span>
                                        <span>{{ $testType->created_at->format('M d, Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Last Updated</span>
                                        <span>{{ $testType->updated_at->format('M d, Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Subjects</span>
                                        <a href="{{ route('subjects.index', ['test_type_id' => $testType->id]) }}" 
                                           class="badge bg-primary">
                                            {{ $testType->subjects_count ?? 0 }} subjects
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            @if($testType->subjects_count > 0)
                            <div class="alert alert-info mt-3">
                                <h6><i class="fas fa-link me-2"></i>Connected Data</h6>
                                <p class="mb-0">This test type has {{ $testType->subjects_count }} subject(s). Deleting it will affect all connected data.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>