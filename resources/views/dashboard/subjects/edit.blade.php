<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Edit Subject</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Subjects</a></li>
                                <li class="breadcrumb-item active">Edit {{ $subject->name }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('subjects.index') }}" class="btn btn-outline-secondary">
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
                            <h5 class="mb-0">Edit Subject: {{ $subject->name }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('subjects.update', $subject) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label for="name" class="form-label">Subject Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $subject->name) }}" 
                                               placeholder="e.g., Mathematics, Physics, English" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="active" {{ old('status', $subject->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $subject->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="test_type_id" class="form-label">Test Type</label>
                                        <select class="form-select @error('test_type_id') is-invalid @enderror" 
                                                id="test_type_id" name="test_type_id">
                                            <option value="">Select Test Type (Optional)</option>
                                            @foreach($testTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('test_type_id', $subject->test_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('test_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="icon" class="form-label">Icon</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-icons"></i></span>
                                            <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                                   id="icon" name="icon" value="{{ old('icon', $subject->icon) }}"
                                                   placeholder="e.g., fas fa-calculator">
                                        </div>
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <!-- Icon Preview -->
                                        @if(old('icon', $subject->icon))
                                        <div class="mt-2">
                                            <span class="badge bg-light text-dark">
                                                Preview: <i class="{{ old('icon', $subject->icon) }} me-2"></i>
                                                {{ old('icon', $subject->icon) }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="color_code" class="form-label">Color Code</label>
                                        <div class="input-group colorpicker">
                                            <input type="color" class="form-control form-control-color" 
                                                   id="color_picker" value="{{ old('color_code', $subject->color_code) }}"
                                                   title="Choose color">
                                            <input type="text" class="form-control @error('color_code') is-invalid @enderror" 
                                                   id="color_code" name="color_code" 
                                                   value="{{ old('color_code', $subject->color_code) }}"
                                                   placeholder="#3B82F6">
                                        </div>
                                        @error('color_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="sort_order" class="form-label">Sort Order</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                               id="sort_order" name="sort_order" value="{{ old('sort_order', $subject->sort_order) }}">
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="4"
                                                  placeholder="Describe the subject...">{{ old('description', $subject->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" value="{{ $subject->slug }}" readonly>
                                        <small class="text-muted">Auto-generated from name</small>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Update Subject
                                    </button>
                                    <a href="{{ route('subjects.index') }}" class="btn btn-outline-secondary">
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
                                <p class="mb-0">Changing the name will update the slug. This may affect existing links.</p>
                            </div>
                            
                            <div class="mt-3">
                                <h6 class="mb-2">Subject Details:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Created</span>
                                        <span>{{ $subject->created_at->format('M d, Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Last Updated</span>
                                        <span>{{ $subject->updated_at->format('M d, Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Topics</span>
                                        <a href="{{ route('topics.index', ['subject_id' => $subject->id]) }}" 
                                           class="badge bg-info">
                                            {{ $subject->topics_count ?? 0 }} topics
                                        </a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>MCQs</span>
                                        <a href="{{ route('mcqs.index', ['subject_id' => $subject->id]) }}" 
                                           class="badge bg-warning">
                                            {{ $subject->mcqs_count ?? 0 }} MCQs
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            @if($subject->topics_count > 0 || $subject->mcqs_count > 0)
                            <div class="alert alert-info mt-3">
                                <h6><i class="fas fa-link me-2"></i>Connected Data</h6>
                                <p class="mb-0">This subject has topics and MCQs. Deleting it will affect all connected data.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Color Preview -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-palette me-2"></i>Current Color</h5>
                        </div>
                        <div class="card-body">
                            <div class="color-preview mb-3">
                                <div class="color-box" 
                                     style="background-color: {{ $subject->color_code }};"></div>
                                <div class="ms-3">
                                    <h6 class="mb-1">{{ $subject->color_code }}</h6>
                                    <small class="text-muted">Current subject color</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorPicker = document.getElementById('color_picker');
            const colorCode = document.getElementById('color_code');
            const colorPreview = document.getElementById('colorPreview');
            const colorText = document.getElementById('colorText');
            
            if (colorPicker && colorCode) {
                // Update color code when color picker changes
                colorPicker.addEventListener('input', function() {
                    colorCode.value = this.value;
                });
                
                // Update color picker when color code changes
                colorCode.addEventListener('input', function() {
                    const color = this.value;
                    if (/^#[0-9A-F]{6}$/i.test(color)) {
                        colorPicker.value = color;
                    }
                });
            }
        });
    </script>
    @endpush

    <style>
        .color-preview {
            display: flex;
            align-items: center;
        }
        
        .color-box {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .form-control-color {
            width: 50px;
            height: 38px;
            padding: 2px;
        }
    </style>
</x-app-layout>