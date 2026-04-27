<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Central cache key shapes: model:identifier:attribute
 * (identifier is 0 for aggregate/list scopes, or a model id / uuid string).
 */
final class CacheKeys
{
    public const TTL_HOME_PAGE = 300;

    public const TTL_TESTIMONIALS = 300;

    public const TTL_SCHOOL_PUBLIC_SHOW = 300;

    public const TTL_DIRECTORY = 120;

    public const TTL_REFERENCE_LISTS = 3600;

    public static function homePageData(string $locale): string
    {
        return "home:{$locale}:pageData";
    }

    public static function curriculumList(): string
    {
        return 'curriculum:0:list';
    }

    public static function schoolCitiesList(): string
    {
        return 'school:0:cities';
    }

    public static function testimonialApprovedList(int $limit): string
    {
        return "testimonial:0:approved:{$limit}";
    }

    public static function schoolPublicShowByUuid(string $uuid): string
    {
        return "school:{$uuid}:publicShow";
    }

    /**
     * First page of the public school directory (empty filters, fixed perPage).
     */
    public static function schoolDirectoryPage(int $page, int $perPage, string $variant): string
    {
        return "school:0:{$variant}:p{$page}:per{$perPage}";
    }

    /**
     * @return list<string>
     */
    public static function homeLocales(): array
    {
        return ['en', 'ur'];
    }

    public static function forgetTestimonialListCaches(): void
    {
        foreach ([4, 6, 8, 12, 20, 100] as $limit) {
            Cache::forget(self::testimonialApprovedList($limit));
        }
    }

    public static function forgetDirectoryFirstPageCaches(): void
    {
        foreach (['directory', 'browse'] as $variant) {
            foreach ([12, 20] as $perPage) {
                Cache::forget(self::schoolDirectoryPage(1, $perPage, $variant));
            }
        }
    }
}
