<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;

class DWNThemeMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $homePage = Page::where('slug', 'home')->first();
        $aboutPage = Page::where('slug', 'about')->first();
        $blogPage = Page::where('slug', 'blog')->first();
        $contactPage = Page::where('slug', 'contact')->first();
        $privacyPage = Page::where('slug', 'privacy-policy')->first();
        $termsPage = Page::where('slug', 'terms-of-service')->first();

        // Primary navigation -------------------------------------------------
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
            [
                'title' => 'Home',
                'type' => 'page',
                'object_id' => $homePage?->id,
                'order' => 1,
            ],
            [
                'title' => 'About',
                'type' => 'page',
                'object_id' => $aboutPage?->id,
                'order' => 2,
            ],
            [
                'title' => 'Blog',
                'type' => 'page',
                'object_id' => $blogPage?->id,
                'order' => 3,
            ],
            [
                'title' => 'Contact',
                'type' => 'page',
                'object_id' => $contactPage?->id,
                'order' => 4,
            ],
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

        // Footer navigation --------------------------------------------------
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
            [
                'title' => 'Privacy Policy',
                'type' => 'page',
                'object_id' => $privacyPage?->id,
                'order' => 1,
            ],
            [
                'title' => 'Terms of Service',
                'type' => 'page',
                'object_id' => $termsPage?->id,
                'order' => 2,
            ],
            [
                'title' => 'Contact',
                'type' => 'page',
                'object_id' => $contactPage?->id,
                'order' => 3,
            ],
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
    }
}


