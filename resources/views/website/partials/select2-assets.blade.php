{{-- Searchable Select2 dropdowns: add class `js-select2` to any <select>.
     For custom text entry (e.g. city on registration), also add `js-select2-tags`. --}}
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/website-select2.css') }}?v={{ filemtime(public_path('assets/css/website-select2.css')) }}">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
<script src="{{ asset('assets/js/website-select2-init.js') }}?v={{ filemtime(public_path('assets/js/website-select2-init.js')) }}"></script>
@endpush
