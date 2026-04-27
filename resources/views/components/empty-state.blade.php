@props([
    'title' => 'No data',
    'description' => null,
    'icon' => 'fa-inbox',
])

<div {{ $attributes->merge(['class' => 'text-center py-5 px-3 text-muted']) }}>
    <i class="fas {{ $icon }} fa-3x mb-3 opacity-50" aria-hidden="true"></i>
    <h3 class="h5 text-body">{{ $title }}</h3>
    @if ($description)
        <p class="mb-3">{{ $description }}</p>
    @endif
    @isset($actions)
        <div class="mt-3">
            {{ $actions }}
        </div>
    @endisset
    {{ $slot }}
</div>
