<?php

namespace App\Services;

use App\Models\School;
use App\Models\Curriculum;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class HomeService
{
    /**
     * Get homepage data including schools, filters, and testimonials
     */
    public function getHomePage(): array
    {
        // Cache homepage data for 5 minutes to improve performance
        $cacheKey = 'homepage_data_'.app()->getLocale();

        return Cache::remember($cacheKey, 300, function () {
            return [
                'schools' => $this->getFeaturedSchools(),
                'curriculums' => $this->getCurriculums(),
                'cities' => $this->getCities(),
                'schoolTypes' => $this->getSchoolTypes(),
                'testimonials' => $this->getTestimonials(),
            ];
        });
    }

    /**
     * Get featured/active schools with their relationships
     */
    public function getFeaturedSchools(int $limit = 12)
    {
        return School::with(['curriculums', 'features', 'reviews', 'profile', 'translations'])
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($school) {
                return $this->formatSchoolData($school);
            });
    }

    /**
     * Search schools based on criteria
     */
    public function searchSchools(array $filters): array
    {
        $query = School::with(['curriculums', 'features', 'reviews', 'profile', 'translations'])
            ->where('status', 'active')
            ->where('visibility', 'public');

        // Apply search term filter
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $searchTerm = $filters['search'];
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('city', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhereHas('curriculums', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('translations', function ($q) use ($searchTerm) {
                        $q->where('locale', 'ur')
                            ->where(function ($tq) use ($searchTerm) {
                                $tq->where('name', 'like', "%{$searchTerm}%")
                                    ->orWhere('description', 'like', "%{$searchTerm}%");
                            });
                    });
            });
        }

        // Apply location filter
        if (!empty($filters['location'])) {
            $query->where('city', $filters['location']);
        }

        // Apply school type filter
        if (!empty($filters['type'])) {
            $query->where('school_type', $filters['type']);
        }

        // Apply curriculum filter
        if (!empty($filters['curriculum'])) {
            $query->whereHas('curriculums', function ($q) use ($filters) {
                $q->where('code', $filters['curriculum']);
            });
        }

        $schools = $query->orderBy('created_at', 'desc')->get();

        return [
            'schools' => $schools->map(function ($school) {
                return $this->formatSchoolData($school);
            })->toArray()
        ];
    }

    /**
     * Get all curriculums for filter dropdown
     */
    public function getCurriculums()
    {
        return Cache::remember('curriculums_list', 3600, function () {
            return Curriculum::orderBy('name')->get();
        });
    }

    /**
     * Get unique cities with active schools
     */
    public function getCities()
    {
        return Cache::remember('cities_list', 3600, function () {
            return School::where('status', 'active')
                ->where('visibility', 'public')
                ->whereNotNull('city')
                ->distinct()
                ->pluck('city')
                ->sort()
                ->values();
        });
    }

    /**
     * Get available school types
     */
    public function getSchoolTypes(): array
    {
        return ['Co-Ed', 'Boys', 'Girls', 'Separate'];
    }

    /**
     * Get approved testimonials for homepage
     */
    public function getTestimonials(int $limit = 6)
    {
        return Cache::remember('testimonials_list', 300, function () use ($limit) {
            return Testimonial::approved()
                ->orderBy('created_at', 'desc')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Format school data for API/View response
     */
    public function formatSchoolData($school): array
    {
        // Calculate average rating
        $averageRating = $school->reviews->avg('rating') ?? 0;

        // Get curriculum names
        $curriculumNames = $school->curriculums->pluck('name')->toArray();
        $primaryCurriculum = !empty($curriculumNames) ? $curriculumNames[0] : 'Not Specified';

        // Get feature names (limit to 4 for display)
        $featureNames = $school->features->take(4)->pluck('name')->toArray();

        // Get visitor count from profile relationship
        $visitorCount = 0;
        if ($school->profile && isset($school->profile->visitor_count)) {
            $visitorCount = $school->profile->visitor_count;
        }

        return [
            'id' => $school->id,
            'uuid' => $school->uuid ?? $school->id, // Add uuid if available
            'name' => $school->localized('name'),
            'type' => $school->school_type,
            'location' => $school->city,
            'curriculum' => $primaryCurriculum,
            'rating' => round($averageRating, 1),
            'description' => $school->localized('description') ?: 'No description available.',
            'features' => $featureNames,
            'banner_image' => $school->banner_image ? asset('website/' . $school->banner_image) : null,
            'review_count' => $school->reviews->count(),
            'visitor_count' => $visitorCount, // Add visitor count to the array
            'profile_url' => route('schools.show', $school->id)
        ];
    }

    /**
     * Get how it works page data (if needed in the future)
     */
    public function getHowItWorksPage(): array
    {
        // If you need to pass data to the how-it-works page, add it here
        return [
            'data' => []
        ];
    }
}