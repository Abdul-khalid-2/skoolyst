<div class="d-flex flex-column gap-3 mb-1 mcq-subject-filters">
    @include('website.mcqs_system.mcqs.partials.subject-by-test-type.filters-difficulty', ['difficultyStats' => $difficultyStats])
    @if($topics->count() > 0)
        @include('website.mcqs_system.mcqs.partials.subject-by-test-type.filters-topics', ['topics' => $topics])
    @endif
</div>
