<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Curriculum;
use Illuminate\Http\Request;

class BrowseSchoolController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->get('search');
        $location = $request->get('location');
        $type = $request->get('type');
        $curriculum = $request->get('curriculum');

        // Base query
        $query = School::with(['curriculums', 'features', 'reviews'])
            ->where('status', 'active')
            ->where('visibility', 'public');

        // Apply filters
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('curriculums', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($location) {
            $query->where('city', $location);
        }

        if ($type) {
            $query->where('school_type', $type);
        }

        if ($curriculum) {
            $query->whereHas('curriculums', function ($q) use ($curriculum) {
                $q->where('code', $curriculum);
            });
        }

        // Get schools with pagination
        $schools = $query->orderBy('created_at', 'desc')->paginate(12);

        // Get filter data
        $curriculums = Curriculum::orderBy('name')->get();
        $cities = School::where('status', 'active')
            ->where('visibility', 'public')
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();
        $schoolTypes = ['Co-Ed', 'Boys', 'Girls'];

        return view('website.browse_schools', compact(
            'schools',
            'curriculums',
            'cities',
            'schoolTypes',
            'search',
            'location',
            'type',
            'curriculum'
        ));
    }

    public function search(Request $request)
    {
        $query = School::with(['curriculums', 'features', 'reviews'])
            ->where('status', 'active')
            ->where('visibility', 'public');

        // Search term
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('city', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhereHas('curriculums', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Location filter
        if ($request->has('location') && $request->location) {
            $query->where('city', $request->location);
        }

        // School type filter
        if ($request->has('type') && $request->type) {
            $query->where('school_type', $request->type);
        }

        // Curriculum filter
        if ($request->has('curriculum') && $request->curriculum) {
            $query->whereHas('curriculums', function ($q) use ($request) {
                $q->where('code', $request->curriculum);
            });
        }

        $schools = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'schools' => $schools->map(function ($school) {
                return $this->formatSchoolData($school);
            })
        ]);
    }

    public function show($uuid)
    {
        $school = School::with([
            'profile',
            'curriculums',
            'features',
            'reviews',
            'images',
            'branches',
            'events'
        ])
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->where('uuid', $uuid)
            ->firstOrFail();

        // Increment visitor count
        if ($school->profile) {
            $school->profile->increment('visitor_count');
            $school->profile->update(['last_visited_at' => now()]);
        }

        return view('website.school_profile', compact('school'));
    }

    private function formatSchoolData($school)
    {
        // Calculate average rating
        $averageRating = $school->reviews->avg('rating') ?? 0;

        // Get curriculum names
        $curriculumNames = $school->curriculums->pluck('name')->toArray();
        $primaryCurriculum = !empty($curriculumNames) ? $curriculumNames[0] : 'Not Specified';

        // Get feature names (limit to 4 for display)
        $featureNames = $school->features->take(4)->pluck('name')->toArray();

        return [
            'id' => $school->id,
            'name' => $school->name,
            'type' => $school->school_type,
            'location' => $school->city,
            'curriculum' => $primaryCurriculum,
            'rating' => round($averageRating, 1),
            'description' => $school->description ?: 'No description available.',
            'features' => $featureNames,
            'banner_image' => $school->banner_image ? asset('website/' . $school->banner_image) : null,
            'review_count' => $school->reviews->count(),
            'profile_url' => route('browseSchools.show', $school->uuid) // Fixed to use UUID
        ];
    }
}
