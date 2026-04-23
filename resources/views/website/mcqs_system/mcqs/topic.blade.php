@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/topic-practice.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

@include('website.mcqs_system.mcqs.partials.topic.hero')

<section class="py-5">
    <div class="container">
        @include('website.mcqs_system.mcqs.partials.topic.breadcrumb')
        @include('website.mcqs_system.mcqs.partials.topic.topic-description')

        <div class="row">
            <div class="col-lg-8">
                <form id="testForm" method="POST" action="{{ route('website.mcqs.submit-topic-test') }}">
                    @csrf
                    <input type="hidden" name="topic_id" value="{{ $topic->id }}">
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    <input type="hidden" name="time_taken" id="timeTaken" value="0">

                    @if($mcqs->count() > 0)
                        @include('website.mcqs_system.mcqs.partials.topic.questions-loop')
                        @include('website.mcqs_system.mcqs.partials.topic.test-navigation')
                    @else
                        <div class="empty-state">
                            <i class="fas fa-question-circle"></i>
                            <h5>No questions available</h5>
                            <p>Questions for this topic will be added soon.</p>
                        </div>
                    @endif
                </form>
            </div>

            @if($mcqs->count() > 0)
                @include('website.mcqs_system.mcqs.partials.topic.question-palette')
            @endif
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    window.SKOOLYST_TOPIC_PRACTICE = {
        topicId: {{ (int) $topic->id }},
        totalQuestions: {{ (int) $mcqs->total() }}
    };
</script>
@php
    $topicPracticeJs = public_path('assets/js/topic-practice.js');
    $topicPracticeV = is_file($topicPracticeJs) ? filemtime($topicPracticeJs) : time();
@endphp
<script src="{{ asset('assets/js/topic-practice.js') }}?v={{ $topicPracticeV }}"></script>
@endpush
