<?php

namespace Themes\DwnTheme\Database\Seeders;

use App\Models\Widget;
use App\Models\WidgetZone;
use Illuminate\Database\Seeder;

class DWNThemeWidgetSeeder extends Seeder
{
    protected string $theme = 'dwntheme';

    public function run(): void
    {
        $zones = WidgetZone::where('theme', $this->theme)
            ->whereIn('name', ['footer-1', 'footer-2', 'footer-3'])
            ->get()
            ->keyBy('name');

        $this->seedFooterSocialLinks($zones->get('footer-1'));
        $this->seedFooterMenu($zones->get('footer-2'));
        $this->seedFooterImage($zones->get('footer-3'));
    }

    protected function seedFooterSocialLinks(?WidgetZone $zone): void
    {
        if (!$zone) {
            $this->command?->warn('⚠️  Widget zone footer-1 non trovata per dwntheme.');
            return;
        }

        Widget::updateOrCreate(
            [
                'zone_id' => $zone->id,
                'type' => 'social_links',
            ],
            [
                'title' => 'Stay Connected',
                'settings' => [
                    'use_global_links' => false,
                    'show_labels' => false,
                    'icon_shape' => 'circle',
                    'icon_size' => 'md',
                    'facebook_url' => 'https://facebook.com/vecarcms',
                    'twitter_url' => 'https://twitter.com/vecarcms',
                    'instagram_url' => 'https://www.instagram.com/vecarcms',
                    'linkedin_url' => 'https://www.linkedin.com/company/vecarcms',
                ],
                'order' => 0,
                'is_active' => true,
            ]
        );
    }

    protected function seedFooterMenu(?WidgetZone $zone): void
    {
        if (!$zone) {
            $this->command?->warn('⚠️  Widget zone footer-2 non trovata per dwntheme.');
            return;
        }

        Widget::updateOrCreate(
            [
                'zone_id' => $zone->id,
                'type' => 'custom_menu',
            ],
            [
                'title' => 'Explore',
                'settings' => [
                    'menu_location' => 'footer-company',
                    'fallback_message' => 'Configure a menu in the footer location to display it here.',
                ],
                'order' => 0,
                'is_active' => true,
            ]
        );
    }

    protected function seedFooterImage(?WidgetZone $zone): void
    {
        if (!$zone) {
            $this->command?->warn('⚠️  Widget zone footer-3 non trovata per dwntheme.');
            return;
        }

        Widget::updateOrCreate(
            [
                'zone_id' => $zone->id,
                'type' => 'image',
            ],
            [
                'title' => 'Our Headquarters',
                'settings' => [
                    'image_url' => '/content/themes/dwntheme/assets/images/team-photo.png',
                    'alt_text' => 'VeCarCMS Office',
                    'link_url' => '',
                    'open_in_new_tab' => false,
                    'caption' => 'VeCarCMS headquarters',
                    'alignment' => 'center',
                    'max_width' => 320,
                ],
                'order' => 0,
                'is_active' => true,
            ]
        );
    }
}


