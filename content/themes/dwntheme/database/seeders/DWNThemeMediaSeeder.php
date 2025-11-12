<?php

namespace Themes\DwnTheme\Database\Seeders;

use Illuminate\Database\Seeder;
use Themes\DwnTheme\Database\Seeders\Support\ThemeMediaRegistry;

class DWNThemeMediaSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->items() as $key => $config) {
            $media = ThemeMediaRegistry::ensure($key, $config);

            if ($media) {
                $this->command?->info("✅ Seeded media '{$media->name}' ({$media->url})");
            } else {
                $this->command?->warn("⚠️ Unable to seed media for key '{$key}'");
            }
        }
    }

    public static function mediaUrl(string $key, ?string $fallback = null): ?string
    {
        return ThemeMediaRegistry::getUrl($key, $fallback);
    }

    protected function items(): array
    {
        return [
            'homepage-hero' => [
                'name' => 'Homepage Hero',
                'source_url' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1600&q=80',
                'file_name' => 'dwntheme-homepage-hero.jpg',
                'asset' => base_path('content/themes/dwntheme/assets/images/about-1.jpeg'),
            ],
            'about-story' => [
                'name' => 'About Story',
                'source_url' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1600&q=80',
                'file_name' => 'dwntheme-about-story.jpg',
                'asset' => base_path('content/themes/dwntheme/assets/images/about-2.jpeg'),
            ],
        ];
    }
}


