<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\WidgetZone;
use App\Models\Widget;
use App\Models\Page;
use App\Services\SettingsService;

class CompleteSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settingsService = app(SettingsService::class);
        
        // ============================================================
        // 1. CREATE HOMEPAGE
        // ============================================================
        $homepage = Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'content' => '<p>Welcome to VeCarCMS - A powerful WordPress-like CMS built with Laravel</p>',
                'is_published' => true,
                'is_homepage' => true,
                'meta_title' => 'VeCarCMS - Modern Laravel CMS',
                'meta_description' => 'A powerful WordPress-like content management system built with Laravel',
                'template' => 'default',
            ]
        );
        
        // Set as homepage (this will unset any other homepage)
        $homepage->setAsHomepage();

        // ============================================================
        // 2. CREATE BLOG PAGE
        // ============================================================
        $blogPage = Page::updateOrCreate(
            ['slug' => 'blog'],
            [
                'title' => 'Blog',
                'content' => '<p>Stay updated with our latest insights, stories, and updates</p>',
                'is_published' => true,
                'is_blog' => true,
                'meta_title' => 'Blog - VeCarCMS',
                'meta_description' => 'Read our latest articles and insights',
                'template' => 'default',
            ]
        );
        
        // Set as blog (this will unset any other blog page)
        $blogPage->setAsBlog();

        // ============================================================
        // 3. CREATE PRIMARY MENU
        // ============================================================
        $primaryMenu = Menu::updateOrCreate(
            ['location' => 'primary'],
            [
                'name' => 'Primary Menu',
                'description' => 'Main navigation menu',
                'is_active' => true,
            ]
        );

        // Clear existing items
        $primaryMenu->items()->delete();

        // Add menu items
        MenuItem::create([
            'menu_id' => $primaryMenu->id,
            'title' => 'Home',
            'type' => 'page',
            'object_id' => $homepage->id,
            'order' => 0,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $primaryMenu->id,
            'title' => 'Blog',
            'type' => 'page',
            'object_id' => $blogPage->id,
            'order' => 1,
            'is_active' => true,
        ]);

        // About Us (if exists)
        $aboutPage = Page::where('slug', 'about-us')->first();
        if ($aboutPage) {
            MenuItem::create([
                'menu_id' => $primaryMenu->id,
                'title' => 'About',
                'type' => 'page',
                'object_id' => $aboutPage->id,
                'order' => 2,
                'is_active' => true,
            ]);
        }

        // Contact (if exists)
        $contactPage = Page::where('slug', 'contact')->first();
        if ($contactPage) {
            MenuItem::create([
                'menu_id' => $primaryMenu->id,
                'title' => 'Contact',
                'type' => 'page',
                'object_id' => $contactPage->id,
                'order' => 3,
                'is_active' => true,
            ]);
        }

        // ============================================================
        // 4. CREATE FOOTER MENU
        // ============================================================
        $footerMenu = Menu::updateOrCreate(
            ['location' => 'footer'],
            [
                'name' => 'Footer Menu',
                'description' => 'Footer navigation links',
                'is_active' => true,
            ]
        );

        // Clear existing items
        $footerMenu->items()->delete();

        // Privacy Policy
        $privacyPage = Page::where('slug', 'privacy-policy')->first();
        if ($privacyPage) {
            MenuItem::create([
                'menu_id' => $footerMenu->id,
                'title' => 'Privacy',
                'type' => 'page',
                'object_id' => $privacyPage->id,
                'order' => 0,
                'is_active' => true,
            ]);
        }

        // Add custom links if pages don't exist
        if ($contactPage) {
            MenuItem::create([
                'menu_id' => $footerMenu->id,
                'title' => 'Contact',
                'type' => 'page',
                'object_id' => $contactPage->id,
                'order' => 1,
                'is_active' => true,
            ]);
        }

        // ============================================================
        // 5. CREATE WIDGET ZONES
        // ============================================================
        $sidebarZone = WidgetZone::updateOrCreate(
            ['name' => 'sidebar-main', 'theme' => 'vecartheme'],
            [
                'display_name' => 'Main Sidebar',
                'description' => 'Sidebar that appears on blog and archive pages',
                'is_active' => true,
            ]
        );

        $footer1Zone = WidgetZone::updateOrCreate(
            ['name' => 'footer-1', 'theme' => 'vecartheme'],
            [
                'display_name' => 'Footer Column 1',
                'description' => 'First column in footer',
                'is_active' => true,
            ]
        );

        $footer2Zone = WidgetZone::updateOrCreate(
            ['name' => 'footer-2', 'theme' => 'vecartheme'],
            [
                'display_name' => 'Footer Column 2',
                'description' => 'Second column in footer',
                'is_active' => true,
            ]
        );

        $footer3Zone = WidgetZone::updateOrCreate(
            ['name' => 'footer-3', 'theme' => 'vecartheme'],
            [
                'display_name' => 'Footer Column 3',
                'description' => 'Third column in footer',
                'is_active' => true,
            ]
        );

        // ============================================================
        // 6. ADD WIDGETS TO SIDEBAR
        // ============================================================
        
        // Clear existing widgets
        $sidebarZone->widgets()->delete();

        Widget::create([
            'zone_id' => $sidebarZone->id,
            'type' => 'search',
            'title' => 'Search',
            'settings' => json_encode(['placeholder' => 'Search articles...', 'showButton' => true]),
            'order' => 0,
            'is_active' => true,
        ]);

        Widget::create([
            'zone_id' => $sidebarZone->id,
            'type' => 'recent-posts',
            'title' => 'Recent Posts',
            'settings' => json_encode(['limit' => 5, 'showThumbnail' => true, 'showDate' => true]),
            'order' => 1,
            'is_active' => true,
        ]);

        Widget::create([
            'zone_id' => $sidebarZone->id,
            'type' => 'categories',
            'title' => 'Categories',
            'settings' => json_encode(['showCount' => true, 'showEmpty' => false]),
            'order' => 2,
            'is_active' => true,
        ]);

        Widget::create([
            'zone_id' => $sidebarZone->id,
            'type' => 'tag-cloud',
            'title' => 'Tags',
            'settings' => json_encode(['limit' => 20]),
            'order' => 3,
            'is_active' => true,
        ]);

        // ============================================================
        // 7. ADD WIDGETS TO FOOTER
        // ============================================================
        
        Widget::create([
            'zone_id' => $footer1Zone->id,
            'type' => 'custom-html',
            'title' => 'About VeCarCMS',
            'settings' => json_encode([
                'content' => '<h3 class="text-lg font-semibold text-white mb-3">VeCarCMS</h3><p class="text-gray-400">A powerful WordPress-like CMS built with Laravel. Create beautiful websites with ease.</p>',
                'showTitle' => false
            ]),
            'order' => 0,
            'is_active' => true,
        ]);

        Widget::create([
            'zone_id' => $footer2Zone->id,
            'type' => 'custom-html',
            'title' => 'Quick Links',
            'settings' => json_encode([
                'content' => '<h3 class="text-lg font-semibold text-white mb-3">Quick Links</h3><ul class="space-y-2"><li><a href="/about" class="text-gray-400 hover:text-white">About</a></li><li><a href="/blog" class="text-gray-400 hover:text-white">Blog</a></li><li><a href="/contact" class="text-gray-400 hover:text-white">Contact</a></li></ul>',
                'showTitle' => false
            ]),
            'order' => 0,
            'is_active' => true,
        ]);

        Widget::create([
            'zone_id' => $footer3Zone->id,
            'type' => 'newsletter',
            'title' => 'Newsletter',
            'settings' => json_encode([
                'title' => 'Stay Updated',
                'description' => 'Subscribe to our newsletter for the latest updates',
                'buttonText' => 'Subscribe',
                'placeholder' => 'Your email address'
            ]),
            'order' => 0,
            'is_active' => true,
        ]);

        // ============================================================
        // 8. SEO SETTINGS
        // ============================================================
        
        $settingsService->set('seo_allow_indexing', true);
        $settingsService->set('seo_default_meta_title', 'VeCarCMS - Modern Laravel CMS');
        $settingsService->set('seo_default_meta_description', 'A powerful WordPress-like content management system built with Laravel. Create beautiful websites with advanced features.');
        $settingsService->set('seo_default_meta_keywords', 'cms, laravel, wordpress, page builder, content management');
        $settingsService->set('seo_twitter_handle', 'vecarcms');

        // ============================================================
        // 9. SOCIAL LINKS
        // ============================================================
        
        $settingsService->set('social_facebook', 'https://facebook.com/vecarcms');
        $settingsService->set('social_twitter', 'https://twitter.com/vecarcms');
        $settingsService->set('social_instagram', 'https://instagram.com/vecarcms');
        $settingsService->set('social_linkedin', 'https://linkedin.com/company/vecarcms');

        // ============================================================
        // 10. SITE SETTINGS
        // ============================================================
        
        $settingsService->set('site_tagline', 'A powerful WordPress-like CMS built with Laravel');
        
        $this->command->info('✅ Complete setup seeder executed successfully!');
        $this->command->info('✅ Homepage created and set');
        $this->command->info('✅ Blog page created and set');
        $this->command->info('✅ Primary menu configured with ' . $primaryMenu->items->count() . ' items');
        $this->command->info('✅ Footer menu configured');
        $this->command->info('✅ Widget zones created');
        $this->command->info('✅ Sidebar widgets configured');
        $this->command->info('✅ Footer widgets configured');
        $this->command->info('✅ SEO settings configured');
        $this->command->info('✅ Social links configured');
    }
}

