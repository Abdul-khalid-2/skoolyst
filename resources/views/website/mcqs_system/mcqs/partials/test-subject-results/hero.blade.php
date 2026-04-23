{{-- Same visual language as MCQs index / blog / test-type heroes (see mcqs.css .mcqs-hero-section) --}}
<section class="mcqs-hero-section subject-results-hero--mcqs" id="subject-test-results-hero">
    <div class="container">
        <div class="mcqs-hero-content">
            <p class="subject-results-hero__eyebrow">Test results</p>
            <h1 class="mcqs-hero-title subject-results-hero__title">{{ $subject->name ?? 'Subject' }}</h1>
            @if ($topic)
                <p class="mcqs-hero-subheading subject-results-hero__sub mb-0">{{ $topic->title }}</p>
            @else
                <p class="mcqs-hero-subheading subject-results-hero__sub mb-0">Subject-level test for {{ $testType->name ?? 'this test type' }}</p>
            @endif
        </div>
    </div>
</section>
