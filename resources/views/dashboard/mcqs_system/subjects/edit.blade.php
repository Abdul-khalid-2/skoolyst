<x-app-layout>
    <main class="main-content">
        <div class="container-fluid">
            <x-page-header>
                <x-slot name="heading">
                    <h1 class="h3 mb-2">Edit Subject</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Subjects</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('subjects.show', $subject) }}">{{ $subject->name }}</a></li>
                            <li class="breadcrumb-item active">Edit {{ $subject->name }}</li>
                        </ol>
                    </nav>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('subjects.index') }}" variant="outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </x-button>
                </x-slot>
            </x-page-header>

            <!-- Form -->
            <div class="row">
                <div class="col-lg-8">
                    <x-card>
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
                                            <option value="active" {{ old('status', $subject->status->value) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $subject->status->value) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label">Test Types</label>
                                        <div class="border rounded p-3 @error('test_type_ids') border-danger @enderror">
                                            <div class="row g-2">
                                                @php
                                                    $selectedTestTypeIds = $subject->testTypes->pluck('id')->toArray();
                                                    $oldTestTypeIds = old('test_type_ids', $selectedTestTypeIds);
                                                @endphp
                                                @foreach($testTypes as $type)
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="test_type_ids[]" 
                                                               value="{{ $type->id }}" 
                                                               id="test_type_{{ $type->id }}"
                                                               {{ is_array($oldTestTypeIds) && in_array($type->id, $oldTestTypeIds) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="test_type_{{ $type->id }}">
                                                            @if($type->icon)
                                                                <i class="{{ $type->icon }} me-1"></i>
                                                            @endif
                                                            {{ $type->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @if($testTypes->isEmpty())
                                                <p class="text-muted mb-0">No test types available. <a href="{{ route('test-types.create') }}">Create test types first</a></p>
                                            @endif
                                        </div>
                                        @error('test_type_ids')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Select multiple test types for this subject. Leave empty if subject is general.</small>
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
                                            <x-badge variant="light">
                                                Preview: <i class="{{ old('icon', $subject->icon) }} me-2"></i>
                                                {{ old('icon', $subject->icon) }}
                                            </x-badge>
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
                                        <small class="text-muted">Hex color code for subject</small>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="sort_order" class="form-label">Sort Order</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                               id="sort_order" name="sort_order" value="{{ old('sort_order', $subject->sort_order) }}">
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Lower numbers appear first</small>
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
                                    <x-button type="submit" variant="primary">
                                        <i class="fas fa-save me-2"></i> Update Subject
                                    </x-button>
                                    <x-button href="{{ route('subjects.index') }}" variant="outline-secondary">
                                        Cancel
                                    </x-button>
                                </div>
                            </form>
                        </div>
                    </x-card>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <x-card>
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Subject Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="mb-2">Current Test Types:</h6>
                                @if($subject->testTypes->count() > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($subject->testTypes as $testType)
                                            <x-badge variant="light">
                                                @if($testType->icon)
                                                    <i class="{{ $testType->icon }} me-1"></i>
                                                @endif
                                                {{ $testType->name }}
                                            </x-badge>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0">No test types assigned</p>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="mb-2">Statistics:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                                        <span>Topics</span>
                                        <x-badge variant="primary">{{ $subject->topics_count ?? 0 }}</x-badge>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                                        <span>MCQs</span>
                                        <x-badge variant="success">{{ $subject->mcqs_count ?? 0 }}</x-badge>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                                        <span>Created</span>
                                        <small class="text-muted">{{ $subject->created_at->format('M d, Y') }}</small>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                                        <span>Last Updated</span>
                                        <small class="text-muted">{{ $subject->updated_at->format('M d, Y') }}</small>
                                    </li>
                                </ul>
                            </div>
                            
                            <x-alert variant="info" :dismissible="false" :icon="false">
                                <h6><i class="fas fa-lightbulb me-2"></i>Editing Tips</h6>
                                <ul class="mb-0 ps-3 small">
                                    <li>Changing test types will update all related MCQs</li>
                                    <li>Subjects can belong to multiple test types</li>
                                    <li>Color changes will reflect on the subject cards</li>
                                    <li>Sort order affects display sequence</li>
                                </ul>
                            </x-alert>
                        </div>
                    </x-card>
                    
                    <!-- Color Preview -->
                    <x-card class="mt-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-palette me-2"></i>Color Preview</h5>
                        </div>
                        <div class="card-body">
                            <div class="color-preview mb-3">
                                <div class="color-box" id="colorPreview" 
                                     style="background-color: {{ old('color_code', $subject->color_code) }};"></div>
                                <div class="ms-3">
                                    <h6 class="mb-1" id="colorText">{{ old('color_code', $subject->color_code) }}</h6>
                                    <small class="text-muted">Subject color preview</small>
                                </div>
                            </div>
                            <small class="text-muted">This color will be used for subject cards and indicators.</small>
                        </div>
                    </x-card>
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
            
            // Update color code when color picker changes
            colorPicker.addEventListener('input', function() {
                colorCode.value = this.value;
                updateColorPreview(this.value);
            });
            
            // Update color preview when color code changes
            colorCode.addEventListener('input', function() {
                const color = this.value;
                if (/^#[0-9A-F]{6}$/i.test(color)) {
                    colorPicker.value = color;
                    updateColorPreview(color);
                }
            });
            
            function updateColorPreview(color) {
                colorPreview.style.backgroundColor = color;
                colorText.textContent = color;
            }
            
            // Add select all/deselect all functionality for test types
            const testTypesContainer = document.querySelector('.border.rounded.p-3');
            if (testTypesContainer) {
                const checkboxes = testTypesContainer.querySelectorAll('input[type="checkbox"]');
                
                // Create select all/deselect all buttons
                const buttonGroup = document.createElement('div');
                buttonGroup.className = 'd-flex gap-2 mb-2';
                buttonGroup.innerHTML = `
                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllTestTypes">
                        <i class="fas fa-check-square me-1"></i> Select All
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllTestTypes">
                        <i class="fas fa-square me-1"></i> Deselect All
                    </button>
                `;
                testTypesContainer.prepend(buttonGroup);
                
                document.getElementById('selectAllTestTypes').addEventListener('click', function() {
                    checkboxes.forEach(checkbox => checkbox.checked = true);
                });
                
                document.getElementById('deselectAllTestTypes').addEventListener('click', function() {
                    checkboxes.forEach(checkbox => checkbox.checked = false);
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
        
        .form-check-label {
            cursor: pointer;
        }
        
        .badge {
            font-size: 0.85em;
        }
    </style>
</x-app-layout>