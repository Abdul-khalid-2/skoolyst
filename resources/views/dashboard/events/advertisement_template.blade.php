<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Skoolyst — Dynamic Form / Page Builder</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- jQuery UI for improved drag & drop -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #6c757d;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
        }

        body {
            background: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .sidebar {
            background: #fff;
            border-right: 1px solid #e6e9ef;
            min-height: 100vh;
            padding: 1rem;
            overflow-y: auto;
        }

        .widget {
            cursor: grab;
            user-select: none;
            transition: all 0.2s;
            border: 1px solid #e9ecef;
            margin-bottom: 0.5rem;
        }

        .widget:hover {
            background: #f1f3f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .canvas-area {
            background: linear-gradient(180deg, #ffffff, #fbfdff);
            min-height: 80vh;
            border: 2px dashed #dbe4f2;
            position: relative;
            overflow: auto;
            padding: 20px;
        }

        .canvas-element {
            position: absolute;
            box-shadow: 0 2px 8px rgba(18, 38, 63, 0.06);
            border-radius: 8px;
            background: #fff;
            padding: 1rem;
            border: 1px solid transparent;
            transition: all 0.3s;
            min-height: 60px;
            overflow: visible;
        }

        .canvas-element:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
        }

        .canvas-element.selected {
            border: 2px solid var(--primary);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }

        .element-toolbar {
            position: absolute;
            top: -40px;
            right: 0;
            display: flex;
            gap: 4px;
            background: white;
            padding: 6px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 10;
        }

        .canvas-element.selected .element-toolbar {
            opacity: 1;
        }

        .element-handle {
            cursor: pointer;
            padding: 0.4rem;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            transition: all 0.2s;
        }

        .element-handle:hover {
            background: var(--light);
        }

        .drop-hint {
            position: absolute;
            inset: 0;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9aa7c7;
            font-size: 1.1rem;
        }

        .sidebar .group-title {
            font-weight: 600;
            font-size: 1rem;
            margin-top: 1rem;
            color: #243447;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .canvas-topbar {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #eef2f6;
            background: #ffffff;
            border-radius: 8px 8px 0 0;
        }

        .small-muted {
            color: #64748b;
            font-size: 0.85rem;
        }

        .resizer {
            width: 12px;
            height: 12px;
            position: absolute;
            right: -6px;
            bottom: -6px;
            cursor: se-resize;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .canvas-element:hover .resizer {
            opacity: 1;
        }

        .two-col {
            display: flex;
            gap: 1rem;
            align-items: stretch;
        }

        .two-col .col-left,
        .two-col .col-right {
            flex: 1;
            padding: 0.75rem;
        }

        .canvas-element img {
            max-width: 100%;
            height: auto;
            display: block;
            border-radius: 6px;
        }

        .placeholder {
            min-height: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .placeholder:hover {
            background: #e2e8f0;
            border-color: var(--primary);
        }

        .placeholder i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--secondary);
        }

        .editable {
            min-height: 40px;
            padding: 0.5rem;
            border: 1px dashed transparent;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .editable:hover {
            border-color: #e2e8f0;
            background: #f8fafc;
        }

        .editable:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
        }

        .canvas-area.drag-over {
            background: rgba(67, 97, 238, 0.05);
            border-color: var(--primary);
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            background: var(--success);
            color: white;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s;
        }

        .notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        .grid-guides {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                linear-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
            background-size: 20px 20px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .grid-guides.show {
            opacity: 1;
        }

        .element-content {
            min-height: 40px;
        }

        .element-content h2,
        .element-content h3,
        .element-content h4 {
            margin-bottom: 0.5rem;
        }

        .element-content p {
            margin-bottom: 0.5rem;
        }

        .ck-editor {
            border-radius: 6px;
            overflow: hidden;
        }

        .cke_notifications_area {
            display: none;
        }

        /* Preview Modal Styles */
        .preview-modal .modal-dialog {
            max-width: 90%;
            width: 90%;
            height: 90vh;
        }

        .preview-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            border: 1px solid #eef2f6;
            min-height: 600px;
            height: 100%;
            position: relative;
            overflow: auto;
        }

        /* Positioned preview elements */
        .preview-positioned-element {
            position: absolute !important;
            box-shadow: 0 2px 8px rgba(18, 38, 63, 0.06);
            border-radius: 8px;
            background: #fff;
            padding: 1rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s;
            min-height: 60px;
        }

        .preview-positioned-element:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
        }

        /* Specific element styles for positioned layout */
        .preview-positioned-element.preview-title {
            border-left: 4px solid #4361ee;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
        }

        .preview-positioned-element.preview-title h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #4361ee, #3a56d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }

        .preview-positioned-element.preview-banner {
            padding: 0 !important;
            border-radius: 12px;
            overflow: hidden;
        }

        .preview-positioned-element.preview-banner img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
        }

        .preview-positioned-element.preview-image {
            background: linear-gradient(135deg, #f0f4ff, #ffffff);
            text-align: center;
            border-radius: 12px;
        }

        .preview-positioned-element.preview-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .preview-positioned-element.preview-textarea {
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-radius: 12px;
            border: 1px solid #eef2f6;
        }

        .preview-positioned-element.preview-two-col {
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-radius: 12px;
            border: 1px solid #eef2f6;
        }

        .preview-positioned-element.preview-raw-html {
            background: linear-gradient(135deg, #fff8f5, #ffffff);
            border-radius: 12px;
            border: 1px solid #ffe8d6;
        }

        .preview-caption {
            font-style: italic;
            color: var(--secondary);
            text-align: center;
            margin-top: 1rem;
            font-size: 0.95rem;
            padding: 0.5rem;
        }

        .preview-rich-text {
            color: #4b5563;
            line-height: 1.8;
            font-size: 1.1rem;
        }

        /* Two column layout for positioned elements */
        .preview-positioned-element .two-col {
            display: flex;
            gap: 1rem;
            align-items: stretch;
        }

        .preview-positioned-element .two-col .col-left,
        .preview-positioned-element .two-col .col-right {
            flex: 1;
            padding: 0.75rem;
        }

        .preview-positioned-element .two-col img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
        }

        /* Ensure images maintain aspect ratio in preview */
        .preview-positioned-element img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* Responsive adjustments for preview */
        @media (max-width: 768px) {
            .preview-positioned-element .two-col {
                flex-direction: column;
            }
            
            .preview-positioned-element {
                position: relative !important;
                left: auto !important;
                top: auto !important;
                width: 100% !important;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-12 col-lg-3 sidebar">
                <div class="d-flex align-items-center mb-3">
                    <h4 class="mb-0 text-primary">Skoolyst</h4>
                    <small class="ms-auto small-muted">Form Builder</small>
                </div>

                <div class="group-title">Drag Elements to Canvas</div>
                <div class="mt-2">
                    <div class="card widget" draggable="true" data-type="title">
                        <div class="card-body py-2">
                            <h6 class="card-title mb-1"><i class="fas fa-heading me-2 text-primary"></i>Title</h6>
                            <p class="card-text small-muted mb-0">Heading element with customizable text</p>
                        </div>
                    </div>

                    <div class="card widget" draggable="true" data-type="banner">
                        <div class="card-body py-2">
                            <h6 class="card-title mb-1"><i class="fas fa-image me-2 text-primary"></i>Banner</h6>
                            <p class="card-text small-muted mb-0">Full-width image banner</p>
                        </div>
                    </div>

                    <div class="card widget" draggable="true" data-type="image">
                        <div class="card-body py-2">
                            <h6 class="card-title mb-1"><i class="fas fa-photo-video me-2 text-primary"></i>Image</h6>
                            <p class="card-text small-muted mb-0">Single image with caption</p>
                        </div>
                    </div>

                    <div class="card widget" draggable="true" data-type="textarea">
                        <div class="card-body py-2">
                            <h6 class="card-title mb-1"><i class="fas fa-paragraph me-2 text-primary"></i>Rich Text</h6>
                            <p class="card-text small-muted mb-0">Text area with CKEditor</p>
                        </div>
                    </div>

                    <div class="card widget" draggable="true" data-type="two-col-tr">
                        <div class="card-body py-2">
                            <h6 class="card-title mb-1"><i class="fas fa-columns me-2 text-primary"></i>Text Left / Image Right</h6>
                            <p class="card-text small-muted mb-0">Two column layout</p>
                        </div>
                    </div>

                    <div class="card widget" draggable="true" data-type="two-col-rt">
                        <div class="card-body py-2">
                            <h6 class="card-title mb-1"><i class="fas fa-columns me-2 text-primary"></i>Image Left / Text Right</h6>
                            <p class="card-text small-muted mb-0">Two column layout</p>
                        </div>
                    </div>

                    <div class="card widget" draggable="true" data-type="raw-html">
                        <div class="card-body py-2">
                            <h6 class="card-title mb-1"><i class="fas fa-code me-2 text-primary"></i>Custom HTML</h6>
                            <p class="card-text small-muted mb-0">Add custom HTML code</p>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="group-title">Canvas Tools</div>
                <div class="d-grid gap-2 mt-2">
                    <button id="toggleGrid" class="btn btn-outline-secondary btn-sm"><i class="fas fa-th-large me-1"></i>Toggle Grid</button>
                    <div class="btn-group">
                        <button id="undoBtn" class="btn btn-outline-secondary btn-sm"><i class="fas fa-undo me-1"></i>Undo</button>
                        <button id="redoBtn" class="btn btn-outline-secondary btn-sm"><i class="fas fa-redo me-1"></i>Redo</button>
                    </div>
                    <button id="addSpaceBtn" class="btn btn-outline-info btn-sm"><i class="fas fa-expand me-1"></i>Add Space</button>
                    <button id="previewBtn" class="btn btn-outline-warning btn-sm"><i class="fas fa-eye me-1"></i>Preview Page</button>
                </div>

                <hr>

                <div class="group-title">Page Actions</div>
                <div class="d-grid gap-2 mt-2">
                    <button id="clearCanvas" class="btn btn-outline-danger"><i class="fas fa-trash me-1"></i>Clear Canvas</button>
                </div>

                <hr>
            </div>

            <!-- Editor area -->
            <div class="col-12 col-lg-9 p-3">
                <div class="card shadow-sm">
                    <div class="canvas-topbar">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 me-3">Canvas</h5>
                            <span class="badge bg-primary" id="elementCount">0 elements</span>
                        </div>
                        <div class="ms-auto d-flex align-items-center">
                            <span class="small-muted me-3">Drag, drop, and edit elements</span>
                            <button id="previewBtnTop" class="btn btn-warning btn-sm me-2">
                                <i class="fas fa-eye me-1"></i>Preview
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div id="canvas" class="canvas-area">
                            <div class="grid-guides" id="gridGuides"></div>
                            <div class="drop-hint">
                                <div class="text-center">
                                    <i class="fas fa-arrow-left fa-2x mb-3"></i>
                                    <h5>Drag elements here to start building</h5>
                                    <p class="small-muted">Select from the sidebar and drop in this area</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save to Database Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-database me-1"></i>Save to Database</h6>
                            </div>
                            <div class="card-body">
                                <form id="saveForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="pageName" class="form-label">Page Name *</label>
                                                <input type="text" class="form-control" id="pageName" name="name" required placeholder="Enter page name">
                                                <input type="hidden" class="form-control" id="schoolId" name="school_id" value="{{ $event->school->id }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="eventId" class="form-label">Event</label>
                                                <select class="form-control" id="eventId" name="event_id">
                                                    <option value="{{ $event->id }}">{{ $event->event_name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
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
                                <div id="saveResult" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade preview-modal" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="max-width: 95%; height: 95vh;">
            <div class="modal-content h-100">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="previewModalLabel">
                        <i class="fas fa-eye me-2"></i>Page Preview - Exact Layout
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="height: calc(100% - 120px);">
                    <div class="preview-container h-100" id="previewContainer" style="position: relative; min-height: 800px;">
                        <!-- Preview content will be inserted here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="savePage()">
                        <i class="fas fa-save me-1"></i>Save Page
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Element -->
    <div class="notification" id="notification"></div>

    <script>
        // Global variables
        let currentDragType = null;
        let history = [];
        let redoStack = [];
        let isGridVisible = false;
        let nextElementY = 20;

        // Utility functions
        function uid(prefix = 'el') {
            return prefix + '_' + Math.random().toString(36).substr(2, 9);
        }

        function showNotification(message, type = 'success') {
            const notification = $('#notification');
            const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';

            notification.removeClass('bg-success bg-danger').addClass(bgClass);
            notification.text(message).addClass('show');

            setTimeout(() => {
                notification.removeClass('show');
            }, 3000);
        }

        function updateElementCount() {
            const count = $('#canvas .canvas-element').length;
            $('#elementCount').text(`${count} element${count !== 1 ? 's' : ''}`);
        }

        /// Enhanced export function to include styling and positioning
        function exportCanvasToJson() {
            const data = {
                meta: {
                    created_at: new Date().toISOString(),
                    source: 'Skoolyst Form Builder',
                    version: '1.0'
                },
                elements: [],
                canvas_elements: [] // New array for individual elements with styling
            };

            $('#canvas .canvas-element').each(function() {
                const $el = $(this);
                const id = $el.attr('data-id');
                const type = $el.attr('data-type');
                const position = $el.position();
                const width = $el.width();
                const height = $el.height();
                const zIndex = parseInt($el.css('z-index')) || 0;
                
                // Get computed styles
                const styles = {
                    position: 'absolute',
                    left: position.left + 'px',
                    top: position.top + 'px',
                    width: width + 'px',
                    height: height + 'px',
                    zIndex: zIndex,
                    backgroundColor: $el.css('background-color'),
                    color: $el.css('color'),
                    fontSize: $el.css('font-size'),
                    fontFamily: $el.css('font-family'),
                    fontWeight: $el.css('font-weight'),
                    textAlign: $el.css('text-align'),
                    padding: $el.css('padding'),
                    margin: $el.css('margin'),
                    border: $el.css('border'),
                    borderRadius: $el.css('border-radius'),
                    boxShadow: $el.css('box-shadow')
                };

                let content = {};

                switch (type) {
                    case 'title':
                        content = {
                            text: $el.find('h2').text() || '',
                            html: $el.find('h2').html() || '',
                            level: $el.find('h2').prop('tagName') || 'h2',
                            alignment: $el.find('h2').css('text-align') || 'left'
                        };
                        break;

                    case 'banner':
                    case 'image':
                        const imgSrc = $el.find('img').attr('src') || '';
                        const caption = $el.find('.caption-input').val() || '';
                        const altText = $el.find('img').attr('alt') || '';
                        content = {
                            src: imgSrc,
                            caption: caption,
                            alt: altText,
                            size: $el.find('img').css('width') || '100%'
                        };
                        break;

                    case 'textarea':
                        const textareaId = $el.find('textarea.rich-text').attr('id');
                        let textData = '';

                        if (textareaId && CKEDITOR.instances[textareaId]) {
                            textData = CKEDITOR.instances[textareaId].getData();
                        } else {
                            textData = $el.find('textarea.rich-text').val() || '';
                        }

                        content = {
                            data: textData,
                            format: 'html'
                        };
                        break;

                    case 'two-col-tr':
                    case 'two-col-rt':
                        const leftContent = $el.find('.col-left').html();
                        const rightContent = $el.find('.col-right').html();
                        const images = [];

                        // Extract image sources as strings (not objects)
                        $el.find('img').each(function() {
                            images.push($(this).attr('src')); // Just push the src string
                        });

                        content = {
                            left: leftContent,
                            right: rightContent,
                            images: images, // Now this is just an array of strings
                            layout: type === 'two-col-tr' ? 'text-image' : 'image-text'
                        };
                        break;

                    case 'raw-html':
                        content = {
                            html: $el.find('.raw-html-input').val() || '',
                            type: 'custom'
                        };
                        break;

                    default:
                        content = {
                            html: $el.html()
                        };
                }

                // Add to elements array (existing structure)
                data.elements.push({
                    id: id,
                    type: type,
                    position: {
                        left: position.left,
                        top: position.top,
                        width: width,
                        height: height,
                        zIndex: zIndex
                    },
                    styles: styles,
                    content: content
                });

                // Add to canvas_elements array with individual element data
                const elementData = {
                    id: id,
                    type: type,
                    position: {
                        x: position.left,
                        y: position.top,
                        width: width,
                        height: height
                    },
                    styles: styles,
                    content: content,
                    metadata: {
                        created: new Date().toISOString(),
                        modified: new Date().toISOString()
                    }
                };

                // Store in individual arrays based on type
                if (!data[type]) {
                    data[type] = [];
                }
                data[type].push(elementData);

                // Also add to canvas_elements
                data.canvas_elements.push(elementData);
            });

            return data;
        }

        // Generate preview HTML with exact positioning
        function generatePreview() {
            const jsonData = exportCanvasToJson();
            const elements = jsonData.elements;
            
            let previewHtml = '';
            
            elements.forEach(element => {
                const type = element.type;
                const content = element.content || {};
                const position = element.position || {};
                
                const width = position.width || 'auto';
                const left = position.left || 0;
                const top = position.top || 0;
                const zIndex = position.zIndex || 1;
                
                let elementHtml = '';
                let previewClass = '';
                
                switch (type) {
                    case 'title':
                        previewClass = 'preview-title';
                        elementHtml = `
                            <div class="preview-element ${previewClass}">
                                <h1>${content.html || content.text || 'Untitled'}</h1>
                            </div>
                        `;
                        break;
                        
                    case 'banner':
                        previewClass = 'preview-banner';
                        if (content.src) {
                            elementHtml = `
                                <div class="preview-element ${previewClass}">
                                    <img src="${content.src}" alt="Banner">
                                    ${content.caption ? `<div class="preview-caption">${content.caption}</div>` : ''}
                                </div>
                            `;
                        }
                        break;
                        
                    case 'image':
                        previewClass = 'preview-image';
                        if (content.src) {
                            elementHtml = `
                                <div class="preview-element ${previewClass}">
                                    <img src="${content.src}" alt="Image">
                                    ${content.caption ? `<div class="preview-caption">${content.caption}</div>` : ''}
                                </div>
                            `;
                        }
                        break;
                        
                    case 'textarea':
                        previewClass = 'preview-textarea';
                        if (content.data) {
                            elementHtml = `
                                <div class="preview-element ${previewClass}">
                                    <div class="preview-rich-text">${content.data}</div>
                                </div>
                            `;
                        }
                        break;
                        
                    case 'two-col-tr':
                        previewClass = 'preview-two-col';
                        elementHtml = `
                            <div class="preview-element ${previewClass}">
                                <div class="two-col">
                                    <div class="col-left">${content.left || ''}</div>
                                    <div class="col-right">
                                        ${content.images && content.images.length > 0 ? 
                                            `<img src="${content.images[0]}" alt="Image">` : 
                                            '<div class="text-center py-4 bg-light rounded">No image</div>'
                                        }
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'two-col-rt':
                        previewClass = 'preview-two-col';
                        elementHtml = `
                            <div class="preview-element ${previewClass}">
                                <div class="two-col">
                                    <div class="col-left">
                                        ${content.images && content.images.length > 0 ? 
                                            `<img src="${content.images[0]}" alt="Image">` : 
                                            '<div class="text-center py-4 bg-light rounded">No image</div>'
                                        }
                                    </div>
                                    <div class="col-right">${content.right || ''}</div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'raw-html':
                        previewClass = 'preview-raw-html';
                        if (content.html) {
                            elementHtml = `
                                <div class="preview-element ${previewClass}">
                                    ${content.html}
                                </div>
                            `;
                        }
                        break;
                }
                
                // Add the positioned element with exact styling
                if (elementHtml) {
                    previewHtml += `
                        <div class="preview-positioned-element ${previewClass}" 
                            style="position: absolute; 
                                    left: ${left}px; 
                                    top: ${top}px; 
                                    width: ${typeof width === 'number' ? width + 'px' : width}; 
                                    z-index: ${zIndex};
                                    max-width: 100%;">
                            ${elementHtml}
                        </div>
                    `;
                }
            });
            
            return previewHtml || '<div class="text-center py-5 text-muted">No content to preview</div>';
        }

        // Show preview modal
        function showPreview() {
            const elementsCount = $('#canvas .canvas-element').length;
            if (elementsCount === 0) {
                showNotification('Please add some elements to the canvas first', 'error');
                return;
            }
            
            const previewHtml = generatePreview();
            $('#previewContainer').html(previewHtml);
            $('#previewModal').modal('show');
        }

        // Save page directly from preview
        function savePage() {
            $('#previewModal').modal('hide');
            $('#saveForm').trigger('submit');
        }

        // Add this function to your JavaScript
        function processImagesBeforeSave(jsonData) {
            if (!jsonData.elements) return jsonData;

            jsonData.elements.forEach(element => {
                if (element.content && element.content.images) {
                    // Images array will now be processed on the server side
                    // We keep the base64 data for server processing
                }
                
                // Process HTML content for images
                if (element.content) {
                    Object.keys(element.content).forEach(key => {
                        if (typeof element.content[key] === 'string' && 
                            element.content[key].includes('data:image/')) {
                            // Server will process these base64 images
                        }
                    });
                }
            });

            return jsonData;
        }

        // Main application
        $(function() {
            const $canvas = $('#canvas');
            const $gridGuides = $('#gridGuides');

            // Initialize with empty canvas state
            saveHistory();
            updateElementCount();

            // Preview buttons
            $('#previewBtn, #previewBtnTop').on('click', showPreview);

            // Make sidebar widgets draggable
            $('.widget').on('dragstart', function(e) {
                const type = $(this).data('type');
                e.originalEvent.dataTransfer.setData('text/plain', type);
                currentDragType = type;
                $(this).addClass('dragging');
            });

            $('.widget').on('dragend', function() {
                $(this).removeClass('dragging');
            });

            // Canvas drag and drop handlers
            $canvas.on('dragover', function(e) {
                e.preventDefault();
                $canvas.addClass('drag-over');
            });

            $canvas.on('dragleave', function(e) {
                $canvas.removeClass('drag-over');
            });

            $canvas.on('drop', function(e) {
                e.preventDefault();
                $canvas.removeClass('drag-over');

                const type = e.originalEvent.dataTransfer.getData('text/plain') || currentDragType;
                const rect = $canvas[0].getBoundingClientRect();
                const x = e.originalEvent.clientX - rect.left;
                const y = e.originalEvent.clientY - rect.top;

                addElementToCanvas(type, x, y);
                saveHistory();
                updateElementCount();
                showNotification(`${type.replace(/-/g, ' ')} element added`);
            });

            // Canvas element selection
            $canvas.on('click', '.canvas-element', function(e) {
                e.stopPropagation();
                $('.canvas-element').removeClass('selected').find('.element-toolbar').remove();
                $(this).addClass('selected');
                attachToolbar($(this));
            });

            // Deselect on canvas click
            $canvas.on('click', function(e) {
                if ($(e.target).is('#canvas')) {
                    $('.canvas-element').removeClass('selected').find('.element-toolbar').remove();
                }
            });

            // Clear canvas
            $('#clearCanvas').on('click', function() {
                if (!confirm('Clear entire canvas? This action cannot be undone.')) return;

                // Destroy CKEditor instances
                for (const id in CKEDITOR.instances) {
                    try {
                        CKEDITOR.instances[id].destroy(true);
                    } catch (e) {}
                }

                $canvas.empty().append('<div class="drop-hint">Drag elements here to start building</div>');
                saveHistory();
                updateElementCount();
                showNotification('Canvas cleared');
            });

            // Undo/Redo functionality
            $('#undoBtn').on('click', function() {
                if (history.length > 1) {
                    redoStack.push(history.pop());
                    const prev = JSON.parse(history[history.length - 1]);
                    loadCanvasFromJson(prev);
                    updateElementCount();
                    showNotification('Undo performed');
                } else {
                    showNotification('Nothing to undo', 'error');
                }
            });

            $('#redoBtn').on('click', function() {
                if (redoStack.length) {
                    const next = JSON.parse(redoStack.pop());
                    loadCanvasFromJson(next);
                    history.push(JSON.stringify(next));
                    updateElementCount();
                    showNotification('Redo performed');
                } else {
                    showNotification('Nothing to redo', 'error');
                }
            });

            // Expand canvas
            $('#addSpaceBtn').on('click', function() {
                let currentH = $canvas.height();
                $canvas.css('min-height', currentH + 500 + 'px');
                showNotification('Canvas space increased');
            });

            // Toggle grid visibility
            $('#toggleGrid').on('click', function() {
                isGridVisible = !isGridVisible;
                if (isGridVisible) {
                    $gridGuides.addClass('show');
                    $(this).html('<i class="fas fa-th-large me-1"></i>Hide Grid');
                } else {
                    $gridGuides.removeClass('show');
                    $(this).html('<i class="fas fa-th-large me-1"></i>Show Grid');
                }
            });

            // Handle form submission
            $('#saveForm').on('submit', function(e) {
                e.preventDefault();

                const pageName = $('#pageName').val().trim();
                if (!pageName) {
                    alert('Please enter a page name');
                    return;
                }

                // Get the current JSON data
                let jsonData = exportCanvasToJson();
                if (!jsonData.elements || jsonData.elements.length === 0) {
                    alert('Please add some elements to the canvas before saving');
                    return;
                }

                // Process images (server will handle the actual conversion)
                jsonData = processImagesBeforeSave(jsonData);

                // Show loading state
                const $saveBtn = $('#saveBtn');
                const originalText = $saveBtn.html();
                $saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Saving...');

                // Prepare form data
                const formData = new FormData();
                formData.append('name', pageName);
                formData.append('event_id', $('#eventId').val());
                formData.append('school_id', $('#schoolId').val());
                formData.append('form_data', JSON.stringify(jsonData));
                formData.append('_token', $('input[name="_token"]').val());

                // Send AJAX request
                $.ajax({
                    url: '/pages/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            showNotification('Page saved successfully!', 'success');
                            $('#saveResult').html(`
                                <div class="alert alert-success">
                                    <strong>Success!</strong> Page "${pageName}" has been saved successfully.
                                    <br><small>Page ID: ${response.page_id} | Slug: ${response.slug}</small>
                                    <br><a href="${response.redirect_url}" class="btn btn-primary btn-sm mt-2">View Page</a>
                                </div>
                            `);

                            // Clear form
                            $('#pageName').val('');
                        } else {
                            throw new Error(response.message);
                        }
                    },
                    error: function(xhr) {
                        let message = 'Failed to save page';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showNotification(message, 'error');
                        $('#saveResult').html(`
                            <div class="alert alert-danger">
                                <strong>Error!</strong> ${message}
                            </div>
                        `);
                    },
                    complete: function() {
                        $saveBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Create and add element to canvas
            function addElementToCanvas(type, x = 20, y = null, options = {}) {
                if (y === null) {
                    y = nextElementY;
                    nextElementY += 200;
                }

                const id = uid('c');
                const $el = $('<div class="canvas-element"></div>');
                $el.attr('data-id', id);
                $el.attr('data-type', type);

                $el.css({
                    left: x + 'px',
                    top: y + 'px',
                    width: options.width || 'auto',
                    maxWidth: '100%'
                });

                let contentHtml = '';

                switch (type) {
                    case 'title':
                        contentHtml = `
                            <div class="element-content">
                                <h2 class="editable" contenteditable="true" data-placeholder="Enter title here">Your Title Here</h2>
                            </div>
                        `;
                        break;

                    case 'banner':
                        contentHtml = `
                            <div class="element-content">
                                <div class="placeholder banner-placeholder">
                                    <i class="fas fa-image"></i>
                                    <span>Banner - Click to upload image</span>
                                </div>
                                <input type="file" accept="image/*" class="d-none banner-file">
                            </div>
                        `;
                        $el.css({
                            width: '100%',
                            left: 0
                        });
                        break;

                    case 'image':
                        contentHtml = `
                            <div class="element-content">
                                <div class="placeholder image-placeholder">
                                    <i class="fas fa-image"></i>
                                    <span>Image - Click to upload</span>
                                </div>
                                <input type="file" accept="image/*" class="d-none image-file">
                                <div class="mt-2">
                                    <input type="text" class="form-control form-control-sm caption-input" placeholder="Image caption (optional)">
                                </div>
                            </div>
                        `;
                        break;

                    case 'textarea':
                        const taId = id + '_ta';
                        contentHtml = `
                            <div class="element-content">
                                <textarea id="${taId}" class="rich-text form-control" rows="6" placeholder="Enter your text here..."></textarea>
                            </div>
                        `;
                        break;

                    case 'two-col-tr':
                        contentHtml = `
                            <div class="element-content">
                                <div class="two-col">
                                    <div class="col-left">
                                        <h3 class="editable" contenteditable="true" data-placeholder="Enter heading">Heading</h3>
                                        <div class="editable" contenteditable="true" data-placeholder="Enter paragraph text">Paragraph text here. Click to edit.</div>
                                    </div>
                                    <div class="col-right">
                                        <div class="placeholder image-placeholder">
                                            <i class="fas fa-image"></i>
                                            <span>Upload image</span>
                                        </div>
                                        <input type="file" accept="image/*" class="d-none image-file">
                                    </div>
                                </div>
                            </div>
                        `;
                        $el.css({
                            width: options.width || '90%'
                        });
                        break;

                    case 'two-col-rt':
                        contentHtml = `
                            <div class="element-content">
                                <div class="two-col">
                                    <div class="col-left">
                                        <div class="placeholder image-placeholder">
                                            <i class="fas fa-image"></i>
                                            <span>Upload image</span>
                                        </div>
                                        <input type="file" accept="image/*" class="d-none image-file">
                                    </div>
                                    <div class="col-right">
                                        <h3 class="editable" contenteditable="true" data-placeholder="Enter heading">Heading</h3>
                                        <div class="editable" contenteditable="true" data-placeholder="Enter paragraph text">Paragraph text here. Click to edit.</div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $el.css({
                            width: options.width || '90%'
                        });
                        break;

                    case 'raw-html':
                        contentHtml = `
                                <div class="element-content">
                                    <textarea class="form-control raw-html-input" rows="4" placeholder="Paste your HTML code here"></textarea>
                                    <div class="raw-preview mt-2 p-2 border rounded bg-light small" style="display: none;">
                                        <div class="raw-html-sandbox"></div>
                                    </div>
                                </div>
                            `;
                        break;

                    default:
                        contentHtml = '<div class="element-content">Unknown element type</div>';
                }

                $el.html(contentHtml);
                $canvas.append($el);

                $canvas.find('.drop-hint').remove();
                initializeElementBehaviors($el, type);
                makeElementMovable($el);
                addResizer($el);

                return $el;
            }

            // Initialize element-specific behaviors
            function initializeElementBehaviors($el, type) {
                $el.find('.placeholder').on('click', function() {
                    $(this).siblings('input[type="file"]').trigger('click');
                });

                $el.find('input[type="file"]').on('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const dataUrl = e.target.result;
                        const $placeholder = $(this).siblings('.placeholder').first();

                        if ($placeholder.length) {
                            $placeholder.replaceWith(`<img src="${dataUrl}" alt="Uploaded image" class="img-fluid">`);
                        }

                        saveHistory();
                    }.bind(this);

                    reader.readAsDataURL(file);
                });

                if (type === 'textarea') {
                    const taId = $el.find('textarea.rich-text').attr('id');
                    setTimeout(() => {
                        CKEDITOR.replace(taId, {
                            height: 200,
                            toolbar: [{
                                    name: 'basicstyles',
                                    items: ['Bold', 'Italic', 'Underline', 'Strike']
                                },
                                {
                                    name: 'paragraph',
                                    items: ['NumberedList', 'BulletedList', 'Blockquote']
                                },
                                {
                                    name: 'links',
                                    items: ['Link', 'Unlink']
                                },
                                {
                                    name: 'insert',
                                    items: ['Image', 'Table']
                                },
                                {
                                    name: 'tools',
                                    items: ['Maximize']
                                },
                                '/',
                                {
                                    name: 'styles',
                                    items: ['Format', 'FontSize']
                                },
                                {
                                    name: 'colors',
                                    items: ['TextColor', 'BGColor']
                                },
                                {
                                    name: 'align',
                                    items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
                                }
                            ],
                            on: {
                                change: function() {
                                    saveHistory();
                                }
                            }
                        });
                    }, 100);
                }

                if (type === 'raw-html') {
                    const $textarea = $el.find('.raw-html-input');
                    const $preview = $el.find('.raw-preview');
                    const $sandbox = $el.find('.raw-html-sandbox');

                    $textarea.on('input', function() {
                        const html = $(this).val();
                        if (html.trim()) {
                            $sandbox.html(sanitizeHtml(html));
                            $preview.show();
                        } else {
                            $preview.hide();
                        }
                        saveHistory();
                    });
                }

                $el.on('input', '[contenteditable="true"]', function() {
                    saveHistory();
                });

                $el.on('input', 'input, textarea', function() {
                    saveHistory();
                });
            }

            function sanitizeHtml(html) {
                return html
                    .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
                    .replace(/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/gi, '')
                    .replace(/ on\w+="[^"]*"/gi, '');
            }

            // Make element movable
            function makeElementMovable($el) {
                $el.draggable({
                    containment: "#canvas",
                    scroll: false,
                    stack: ".canvas-element",
                    stop: function() {
                        saveHistory();
                    }
                });
            }

            // Add resizer to element
            function addResizer($el) {
                const $resizer = $('<div class="resizer"></div>');
                $el.append($resizer);

                $resizer.on('mousedown', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const startX = e.clientX;
                    const startWidth = $el.width();

                    function onMouseMove(e) {
                        const newWidth = Math.max(200, startWidth + (e.clientX - startX));
                        $el.width(newWidth);
                    }

                    function onMouseUp() {
                        $(document).off('mousemove', onMouseMove);
                        $(document).off('mouseup', onMouseUp);
                        saveHistory();
                    }

                    $(document).on('mousemove', onMouseMove);
                    $(document).on('mouseup', onMouseUp);
                });
            }

            // Attach toolbar to selected element
            function attachToolbar($el) {
                $el.find('.element-toolbar').remove();

                const $toolbar = $('<div class="element-toolbar"></div>');
                const $duplicate = $('<button class="element-handle" title="Duplicate"><i class="fas fa-copy"></i></button>');
                const $delete = $('<button class="element-handle" title="Delete"><i class="fas fa-trash text-danger"></i></button>');
                const $front = $('<button class="element-handle" title="Bring to Front"><i class="fas fa-arrow-up"></i></button>');
                const $back = $('<button class="element-handle" title="Send to Back"><i class="fas fa-arrow-down"></i></button>');

                $toolbar.append($duplicate, $front, $back, $delete);
                $el.append($toolbar);

                $duplicate.on('click', function(e) {
                    e.stopPropagation();
                    const type = $el.attr('data-type');
                    const position = $el.position();
                    const width = $el.width();

                    const $clone = addElementToCanvas(type, position.left + 20, position.top + 20, {
                        width: width
                    });
                    $clone.css('z-index', parseInt($el.css('z-index')) + 1);

                    saveHistory();
                    updateElementCount();
                    showNotification('Element duplicated');
                });

                $delete.on('click', function(e) {
                    e.stopPropagation();
                    if (!confirm('Delete this element?')) return;

                    const textarea = $el.find('textarea.rich-text');
                    if (textarea.length) {
                        const editorId = textarea.attr('id');
                        if (editorId && CKEDITOR.instances[editorId]) {
                            CKEDITOR.instances[editorId].destroy(true);
                        }
                    }

                    $el.remove();
                    saveHistory();
                    updateElementCount();
                    showNotification('Element deleted');

                    if ($canvas.children('.canvas-element').length === 0) {
                        $canvas.append('<div class="drop-hint">Drag elements here to start building</div>');
                    }
                });

                $front.on('click', function(e) {
                    e.stopPropagation();
                    const maxZ = Math.max(...$('.canvas-element').map(function() {
                        return parseInt($(this).css('z-index')) || 0;
                    }).get());

                    $el.css('z-index', maxZ + 1);
                    saveHistory();
                });

                $back.on('click', function(e) {
                    e.stopPropagation();
                    const minZ = Math.min(...$('.canvas-element').map(function() {
                        return parseInt($(this).css('z-index')) || 0;
                    }).get());

                    $el.css('z-index', minZ - 1);
                    saveHistory();
                });
            }

            // Load canvas from JSON
            function loadCanvasFromJson(json) {
                for (const id in CKEDITOR.instances) {
                    try {
                        CKEDITOR.instances[id].destroy(true);
                    } catch (e) {}
                }

                $canvas.empty();

                if (!json || !Array.isArray(json.elements)) {
                    alert('Invalid JSON format. Expected: { elements: [ ... ] }');
                    return;
                }

                json.elements.forEach(function(item) {
                    const type = item.type;
                    const position = item.position || {
                        left: 20,
                        top: 20,
                        width: 'auto'
                    };

                    const $el = addElementToCanvas(
                        type,
                        position.left,
                        position.top, {
                            width: position.width
                        }
                    );

                    if (position.zIndex) {
                        $el.css('z-index', position.zIndex);
                    }

                    switch (type) {
                        case 'title':
                            if (item.content && item.content.text) {
                                $el.find('h2').text(item.content.text);
                            }
                            break;

                        case 'banner':
                        case 'image':
                            if (item.content && item.content.src) {
                                $el.find('.placeholder').replaceWith(`<img src="${item.content.src}" alt="Uploaded image" class="img-fluid">`);
                                if (item.content.caption) {
                                    $el.find('.caption-input').val(item.content.caption);
                                }
                            }
                            break;

                        case 'textarea':
                            if (item.content && item.content.data) {
                                const textareaId = $el.find('textarea.rich-text').attr('id');
                                setTimeout(() => {
                                    if (textareaId && CKEDITOR.instances[textareaId]) {
                                        CKEDITOR.instances[textareaId].setData(item.content.data);
                                    } else {
                                        $el.find('textarea.rich-text').val(item.content.data);
                                    }
                                }, 200);
                            }
                            break;

                        case 'two-col-tr':
                        case 'two-col-rt':
                            if (item.content) {
                                if (item.content.left) {
                                    $el.find('.col-left').html(item.content.left);
                                }
                                if (item.content.right) {
                                    $el.find('.col-right').html(item.content.right);
                                }
                                if (item.content.images && item.content.images.length > 0) {
                                    $el.find('.placeholder').each(function(index) {
                                        if (index < item.content.images.length) {
                                            $(this).replaceWith(`<img src="${item.content.images[index]}" alt="Uploaded image" class="img-fluid">`);
                                        }
                                    });
                                }
                            }
                            break;

                        case 'raw-html':
                            if (item.content && item.content.html) {
                                $el.find('.raw-html-input').val(item.content.html);
                                $el.find('.raw-preview').html(item.content.html).show();
                            }
                            break;
                    }
                });

                if ($canvas.children('.canvas-element').length === 0) {
                    $canvas.append('<div class="drop-hint">Drag elements here to start building</div>');
                }
            }

            // Save state to history
            function saveHistory() {
                const snapshot = exportCanvasToJson();
                history.push(JSON.stringify(snapshot));

                if (history.length > 50) {
                    history.shift();
                }

                redoStack = [];
            }
        });
    </script>
</body>

</html>