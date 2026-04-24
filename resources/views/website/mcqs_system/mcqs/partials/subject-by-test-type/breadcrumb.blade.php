@php
    $filteredTopic = null;
    if (request('topic') && isset($topics)) {
        $filteredTopic = $topics->firstWhere('id', request('topic'));
    }
@endphp

<div class="breadcrumb-wrapper">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('website.mcqs.index') }}">MCQs</a></li>
            <li class="breadcrumb-item"><a href="{{ route('website.mcqs.test-type', $testType->slug ?? '') }}">{{ $testType->name ?? 'Test Type' }}</a></li>
            <li class="breadcrumb-item active">{{ $subject->name ?? 'Subject' }}</li>
            @if($filteredTopic)
                <li class="breadcrumb-item active">{{ $filteredTopic->title }}</li>
            @endif
        </ol>
    </nav>
</div>

@if($filteredTopic)
    <div class="back-button-wrapper">
        <a href="{{ route('website.mcqs.subject-by-test-type', [$testType->slug ?? '', $subject->slug ?? '']) }}"
           class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to {{ $subject->name ?? 'Subject' }}
        </a>
    </div>
@endif
