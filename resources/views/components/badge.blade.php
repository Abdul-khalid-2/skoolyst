@props(['variant' => 'primary'])

@php
$classes = match ($variant) {
    'primary'   => 'badge-custom badge-primary-custom',
    'success'   => 'badge-custom badge-success-custom',
    'danger'    => 'badge-custom badge-danger-custom',
    'warning'   => 'badge-custom badge-warning-custom',
    'info'      => 'badge-custom badge-info-custom',
    'secondary' => 'badge-custom badge-secondary-custom',
    'dark'      => 'badge-custom badge-dark-custom',
    default     => 'badge-custom badge-primary-custom',
};
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>
