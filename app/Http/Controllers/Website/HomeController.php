<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Curriculum;
use App\Models\Feature;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        // Get active schools with their relationships
        $schools = School::with(['curriculums', 'features', 'reviews'])
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->orderBy('created_at', 'desc')
            ->take(12) // Limit to 12 schools for performance
            ->get();

        // Get all curriculums for filter dropdown
        $curriculums = Curriculum::orderBy('name')->get();

        // Get unique cities for location filter
        $cities = School::where('status', 'active')
            ->where('visibility', 'public')
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        // Get school types for filter
        $schoolTypes = ['Co-Ed', 'Boys', 'Girls'];

        return view('website.home', compact('schools', 'curriculums', 'cities', 'schoolTypes'));
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
            'profile_url' => route('schools.show', $school->id) // Adjust this route as needed
        ];
    }

    public function howItWorks()
    {
        return view('website.how_it_works');
    }
}
