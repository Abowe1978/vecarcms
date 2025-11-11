<?php

namespace Themes\DwnTheme\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Theme;
use Illuminate\Support\Facades\File;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themeName = 'dwntheme';
        $themePath = base_path("content/themes/{$themeName}/theme.json");
        
        if (!File::exists($themePath)) {
            $this->command?->error("⚠️  {$themeName} configuration file not found!");
            return;
        }

        $themeConfig = json_decode(File::get($themePath), true);

        $theme = Theme::where('name', $themeName)
            ->orWhere('name', ucfirst($themeName))
            ->first();

        $screenshotPath = "content/themes/{$themeName}/" . ($themeConfig['screenshot'] ?? 'screenshot.png');

        $payload = [
            'name' => $themeName,
            'display_name' => $themeConfig['display_name'] ?? 'DWNTheme',
            'description' => $themeConfig['description'] ?? '',
            'version' => $themeConfig['version'] ?? '1.0.0',
            'author' => $themeConfig['author'] ?? null,
            'author_url' => $themeConfig['author_url'] ?? null,
            'screenshot' => $screenshotPath,
            'settings' => json_encode($themeConfig['settings'] ?? []),
        ];

        if ($theme) {
            $this->command?->warn("⚠️  {$themeName} already exists, updating...");
            $theme->update($payload);
        } else {
            $theme = Theme::create($payload + [
                'is_active' => true,
                'parent_theme' => null,
            ]);

            $this->command?->info("✅ {$themeName} created and activated!");
        }

        $this->command?->newLine();
        $this->command?->info("   Theme: {$theme->display_name}");
        $this->command?->info("   Version: {$theme->version}");
        $this->command?->info("   Author: {$theme->author}");
        $this->command?->info("   Status: " . ($theme->is_active ? 'Active' : 'Inactive'));
        $this->command?->newLine();
    }
}

