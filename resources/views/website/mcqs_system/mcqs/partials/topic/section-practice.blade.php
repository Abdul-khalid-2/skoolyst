{{-- Main content: filters area could be added here later; breadcrumb, description, questions + palette --}}
<section class="py-5 topic-mcq-page">
    <div class="container">
        @include('website.mcqs_system.mcqs.partials.topic.breadcrumb')
        @include('website.mcqs_system.mcqs.partials.topic.topic-description')

        <div class="row">
            @include('website.mcqs_system.mcqs.partials.topic.main-column')
            @if($mcqs->count() > 0)
                @include('website.mcqs_system.mcqs.partials.topic.question-palette')
            @endif
        </div>
    </div>
</section>
