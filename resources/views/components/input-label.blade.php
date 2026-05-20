@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'form-label fw-medium mb-1']) }}>
    {{ $value ?? $slot }}
    @if ($required)
        <span class="text-danger ms-1" aria-hidden="true">*</span>
    @endif
</label>
