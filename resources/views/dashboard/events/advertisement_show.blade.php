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
            --secondary: #38b000;
            --accent: #ff9e00;
            --dark: #1a1a1a;
            --light: #f8f9fa;
            --purple: #667eea;
            --purple-dark: #764ba2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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

        .page-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            animation: patternFloat 20s linear infinite;
            opacity: 0.3;
        }

        @keyframes patternFloat {
            0% {
                transform: translateY(0px) translateX(0px);
            }
            100% {
                transform: translateY(-100px) translateX(-100px);
            }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
            animation: fadeInDown 0.8s ease-out;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto 2rem;
            line-height: 1.8;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-meta {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 2rem;
            animation: fadeIn 1s ease-out;
        }

        .hero-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .hero-meta-item:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
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
            backdrop-filter: blur(10px);
            z-index: 10;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.4);
            color: white;
            transform: translateX(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        /* ==================== CONTENT CANVAS ==================== */
        .content-wrapper {
            padding: 60px 0;
            min-height: 600px;
        }

        .canvas-container {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 3rem;
            position: relative;
            min-height: 800px;
            overflow: visible;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* ==================== POSITIONED ELEMENTS ==================== */
        .positioned-element {
            position: absolute !important;
            /* background: rgb(138, 71, 71); */
            border-radius: 20px;
            padding: 2rem;
            /* box-shadow: 0 8px 30px rgba(160, 127, 20, 0.1); */
            border: 2px solid transparent;
            /* transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); */
            min-height: 80px;
            max-width: 100%;
            opacity: 0;
            transform: translateY(30px);
        }

        .positioned-element.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .positioned-element:hover {
            /* border-color: var(--primary);
            box-shadow: 0 15px 50px rgb(13, 204, 86); */
            transform: translateY(-5px) !important;
        }

        /* Element Type Specific Styles */
        /* .positioned-element.element-title {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-left: 6px solid var(--primary);
            padding-left: 2.5rem;
        } */

        .positioned-element.element-title h1,
        .positioned-element.element-title h2,
        .positioned-element.element-title h3 {
            background: linear-gradient(135deg, #4361ee 0%, #38b000 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            font-weight: 800;
            line-height: 1.3;
        }

        .positioned-element.element-banner {
            padding: 0 !important;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
        }

        .positioned-element.element-banner img {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }

        .positioned-element.element-banner:hover img {
            transform: scale(1.05);
        }

        /* .positioned-element.element-image {
            background: linear-gradient(135deg, #213d88 0%, #9e2020 100%);
            text-align: center;
            border: 2px solid #e8eeff;
        } */

        .positioned-element.element-image img {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.4s ease;
        }

        .positioned-element.element-image:hover img {
            transform: scale(1.03) rotate(1deg);
        }

        /* .positioned-element.element-textarea {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid #eef2f6;
        }

        .positioned-element.element-two-col {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid #eef2f6;
        }

        .positioned-element.element-raw-html {
            background: linear-gradient(135deg, #fffbf5 0%, #ffffff 100%);
            border: 2px solid #ffe8d6;
        } */

        /* Element Badge */
        .element-badge {
            position: absolute;
            top: -12px;
            right: 25px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 10;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Banner Caption */
        .banner-caption {
            padding: 1.5rem;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.95));
            color: white;
            text-align: center;
            font-style: italic;
            font-size: 1.1rem;
        }

        /* Image Caption */
        .image-caption {
            font-style: italic;
            color: #64748b;
            text-align: center;
            margin-top: 1.5rem;
            font-size: 1rem;
            padding: 0.75rem;
            background: rgba(67, 97, 238, 0.05);
            border-radius: 10px;
        }

        /* Two Column Layout */
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
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* Rich Text Content */
        .rich-text-content {
            color: #4b5563;
            line-height: 1.9;
            font-size: 1.15rem;
        }

        .rich-text-content h1,
        .rich-text-content h2,
        .rich-text-content h3,
        .rich-text-content h4 {
            color: var(--dark);
            margin-top: 2.5rem;
            margin-bottom: 1.25rem;
            font-weight: 700;
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
            padding-left: 2.5rem;
        }

        .rich-text-content li {
            margin-bottom: 0.75rem;
            position: relative;
        }

        .rich-text-content ul li::marker {
            color: var(--primary);
            font-size: 1.2em;
        }

        .rich-text-content blockquote {
            border-left: 6px solid var(--primary);
            padding: 1.5rem 2rem;
            margin: 2.5rem 0;
            color: #64748b;
            font-style: italic;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: 0 15px 15px 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 8rem 2rem;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 5rem;
            margin-bottom: 2rem;
            opacity: 0.4;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .empty-state h3 {
            color: #64748b;
            margin-bottom: 1rem;
            font-size: 2rem;
            font-weight: 700;
        }

        .empty-state p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .cta-button {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(67, 97, 238, 0.4);
            color: white;
        }

        /* ==================== FOOTER ==================== */
        .page-footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #e2e8f0;
            padding: 3rem 0;
            margin-top: 5rem;
            border-top: 4px solid var(--primary);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #4361ee, #38b000);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-text {
            margin: 0;
            font-size: 1rem;
            opacity: 0.9;
        }

        /* ==================== ANIMATIONS ==================== */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* ==================== RESPONSIVE DESIGN ==================== */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .back-button {
                top: 15px;
                left: 15px;
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .hero-meta {
                gap: 1rem;
            }

            .hero-meta-item {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .canvas-container {
                padding: 1.5rem;
                border-radius: 15px;
                min-height: 600px;
            }

            .positioned-element {
                position: relative !important;
                left: auto !important;
                top: auto !important;
                width: 100% !important;
                margin-bottom: 1.5rem;
                padding: 1.5rem;
            }

            .positioned-element:hover {
                transform: translateY(-3px) !important;
            }

            .two-col {
                flex-direction: column;
                gap: 2rem;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 991px) {
            .page-hero {
                padding: 100px 0 80px;
            }

            .content-wrapper {
                padding: 40px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Back Button -->
    <a href="{{ route('pages.index', $page->event_id) }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Builder</span>
    </a>

    <!-- Hero Header -->
    <div class="page-hero">
        <div class="hero-content container">
            <h1 class="hero-title">{{ $page->name }}</h1>
            @if($page->event)
            <p class="hero-subtitle">
                {{ $page->event->event_name }} - A beautifully crafted page with modern design and engaging content.
            </p>
            @else
            <p class="hero-subtitle">
                A beautifully crafted page with modern design and engaging content.
            </p>
            @endif
            <div class="hero-meta">
                <div class="hero-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>Created: {{ $page->created_at->format('M j, Y') }}</span>
                </div>
                @if($page->updated_at != $page->created_at)
                <div class="hero-meta-item">
                    <i class="fas fa-clock"></i>
                    <span>Updated: {{ $page->updated_at->format('M j, Y') }}</span>
                </div>
                @endif
                <div class="hero-meta-item">
                    <i class="fas fa-eye"></i>
                    <span>Modern Design</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Canvas -->
    <div class="content-wrapper">
        <div class="container">
            <div class="canvas-container" id="pageCanvas">
                @if(isset($page->elements) && count($page->elements) > 0)
                    @foreach($page->elements as $index => $element)
                        @php
                            // Determine element class based on type
                            $elementClass = 'element-' . str_replace('-', '_', $element['type']);
                            $position = $element['position'] ?? ['left' => 20, 'top' => 20, 'width' => 'auto', 'zIndex' => 1];
                            $styles = $element['styles'] ?? [];
                            
                            // Build style string from stored styles
                            $styleString = "left: {$position['left']}px; top: {$position['top']}px; width: " . 
                                (is_numeric($position['width']) ? $position['width'] . 'px' : $position['width']) . 
                                "; z-index: " . ($position['zIndex'] ?? 1);
                            
                            // Add additional styles if available
                            if (!empty($styles)) {
                                foreach ($styles as $styleKey => $styleValue) {
                                    if (!in_array($styleKey, ['position', 'left', 'top', 'width', 'zIndex']) && $styleValue) {
                                        $styleString .= "; {$styleKey}: {$styleValue}";
                                    }
                                }
                            }
                        @endphp

                        <div class="positioned-element {{ $elementClass }} " 
                             style="{{ $styleString }}"
                             data-type="{{ $element['type'] }}">
          

                            @switch($element['type'])

                            @case('title')
                            <div class="title-content">
                                @php
                                    $level = $element['content']['level'] ?? 'h2';
                                    $alignment = $element['content']['alignment'] ?? 'left';
                                    $titleContent = $element['content']['html'] ?? $element['content']['text'] ?? 'Untitled';
                                @endphp
                                <{{ $level }} style="text-align: {{ $alignment }};">
                                    {!! App\Http\Controllers\PageController::cleanContent($titleContent) !!}
                                </{{ $level }}>
                            </div>
                            @break

                            @case('banner')
                            <div class="banner-element">
                                @if(isset($element['content']['src']) && $element['content']['src'])
                                    <img src="{{ $element['content']['src'] }}" 
                                         alt="{{ $element['content']['alt'] ?? 'Banner' }}"
                                         style="width: {{ $element['content']['size'] ?? '100%' }};">
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
                                    <img src="{{ $element['content']['src'] }}" 
                                         alt="{{ $element['content']['alt'] ?? 'Image' }}"
                                         style="width: {{ $element['content']['size'] ?? '100%' }};">
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
                                        <img src="{{ $element['content']['images'][0] }}" alt="Image">
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
                                        <img src="{{ $element['content']['images'][0] }}" alt="Image">
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
                    <a href="{{ route('pages.index', $page->event_id) }}" class="cta-button">
                        <i class="fas fa-plus me-2"></i>Create Content
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer">
        <div class="container">
            <div class="footer-content">
                <div>
                    <div class="footer-brand">SKOOLYST</div>
                    <p class="footer-text mt-2">&copy; {{ date('Y') }} Skoolyst. All rights reserved.</p>
                </div>
                <div>
                    <p class="footer-text">Skoolyst.com - Find Your Future</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer for scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('visible');
                        }, index * 100);
                    }
                });
            }, observerOptions);

            // Observe all positioned elements
            document.querySelectorAll('.positioned-element').forEach(el => {
                observer.observe(el);
            });

            // Dynamic canvas height adjustment
            function adjustCanvasHeight() {
                const canvas = document.getElementById('pageCanvas');
                const elements = document.querySelectorAll('.positioned-element');
                let maxBottom = 0;

                elements.forEach(element => {
                    const rect = element.getBoundingClientRect();
                    const canvasRect = canvas.getBoundingClientRect();
                    const relativeBottom = rect.bottom - canvasRect.top;

                    if (relativeBottom > maxBottom) {
                        maxBottom = relativeBottom;
                    }
                });

                if (maxBottom > 0) {
                    canvas.style.minHeight = (maxBottom + 100) + 'px';
                }
            }

            // Adjust height on load and after images load
            window.addEventListener('load', adjustCanvasHeight);
            setTimeout(adjustCanvasHeight, 300);
            setTimeout(adjustCanvasHeight, 1000);

            // Image lazy loading effect
            const images = document.querySelectorAll('.positioned-element img');
            images.forEach(img => {
                img.addEventListener('load', function() {
                    this.style.opacity = '0';
                    setTimeout(() => {
                        this.style.transition = 'opacity 0.6s ease';
                        this.style.opacity = '1';
                    }, 100);
                });
            });

            // Responsive behavior
            function handleResize() {
                if (window.innerWidth <= 768) {
                    document.querySelectorAll('.positioned-element').forEach((el, index) => {
                        el.style.marginBottom = '1.5rem';
                    });
                }
                adjustCanvasHeight();
            }

            window.addEventListener('resize', handleResize);
            handleResize();

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>