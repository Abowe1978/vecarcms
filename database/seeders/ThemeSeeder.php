<?php

namespace Database\Seeders;

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
            $this->command->error("⚠️  {$themeName} configuration file not found!");
            return;
        }

        $themeConfig = json_decode(File::get($themePath), true);

        // Check if theme already exists
        $theme = Theme::where('name', $themeName)
            ->orWhere('name', ucfirst($themeName))
            ->first();

        $screenshotPath = "content/themes/{$themeName}/" . ($themeConfig['screenshot'] ?? 'screenshot.png');

        if ($theme) {
            $this->command->warn("⚠️  {$themeName} already exists, updating...");
            
            $theme->update([
                'name' => $themeName,
                'display_name' => $themeConfig['display_name'] ?? 'DWNTheme',
                'description' => $themeConfig['description'],
                'version' => $themeConfig['version'],
                'author' => $themeConfig['author'],
                'author_url' => $themeConfig['author_url'],
                'screenshot' => $screenshotPath,
                'settings' => json_encode($themeConfig['settings'] ?? []),
            ]);
        } else {
            $theme = Theme::create([
                'name' => $themeName,
                'display_name' => $themeConfig['display_name'] ?? 'DWNTheme',
                'description' => $themeConfig['description'],
                'version' => $themeConfig['version'],
                'author' => $themeConfig['author'],
                'author_url' => $themeConfig['author_url'],
                'screenshot' => $screenshotPath,
                'settings' => json_encode($themeConfig['settings'] ?? []),
                'is_active' => true, // Attiva il tema di default
                'parent_theme' => null,
            ]);

            $this->command->info("✅ {$themeName} created and activated!");
        }

        $this->command->newLine();
        $this->command->info("   Theme: {$theme->display_name}");
        $this->command->info("   Version: {$theme->version}");
        $this->command->info("   Author: {$theme->author}");
        $this->command->info("   Status: " . ($theme->is_active ? 'Active' : 'Inactive'));
        $this->command->newLine();
    }
}

