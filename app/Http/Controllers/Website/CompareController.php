<?php

namespace App\Http\Controllers\Website;

use App\Enums\ActiveStatus;
use App\Enums\SchoolVisibility;
use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index(Request $request)
    {
        $requestedUuids = collect(explode(',', (string) $request->get('schools')))
            ->map(fn ($uuid) => trim($uuid))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $selectedSchools = collect();

        if ($requestedUuids->count() >= 2) {
            $selectedSchools = School::with(['curriculums', 'features', 'reviews'])
                ->where('status', ActiveStatus::Active)
                ->where('visibility', SchoolVisibility::Public)
                ->published()
                ->whereIn('uuid', $requestedUuids)
                ->get()
                ->sortBy(fn ($school) => $requestedUuids->search($school->uuid))
                ->values();
        }

        $allSchools = School::where('status', ActiveStatus::Active)
            ->where('visibility', SchoolVisibility::Public)
            ->published()
            ->orderBy('name')
            ->get(['uuid', 'name', 'city']);

        $isValidComparison = $selectedSchools->count() >= 2;

        $canonicalUuids = $isValidComparison
            ? $selectedSchools->pluck('uuid')->sort()->values()
            : collect();

        $canonicalUrl = $isValidComparison
            ? route('compare.index', ['schools' => $canonicalUuids->implode(',')])
            : route('compare.index');

        return view('website.compare', [
            'allSchools' => $allSchools,
            'selectedSchools' => $selectedSchools,
            'isValidComparison' => $isValidComparison,
            'canonicalUrl' => $canonicalUrl,
            'pageSetsOwnMeta' => true,
            'pageSetsOwnCanonical' => true,
        ]);
    }
}
