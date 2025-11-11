<?php

namespace App\Services;

use App\Repositories\SeoRepository;
use App\Repositories\PostRepository;
use App\Repositories\PageRepository;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SeoService
{
    public function __construct(
        protected SeoRepository $seoRepository,
        protected PostRepository $postRepository,
        protected PageRepository $pageRepository
    ) {}

    /**
     * Get all SEO settings as array
     */
    public function getSeoSettings(): array
    {
        return $this->seoRepository->getSeoSettingsArray();
    }

    /**
     * Update SEO settings
     */
    public function updateSeoSettings(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->seoRepository->updateSetting($key, $value);
        }

        Log::info('SEO settings updated', ['keys' => array_keys($data)]);
    }

    /**
     * Generate sitemap.xml
     */
    public function generateSitemap(): bool
    {
        try {
            $sitemap = Sitemap::create();

            // Add homepage
            $sitemap->add(
                Url::create('/')
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(1.0)
            );

            // Add published posts
            $posts = $this->postRepository->getPublishedPosts();
            foreach ($posts as $post) {
                $sitemap->add(
                    Url::create("/posts/{$post->slug}")
                        ->setLastModificationDate($post->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );
            }

            // Add published pages
            $pages = $this->pageRepository->getPublished();
            foreach ($pages as $page) {
                $sitemap->add(
                    Url::create("/pages/{$page->slug}")
                        ->setLastModificationDate($page->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.6)
                );
            }

            $sitemap->writeToFile(public_path('sitemap.xml'));

            Log::info('Sitemap generated successfully');

            return true;

        } catch (\Exception $e) {
            Log::error('Error generating sitemap', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get robots.txt content
     */
    public function getRobotsTxt(): string
    {
        return $this->seoRepository->getRobotsTxt() 
            ?? "User-agent: *\nDisallow:";
    }
}

