@props([
    'label'  => 'Stat',
    'value'  => '0',
    'sub'    => null,
    'icon'   => 'fa-chart-bar',
    'color'  => 'primary',
])

@php
$colorMap = [
    'primary' => ['bg' => '#051f47', 'icon_bg' => 'rgba(255,255,255,0.15)'],
    'success' => ['bg' => '#15803d', 'icon_bg' => 'rgba(255,255,255,0.15)'],
    'warning' => ['bg' => '#b45309', 'icon_bg' => 'rgba(255,255,255,0.15)'],
    'info'    => ['bg' => '#0e7490', 'icon_bg' => 'rgba(255,255,255,0.15)'],
    'danger'  => ['bg' => '#b91c1c', 'icon_bg' => 'rgba(255,255,255,0.15)'],
];
$c = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div class="stat-card" style="--stat-bg: {{ $c['bg'] }}; --stat-icon-bg: {{ $c['icon_bg'] }};">
    <div class="stat-card__body">
        <div>
            <p class="stat-card__label">{{ $label }}</p>
            <h2 class="stat-card__value">{{ $value }}</h2>
            @if ($sub)
                <p class="stat-card__sub">{{ $sub }}</p>
            @endif
        </div>
        <div class="stat-card__icon-wrap">
            <i class="fas {{ $icon }} fa-xl" aria-hidden="true"></i>
        </div>
    </div>
    @isset($footer)
        <div class="stat-card__footer">{{ $footer }}</div>
    @endisset
</div>
