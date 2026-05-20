@props([
    'title'       => 'No data',
    'description' => null,
    'icon'        => 'fa-inbox',
])

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <div class="empty-state__icon-wrap">
        <i class="fas {{ $icon }} empty-state__icon" aria-hidden="true"></i>
    </div>
    <h3 class="empty-state__title">{{ $title }}</h3>
    @if ($description)
        <p class="empty-state__desc">{{ $description }}</p>
    @endif
    @isset($actions)
        <div class="empty-state__actions">{{ $actions }}</div>
    @endisset
    {{ $slot }}
</div>
