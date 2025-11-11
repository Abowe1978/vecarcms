<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use App\Services\SitemapService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeoController extends Controller
{
    public function __construct(
        protected SettingsService $settingsService,
        protected SitemapService $sitemapService
    ) {
        $this->middleware('permission:manage_settings');
    }

    /**
     * Show SEO settings page
     */
    public function index(): View
    {
        $settings = [
            'seo_allow_indexing' => $this->settingsService->get('seo_allow_indexing', true),
            'seo_default_meta_title' => $this->settingsService->get('seo_default_meta_title', ''),
            'seo_default_meta_description' => $this->settingsService->get('seo_default_meta_description', ''),
            'seo_default_meta_keywords' => $this->settingsService->get('seo_default_meta_keywords', ''),
            'seo_og_default_image' => $this->settingsService->get('seo_og_default_image', ''),
            'seo_twitter_handle' => $this->settingsService->get('seo_twitter_handle', ''),
            'seo_google_analytics_id' => $this->settingsService->get('seo_google_analytics_id', ''),
            'seo_google_tag_manager_id' => $this->settingsService->get('seo_google_tag_manager_id', ''),
            'seo_facebook_pixel_id' => $this->settingsService->get('seo_facebook_pixel_id', ''),
        ];

        return view('admin.seo.index', compact('settings'));
    }

    /**
     * Update SEO settings
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'seo_allow_indexing' => 'boolean',
            'seo_default_meta_title' => 'nullable|string|max:255',
            'seo_default_meta_description' => 'nullable|string|max:500',
            'seo_default_meta_keywords' => 'nullable|string|max:500',
            'seo_og_default_image' => 'nullable|string|max:500',
            'seo_twitter_handle' => 'nullable|string|max:50',
            'seo_google_analytics_id' => 'nullable|string|max:50',
            'seo_google_tag_manager_id' => 'nullable|string|max:50',
            'seo_facebook_pixel_id' => 'nullable|string|max:50',
        ]);

        foreach ($validated as $key => $value) {
            $this->settingsService->set($key, $value);
        }

        // Clear sitemap cache
        $this->sitemapService->clearCache();

        return back()->with('success', 'SEO settings updated successfully!');
    }

    /**
     * Regenerate sitemap manually
     */
    public function regenerateSitemap(): RedirectResponse
    {
        $this->sitemapService->clearCache();
        
        return back()->with('success', 'Sitemap regenerated successfully!');
    }
}
