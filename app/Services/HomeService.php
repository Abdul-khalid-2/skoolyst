<?php

namespace App\Services;

use App\Models\School;
use App\Models\Curriculum;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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
        return School::query()
            ->with(['curriculums', 'features', 'profile', 'translations'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
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
     * Search schools based on criteria (optimized: aggregates, limited rows, no full review lists).
     */
    public function searchSchools(array $filters): array
    {
        $query = School::query()
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->with([
                'curriculums',
                'features',
                'profile' => static function ($q): void {
                    $q->select('id', 'school_id', 'visitor_count', 'mission', 'vision', 'school_motto');
                },
                'translations',
            ])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        if (! empty($filters['search'])) {
            $this->applyTextSearchToQuery($query, (string) $filters['search'], 200, 8);
        }

        $this->applyDirectoryFilters($query, $filters);

        $schools = $query->orderByDesc('created_at')->limit(60)->get();

        return [
            'schools' => $schools->map(function ($school) {
                return $this->formatSchoolData($school);
            })->all(),
        ];
    }

    /**
     * Live / typeahead search (max $limit rows, lightweight relations).
     *
     * @return array{results: list<array<string, mixed>>}
     */
    public function searchSchoolsSuggest(array $filters, string $q, int $limit = 6): array
    {
        $q = trim($q);
        if (mb_strlen($q) < 2) {
            return ['results' => []];
        }
        if (mb_strlen($q) > 100) {
            $q = mb_substr($q, 0, 100);
        }

        $query = School::query()
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->with(['curriculums', 'translations']);

        $this->applyTextSearchToQuery($query, $q, 100, 6);
        $this->applyDirectoryFilters($query, $filters);

        $schools = $query->orderByDesc('created_at')->limit($limit)->get();

        return [
            'results' => $schools->map(function (School $school) use ($q) {
                return $this->formatSchoolSuggestRow($school, $q);
            })->all(),
        ];
    }

    /**
     * @param  array{search?:?string,location?:?string,type?:?string,curriculum?:?string}  $filters
     */
    private function applyDirectoryFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['location'])) {
            $query->where('city', $filters['location']);
        }

        if (! empty($filters['type'])) {
            $query->where('school_type', $filters['type']);
        }

        if (! empty($filters['curriculum'])) {
            $query->whereHas('curriculums', function ($q) use ($filters) {
                $q->where('code', $filters['curriculum']);
            });
        }
    }

    private function likePattern(string $raw): string
    {
        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $raw);

        return '%'.$escaped.'%';
    }

    private function applyTextSearchToQuery(Builder $query, string $raw, int $maxQueryLen, int $maxTokens): void
    {
        $raw = trim($raw);
        if ($raw === '') {
            return;
        }
        if (mb_strlen($raw) > $maxQueryLen) {
            $raw = mb_substr($raw, 0, $maxQueryLen);
        }
        $tokens = preg_split('/\s+/u', $raw, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if (count($tokens) > $maxTokens) {
            $tokens = array_slice($tokens, 0, $maxTokens);
        }
        foreach ($tokens as $token) {
            if ($token === '' || mb_strlen($token) < 1) {
                continue;
            }
            $l = $this->likePattern($token);
            $query->where(function ($q) use ($l) {
                $q->where('name', 'like', $l)
                    ->orWhere('email', 'like', $l)
                    ->orWhere('contact_number', 'like', $l)
                    ->orWhere('address', 'like', $l)
                    ->orWhere('city', 'like', $l)
                    ->orWhere('website', 'like', $l)
                    ->orWhere('description', 'like', $l)
                    ->orWhere('facilities', 'like', $l)
                    ->orWhereHas('curriculums', function ($c) use ($l) {
                        $c->where('name', 'like', $l)->orWhere('code', 'like', $l);
                    })
                    ->orWhereHas('translations', function ($tr) use ($l) {
                        $tr->where(function ($t) use ($l) {
                            $t->where('name', 'like', $l)->orWhere('description', 'like', $l);
                        });
                    })
                    ->orWhereHas('profile', function ($pr) use ($l) {
                        $pr->where('mission', 'like', $l)
                            ->orWhere('vision', 'like', $l)
                            ->orWhere('school_motto', 'like', $l);
                    });
            });
        }
    }

    private function formatSchoolSuggestRow(School $school, string $query): array
    {
        $name = $school->localized('name');
        $description = $school->localized('description') ?: '';
        $excerpt = Str::limit(preg_replace('/\s+/u', ' ', strip_tags($description)), 90, '…');
        if ($excerpt === '') {
            $excerpt = Str::limit($name, 90, '…');
        }

        $curriculumNames = $school->curriculums->pluck('name')->toArray();
        $primaryCurriculum = ! empty($curriculumNames) ? $curriculumNames[0] : '—';

        $uuid = $school->uuid ?? (string) $school->id;

        return [
            'id' => $school->id,
            'name' => $name,
            'city' => $school->city ?? '',
            'type' => $school->school_type,
            'curriculum' => $primaryCurriculum,
            'excerpt' => $excerpt,
            'highlight' => $this->highlightQueryInText($excerpt, $query),
            'title_highlight' => $this->highlightQueryInText($name, $query),
            'profile_url' => route('browseSchools.show', ['uuid' => $uuid]),
        ];
    }

    private function highlightQueryInText(string $plain, string $query): string
    {
        $plain = trim($plain);
        if ($plain === '') {
            return '';
        }
        $safe = e($plain);
        $tokens = preg_split('/\s+/u', trim($query), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if ($tokens === []) {
            return $safe;
        }
        $tokens = array_values(array_unique($tokens, SORT_STRING));
        usort($tokens, function (string $a, string $b) {
            return mb_strlen($b) <=> mb_strlen($a);
        });
        $parts = [];
        foreach (array_slice($tokens, 0, 20) as $t) {
            if (mb_strlen($t) < 1) {
                continue;
            }
            $parts[] = preg_quote($t, '/');
        }
        if ($parts === []) {
            return $safe;
        }
        $pattern = '('.implode('|', $parts).')/iu';
        $out = @preg_replace(
            $pattern,
            '<mark class="home-search-mark">$1</mark>',
            $safe
        );

        return $out === null || $out === '' ? $safe : (string) $out;
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
        $averageRating = isset($school->reviews_avg_rating) && $school->reviews_avg_rating !== null
            ? (float) $school->reviews_avg_rating
            : (float) ($school->relationLoaded('reviews') ? ($school->reviews->avg('rating') ?? 0) : 0);

        $reviewCount = $school->reviews_count ?? ($school->relationLoaded('reviews') ? $school->reviews->count() : 0);

        // Get curriculum names
        $curriculumNames = $school->curriculums->pluck('name')->toArray();
        $primaryCurriculum = ! empty($curriculumNames) ? $curriculumNames[0] : 'Not Specified';

        // Get feature names (limit to 4 for display)
        $featureNames = $school->features->take(4)->pluck('name')->toArray();

        $visitorCount = 0;
        if ($school->profile && isset($school->profile->visitor_count)) {
            $visitorCount = (int) $school->profile->visitor_count;
        }

        $uuid = $school->uuid ?? (string) $school->id;

        return [
            'id' => $school->id,
            'uuid' => $uuid,
            'name' => $school->localized('name'),
            'type' => $school->school_type,
            'location' => $school->city,
            'curriculum' => $primaryCurriculum,
            'rating' => round($averageRating, 1),
            'description' => $school->localized('description') ?: 'No description available.',
            'features' => $featureNames,
            'banner_image' => $school->banner_image ? asset('website/'.$school->banner_image) : null,
            'review_count' => (int) $reviewCount,
            'visitor_count' => $visitorCount,
            'profile_url' => route('browseSchools.show', ['uuid' => $uuid]),
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