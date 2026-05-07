@props([])

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    <button
        type="button"
        class="btn btn-sm btn-outline-secondary"
        data-bs-toggle="dropdown"
        data-bs-popper-config='{"strategy":"fixed"}'
        aria-expanded="false"
        aria-label="{{ __('Actions') }}"
    >
        <i class="fas fa-ellipsis-v" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="z-index: 1080;">
        {{ $slot }}
    </ul>
</div>
