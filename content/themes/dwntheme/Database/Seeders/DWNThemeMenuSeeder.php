<?php

namespace Themes\DwnTheme\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;

class DWNThemeMenuSeeder extends Seeder
{
    public function run(): void
    {
        $homePage = Page::where('slug', 'home')->first();
        $aboutPage = Page::where('slug', 'about')->first();
        $blogPage = Page::where('slug', 'blog')->first();
        $contactPage = Page::where('slug', 'contact')->first();
        $privacyPage = Page::where('slug', 'privacy-policy')->first();
        $termsPage = Page::where('slug', 'terms-of-service')->first();
        $servicesPage = Page::where('slug', 'services')->first();
        $faqPage = Page::where('slug', 'faq')->first();

        $primaryMenu = Menu::updateOrCreate(
            ['location' => 'primary'],
            [
                'name' => 'Primary Menu',
                'description' => 'Main navigation menu displayed in the header',
                'is_active' => true,
            ]
        );

        $primaryMenu->allItems()->delete();

        $primaryItems = [
            $this->pageMenuItem('Home', $homePage, '/', 1),
            $this->pageMenuItem('About', $aboutPage, '/about', 2),
            $this->pageMenuItem('Blog', $blogPage, '/blog', 3),
            $this->pageMenuItem('Contact', $contactPage, '/contact', 4),
        ];

        foreach ($primaryItems as $item) {
            MenuItem::create([
                'menu_id' => $primaryMenu->id,
                'title' => $item['title'],
                'type' => $item['type'],
                'object_id' => $item['object_id'] ?? null,
                'url' => $item['url'] ?? null,
                'order' => $item['order'],
                'is_active' => true,
            ]);
        }

        $footerMenu = Menu::updateOrCreate(
            ['location' => 'footer'],
            [
                'name' => 'Footer Menu',
                'description' => 'Navigation menu displayed in the footer',
                'is_active' => true,
            ]
        );

        $footerMenu->allItems()->delete();

        $footerItems = [
            $this->pageMenuItem('Privacy Policy', $privacyPage, '/privacy-policy', 1),
            $this->pageMenuItem('Terms of Service', $termsPage, '/terms-of-service', 2),
            $this->pageMenuItem('Contact', $contactPage, '/contact', 3),
            [
                'title' => 'Sitemap',
                'type' => 'custom',
                'url' => '/sitemap.xml',
                'order' => 4,
            ],
        ];

        foreach ($footerItems as $item) {
            MenuItem::create([
                'menu_id' => $footerMenu->id,
                'title' => $item['title'],
                'type' => $item['type'],
                'object_id' => $item['object_id'] ?? null,
                'url' => $item['url'] ?? null,
                'order' => $item['order'],
                'is_active' => true,
            ]);
        }

        $companyMenu = Menu::updateOrCreate(
            ['location' => 'footer-company'],
            [
                'name' => 'Company',
                'description' => 'Company links displayed in footer column',
                'is_active' => true,
            ]
        );

        $companyMenu->allItems()->delete();

        $companyItems = [
            $this->pageMenuItem('Home', $homePage, '/', 1),
            $this->pageMenuItem('About', $aboutPage, '/about', 2),
            $this->pageMenuItem('Contact', $contactPage, '/contact', 3),
        ];

        foreach ($companyItems as $item) {
            MenuItem::create([
                'menu_id' => $companyMenu->id,
                'title' => $item['title'],
                'type' => $item['type'],
                'object_id' => $item['object_id'] ?? null,
                'url' => $item['url'] ?? null,
                'order' => $item['order'],
                'is_active' => true,
            ]);
        }

        $resourcesMenu = Menu::updateOrCreate(
            ['location' => 'footer-resources'],
            [
                'name' => 'Resources',
                'description' => 'Resource links displayed in footer column',
                'is_active' => true,
            ]
        );

        $resourcesMenu->allItems()->delete();

        $resourcesItems = [
            $this->pageMenuItem('Blog', $blogPage, '/blog', 1),
            $this->pageMenuItem('Services', $servicesPage, '/services', 2),
            $this->pageMenuItem('FAQ', $faqPage, '/faq', 3),
        ];

        foreach ($resourcesItems as $item) {
            MenuItem::create([
                'menu_id' => $resourcesMenu->id,
                'title' => $item['title'],
                'type' => $item['type'],
                'object_id' => $item['object_id'] ?? null,
                'url' => $item['url'] ?? null,
                'order' => $item['order'],
                'is_active' => true,
            ]);
        }

        $legalMenu = Menu::updateOrCreate(
            ['location' => 'footer-legal'],
            [
                'name' => 'Legal',
                'description' => 'Legal links displayed in footer column',
                'is_active' => true,
            ]
        );

        $legalMenu->allItems()->delete();

        $legalItems = [
            $this->pageMenuItem('Privacy Policy', $privacyPage, '/privacy-policy', 1),
            $this->pageMenuItem('Terms of Service', $termsPage, '/terms-of-service', 2),
            [
                'title' => 'Sitemap',
                'type' => 'custom',
                'url' => '/sitemap.xml',
                'order' => 3,
            ],
        ];

        foreach ($legalItems as $item) {
            MenuItem::create([
                'menu_id' => $legalMenu->id,
                'title' => $item['title'],
                'type' => $item['type'],
                'object_id' => $item['object_id'] ?? null,
                'url' => $item['url'] ?? null,
                'order' => $item['order'],
                'is_active' => true,
            ]);
        }
    }

    protected function pageMenuItem(string $title, ?Page $page, string $fallbackUrl, int $order): array
    {
        if ($page) {
            return [
                'title' => $title,
                'type' => 'page',
                'object_id' => $page->id,
                'order' => $order,
            ];
        }

        return [
            'title' => $title,
            'type' => 'custom',
            'url' => $fallbackUrl,
            'order' => $order,
        ];
    }
}

