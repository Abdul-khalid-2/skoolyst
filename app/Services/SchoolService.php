<?php

namespace App\Services;

use App\Actions\School\RecordSchoolProfileVisitAction;
use App\Enums\ActiveStatus;
use App\Enums\SchoolVisibility;
use App\Models\Curriculum;
use App\Models\School;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class SchoolService
{
    public function __construct(
        private readonly RecordSchoolProfileVisitAction $recordSchoolProfileVisit,
    ) {
    }

    /**
     * Get base school query with common relationships
     */
    protected function getBaseQuery()
    {
        return School::with(['curriculums', 'features', 'reviews', 'profile', 'translations'])
            ->where('status', ActiveStatus::Active)
            ->where('visibility', SchoolVisibility::Public);
    }

    /**
     * Apply search filters to query
     */
    protected function applyFilters($query, array $filters)
    {
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

        return $query;
    }

    /**
     * Search schools with pagination
     */
    public function searchSchools(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = $this->getBaseQuery();
        $query = $this->applyFilters($query, $filters);
        
        $schools = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Transform the collection
        $schools->getCollection()->transform(function ($school) {
            return $this->formatSchoolData($school);
        });
        
        return $schools;
    }

    /**
     * Search schools (paginated) for API / AJAX responses (e.g. homepage directory search).
     */
    public function getAllSchools(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $perPage = max(1, min($perPage, 100));

        $query = $this->getBaseQuery();
        $query = $this->applyFilters($query, $filters);

        $paginator = $query->orderBy('created_at', 'desc')->paginate($perPage);
        $paginator->getCollection()->transform(function ($school) {
            return $this->formatSchoolData($school);
        });

        return $paginator;
    }

    /**
     * Get a single school by UUID
     */
    public function getSchoolByUuid(string $uuid)
    {
        $school = School::with([
            'profile',
            'translations',
            'curriculums',
            'features',
            'reviews',
            'images',
            'branches',
            'events'
        ])
            ->where('status', ActiveStatus::Active)
            ->where('visibility', SchoolVisibility::Public)
            ->where('uuid', $uuid)
            ->firstOrFail();

        $this->recordSchoolProfileVisit->execute($school);

        return $school;
    }

    /**
     * Get filter data (cities, curriculums, school types)
     */
    public function getFilterData(): array
    {
        return [
            'curriculums' => $this->getCurriculums(),
            'cities' => $this->getCities(),
            'schoolTypes' => $this->getSchoolTypes(),
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
            return School::where('status', ActiveStatus::Active)
                ->where('visibility', SchoolVisibility::Public)
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
            'uuid' => $school->uuid ?? $school->id,
            'name' => $school->localized('name'),
            'type' => $school->school_type->value,
            'location' => $school->city,
            'curriculum' => $primaryCurriculum,
            'rating' => round($averageRating, 1),
            'description' => $school->localized('description') ?: 'No description available.',
            'features' => $featureNames,
            'banner_image' => $school->banner_image ? asset('website/' . $school->banner_image) : null,
            'review_count' => $school->reviews->count(),
            'visitor_count' => $visitorCount,
            'profile_url' => route('browseSchools.show', $school->uuid ?? $school->id)
        ];
    }
}