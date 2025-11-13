<x-app-layout>
    @push('css')
        <style>
            .ck-editor__editable {
                min-height: 400px;
            }
            .ck-content {
                font-size: 14px;
                line-height: 1.6;
            }
        </style>
    @endpush>
    <main class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Create Announcement</h3>
                        </div>
                        <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data" id="announcementForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="title">Title *</label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                                id="title" name="title" value="{{ old('title') }}" required>
                                            @error('title')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="content">Content *</label>
                                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                                    id="content" name="content" rows="10">{{ old('content') }}</textarea>
                                            @error('content')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="branch_id">Branch</label>
                                            <select class="form-control @error('branch_id') is-invalid @enderror" 
                                                    id="branch_id" name="branch_id">
                                                <option value="">All Branches</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="feature_image">Feature Image</label>
                                            <input type="file" class="form-control-file @error('feature_image') is-invalid @enderror" 
                                                id="feature_image" name="feature_image" accept="image/*">
                                            @error('feature_image')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="publish_at">Publish Date</label>
                                            <input type="datetime-local" class="form-control @error('publish_at') is-invalid @enderror" 
                                                id="publish_at" name="publish_at" value="{{ old('publish_at') }}">
                                            @error('publish_at')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="expire_at">Expire Date</label>
                                            <input type="datetime-local" class="form-control @error('expire_at') is-invalid @enderror" 
                                                id="expire_at" name="expire_at" value="{{ old('expire_at') }}">
                                            @error('expire_at')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                                id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                                            @error('meta_title')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                                    id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                                            @error('meta_description')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Create Announcement</button>
                                <a href="{{ route('announcements.index') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let editor;
            
            // Initialize CKEditor
            ClassicEditor
                .create(document.querySelector('#content'), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'underline', 'strikethrough', '|',
                            'link', 'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo', '|',
                            'alignment', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                        ],
                        shouldNotGroupWhenFull: true
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
                    link: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://'
                    },
                    table: {
                        contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
                    }
                })
                .then(editorInstance => {
                    editor = editorInstance;
                    console.log('Editor was initialized', editor);
                })
                .catch(error => {
                    console.error('Error initializing CKEditor:', error);
                });

            // Form submission handling
            const form = document.getElementById('announcementForm');
            form.addEventListener('submit', function(e) {
                // Ensure CKEditor content is copied to textarea
                if (editor) {
                    const editorData = editor.getData();
                    document.querySelector('#content').value = editorData;
                    
                    // Client-side validation for empty content
                    if (!editorData.trim()) {
                        e.preventDefault();
                        alert('Please enter announcement content.');
                        editor.editing.view.focus();
                        return false;
                    }
                }
            });

            // Auto-generate meta title and description
            const titleInput = document.getElementById('title');
            const metaTitleInput = document.getElementById('meta_title');
            const metaDescriptionInput = document.getElementById('meta_description');

            function generateMetaData() {
                if (!metaTitleInput.value && titleInput.value) {
                    metaTitleInput.value = titleInput.value;
                }
            }

            titleInput.addEventListener('blur', generateMetaData);
        });
    </script>
    @endpush
</x-app-layout>