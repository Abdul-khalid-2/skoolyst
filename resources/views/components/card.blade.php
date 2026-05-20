@props([
    'flush' => false,
    'noPad' => false,
])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @isset($cardHeader)
        <div class="card-header">{{ $cardHeader }}</div>
    @endisset
    <div @class(['card-body', 'p-0' => $flush || $noPad])>
        {{ $slot }}
    </div>
    @isset($cardFooter)
        <div class="card-footer text-muted small">{{ $cardFooter }}</div>
    @endisset
</div>
