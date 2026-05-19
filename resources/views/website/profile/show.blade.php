@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@php
    $avatarUrl = $user->profile_picture_url
        ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=256&background=15a362&color=fff';
@endphp

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <h1 class="h3 mb-4">My profile</h1>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <img
                            src="{{ $avatarUrl }}"
                            alt="{{ $user->name }}"
                            class="rounded-circle border"
                            style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <h2 class="h5 card-title mb-1">{{ $user->name }}</h2>
                    <p class="text-muted mb-4">{{ $user->email }}</p>
                    <a href="{{ LaravelLocalization::localizeUrl(route('user_profile.edit', [], false)) }}" class="btn btn-primary">
                        Edit profile
                    </a>
                </div>
            </div>

            {{-- MCQ Activity --}}
            <div class="mt-5">
                <h2 class="h5 mb-3">
                    <i class="fas fa-clipboard-list me-2 text-primary"></i>MCQ Activity
                </h2>

                @if($attempts->isEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5 text-muted">
                            <i class="fas fa-file-alt fa-2x mb-3 opacity-50"></i>
                            <p class="mb-0">No completed test attempts yet. Take a test to see your results here.</p>
                        </div>
                    </div>
                @else
                    <div class="card border-0 shadow-sm">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Test</th>
                                        <th class="text-center">Score</th>
                                        <th class="text-center">Percentage</th>
                                        <th class="text-center">Result</th>
                                        <th class="text-center">Time Spent</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Certificate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attempts as $attempt)
                                    @php
                                        $sourceValue = $attempt->test_source instanceof \BackedEnum ? $attempt->test_source->value : $attempt->test_source;
                                        $testName = match ($sourceValue) {
                                            'mock_test'    => optional($attempt->mockTest)->title ?? 'Mock Test',
                                            'topic_test'   => optional($attempt->topic)->name  ?? 'Topic Test',
                                            'subject_test' => optional($attempt->subject)->name ?? 'Subject Test',
                                            default        => 'MCQ Test',
                                        };
                                        $resultValue = $attempt->result_status instanceof \BackedEnum ? $attempt->result_status->value : $attempt->result_status;
                                        $seconds  = (int) $attempt->time_taken_seconds;
                                        $timeTaken = sprintf('%02dh %02dm %02ds', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60);
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="fw-medium">{{ $testName }}</span>
                                            <div class="text-muted small text-capitalize">{{ str_replace('_', ' ', $sourceValue) }}</div>
                                        </td>
                                        <td class="text-center">
                                            {{ $attempt->correct_answers }} / {{ $attempt->total_questions }}
                                        </td>
                                        <td class="text-center fw-semibold">
                                            {{ number_format($attempt->percentage, 1) }}%
                                        </td>
                                        <td class="text-center">
                                            @if($resultValue === 'passed')
                                                <span class="badge bg-success">Passed</span>
                                            @elseif($resultValue === 'failed')
                                                <span class="badge bg-danger">Failed</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($resultValue ?? 'N/A') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-muted small">{{ $timeTaken }}</td>
                                        <td class="text-center text-muted small">
                                            {{ $attempt->submitted_at ? $attempt->submitted_at->format('d M Y') : ($attempt->completed_at ? $attempt->completed_at->format('d M Y') : '—') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ LaravelLocalization::localizeUrl(route('user_profile.certificate', $attempt->uuid, false)) }}"
                                               class="btn btn-success btn-sm"
                                               target="_blank"
                                               title="Download Certificate">
                                                <i class="fas fa-certificate me-1"></i>Certificate
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
