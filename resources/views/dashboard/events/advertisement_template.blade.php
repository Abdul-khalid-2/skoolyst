<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simple Page Builder</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .builder-container {
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .sidebar {
            background: white;
            border-right: 1px solid #dee2e6;
            height: 100vh;
            overflow-y: auto;
        }
        
        .widget-card {
            cursor: grab;
            border: 2px dashed #dee2e6;
            transition: all 0.3s;
            margin-bottom: 10px;
        }
        
        .widget-card:hover {
            border-color: #0d6efd;
            transform: translateY(-2px);
        }
        
        .canvas-area {
            background: white;
            min-height: 80vh;
            border: 2px dashed #ced4da;
            padding: 20px;
        }
        
        .canvas-element {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
        }
        
        .canvas-element:hover {
            border-color: #0d6efd;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .element-header {
            background: #f8f9fa;
            padding: 8px 15px;
            margin: -15px -15px 15px -15px;
            border-radius: 8px 8px 0 0;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .empty-canvas {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .position-controls {
            display: flex;
            gap: 5px;
        }
        
        .position-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dee2e6;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .position-btn:hover {
            background: #f8f9fa;
            border-color: #6c757d;
        }
        
        .position-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .position-btn:disabled:hover {
            background: white;
            border-color: #dee2e6;
        }
        
        .element-type-badge {
            font-size: 0.75rem;
            background: #6c757d;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            margin-left: 10px;
        }
        
        .html-preview {
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 15px;
            background: #f8f9fa;
            min-height: 100px;
            margin-top: 10px;
            overflow: auto;
            max-height: 300px;
        }
        
        .html-editor {
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        
        .preview-container {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
            background: white;
            min-height: 100px;
            margin-top: 10px;
        }
        
        .code-tabs {
            margin-bottom: 10px;
        }
        
        .code-tab {
            padding: 5px 15px;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
            border-radius: 4px 4px 0 0;
            cursor: pointer;
            margin-right: 5px;
        }
        
        .code-tab.active {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
    </style>
</head>

<body>
    <div class="builder-container">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar p-3">
                <h5 class="mb-3">Page Elements</h5>
                
                <div class="widget-card p-3" draggable="true" data-type="heading">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-heading text-primary me-2"></i>
                        <span>Heading</span>
                    </div>
                </div>
                
                <div class="widget-card p-3" draggable="true" data-type="text">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-paragraph text-primary me-2"></i>
                        <span>Text Content</span>
                    </div>
                </div>
                
                <div class="widget-card p-3" draggable="true" data-type="image">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-image text-primary me-2"></i>
                        <span>Image</span>
                    </div>
                </div>
                
                <div class="widget-card p-3" draggable="true" data-type="banner">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-banner text-primary me-2"></i>
                        <span>Banner</span>
                    </div>
                </div>
                
                <div class="widget-card p-3" draggable="true" data-type="columns">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-columns text-primary me-2"></i>
                        <span>Two Columns</span>
                    </div>
                </div>

                <!-- NEW: Custom HTML Widget -->
                <div class="widget-card p-3" draggable="true" data-type="custom_html">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-code text-primary me-2"></i>
                        <span>Custom HTML</span>
                    </div>
                </div>

                <hr>
                
                <div class="mt-3">
                    <button class="btn btn-outline-primary btn-sm w-100 mb-2" onclick="previewPage()">
                        <i class="fas fa-eye me-1"></i>Preview
                    </button>
                    <button class="btn btn-outline-danger btn-sm w-100" onclick="clearCanvas()">
                        <i class="fas fa-trash me-1"></i>Clear All
                    </button>
                </div>
            </div>

            <!-- Canvas Area -->
            <div class="col-md-9 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Page Builder</h4>
                    <span class="badge bg-secondary" id="elementCount">0 elements</span>
                </div>

                <div class="canvas-area" id="canvas">
                    <div class="empty-canvas" id="emptyCanvas">
                        <i class="fas fa-arrow-left fa-2x mb-3"></i>
                        <h5>Drag elements here to start building</h5>
                        <p class="text-muted">Select from the sidebar and drop in this area</p>
                    </div>
                </div>

                <!-- Save Form -->
                <div class="card mt-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-save me-1"></i>Save Page</h6>
                    </div>
                    <div class="card-body">
                        <form id="saveForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pageName" class="form-label">Page Name *</label>
                                        <input type="text" class="form-control" id="pageName" name="name" required>
                                        <input type="hidden" id="schoolId" name="school_id" value="{{ $event->school->id }}">
                                        <input type="hidden" id="eventId" name="event_id" value="{{ $event->id }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success" id="saveBtn">
                                                <i class="fas fa-save me-1"></i>Save to Database
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="saveResult"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Page Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="previewContent"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        class SimplePageBuilder {
            constructor() {
                this.elements = [];
                this.nextId = 1;
                this.init();
            }

            init() {
                this.setupDragAndDrop();
                this.setupEventListeners();
                this.updateElementCount();
            }

            setupDragAndDrop() {
                // Make widgets draggable
                $('.widget-card').on('dragstart', (e) => {
                    const type = $(e.currentTarget).data('type');
                    e.originalEvent.dataTransfer.setData('text/plain', type);
                    e.originalEvent.dataTransfer.effectAllowed = 'copy';
                    $(e.currentTarget).addClass('dragging');
                });

                $('.widget-card').on('dragend', (e) => {
                    $(e.currentTarget).removeClass('dragging');
                });

                // Canvas drop zone
                $('#canvas').on('dragover', (e) => {
                    e.preventDefault();
                    e.originalEvent.dataTransfer.dropEffect = 'copy';
                    $('#canvas').addClass('border-primary bg-light');
                });

                $('#canvas').on('dragleave', (e) => {
                    // Only remove class if not dragging over child elements
                    if (!$(e.currentTarget).has(e.relatedTarget).length) {
                        $('#canvas').removeClass('border-primary bg-light');
                    }
                });

                $('#canvas').on('drop', (e) => {
                    e.preventDefault();
                    $('#canvas').removeClass('border-primary bg-light');
                    
                    const type = e.originalEvent.dataTransfer.getData('text/plain');
                    if (type) {
                        this.addElement(type);
                    }
                });
            }

            setupEventListeners() {
                // Form submission
                $('#saveForm').on('submit', (e) => this.savePage(e));
            }

            addElement(type) {
                const id = `element_${this.nextId++}`;
                const element = {
                    id: id,
                    type: type,
                    content: this.getDefaultContent(type),
                    position: this.elements.length
                };

                this.elements.push(element);
                this.renderElement(element);
                this.updateElementCount();
                this.hideEmptyCanvas();
                this.updatePositionButtons();
            }

            getDefaultContent(type) {
                const defaults = {
                    heading: { text: 'New Heading', level: 'h2' },
                    text: { content: 'Enter your text here...' },
                    image: { src: '', alt: 'Image', caption: '' },
                    banner: { src: '', title: 'Banner Title', subtitle: 'Banner subtitle' },
                    columns: { 
                        left: 'Left column content...', 
                        right: 'Right column content...' 
                    },
                    'custom_html': {
                        html: '<div class="alert alert-info">\n  <h4>Custom HTML Content</h4>\n  <p>Edit this HTML to create custom content with your own styles and structure.</p>\n</div>',
                        css: '/* Add your custom CSS here */\n.alert-info {\n  border-left: 4px solid #17a2b8;\n}'
                    }
                };
                return defaults[type] || {};
            }

            renderElement(element) {
                const html = this.getElementHTML(element);
                $('#canvas').append(html);
                this.attachElementEvents(element.id);
            }

            getElementHTML(element) {
                const typeNames = {
                    heading: 'Heading',
                    text: 'Text',
                    image: 'Image',
                    banner: 'Banner',
                    columns: 'Two Columns',
                    'custom_html': 'Custom HTML'
                };

                const templates = {
                    heading: (el) => `
                        <div class="canvas-element" data-id="${el.id}">
                            <div class="element-header">
                                <div>
                                    <span>${typeNames[el.type]}</span>
                                    <span class="element-type-badge">${el.content.level || 'h2'}</span>
                                </div>
                                <div class="position-controls">
                                    <button type="button" class="position-btn move-up" title="Move Up" onclick="builder.moveElementUp('${el.id}')">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <button type="button" class="position-btn move-down" title="Move Down" onclick="builder.moveElementDown('${el.id}')">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="text" class="form-control heading-input" 
                                value="${el.content.text}" 
                                placeholder="Enter heading text">
                            <select class="form-select mt-2 heading-level">
                                <option value="h1" ${el.content.level === 'h1' ? 'selected' : ''}>H1</option>
                                <option value="h2" ${el.content.level === 'h2' || !el.content.level ? 'selected' : ''}>H2</option>
                                <option value="h3" ${el.content.level === 'h3' ? 'selected' : ''}>H3</option>
                            </select>
                        </div>
                    `,
                    
                    text: (el) => `
                        <div class="canvas-element" data-id="${el.id}">
                            <div class="element-header">
                                <div>
                                    <span>${typeNames[el.type]}</span>
                                    <span class="element-type-badge">Text</span>
                                </div>
                                <div class="position-controls">
                                    <button type="button" class="position-btn move-up" title="Move Up" onclick="builder.moveElementUp('${el.id}')">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <button type="button" class="position-btn move-down" title="Move Down" onclick="builder.moveElementDown('${el.id}')">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                            </div>
                            <textarea class="form-control text-content" rows="4" 
                                    placeholder="Enter your text here">${el.content.content}</textarea>
                        </div>
                    `,
                    
                    image: (el) => `
                        <div class="canvas-element" data-id="${el.id}">
                            <div class="element-header">
                                <div>
                                    <span>${typeNames[el.type]}</span>
                                    <span class="element-type-badge">Image</span>
                                </div>
                                <div class="position-controls">
                                    <button type="button" class="position-btn move-up" title="Move Up" onclick="builder.moveElementUp('${el.id}')">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <button type="button" class="position-btn move-down" title="Move Down" onclick="builder.moveElementDown('${el.id}')">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="file" class="form-control image-upload" accept="image/*">
                            </div>
                            <input type="text" class="form-control mb-2 image-caption" 
                                value="${el.content.caption}" placeholder="Image caption">
                            ${el.content.src ? `<img src="${el.content.src}" class="img-fluid mt-2" style="max-height: 200px;">` : ''}
                        </div>
                    `,
                    
                    banner: (el) => `
                        <div class="canvas-element" data-id="${el.id}">
                            <div class="element-header">
                                <div>
                                    <span>${typeNames[el.type]}</span>
                                    <span class="element-type-badge">Banner</span>
                                </div>
                                <div class="position-controls">
                                    <button type="button" class="position-btn move-up" title="Move Up" onclick="builder.moveElementUp('${el.id}')">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <button type="button" class="position-btn move-down" title="Move Down" onclick="builder.moveElementDown('${el.id}')">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="file" class="form-control image-upload" accept="image/*">
                            </div>
                            <input type="text" class="form-control mb-2 banner-title" 
                                value="${el.content.title}" placeholder="Banner title">
                            <input type="text" class="form-control mb-2 banner-subtitle" 
                                value="${el.content.subtitle}" placeholder="Banner subtitle">
                            ${el.content.src ? `<img src="${el.content.src}" class="img-fluid mt-2" style="max-height: 200px;">` : ''}
                        </div>
                    `,
                    
                    columns: (el) => `
                        <div class="canvas-element" data-id="${el.id}">
                            <div class="element-header">
                                <div>
                                    <span>${typeNames[el.type]}</span>
                                    <span class="element-type-badge">Columns</span>
                                </div>
                                <div class="position-controls">
                                    <button type="button" class="position-btn move-up" title="Move Up" onclick="builder.moveElementUp('${el.id}')">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <button type="button" class="position-btn move-down" title="Move Down" onclick="builder.moveElementDown('${el.id}')">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <textarea class="form-control column-left" rows="3" 
                                            placeholder="Left column content">${el.content.left}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <textarea class="form-control column-right" rows="3" 
                                            placeholder="Right column content">${el.content.right}</textarea>
                                </div>
                            </div>
                        </div>
                    `,
                    
                    'custom_html': (el) => `
                        <div class="canvas-element" data-id="${el.id}">
                            <div class="element-header">
                                <div>
                                    <span>${typeNames[el.type]}</span>
                                    <span class="element-type-badge">Code</span>
                                </div>
                                <div class="position-controls">
                                    <button type="button" class="position-btn move-up" title="Move Up" onclick="builder.moveElementUp('${el.id}')">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <button type="button" class="position-btn move-down" title="Move Down" onclick="builder.moveElementDown('${el.id}')">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="code-tabs">
                                <span class="code-tab active" onclick="builder.switchTab('${el.id}', 'html')">HTML</span>
                                <span class="code-tab" onclick="builder.switchTab('${el.id}', 'css')">CSS</span>
                                <span class="code-tab" onclick="builder.switchTab('${el.id}', 'preview')">Preview</span>
                            </div>
                            
                            <div class="html-editor-container" id="html-editor-${el.id}">
                                <textarea class="form-control html-editor" rows="8" 
                                        placeholder="Enter your HTML code here">${el.content.html || ''}</textarea>
                                <small class="text-muted">You can use HTML, CSS classes, and inline styles</small>
                            </div>
                            
                            <div class="css-editor-container" id="css-editor-${el.id}" style="display: none;">
                                <textarea class="form-control html-editor" rows="8" 
                                        placeholder="Enter your custom CSS here">${el.content.css || ''}</textarea>
                                <small class="text-muted">Add custom CSS styles for your HTML</small>
                            </div>
                            
                            <div class="preview-container" id="preview-${el.id}" style="display: none;">
                                <div id="preview-content-${el.id}"></div>
                            </div>
                        </div>
                    `
                };

                return templates[element.type] ? templates[element.type](element) : '<div>Unknown element type</div>';
            }

            switchTab(elementId, tabName) {
                // Hide all containers
                $(`#html-editor-${elementId}`).hide();
                $(`#css-editor-${elementId}`).hide();
                $(`#preview-${elementId}`).hide();
                
                // Remove active class from all tabs
                $(`[data-id="${elementId}"] .code-tab`).removeClass('active');
                
                // Show selected container and activate tab
                if (tabName === 'html') {
                    $(`#html-editor-${elementId}`).show();
                    $(`[data-id="${elementId}"] .code-tab:contains("HTML")`).addClass('active');
                } else if (tabName === 'css') {
                    $(`#css-editor-${elementId}`).show();
                    $(`[data-id="${elementId}"] .code-tab:contains("CSS")`).addClass('active');
                } else if (tabName === 'preview') {
                    $(`#preview-${elementId}`).show();
                    $(`[data-id="${elementId}"] .code-tab:contains("Preview")`).addClass('active');
                    this.updateHtmlPreview(elementId);
                }
            }

            updateHtmlPreview(elementId) {
                const element = this.elements.find(el => el.id === elementId);
                if (!element) return;

                const htmlContent = element.content.html || '';
                const cssContent = element.content.css || '';
                
                const previewContainer = $(`#preview-content-${elementId}`)[0];
                
                // Clear existing content
                previewContainer.innerHTML = '';
                
                // Create shadow root for style isolation
                const shadowRoot = previewContainer.attachShadow({ mode: 'open' });
                
                // Add styles and HTML to shadow DOM
                shadowRoot.innerHTML = `
                    <style>
                        /* Reset styles for the shadow DOM */
                        :host {
                            all: initial;
                            display: block;
                        }
                        
                        /* Custom CSS from user */
                        ${cssContent}
                    </style>
                    ${htmlContent}
                `;
            }

            attachElementEvents(elementId) {
                // Handle input changes
                $(`[data-id="${elementId}"] input, [data-id="${elementId}"] textarea, [data-id="${elementId}"] select`).on('change input', (e) => {
                    this.updateElementContent(elementId, e.target);
                });

                // Handle file uploads
                $(`[data-id="${elementId}"] .image-upload`).on('change', (e) => {
                    this.handleImageUpload(elementId, e.target);
                });

                // Special handling for HTML editor
                $(`[data-id="${elementId}"] .html-editor`).on('input', (e) => {
                    this.updateElementContent(elementId, e.target);
                    
                    // If preview is visible, update it in real-time
                    const $previewContainer = $(`#preview-${elementId}`);
                    if ($previewContainer.is(':visible')) {
                        this.updateHtmlPreview(elementId);
                    }
                });
            }

            updateElementContent(elementId, target) {
                const element = this.elements.find(el => el.id === elementId);
                if (!element) return;

                const $target = $(target);
                const className = $target.attr('class');
                
                if (className.includes('heading-input')) {
                    element.content.text = $target.val();
                } else if (className.includes('heading-level')) {
                    element.content.level = $target.val();
                    // Update the badge if it's a heading element
                    const $badge = $(`[data-id="${elementId}"] .element-type-badge`);
                    if ($badge.length) {
                        $badge.text($target.val());
                    }
                } else if (className.includes('text-content')) {
                    element.content.content = $target.val();
                } else if (className.includes('image-caption')) {
                    element.content.caption = $target.val();
                } else if (className.includes('banner-title')) {
                    element.content.title = $target.val();
                } else if (className.includes('banner-subtitle')) {
                    element.content.subtitle = $target.val();
                } else if (className.includes('column-left')) {
                    element.content.left = $target.val();
                } else if (className.includes('column-right')) {
                    element.content.right = $target.val();
                } else if (className.includes('html-editor')) {
                    // Determine if it's HTML or CSS editor
                    const $container = $target.closest('.html-editor-container, .css-editor-container');
                    if ($container.hasClass('html-editor-container')) {
                        element.content.html = $target.val();
                    } else if ($container.hasClass('css-editor-container')) {
                        element.content.css = $target.val();
                    }
                }
            }

            handleImageUpload(elementId, fileInput) {
                const file = fileInput.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    const element = this.elements.find(el => el.id === elementId);
                    if (element) {
                        element.content.src = e.target.result;
                        this.updateElementDisplay(elementId);
                    }
                };
                reader.readAsDataURL(file);
            }

            updateElementDisplay(elementId) {
                // Re-render the element with updated content
                const element = this.elements.find(el => el.id === elementId);
                if (element) {
                    $(`[data-id="${elementId}"]`).remove();
                    this.renderElement(element);
                }
            }

            moveElementUp(elementId) {
                const index = this.elements.findIndex(el => el.id === elementId);
                if (index > 0) {
                    // Swap elements in array
                    [this.elements[index], this.elements[index - 1]] = [this.elements[index - 1], this.elements[index]];
                    
                    // Update positions
                    this.elements.forEach((el, idx) => {
                        el.position = idx;
                    });
                    
                    // Re-render all elements
                    this.renderAllElements();
                    this.updatePositionButtons();
                }
            }

            moveElementDown(elementId) {
                const index = this.elements.findIndex(el => el.id === elementId);
                if (index < this.elements.length - 1) {
                    // Swap elements in array
                    [this.elements[index], this.elements[index + 1]] = [this.elements[index + 1], this.elements[index]];
                    
                    // Update positions
                    this.elements.forEach((el, idx) => {
                        el.position = idx;
                    });
                    
                    // Re-render all elements
                    this.renderAllElements();
                    this.updatePositionButtons();
                }
            }

            renderAllElements() {
                $('#canvas').empty();
                this.elements.forEach(element => {
                    this.renderElement(element);
                });
                if (this.elements.length === 0) {
                    this.showEmptyCanvas();
                }
            }

            updatePositionButtons() {
                this.elements.forEach((element, index) => {
                    const $upBtn = $(`[data-id="${element.id}"] .move-up`);
                    const $downBtn = $(`[data-id="${element.id}"] .move-down`);
                    
                    $upBtn.prop('disabled', index === 0);
                    $downBtn.prop('disabled', index === this.elements.length - 1);
                });
            }

            updateElementCount() {
                $('#elementCount').text(`${this.elements.length} element${this.elements.length !== 1 ? 's' : ''}`);
            }

            hideEmptyCanvas() {
                $('#emptyCanvas').hide();
            }

            showEmptyCanvas() {
                $('#emptyCanvas').show();
            }

            clearCanvas() {
                if (!confirm('Clear all elements? This cannot be undone.')) return;
                
                this.elements = [];
                $('#canvas').empty().append($('#emptyCanvas').show());
                $('#canvas').append(`
                <div class="empty-canvas" id="emptyCanvas">
                        <i class="fas fa-arrow-left fa-2x mb-3"></i>
                        <h5>Drag elements here to start building</h5>
                        <p class="text-muted">Select from the sidebar and drop in this area</p>
                    </div>`);
                this.updateElementCount();
            }

            getPageData() {
                return {
                    elements: this.elements,
                    metadata: {
                        created: new Date().toISOString(),
                        total_elements: this.elements.length,
                        version: '1.0'
                    }
                };
            }

            previewPage() {
                if (this.elements.length === 0) {
                    alert('Please add some elements to the canvas first');
                    return;
                }

                const previewHTML = this.generatePreview();
                $('#previewContent').html(previewHTML);
                
                // Initialize Bootstrap modal
                const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
                previewModal.show();
            }

            generatePreview() {
                let html = '';
                this.elements.forEach(element => {
                    html += this.renderPreviewElement(element);
                });
                return html || '<p class="text-muted text-center py-4">No content to preview</p>';
            }

            renderPreviewElement(element) {
                const templates = {
                    heading: (el) => `<${el.content.level} class="mb-3">${el.content.text}</${el.content.level}>`,
                    text: (el) => `<div class="mb-3">${el.content.content.replace(/\n/g, '<br>')}</div>`,
                    image: (el) => `
                        <div class="mb-4">
                            ${el.content.src ? `<img src="${el.content.src}" class="img-fluid rounded mb-2" style="max-height: 300px;">` : '<div class="bg-light text-center py-5 rounded text-muted">No image</div>'}
                            ${el.content.caption ? `<p class="text-muted text-center mt-2">${el.content.caption}</p>` : ''}
                        </div>
                    `,
                    banner: (el) => `
                        <div class="bg-light p-5 mb-4 text-center rounded">
                            ${el.content.src ? `<img src="${el.content.src}" class="img-fluid mb-3" style="max-height: 200px;">` : ''}
                            <h2>${el.content.title || 'Banner Title'}</h2>
                            <p class="lead">${el.content.subtitle || 'Banner subtitle'}</p>
                        </div>
                    `,
                    columns: (el) => `
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    ${el.content.left.replace(/\n/g, '<br>')}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    ${el.content.right.replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        </div>
                    `,
                    'custom_html': (el) => `
                        <div class="mb-4">
                            <style>${el.content.css || ''}</style>
                            ${el.content.html || ''}
                        </div>
                    `
                };

                return templates[element.type] ? templates[element.type](element) : '';
            }

            async savePage(e) {
                e.preventDefault();

                const pageName = $('#pageName').val().trim();
                if (!pageName) {
                    alert('Please enter a page name');
                    return;
                }

                if (this.elements.length === 0) {
                    alert('Please add some elements to the page before saving');
                    return;
                }

                const formData = new FormData();
                formData.append('name', pageName);
                formData.append('school_id', $('#schoolId').val());
                formData.append('event_id', $('#eventId').val());
                formData.append('form_data', JSON.stringify(this.getPageData()));
                formData.append('_token', $('input[name="_token"]').val());

                const $saveBtn = $('#saveBtn');
                $saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Saving...');

                try {
                    const response = await fetch('/pages/store', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        $('#saveResult').html(`
                            <div class="alert alert-success">
                                <strong>Success!</strong> Page "${pageName}" has been saved successfully.
                                <br><a href="${result.redirect_url}" class="btn btn-primary btn-sm mt-2">View Page</a>
                            </div>
                        `);
                        $('#pageName').val('');
                        
                        // RESET CANVAS CONTENT - Add these lines
                        if (window.builder) {
                            window.builder.clearCanvas();
                        }
                    }  else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    $('#saveResult').html(`
                        <div class="alert alert-danger">
                            <strong>Error!</strong> ${error.message}
                        </div>
                    `);
                } finally {
                    $saveBtn.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Save to Database');
                }
            }
        }

        // Initialize the page builder when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            window.builder = new SimplePageBuilder();
        });

        // Global functions for HTML onclick events
        function previewPage() {
            if (window.builder) {
                window.builder.previewPage();
            }
        }

        function clearCanvas() {
            if (window.builder) {
                window.builder.clearCanvas();
            }
        }
    </script>
</body>
</html>