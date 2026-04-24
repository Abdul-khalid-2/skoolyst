<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
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
                $sitemap->add(
                    $this->makeUrl("{$base}/{$locale}".$this->pathSuffix($path))
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

    private function makeUrl(string $location, $lastMod = null): Url
    {
        $u = Url::create($location)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.6);

        if ($lastMod) {
            $u->setLastModificationDate($lastMod);
        }

        return $u;
    }
}
