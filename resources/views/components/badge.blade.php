@props(['variant' => 'primary'])

@php
    $bg = match ($variant) {
        'primary' => 'bg-primary',
        'secondary' => 'bg-secondary',
        'success' => 'bg-success',
        'danger' => 'bg-danger',
        'warning' => 'bg-warning text-dark',
        'info' => 'bg-info',
        'light' => 'bg-light text-dark',
        'dark' => 'bg-dark',
        default => 'bg-primary',
    };
@endphp

<span {{ $attributes->merge(['class' => "badge {$bg}"]) }}>{{ $slot }}</span>
