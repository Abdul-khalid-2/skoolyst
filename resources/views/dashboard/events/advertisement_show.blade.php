<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->name }} - Skoolyst</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #38b000;
            --accent: #ff9e00;
            --dark: #1a1a1a;
            --light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            min-height: 100vh;
        }

        /* ==================== HERO HEADER ==================== */
        .page-hero {
            background: linear-gradient(135deg, #4361ee 0%, #38b000 50%, #ff9e00 100%);
            color: white;
            padding: 120px 0 100px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }

        .back-button {
            position: absolute;
            top: 30px;
            left: 30px;
            background: rgba(255, 255, 255, 0.25);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.4);
            padding: 0.75rem 1.75rem;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        /* ==================== CONTENT CANVAS ==================== */
        .content-wrapper {
            padding: 60px 0;
        }

        .canvas-container {
            padding: 3rem;
            position: relative;
            min-height: 400px;
        }

        /* ==================== ELEMENTS ==================== */
        .page-element {
            margin-bottom: 2rem;
            padding: 2rem;
            border-radius: 15px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .page-element:hover {
            /* border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2); */
        }

        /* Element Type Specific Styles */
        .element-heading {
            border-left: 6px solid var(--primary);
        }

        .element-heading h1,
        .element-heading h2,
        .element-heading h3 {
            background: linear-gradient(135deg, #4361ee 0%, #38b000 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            font-weight: 800;
        }

        .element-text {
            /* background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); */
            /* border: 2px solid #eef2f6; */
        }

        .element-image {
            text-align: center;
            padding: 2rem !important;
        }

        .element-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .element-banner {
            padding: 0 !important;
            border-radius: 15px;
            overflow: hidden;
        }

        .element-banner img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
        }

        .element-columns {
            /* background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); */
            /* border: 2px solid #eef2f6; */
        }

        /* Element Badge */
        .element-badge {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        /* Banner Caption */
        .banner-caption {
            padding: 1.5rem;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.95));
            color: white;
            text-align: center;
            font-style: italic;
        }

        /* Image Caption */
        .image-caption {
            font-style: italic;
            color: #64748b;
            text-align: center;
            margin-top: 1rem;
            padding: 0.75rem;
            background: rgba(67, 97, 238, 0.05);
            border-radius: 8px;
        }

        /* Two Column Layout */
        .two-col {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        .two-col .col-left,
        .two-col .col-right {
            flex: 1;
        }

        /* Rich Text Content */
        .rich-text-content {
            color: #4b5563;
            line-height: 1.7;
        }

        .rich-text-content h1,
        .rich-text-content h2,
        .rich-text-content h3 {
            color: var(--dark);
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .rich-text-content ul,
        .rich-text-content ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }

        /* CKEditor Content Styles */
        .ck-content {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .ck-content h1, 
        .ck-content h2, 
        .ck-content h3, 
        .ck-content h4, 
        .ck-content h5, 
        .ck-content h6 {
            margin-top: 1.5em;
            margin-bottom: 0.5em;
            font-weight: 600;
            color: #1a1a1a;
        }

        .ck-content h1 { font-size: 2.5rem; }
        .ck-content h2 { font-size: 2rem; }
        .ck-content h3 { font-size: 1.75rem; }
        .ck-content h4 { font-size: 1.5rem; }
        .ck-content h5 { font-size: 1.25rem; }
        .ck-content h6 { font-size: 1rem; }

        .ck-content p {
            margin-bottom: 1em;
        }

        .ck-content ul, 
        .ck-content ol {
            margin-bottom: 1em;
            padding-left: 2em;
        }

        .ck-content ul { list-style-type: disc; }
        .ck-content ol { list-style-type: decimal; }

        .ck-content blockquote {
            border-left: 4px solid #4361ee;
            padding-left: 1em;
            margin: 1em 0;
            font-style: italic;
            color: #666;
        }

        .ck-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1em 0;
        }

        .ck-content table th,
        .ck-content table td {
            border: 1px solid #ddd;
            padding: 0.75em;
            text-align: left;
        }

        .ck-content table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .ck-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .ck-content a {
            color: #4361ee;
            text-decoration: none;
        }

        .ck-content a:hover {
            text-decoration: underline;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.4;
        }

        /* Footer */
        .page-footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #e2e8f0;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .back-button {
                top: 15px;
                left: 15px;
                padding: 0.6rem 1.2rem;
            }

            .canvas-container {
                padding: 1.5rem;
                border-radius: 15px;
            }

            .page-element {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .two-col {
                flex-direction: column;
                gap: 1rem;
            }

            .ck-content h1 { font-size: 2rem; }
            .ck-content h2 { font-size: 1.75rem; }
            .ck-content h3 { font-size: 1.5rem; }
        }
    </style>
</head>

<body>
    <!-- Back Button -->
    <a href="{{ url()->previous() }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        <span>Back</span>
    </a>

    <!-- Hero Header -->
    <div class="page-hero">
        <div class="hero-content container">
            <h1 class="hero-title">{{ $page->name }}</h1>
            <p class="hero-subtitle">
                A beautifully crafted page created with Skoolyst Page Builder
            </p>
            <div class="hero-meta">
                <div class="hero-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>Created: {{ $page->created_at->format('M d, Y') }}</span>
                </div>
                <div class="hero-meta-item">
                    <i class="fas fa-layer-group"></i>
                    <span>{{ count($page->structure['elements'] ?? []) }} Elements</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Canvas -->
    <div class="content-wrapper">
        <div class="container">
            <div class="canvas-container" id="pageCanvas">
                @if(isset($page->structure['elements']) && count($page->structure['elements']) > 0)
                    @foreach($page->structure['elements'] as $index => $element)
                        @php
                            $elementType = $element['type'] ?? '';
                            $elementContent = $element['content'] ?? [];
                            $elementPosition = $element['position'] ?? $index; // Use index as fallback position
                        @endphp

                        @switch($elementType)
                            @case('heading')
                                <div class="page-element element-heading">
                                    <div class="element-badge">
                                        <i class="fas fa-heading"></i>
                                        Heading • {{ $elementContent['level'] ?? 'h2' }}
                                    </div>
                                    <{{ $elementContent['level'] ?? 'h2' }} class="mb-0">
                                        {{ $elementContent['text'] ?? 'Heading Text' }}
                                    </{{ $elementContent['level'] ?? 'h2' }}>
                                </div>
                                @break

                            @case('text')
                                <div class="page-element element-text">
                                    <div class="element-badge">
                                        <i class="fas fa-paragraph"></i>
                                        Text Content
                                    </div>
                                    <div class="ck-content rich-text-content">
                                        {!! $elementContent['content'] ?? '<p>Text content goes here...</p>' !!}
                                    </div>
                                </div>
                                @break

                            @case('image')
                                <div class="page-element element-image">
                                    <div class="element-badge">
                                        <i class="fas fa-image"></i>
                                        Image
                                    </div>
                                    @if(isset($elementContent['src']) && $elementContent['src'])
                                        <img src="{{ $elementContent['src'] }}" 
                                             alt="{{ $elementContent['alt'] ?? 'Image' }}" 
                                             style="max-height: 300px;">
                                    @else
                                        <div class="text-center py-4 bg-light rounded text-muted">
                                            <i class="fas fa-image fa-2x mb-2"></i>
                                            <p>No Image Available</p>
                                        </div>
                                    @endif
                                    @if(isset($elementContent['caption']) && $elementContent['caption'])
                                        <div class="image-caption mt-3">
                                            {{ $elementContent['caption'] }}
                                        </div>
                                    @endif
                                </div>
                                @break

                            @case('banner')
                                <div class="page-element element-banner">
                                    <div class="element-badge">
                                        <i class="fas fa-banner"></i>
                                        Banner
                                    </div>
                                    @if(isset($elementContent['src']) && $elementContent['src'])
                                        <img src="{{ $elementContent['src'] }}" 
                                             alt="{{ $elementContent['alt'] ?? 'Banner' }}"
                                             style="max-height: 400px;">
                                    @else
                                        <div class="text-center py-5 bg-light text-muted">
                                            <i class="fas fa-image fa-3x mb-3"></i>
                                            <p>No Banner Image</p>
                                        </div>
                                    @endif
                                    @if(isset($elementContent['title']) || isset($elementContent['subtitle']))
                                        <div class="banner-caption">
                                            @if(isset($elementContent['title']))
                                                <h3 class="mb-2 text-white">{{ $elementContent['title'] }}</h3>
                                            @endif
                                            @if(isset($elementContent['subtitle']))
                                                <p class="mb-0">{{ $elementContent['subtitle'] }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                @break

                            @case('columns')
                                <div class="page-element element-columns">
                                    <div class="element-badge">
                                        <i class="fas fa-columns"></i>
                                        Two Columns
                                    </div>
                                    <div class="two-col">
                                        <div class="col-left">
                                            @if(isset($elementContent['left']))
                                                <div class="ck-content rich-text-content">
                                                    {!! $elementContent['left'] !!}
                                                </div>
                                            @else
                                                <p class="text-muted">Left column content</p>
                                            @endif
                                        </div>
                                        <div class="col-right">
                                            @if(isset($elementContent['right']))
                                                <div class="ck-content rich-text-content">
                                                    {!! $elementContent['right'] !!}
                                                </div>
                                            @else
                                                <p class="text-muted">Right column content</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @break

                            @case('custom_html')
                                <div class="page-element element-custom-html">
                                    <div class="element-badge">
                                        <i class="fas fa-code"></i>
                                        Custom HTML
                                    </div>
                                    <div class="custom-html-content">
                                        <style>
                                            {!! $elementContent['css'] ?? '' !!}
                                        </style>
                                        {!! $elementContent['html'] ?? '<p>No custom HTML content</p>' !!}
                                    </div>
                                </div>
                                @break

                            @default
                                <div class="page-element">
                                    <div class="element-badge">
                                        <i class="fas fa-question"></i>
                                        Unknown Element
                                    </div>
                                    <p>Unknown element type: {{ $elementType }}</p>
                                    <pre>{{ json_encode($element, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                        @endswitch
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-palette"></i>
                        <h3>No Content Yet</h3>
                        <p>This page doesn't have any content elements added yet.</p>
                        <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                            Edit Page
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer">
        <div class="container">
            <div class="text-center">
                <div class="footer-brand mb-2" style="font-size: 1.2rem; font-weight: 700; background: linear-gradient(135deg, #4361ee, #38b000); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    SKOOLYST
                </div>
                <p class="mb-0">&copy; {{ date('Y') }} Skoolyst. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation to elements
            const elements = document.querySelectorAll('.page-element');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    element.style.transition = 'all 0.5s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Make images in CKEditor content responsive
            document.querySelectorAll('.ck-content img').forEach(img => {
                img.classList.add('img-fluid');
            });

            // Add table responsive wrapper
            document.querySelectorAll('.ck-content table').forEach(table => {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            });
        });
    </script>
</body>
</html>