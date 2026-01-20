<x-app-layout>
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
                            <form action="{{ route('topics.update', $topic) }}" method="POST">
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
                                        <small class="text-muted">Auto-generated from title</small>
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
                                           class="badge bg-warning">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize text editor for content
            const contentTextarea = document.getElementById('content');
            if (contentTextarea) {
                const editorButtons = document.createElement('div');
                editorButtons.className = 'mb-2';
                editorButtons.innerHTML = `
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('bold')">
                            <i class="fas fa-bold"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('italic')">
                            <i class="fas fa-italic"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('underline')">
                            <i class="fas fa-underline"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('list')">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                `;
                
                contentTextarea.parentNode.insertBefore(editorButtons, contentTextarea);
            }
        });
        
        function formatText(command) {
            const textarea = document.getElementById('content');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            
            let formattedText = '';
            switch(command) {
                case 'bold':
                    formattedText = `<strong>${selectedText}</strong>`;
                    break;
                case 'italic':
                    formattedText = `<em>${selectedText}</em>`;
                    break;
                case 'underline':
                    formattedText = `<u>${selectedText}</u>`;
                    break;
                case 'list':
                    formattedText = `<ul><li>${selectedText}</li></ul>`;
                    break;
            }
            
            textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
            textarea.focus();
            textarea.setSelectionRange(start + formattedText.length, start + formattedText.length);
        }
    </script>
    @endpush
</x-app-layout>