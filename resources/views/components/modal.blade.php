@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$maxWidth = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
][$maxWidth];
$dialogId = 'dialog-'.$name;
@endphp

<dialog
    id="{{ $dialogId }}"
    class="rounded-lg p-0 shadow-xl dark:bg-gray-800 {{ $maxWidth }} w-[calc(100%-2rem)] sm:w-full border-0 bg-white text-gray-900 dark:text-gray-100 backdrop:bg-gray-500/75"
    @if($show) open @endif
>
    <div class="max-h-[90vh] overflow-y-auto p-0">
        {{ $slot }}
    </div>
</dialog>
