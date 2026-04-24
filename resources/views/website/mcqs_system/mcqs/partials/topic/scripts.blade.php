@php
    $topicPracticeJs = public_path('assets/js/topic-practice.js');
    $topicPracticeV = is_file($topicPracticeJs) ? filemtime($topicPracticeJs) : time();
@endphp
<script>
    window.SKOOLYST_TOPIC_PRACTICE = {
        topicId: {{ (int) $topic->id }},
        totalQuestions: {{ (int) $mcqs->total() }}
    };
</script>
<script src="{{ asset('assets/js/topic-practice.js') }}?v={{ $topicPracticeV }}"></script>
