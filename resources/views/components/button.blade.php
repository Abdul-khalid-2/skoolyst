@props([
    'variant' => 'primary',
    'type'    => 'button',
    'href'    => null,
    'size'    => 'md',
])

@php
    $variantClass = match ($variant) {
        'primary'           => 'btn-primary',
        'secondary'         => 'btn-secondary',
        'danger'            => 'btn-danger',
        'success'           => 'btn-success',
        'warning'           => 'btn-warning',
        'info'              => 'btn-info',
        'light'             => 'btn-light',
        'dark'              => 'btn-dark',
        'outline-primary'   => 'btn-outline-primary',
        'outline-secondary' => 'btn-outline-secondary',
        'outline-danger'    => 'btn-outline-danger',
        'outline-success'   => 'btn-outline-success',
        'outline-info'      => 'btn-outline-info',
        'ghost'             => 'btn-ghost-custom',
        default             => 'btn-primary',
    };
    $sizeClass = match ($size) {
        'sm'  => 'btn-sm',
        'lg'  => 'btn-lg',
        default => '',
    };
    $classList = trim('btn ' . $variantClass . ' ' . $sizeClass);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classList]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classList]) }}>{{ $slot }}</button>
@endif
