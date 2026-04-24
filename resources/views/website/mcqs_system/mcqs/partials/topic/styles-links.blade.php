@php
    $mcqBase = 'assets/css/mcq';
    $v = function (string $name) use ($mcqBase) {
        $path = public_path($mcqBase . '/' . $name);
        return is_file($path) ? filemtime($path) : time();
    };
@endphp
<link rel="stylesheet" href="{{ asset($mcqBase . '/topic-hero-breadcrumb-description.css') }}?v={{ $v('topic-hero-breadcrumb-description.css') }}">
<link rel="stylesheet" href="{{ asset($mcqBase . '/topic-questions-testnav.css') }}?v={{ $v('topic-questions-testnav.css') }}">
<link rel="stylesheet" href="{{ asset($mcqBase . '/topic-question-palette-mcq.css') }}?v={{ $v('topic-question-palette-mcq.css') }}">
<link rel="stylesheet" href="{{ asset($mcqBase . '/topic-mcq-responsive.css') }}?v={{ $v('topic-mcq-responsive.css') }}">
