<x-app-layout>
    @push('styles')
    <style>
        /* CKEditor customization */
        .ck-editor__editable {
            min-height: 350px;
            max-height: 600px;
        }
        
        /* Your existing styles */
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        /* Style for read-only slug field */
        input[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
    </style>
    @endpush

    <main class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Edit Topic</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('topics.index') }}">Topics</a></li>
                                <li class="breadcrumb-item active">Edit {{ $topic->title }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('topics.index') }}" class="btn btn-outline-secondary">
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
                            <h5 class="mb-0">Edit Topic: {{ $topic->title }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('topics.update', $topic) }}" method="POST" id="topicForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label for="title" class="form-label">Topic Title *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title', $topic->title) }}" 
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
                                            <option value="active" {{ old('status', $topic->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $topic->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                                {{ old('subject_id', $topic->subject_id) == $subject->id ? 'selected' : '' }}>
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
                                            <option value="beginner" {{ old('difficulty_level', $topic->difficulty_level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                            <option value="intermediate" {{ old('difficulty_level', $topic->difficulty_level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                            <option value="advanced" {{ old('difficulty_level', $topic->difficulty_level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                        </select>
                                        @error('difficulty_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="estimated_time_minutes" class="form-label">Estimated Time (minutes)</label>
                                        <input type="number" class="form-control @error('estimated_time_minutes') is-invalid @enderror" 
                                               id="estimated_time_minutes" name="estimated_time_minutes" 
                                               value="{{ old('estimated_time_minutes', $topic->estimated_time_minutes) }}" min="1">
                                        @error('estimated_time_minutes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="sort_order" class="form-label">Sort Order</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                               id="sort_order" name="sort_order" value="{{ old('sort_order', $topic->sort_order) }}">
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" value="{{ $topic->slug }}" readonly>
                                        <small class="text-muted">Auto-generated from title (read-only)</small>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="description" class="form-label">Short Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="3"
                                                  placeholder="Brief description of the topic...">{{ old('description', $topic->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="content" class="form-label">Full Content</label>
                                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                                  id="content" name="content" rows="10"
                                                  placeholder="Detailed content for this topic...">{{ old('content', $topic->content) }}</textarea>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Full topic content with explanations, examples, etc.</small>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Update Topic
                                    </button>
                                    <a href="{{ route('topics.index') }}" class="btn btn-outline-secondary">
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
                                <p class="mb-0">Changing the title will update the slug. This may affect existing links.</p>
                            </div>
                            
                            <div class="mt-3">
                                <h6 class="mb-2">Topic Details:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Created</span>
                                        <span>{{ $topic->created_at->format('M d, Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Last Updated</span>
                                        <span>{{ $topic->updated_at->format('M d, Y') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>MCQs</span>
                                        <a href="{{ route('mcqs.index', ['topic_id' => $topic->id]) }}" 
                                           class="badge bg-warning text-decoration-none">
                                            {{ $topic->mcqs_count ?? 0 }} MCQs
                                        </a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>Subject</span>
                                        <span class="badge" style="background-color: {{ $topic->subject->color_code ?? '#6c757d' }}">
                                            {{ $topic->subject->name ?? 'N/A' }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            
                            @if($topic->mcqs_count > 0)
                            <div class="alert alert-info mt-3">
                                <h6><i class="fas fa-link me-2"></i>Connected Data</h6>
                                <p class="mb-0">This topic has {{ $topic->mcqs_count }} MCQ(s). Deleting it will affect all connected data.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('js')
    <!-- Load CKEditor 5 from CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let editor;
            let isEditorDirty = false;
            
            // Initialize CKEditor for content field
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
                            'redo',
                            '|',
                            'removeFormat'
                        ]
                    },
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                            { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
                        ]
                    },
                    table: {
                        contentToolbar: [
                            'tableColumn',
                            'tableRow',
                            'mergeTableCells',
                            'tableProperties',
                            'tableCellProperties'
                        ]
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
                    placeholder: 'Write your topic content here...',
                    // Set initial data from the textarea
                    initialData: document.querySelector('#content').value
                })
                .then(newEditor => {
                    editor = newEditor;
                    
                    // Mark editor as dirty when content changes
                    editor.model.document.on('change:data', () => {
                        isEditorDirty = true;
                    });
                    
                    console.log('CKEditor initialized successfully for edit page');
                })
                .catch(error => {
                    console.error('CKEditor initialization error:', error);
                });

            // Auto-generate slug preview when title changes (optional)
            const titleInput = document.getElementById('title');
            const slugDisplay = document.querySelector('input[readonly]');
            
            if (titleInput && slugDisplay) {
                titleInput.addEventListener('blur', function() {
                    // Just for preview, actual slug is managed by backend
                    const slug = this.value.toLowerCase()
                        .replace(/[^\w\s]/gi, '')
                        .replace(/\s+/g, '-');
                    
                    // Update the readonly field's value (for visual feedback only)
                    // Note: This doesn't actually change the slug in the database
                    // Slug should be handled by the backend
                    if (slugDisplay) {
                        slugDisplay.value = slug;
                        slugDisplay.style.backgroundColor = '#fff3cd';
                        setTimeout(() => {
                            slugDisplay.style.backgroundColor = '#f8f9fa';
                        }, 1000);
                    }
                });
            }

            // Form submission handling
            const form = document.getElementById('topicForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (editor) {
                        // Get the content from CKEditor and update the textarea
                        const content = editor.getData();
                        document.querySelector('#content').value = content;
                        
                        // Optional: Validate content
                        if (content.trim() === '') {
                            e.preventDefault();
                            alert('Please enter some content for the topic.');
                            return;
                        }
                        
                        // Optional: Show warning if content is very long
                        if (content.length > 100000) {
                            if (!confirm('The content is very long. Are you sure you want to save?')) {
                                e.preventDefault();
                                return;
                            }
                        }
                    }
                });

                // Warn user about unsaved changes if they try to leave
                let formSubmitted = false;
                form.addEventListener('submit', function() {
                    formSubmitted = true;
                });

                window.addEventListener('beforeunload', function(e) {
                    if (!formSubmitted && isEditorDirty) {
                        e.preventDefault();
                        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                        return e.returnValue;
                    }
                });
            }

            // Preview functionality (optional)
            const previewButton = document.createElement('button');
            previewButton.type = 'button';
            previewButton.className = 'btn btn-outline-info btn-sm float-end';
            previewButton.innerHTML = '<i class="fas fa-eye me-1"></i> Preview Content';
            previewButton.onclick = function() {
                if (editor) {
                    const content = editor.getData();
                    const previewWindow = window.open('', '_blank');
                    previewWindow.document.write(`
                        <html>
                            <head>
                                <title>Content Preview</title>
                                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                                <style>
                                    body { padding: 20px; }
                                    .preview-container { max-width: 800px; margin: 0 auto; }
                                </style>
                            </head>
                            <body>
                                <div class="preview-container">
                                    <h2>Content Preview</h2>
                                    <hr>
                                    ${content}
                                </div>
                            </body>
                        </html>
                    `);
                    previewWindow.document.close();
                }
            };
            
            // Add preview button near the content label
            const contentLabel = document.querySelector('label[for="content"]');
            if (contentLabel) {
                contentLabel.classList.add('d-flex', 'justify-content-between', 'align-items-center');
                contentLabel.appendChild(previewButton);
            }
        });
    </script>
    @endpush
</x-app-layout>