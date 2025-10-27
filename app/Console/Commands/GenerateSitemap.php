<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml file for the website';

    public function handle()
    {
        $this->info('Generating sitemap...');

        $base = config('app.url') ?? 'https://skoolyst.com';

        Sitemap::create()
            ->add(Url::create($base . '/'))
            ->add(Url::create($base . '/about'))
            ->add(Url::create($base . '/contact'))
            ->add(Url::create($base . '/schools'))
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ Sitemap generated successfully at public/sitemap.xml');
    }
}
