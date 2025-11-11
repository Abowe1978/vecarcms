<?php

namespace App\Services;

use App\Models\Theme;
use App\Repositories\ThemeRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class ThemeService
{
    public function __construct(
        protected ThemeRepository $themeRepository
    ) {}

    /**
     * Get all themes
     */
    public function all(): Collection
    {
        return $this->themeRepository->all();
    }

    /**
     * Get active theme
     */
    public function getActiveTheme(): ?Theme
    {
        return $this->themeRepository->getActive();
    }

    /**
     * Activate a theme
     */
    public function activateTheme(Theme $theme): bool
    {
        try {
            $result = $this->themeRepository->activate($theme);
            
            Log::info('Theme activated', ['theme' => $theme->name]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error activating theme', [
                'theme' => $theme->name,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Scan content/themes/ directory and register new themes
     * WordPress-like theme discovery
     */
    public function scanThemes(): int
    {
        $themesPath = base_path('content/themes');
        
        if (!File::exists($themesPath)) {
            File::makeDirectory($themesPath, 0755, true);
        }

        $folders = File::directories($themesPath);
        $registered = 0;

        foreach ($folders as $folder) {
            $themeName = basename($folder);
            
            // Check if already exists
            if ($this->themeRepository->findByName($themeName)) {
                continue;
            }

            // Look for theme.json
            $themeJsonPath = $folder . '/theme.json';
            
            if (File::exists($themeJsonPath)) {
                $themeData = json_decode(File::get($themeJsonPath), true);
                
                $this->themeRepository->create([
                    'name' => $themeName,
                    'display_name' => $themeData['display_name'] ?? $themeData['name'] ?? $themeName,
                    'description' => $themeData['description'] ?? null,
                    'version' => $themeData['version'] ?? '1.0.0',
                    'author' => $themeData['author'] ?? null,
                    'author_url' => $themeData['author_url'] ?? null,
                    'screenshot' => $themeData['screenshot'] ?? null,
                    'parent_theme' => $themeData['parent_theme'] ?? null,
                    'settings' => isset($themeData['settings']) ? json_encode($themeData['settings']) : null,
                ]);

                $registered++;
                
                // Create symlink for assets
                $this->createAssetSymlink($themeName);
            }
        }

        return $registered;
    }
    
    /**
     * Create symlink for theme assets
     */
    protected function createAssetSymlink(string $themeName): void
    {
        $source = base_path("content/themes/{$themeName}/assets");
        $target = public_path("content/themes/{$themeName}");
        
        // Create public directory if doesn't exist
        if (!File::exists(public_path('content/themes'))) {
            File::makeDirectory(public_path('content/themes'), 0755, true);
        }
        
        // Create symlink if source exists and target doesn't
        if (File::exists($source) && !File::exists($target)) {
            try {
                File::link($source, $target);
                Log::info("Created asset symlink for theme: {$themeName}");
            } catch (\Exception $e) {
                Log::warning("Could not create symlink for theme {$themeName}: " . $e->getMessage());
            }
        }
    }

    /**
     * Delete a theme
     */
    public function deleteTheme(Theme $theme): bool
    {
        if ($theme->is_active) {
            throw new \Exception('Cannot delete active theme');
        }

        return $this->themeRepository->delete($theme);
    }

    /**
     * Update theme settings (for customizer)
     */
    public function updateSettings(Theme $theme, array $settings): bool
    {
        return $this->themeRepository->updateSettings($theme, $settings);
    }

    /**
     * Reset theme settings to defaults
     */
    public function resetSettings(Theme $theme): bool
    {
        // Get default settings from theme.json
        $themeJsonPath = base_path("content/themes/{$theme->slug}/theme.json");
        
        if (!File::exists($themeJsonPath)) {
            return false;
        }

        $themeData = json_decode(File::get($themeJsonPath), true);
        $defaultSettings = $themeData['settings'] ?? [];

        return $this->themeRepository->updateSettings($theme, $defaultSettings);
    }
}

