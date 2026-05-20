@props(['title' => ''])

<header {{ $attributes->merge(['class' => 'page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3']) }}>
    <div>
        @isset($heading)
            {{ $heading }}
        @else
            <h1 class="page-title">{{ $title }}</h1>
        @endisset
        @isset($breadcrumb)
            <nav aria-label="breadcrumb" class="mt-1">{{ $breadcrumb }}</nav>
        @endisset
    </div>
    @isset($actions)
        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{ $actions }}
        </div>
    @endisset
</header>
