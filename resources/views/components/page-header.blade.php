@props(['title' => ''])

<header {{ $attributes->merge(['class' => 'page-header mb-4 px-3 px-md-0']) }}>
    <div
        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center"
    >
        <div class="mb-3 mb-md-0">
            @isset($heading)
                {{ $heading }}
            @else
                <h1 class="h3 mb-1 mb-md-2">{{ $title }}</h1>
            @endisset
        </div>
        @isset($actions)
            <div class="w-100 w-md-auto">
                {{ $actions }}
            </div>
        @endisset
    </div>
</header>
