@props([
    'id',
    'title' => '',
    'centered' => true,
    'size' => null,
    'staticBackdrop' => false,
    'labelledBy' => null,
])

@php
    $labelledBy = $labelledBy ?? $id . 'Label';
    $sizeClass = $size ? 'modal-' . $size : '';
@endphp

<div
    {{ $attributes->merge([
        'id' => $id,
        'class' => 'modal fade',
        'tabindex' => '-1',
        'aria-labelledby' => $labelledBy,
        'aria-hidden' => 'true',
    ]) }}
    @if ($staticBackdrop) data-bs-backdrop="static" data-bs-keyboard="false" @endif
>
    <div @class(['modal-dialog', $sizeClass, 'modal-dialog-centered' => $centered])>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $labelledBy }}">{{ $title }}</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
