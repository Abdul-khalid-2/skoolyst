@props(['messages'])

@if ($messages)
    @foreach ((array) $messages as $message)
        <div class="invalid-feedback d-block">
            <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>{{ $message }}
        </div>
    @endforeach
@endif
