<?php

namespace App\Console\Commands;

use App\Enums\ActiveStatus;
use App\Enums\SchoolVisibility;
use App\Models\BlogPost;
use App\Models\School;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml file for the website';

    public function handle(): int
    {
        $this->info('Generating sitemap...');

        $base = rtrim((string) (config('app.url') ?: 'https://www.skoolyst.com'), '/');
        $locales = array_keys(config('laravellocalization.supportedLocales', ['en' => []]));

        $sitemap = Sitemap::create();

        $staticPrefixPaths = [
            '/',
            '/about',
            '/contact',
            '/all/schools',
            '/blog',
            '/videos',
            '/mcq',
        ];

        foreach ($locales as $locale) {
            foreach ($staticPrefixPaths as $path) {
                $priority = $path === '/all/schools' ? 0.9 : 0.6;
                $sitemap->add(
                    $this->makeUrl("{$base}/{$locale}".$this->pathSuffix($path), null, $priority)
                );
            }
        }

        // City landing pages — high-intent SEO URLs for "schools in Karachi" etc.
        $cities = School::query()
            ->where('status', ActiveStatus::Active)
            ->where('visibility', SchoolVisibility::Public)
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        foreach ($locales as $locale) {
            foreach ($cities as $city) {
                $sitemap->add(
                    $this->makeUrl(
                        "{$base}/{$locale}/all/schools?location=".rawurlencode($city),
                        null,
                        0.75
                    )
                );
            }
        }

        // Individual school profile pages
        $schools = School::query()
            ->where('status', ActiveStatus::Active)
            ->where('visibility', SchoolVisibility::Public)
            ->get(['uuid', 'updated_at']);

        foreach ($locales as $locale) {
            foreach ($schools as $school) {
                $sitemap->add(
                    $this->makeUrl(
                        "{$base}/{$locale}/school/profile/{$school->uuid}",
                        $school->updated_at,
                        0.8
                    )
                );
            }
        }

        $categories = VideoCategory::query()
            ->where('status', 'active')
            ->withCount([
                'videos' => function ($q) {
                    $q->published()->approved();
                },
            ])
            ->having('videos_count', '>', 0)
            ->get(['id', 'slug', 'updated_at']);

        foreach ($locales as $locale) {
            foreach ($categories as $category) {
                $sitemap->add(
                    $this->makeUrl("{$base}/{$locale}/videos/category/{$category->slug}", $category->updated_at)
                );
            }
        }

        $videos = Video::query()
            ->published()
            ->approved()
            ->get(['slug', 'updated_at']);

        foreach ($locales as $locale) {
            foreach ($videos as $video) {
                $sitemap->add(
                    $this->makeUrl("{$base}/{$locale}/videos/{$video->slug}", $video->updated_at)
                );
            }
        }

        $posts = BlogPost::query()
            ->published()
            ->get(['slug', 'updated_at']);

        foreach ($locales as $locale) {
            foreach ($posts as $post) {
                $sitemap->add(
                    $this->makeUrl("{$base}/{$locale}/blog/{$post->slug}", $post->updated_at)
                );
            }
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ Sitemap generated successfully at public/sitemap.xml');

        return self::SUCCESS;
    }

    private function pathSuffix(string $path): string
    {
        if ($path === '/' || $path === '') {
            return '';
        }

        return $path[0] === '/' ? $path : '/'.$path;
    }

    private function makeUrl(string $location, $lastMod = null, float $priority = 0.6): Url
    {
        $u = Url::create($location)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority($priority);

        if ($lastMod) {
            $u->setLastModificationDate($lastMod);
        }

        return $u;
    }
}
