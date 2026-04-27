@props([
    'variant' => 'success',
    'dismissible' => true,
    'message' => null,
    'icon' => true,
])

@php
    $classes = match ($variant) {
        'success' => 'alert-success',
        'error', 'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
        default => 'alert-success',
    };
    $iconClass = match ($variant) {
        'success' => 'fa-check-circle',
        'error', 'danger' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle',
        default => 'fa-check-circle',
    };
    $dismiss = $dismissible ? 'alert-dismissible fade show' : '';
@endphp

<div
    role="alert"
    {{ $attributes->merge(['class' => trim("alert {$classes} {$dismiss} mx-3 mx-md-0 mb-4")]) }}
>
    <div class="d-flex align-items-center">
        @if ($icon)
            <i class="fas {{ $iconClass }} me-2" aria-hidden="true"></i>
        @endif
        <div class="flex-grow-1">
            @if ($message !== null && $message !== '')
                {!! $message !!}
            @else
                {{ $slot }}
            @endif
        </div>
    </div>
    @if ($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
