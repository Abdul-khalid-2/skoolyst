@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/browse_schools.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/browse-schools-seo.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/browse_schools-inline.css') }}?v={{ filemtime(public_path('assets/css/browse_schools-inline.css')) }}">
@endpush

@push('meta')
@include('website.browse-schools.partials.seo-meta', [
    'schools' => $schools,
    'cities' => $cities,
    'curriculums' => $curriculums,
    'schoolGenderTypes' => $schoolGenderTypes,
    'schoolOwnershipTypes' => $schoolOwnershipTypes,
    'location' => $location ?? null,
    'search' => $search ?? null,
    'type' => $type ?? null,
    'ownership' => $ownership ?? null,
    'curriculum' => $curriculum ?? null,
])
@endpush

@section('content')
@include('website.browse-schools.partials.page-header', ['schools' => $schools])
@include('website.browse-schools.partials.filters', ['cities' => $cities, 'schoolGenderTypes' => $schoolGenderTypes, 'schoolOwnershipTypes' => $schoolOwnershipTypes, 'curriculums' => $curriculums])
@include('website.browse-schools.partials.school-grid', ['schools' => $schools])
@include('website.browse-schools.partials.pagination', ['schools' => $schools])
@include('website.browse-schools.partials.seo-content')

@push('scripts')
@include('website.partials.select2-assets')
<script src="{{ asset('assets/js/browse-schools-filters.js') }}?v={{ filemtime(public_path('assets/js/browse-schools-filters.js')) }}"></script>
@endpush

@endsection
