@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/compare.css') }}">
@endpush

@php
    $compareTitle = $isValidComparison
        ? 'Compare ' . $selectedSchools->pluck('name')->implode(' vs ') . ' | SKOOLYST Pakistan'
        : 'Compare Schools in Pakistan | SKOOLYST';
    $compareDescription = $isValidComparison
        ? 'Side-by-side comparison of ' . $selectedSchools->pluck('name')->implode(' and ') . ': curriculum, fees, ownership and reviews on SKOOLYST Pakistan.'
        : 'Pick two or more verified schools on SKOOLYST and compare curriculum, fees, ownership, facilities and reviews side by side.';
@endphp

@push('meta')
<title>{{ $compareTitle }}</title>
<meta name="description" content="{{ $compareDescription }}">
<meta name="robots" content="{{ $isValidComparison ? 'index,follow' : 'noindex,follow' }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

<meta property="og:type" content="website">
<meta property="og:site_name" content="SKOOLYST Pakistan">
<meta property="og:title" content="{{ $compareTitle }}">
<meta property="og:description" content="{{ $compareDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="{{ asset('assets/assets/hero1.png') }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@skoolystpk">
<meta name="twitter:title" content="{{ $compareTitle }}">
<meta name="twitter:description" content="{{ $compareDescription }}">
<meta name="twitter:image" content="{{ asset('assets/assets/hero1.png') }}">

@if($isValidComparison)
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('website.home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'All Schools', 'item' => route('browseSchools.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => 'Compare Schools', 'item' => $canonicalUrl],
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
@endpush

@section('content')
<nav class="school-profile-breadcrumb" aria-label="Breadcrumb">
    <div class="container">
        <ol class="breadcrumb-list">
            <li><a href="{{ route('website.home') }}">Home</a></li>
            <li><a href="{{ route('browseSchools.index') }}">All Schools</a></li>
            <li aria-current="page">Compare Schools</li>
        </ol>
    </div>
</nav>

<main class="compare-page">
    <div class="container">
        <h1 class="compare-heading">
            @if($isValidComparison)
                Compare {{ $selectedSchools->pluck('name')->implode(' vs ') }}
            @else
                Compare Schools in Pakistan
            @endif
        </h1>
        <p class="compare-intro">Select two or more schools below to compare curriculum, fees, ownership, facilities and parent reviews side by side.</p>

        <form method="GET" action="{{ route('compare.index') }}" class="compare-selector-form">
            <div class="compare-selector-grid">
                @foreach($allSchools as $school)
                    <label class="compare-selector-item">
                        <input
                            type="checkbox"
                            name="schools_input[]"
                            value="{{ $school->uuid }}"
                            {{ $isValidComparison && $selectedSchools->pluck('uuid')->contains($school->uuid) ? 'checked' : '' }}
                        >
                        <span>{{ $school->name }}{{ $school->city ? ' — ' . $school->city : '' }}</span>
                    </label>
                @endforeach
            </div>
            <input type="hidden" name="schools" id="compareSchoolsField" value="">
            <button type="submit" class="compare-submit-btn">Compare Selected Schools</button>
        </form>

        @if($isValidComparison)
        <div class="compare-table-wrapper">
            <table class="compare-table">
                <tbody>
                    <tr class="compare-row-header">
                        <th scope="row">School</th>
                        @foreach($selectedSchools as $school)
                            <td>
                                <a href="{{ route('browseSchools.show', $school->uuid) }}">{{ $school->localized('name') }}</a>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">City</th>
                        @foreach($selectedSchools as $school)
                            <td>{{ $school->city ?: '—' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Ownership</th>
                        @foreach($selectedSchools as $school)
                            <td>{{ $school->school_ownership_type?->label() ?? '—' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Type</th>
                        @foreach($selectedSchools as $school)
                            <td>{{ $school->school_gender_type?->label() ?? '—' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Curriculum</th>
                        @foreach($selectedSchools as $school)
                            <td>{{ $school->curriculums->pluck('name')->implode(', ') ?: '—' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Fee Structure</th>
                        @foreach($selectedSchools as $school)
                            <td>{{ $school->fee_structure_type?->label() ?? '—' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Monthly Fees</th>
                        @foreach($selectedSchools as $school)
                            <td>{{ $school->regular_fees ? 'PKR ' . number_format($school->regular_fees) : 'Not disclosed' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Admission Fees</th>
                        @foreach($selectedSchools as $school)
                            <td>{{ $school->admission_fees ? 'PKR ' . number_format($school->admission_fees) : 'Not disclosed' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Facilities</th>
                        @foreach($selectedSchools as $school)
                            <td>{{ $school->features->pluck('name')->take(5)->implode(', ') ?: '—' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th scope="row">Rating</th>
                        @foreach($selectedSchools as $school)
                            @php
                                $avgRating = round($school->reviews->avg('rating') ?? 0, 1);
                                $reviewCount = $school->reviews->count();
                            @endphp
                            <td>
                                @if($avgRating > 0 && $reviewCount > 0)
                                    {{ $avgRating }} / 5 ({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})
                                @else
                                    No reviews yet
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        @elseif(request()->has('schools'))
        <p class="compare-empty-note">Select at least two schools above to see a side-by-side comparison.</p>
        @endif
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.querySelector('.compare-selector-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        var checked = Array.from(this.querySelectorAll('input[name="schools_input[]"]:checked')).map(el => el.value);
        if (checked.length < 2) {
            alert('Please select at least two schools to compare.');
            return;
        }
        document.getElementById('compareSchoolsField').value = checked.join(',');
        window.location.href = "{{ route('compare.index') }}" + "?schools=" + encodeURIComponent(checked.join(','));
    });
</script>
@endpush
