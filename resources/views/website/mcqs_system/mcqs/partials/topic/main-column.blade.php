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
            @include('website.mcqs_system.mcqs.partials.topic.empty-state')
        @endif
    </form>
</div>
