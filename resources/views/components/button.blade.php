@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
])

@php
    $base = 'btn';
    $variantClass = match ($variant) {
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'danger' => 'btn-danger',
        'success' => 'btn-success',
        'warning' => 'btn-warning',
        'info' => 'btn-info',
        'light' => 'btn-light',
        'dark' => 'btn-dark',
        'outline-primary' => 'btn-outline-primary',
        'outline-secondary' => 'btn-outline-secondary',
        'outline-danger' => 'btn-outline-danger',
        'outline-info' => 'btn-outline-info',
        default => 'btn-primary',
    };
    $classList = trim($base . ' ' . $variantClass);
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classList]) }}
    >{{ $slot }}</a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classList]) }}
    >{{ $slot }}</button>
@endif
