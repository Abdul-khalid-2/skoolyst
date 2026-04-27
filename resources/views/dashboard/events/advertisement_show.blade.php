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

    <link rel="stylesheet" href="{{ asset('css/dashboard/events/advertisement-show.css') }}">
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
                        <x-button href="{{ route('pages.edit', $page->id) }}" variant="primary">
                            <i class="fas fa-edit"></i>
                            Edit Page
                        </x-button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer">
        <div class="container">
            <div class="text-center">
                <div class="footer-brand mb-2" style="font-size: 1.2rem; font-weight: 700; background: #0f4077; -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
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