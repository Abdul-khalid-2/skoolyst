@props([
    'action' => '',
    'method' => 'GET',
    'id'     => 'filter-form',
])

<div class="filter-card">
    <form action="{{ $action }}" method="{{ $method }}" id="{{ $id }}">
        @if(strtoupper($method) !== 'GET')
            @csrf
        @endif
        <div class="filter-card__body">
            {{ $slot }}
        </div>
        @isset($footer)
            <div class="filter-card__footer">{{ $footer }}</div>
        @endisset
    </form>
</div>
