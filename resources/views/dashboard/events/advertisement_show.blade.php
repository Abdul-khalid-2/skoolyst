<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->name }} - Skoolyst</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #6c757d;
            --light: #f8f9fa;
            --dark: #212529;
        }

        body {
            background: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .page-header {
            background: linear-gradient(135deg, #4361ee, #3a56d4);
            color: white;
            padding: 3rem 0;
            margin-bottom: 3rem;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }

        .page-header h1 {
            margin: 0 0 0.5rem 0;
            font-weight: 600;
        }

        .page-header .lead {
            margin: 0;
            opacity: 0.95;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            font-weight: 500;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* Canvas container - UPDATED for positioned layout */
        .canvas-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            padding: 2rem;
            border: 1px solid #eef2f6;
            position: relative;
            min-height: 800px;
            height: auto;
            overflow: visible;
        }

        /* Positioned elements - UPDATED to match preview system */
        .positioned-element {
            position: absolute !important;
            box-shadow: 0 2px 8px rgba(18, 38, 63, 0.06);
            border-radius: 8px;
            background: #fff;
            padding: 1rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s;
            min-height: 60px;
            max-width: 100%;
        }

        .positioned-element:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
        }

        /* Specific element styles for positioned layout */
        .positioned-element.element-title {
            border-left: 4px solid #4361ee;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
        }

        .positioned-element.element-title h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #4361ee, #3a56d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }

        .positioned-element.element-banner {
            padding: 0 !important;
            border-radius: 12px;
            overflow: hidden;
        }

        .positioned-element.element-banner img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
        }

        .positioned-element.element-image {
            background: linear-gradient(135deg, #f0f4ff, #ffffff);
            text-align: center;
            border-radius: 12px;
        }

        .positioned-element.element-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .positioned-element.element-textarea {
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-radius: 12px;
            border: 1px solid #eef2f6;
        }

        .positioned-element.element-two-col {
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-radius: 12px;
            border: 1px solid #eef2f6;
        }

        .positioned-element.element-raw-html {
            background: linear-gradient(135deg, #fff8f5, #ffffff);
            border-radius: 12px;
            border: 1px solid #ffe8d6;
        }

        .element-badge {
            position: absolute;
            top: -10px;
            right: 20px;
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 10;
        }

        /* Banner Element */
        .banner-element img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
            display: block;
        }

        .banner-caption {
            padding: 1rem;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            text-align: center;
            font-style: italic;
        }

        /* Image Element */
        .image-element img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            display: block;
            margin: 0 auto;
        }

        .image-caption {
            font-style: italic;
            color: var(--secondary);
            text-align: center;
            margin-top: 1rem;
            font-size: 0.95rem;
            padding: 0.5rem;
        }

        /* Two Column Layouts */
        .two-col {
            display: flex;
            gap: 3rem;
            align-items: flex-start;
        }

        .two-col .col-left,
        .two-col .col-right {
            flex: 1;
        }

        .two-col img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        /* Rich Text Content */
        .rich-text-content {
            color: #4b5563;
            line-height: 1.8;
            font-size: 1.1rem;
        }

        .rich-text-content h1,
        .rich-text-content h2,
        .rich-text-content h3,
        .rich-text-content h4 {
            color: #243447;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .rich-text-content h1:first-child,
        .rich-text-content h2:first-child,
        .rich-text-content h3:first-child,
        .rich-text-content h4:first-child {
            margin-top: 0;
        }

        .rich-text-content ul,
        .rich-text-content ol {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }

        .rich-text-content li {
            margin-bottom: 0.5rem;
        }

        .rich-text-content blockquote {
            border-left: 4px solid var(--primary);
            padding: 1rem 1.5rem;
            margin: 2rem 0;
            color: #64748b;
            font-style: italic;
            background: #f8fafc;
            border-radius: 0 8px 8px 0;
        }

        .rich-text-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        /* Raw HTML Content */
        .raw-html-content {
            width: 100%;
            font-family: inherit;
            font-size: 1.1rem;
            line-height: 1.8;
            color: #4b5563;
            box-sizing: border-box;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .raw-html-content * {
            max-width: 100%;
        }

        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .page-footer {
            background: #1e293b;
            color: #e2e8f0;
            padding: 2rem 0;
            margin-top: 4rem;
        }

        .page-footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        /* Animation for elements */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .positioned-element {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Image hover effects */
        .positioned-element img {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .positioned-element img:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Responsive Design - UPDATED for positioned layout */
        @media (max-width: 768px) {
            .two-col {
                flex-direction: column;
                gap: 1.5rem;
            }

            .page-header {
                padding: 2rem 0;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .canvas-container {
                padding: 1rem;
                position: relative;
                height: auto;
                min-height: 600px;
            }

            /* Convert absolute positioning to relative on mobile */
            .positioned-element {
                position: relative !important;
                left: auto !important;
                top: auto !important;
                width: 100% !important;
                margin-bottom: 1rem;
            }

            .positioned-element.element-title h1 {
                font-size: 2rem;
            }

            .banner-element img {
                max-height: 250px;
            }
        }

        @media (max-width: 991px) {
            .text-md-end {
                text-align: left !important;
                margin-top: 1rem;
            }
        }

        /* Print styles */
        @media print {
            .page-header {
                background: #4361ee !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .positioned-element {
                position: relative !important;
                page-break-inside: avoid;
            }
            
            .back-btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 mb-2">{{ $page->name }}</h1>
                    @if($page->event)
                    <p class="lead mb-0">
                        <i class="fas fa-calendar me-2"></i>
                        Event: {{ $page->event->event_name }}
                    </p>
                    @endif
                    <p class="mb-0 mt-2 opacity-75">
                        <small>
                            <i class="fas fa-clock me-1"></i>
                            Created: {{ $page->created_at->format('M j, Y') }}
                            @if($page->updated_at != $page->created_at)
                            | Updated: {{ $page->updated_at->format('M j, Y') }}
                            @endif
                        </small>
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('pages.index', $page->event_id) }}" class="back-btn">
                        <i class="fas fa-arrow-left"></i>
                        Back to Builder
                    </a>
                    <button onclick="printPage()" class="back-btn ms-2">
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content - UPDATED for positioned layout -->
    <div class="container">
        <div class="canvas-container" id="pageCanvas">
            @if(isset($page->structure['elements']) && count($page->structure['elements']) > 0)
            @php
            // Use the exact order from the stored data (no sorting by top position)
            $elements = $page->structure['elements'];
            @endphp

            @foreach($elements as $index => $element)
            @php
                // Determine element class based on type
                $elementClass = 'element-' . str_replace('-', '_', $element['type']);
                $position = $element['position'] ?? ['left' => 20, 'top' => 20, 'width' => 'auto', 'zIndex' => 1];
            @endphp

            <div class="positioned-element {{ $elementClass }}" 
                 style="left: {{ $position['left'] }}px; 
                        top: {{ $position['top'] }}px; 
                        width: {{ is_numeric($position['width']) ? $position['width'] . 'px' : $position['width'] }}; 
                        z-index: {{ $position['zIndex'] ?? 1 }};"
                 data-type="{{ $element['type'] }}">
                <span class="element-badge">
                    <i class="fas 
                    @if($element['type'] === 'title') fa-heading 
                    @elseif($element['type'] === 'banner') fa-image 
                    @elseif($element['type'] === 'image') fa-photo-video 
                    @elseif($element['type'] === 'textarea') fa-paragraph 
                    @elseif($element['type'] === 'two-col-tr' || $element['type'] === 'two-col-rt') fa-columns 
                    @elseif($element['type'] === 'raw-html') fa-code 
                    @else fa-cube 
                    @endif me-1">
                    </i>
                    {{ $element['type'] }}
                </span>

                @switch($element['type'])

                @case('title')
                <div class="title-content">
                    <h1>{!! App\Http\Controllers\PageController::cleanContent($element['content']['html'] ?? $element['content']['text'] ?? 'Untitled') !!}</h1>
                </div>
                @break

                @case('banner')
                <div class="banner-element">
                    @if(isset($element['content']['src']) && $element['content']['src'])
                    @php
                        $imageUrl = strpos($element['content']['src'], 'school/') === 0 ? 
                                   asset('website/' . $element['content']['src']) : 
                                   $element['content']['src'];
                    @endphp
                    <img src="{{ $imageUrl }}" alt="Banner">
                    @if(isset($element['content']['caption']) && $element['content']['caption'])
                    <div class="banner-caption">
                        <p class="mb-0">{{ $element['content']['caption'] }}</p>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-5 bg-light">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No banner image</p>
                    </div>
                    @endif
                </div>
                @break

                @case('image')
                <div class="image-element">
                    @if(isset($element['content']['src']) && $element['content']['src'])
                    @php
                        $imageUrl = strpos($element['content']['src'], 'school/') === 0 ? 
                                   asset('website/' . $element['content']['src']) : 
                                   $element['content']['src'];
                    @endphp
                    <img src="{{ $imageUrl }}" alt="Image">
                    @if(isset($element['content']['caption']) && $element['content']['caption'])
                    <div class="image-caption">{{ $element['content']['caption'] }}</div>
                    @endif
                    @else
                    <div class="text-center py-4 bg-light rounded">
                        <i class="fas fa-image fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No image available</p>
                    </div>
                    @endif
                </div>
                @break

                @case('textarea')
                <div class="rich-text-content">
                    {!! App\Http\Controllers\PageController::cleanContent($element['content']['data'] ?? '') !!}
                </div>
                @break

                @case('two-col-tr')
                <div class="two-col">
                    <div class="col-left">
                        {!! App\Http\Controllers\PageController::cleanContent($element['content']['left'] ?? '') !!}
                    </div>
                    <div class="col-right">
                        @if(isset($element['content']['images']) && count($element['content']['images']) > 0)
                        @php
                            $imageUrl = strpos($element['content']['images'][0], 'school/') === 0 ? 
                                       asset('website/' . $element['content']['images'][0]) : 
                                       $element['content']['images'][0];
                        @endphp
                        <img src="{{ $imageUrl }}" alt="Image">
                        @else
                        <div class="text-center py-4 bg-light rounded">
                            <i class="fas fa-image fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No image</p>
                        </div>
                        @endif
                    </div>
                </div>
                @break

                @case('two-col-rt')
                <div class="two-col">
                    <div class="col-left">
                        @if(isset($element['content']['images']) && count($element['content']['images']) > 0)
                        @php
                            $imageUrl = strpos($element['content']['images'][0], 'school/') === 0 ? 
                                       asset('website/' . $element['content']['images'][0]) : 
                                       $element['content']['images'][0];
                        @endphp
                        <img src="{{ $imageUrl }}" alt="Image">
                        @else
                        <div class="text-center py-4 bg-light rounded">
                            <i class="fas fa-image fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No image</p>
                        </div>
                        @endif
                    </div>
                    <div class="col-right">
                        {!! App\Http\Controllers\PageController::cleanContent($element['content']['right'] ?? '') !!}
                    </div>
                </div>
                @break

                @case('raw-html')
                <div class="raw-html-content">
                    {!! App\Http\Controllers\PageController::cleanContent($element['content']['html'] ?? '') !!}
                </div>
                @break

                @default
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Unknown element type: {{ $element['type'] }}
                </div>
                @endswitch
            </div>
            @endforeach
            @else
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <h3>No Content Available</h3>
                <p>This page doesn't have any content elements yet.</p>
                <a href="{{ route('pages.index', $page->event_id) }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus me-2"></i>Create Content
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Page Footer -->
    <footer class="page-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; {{ date('Y') }} Skoolyst. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>
                        Skoolyst.com find your future
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add intersection observer for fade-in animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all positioned elements
            document.querySelectorAll('.positioned-element').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });

            // Add hover effects for images
            document.querySelectorAll('.positioned-element img').forEach(img => {
                img.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.02)';
                });

                img.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Adjust canvas container height based on content
            function adjustCanvasHeight() {
                const canvas = document.getElementById('pageCanvas');
                const elements = document.querySelectorAll('.positioned-element');
                let maxBottom = 0;

                elements.forEach(element => {
                    const rect = element.getBoundingClientRect();
                    const bottom = rect.top + rect.height;
                    if (bottom > maxBottom) {
                        maxBottom = bottom;
                    }
                });

                // Add some padding
                canvas.style.minHeight = (maxBottom + 100) + 'px';
            }

            // Adjust height after images load
            window.addEventListener('load', adjustCanvasHeight);
            setTimeout(adjustCanvasHeight, 500);
        });

        // Print functionality
        function printPage() {
            window.print();
        }

        // Handle window resize for responsive behavior
        window.addEventListener('resize', function() {
            const elements = document.querySelectorAll('.positioned-element');
            if (window.innerWidth <= 768) {
                // Mobile: ensure elements are properly stacked
                elements.forEach((el, index) => {
                    el.style.marginBottom = '1rem';
                });
            }
        });
    </script>
</body>

</html>