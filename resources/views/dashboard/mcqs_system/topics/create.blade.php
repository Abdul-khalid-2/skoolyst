<x-app-layout>
    @push('styles')
    <style>
        /* Your existing styles */
        .ck-editor__editable {
            min-height: 300px;
            max-height: 500px;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
    @endpush

    <main class="main-content">
        <!-- Your existing HTML content remains the same until the form -->
        <div class="container-fluid">
            <x-page-header>
                <x-slot name="heading">
                    <h1 class="h3 mb-2">Create Topic</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('topics.index') }}">Topics</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </nav>
                </x-slot>
                <x-slot name="actions">
                    <x-button href="{{ route('topics.index') }}" variant="outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </x-button>
                </x-slot>
            </x-page-header>

            <!-- Form -->
            <div class="row">
                <div class="col-lg-8">
                    <x-card>
                        <div class="card-header">
                            <h5 class="mb-0">Topic Details</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('topics.store') }}" method="POST">
                                @csrf
                                
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label for="title" class="form-label">Topic Title *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title') }}" 
                                               placeholder="e.g., Algebra Basics, Newton's Laws, Grammar Rules" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="subject_id" class="form-label">Subject *</label>
                                        <select class="form-select @error('subject_id') is-invalid @enderror" 
                                                id="subject_id" name="subject_id" required>
                                            <option value="">Select Subject</option>
                                            @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                {{ old('subject_id', $selectedSubject) == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                                @if($subject->testType)
                                                ({{ $subject->testType->name }})
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('subject_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="difficulty_level" class="form-label">Difficulty Level *</label>
                                        <select class="form-select @error('difficulty_level') is-invalid @enderror" 
                                                id="difficulty_level" name="difficulty_level" required>
                                            <option value="">Select Difficulty</option>
                                            <option value="beginner" {{ old('difficulty_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                            <option value="intermediate" {{ old('difficulty_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                            <option value="advanced" {{ old('difficulty_level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                        </select>
                                        @error('difficulty_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="estimated_time_minutes" class="form-label">Estimated Time (minutes)</label>
                                        <input type="number" class="form-control @error('estimated_time_minutes') is-invalid @enderror" 
                                               id="estimated_time_minutes" name="estimated_time_minutes" 
                                               value="{{ old('estimated_time_minutes', 30) }}" min="1">
                                        @error('estimated_time_minutes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Estimated time to complete this topic</small>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="sort_order" class="form-label">Sort Order</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                               id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Lower numbers appear first</small>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="description" class="form-label">Short Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="3"
                                                  placeholder="Brief description of the topic...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                
                                <div class="col-12">
                                    <label for="content" class="form-label">Full Content</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" name="content" rows="10"
                                              placeholder="Detailed content for this topic...">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Full topic content with explanations, examples, etc.</small>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <x-button type="submit" variant="primary">
                                        <i class="fas fa-save me-2"></i> Create Topic
                                    </x-button>
                                    <x-button href="{{ route('topics.index') }}" variant="outline-secondary">
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
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Information</h5>
                        </div>
                        <div class="card-body">
                            <x-alert variant="info" :dismissible="false" :icon="false">
                                <h6><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Topics organize learning material within subjects</li>
                                    <li>Use descriptive titles that students will understand</li>
                                    <li>Set appropriate difficulty levels</li>
                                    <li>Add estimated time for better planning</li>
                                    <li>Full content supports HTML formatting</li>
                                </ul>
                            </x-alert>
                            
                            <div class="mt-3">
                                <h6 class="mb-2">Difficulty Levels:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Beginner</span>
                                        <x-badge variant="success">Basic concepts</x-badge>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Intermediate</span>
                                        <x-badge variant="warning">Moderate difficulty</x-badge>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Advanced</span>
                                        <x-badge variant="danger">Complex topics</x-badge>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="mt-3">
                                <h6 class="mb-2">Example Topics:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Algebra Basics</span>
                                        <x-badge variant="success">Beginner</x-badge>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Trigonometry</span>
                                        <x-badge variant="warning">Intermediate</x-badge>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Calculus</span>
                                        <x-badge variant="danger">Advanced</x-badge>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </main>

    @push('js')
    <!-- Load CKEditor 5 from CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CKEditor
            let editor;
            
            ClassicEditor
                .create(document.querySelector('#content'), {
                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'strikethrough',
                            '|',
                            'bulletedList',
                            'numberedList',
                            '|',
                            'alignment',
                            'outdent',
                            'indent',
                            '|',
                            'link',
                            'blockQuote',
                            'insertTable',
                            'mediaEmbed',
                            '|',
                            'undo',
                            'redo'
                        ]
                    },
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                        ]
                    },
                    table: {
                        contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
                    },
                    // Enable HTML support
                    htmlSupport: {
                        allow: [
                            {
                                name: /.*/,
                                attributes: true,
                                classes: true,
                                styles: true
                            }
                        ]
                    },
                    // Remove the default placeholder if needed
                    placeholder: 'Write your topic content here...',
                })
                .then(newEditor => {
                    editor = newEditor;
                    
                    // Auto-sync with textarea (CKEditor does this automatically)
                    // But you can add custom event listeners if needed
                })
                .catch(error => {
                    console.error('CKEditor initialization error:', error);
                });

            // Auto-generate slug from title (optional)
            const titleInput = document.getElementById('title');
            const slugPreview = document.getElementById('slugPreview');
            
            if (titleInput && slugPreview) {
                titleInput.addEventListener('blur', function() {
                    const slug = this.value.toLowerCase()
                        .replace(/[^\w\s]/gi, '')
                        .replace(/\s+/g, '-');
                    slugPreview.textContent = slug;
                });
            }

            // Form validation to ensure CKEditor content is included
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // CKEditor automatically updates the textarea on form submit
                    // But we can add custom validation if needed
                    if (editor) {
                        const content = editor.getData();
                        if (content.trim() === '') {
                            e.preventDefault();
                            alert('Please enter some content for the topic.');
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>