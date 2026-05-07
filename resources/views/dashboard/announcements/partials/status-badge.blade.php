@php
    use App\Enums\ContentStatus;
    $variant = match ($announcement->status) {
        ContentStatus::Published => 'success',
        ContentStatus::Draft => 'warning',
        default => 'secondary',
    };
@endphp
<x-badge :variant="$variant">
    {{ ucfirst($announcement->status->value) }}
</x-badge>
