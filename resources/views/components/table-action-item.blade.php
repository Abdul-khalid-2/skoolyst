@props([
    'href' => '#',
    'icon' => null,
    'variant' => 'default',
    'method' => 'GET',
])

@php
    $method = strtoupper($method);
    $isForm = in_array($method, ['POST', 'DELETE', 'PATCH', 'PUT'], true);
    $itemClass = 'dropdown-item d-flex align-items-center';
    if ($variant === 'danger') {
        $itemClass .= ' text-danger';
    }
@endphp

<li>
    @if ($isForm)
        <form action="{{ $href }}" method="POST" class="m-0">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif
            <button
                type="submit"
                {{ $attributes->merge(['class' => $itemClass . ' w-100 border-0 bg-transparent text-start']) }}
            >
                @if ($icon)
                    <i class="fas {{ $icon }} me-2" aria-hidden="true"></i>
                @endif
                {{ $slot }}
            </button>
        </form>
    @else
        <a href="{{ $href }}" {{ $attributes->merge(['class' => $itemClass]) }}>
            @if ($icon)
                <i class="fas {{ $icon }} me-2" aria-hidden="true"></i>
            @endif
            {{ $slot }}
        </a>
    @endif
</li>
