@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/subject-practice.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@include('website.mcqs_system.mcqs.partials.subject-by-test-type.page-inline-styles')
@endpush

@section('content')

@include('website.mcqs_system.mcqs.partials.subject-by-test-type.hero')

<section class="py-5">
    <div class="container">

        @include('website.mcqs_system.mcqs.partials.subject-by-test-type.filters-section')

        @include('website.mcqs_system.mcqs.partials.subject-by-test-type.breadcrumb')

        <div class="row">
            @include('website.mcqs_system.mcqs.partials.subject-by-test-type.main-column')
            @include('website.mcqs_system.mcqs.partials.subject-by-test-type.sidebar-question-palette')
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('website.mcqs_system.mcqs.partials.subject-by-test-type.scripts')
@endpush
