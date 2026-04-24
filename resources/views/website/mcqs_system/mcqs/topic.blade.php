@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
@include('website.mcqs_system.mcqs.partials.topic.styles-links')
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

@include('website.mcqs_system.mcqs.partials.topic.hero')
@include('website.mcqs_system.mcqs.partials.topic.section-practice')
@endsection

@push('scripts')
@include('website.mcqs_system.mcqs.partials.topic.scripts')
@endpush
