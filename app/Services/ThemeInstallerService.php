<?php

namespace App\Services;

use App\Models\Theme;
use App\Repositories\ThemeRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use ZipArchive;

class ThemeInstallerService
{
    public function __construct(
        protected ThemeRepository $themeRepository
    ) {}

    /**
     * Install theme from ZIP file (WordPress-like)
     *
     * @param UploadedFile $zipFile
     * @return Theme
     * @throws \Exception
     */
    public function installFromZip(UploadedFile $zipFile): Theme
    {
        $zip = new ZipArchive();
        $tempPath = storage_path('app/temp-theme-' . time());
        
        try {
            // Extract ZIP to temp directory
            if ($zip->open($zipFile->getRealPath()) !== true) {
                throw new \Exception('Unable to open ZIP file');
            }

            File::makeDirectory($tempPath, 0755, true);
            $zip->extractTo($tempPath);
            $zip->close();

            // Find theme.json in extracted files
            $themeJsonPath = $this->findThemeJson($tempPath);
            
            if (!$themeJsonPath) {
                throw new \Exception('theme.json not found in ZIP file');
            }

            // Read theme.json
            $themeData = json_decode(File::get($themeJsonPath), true);
            
            if (!isset($themeData['name'])) {
                throw new \Exception('Invalid theme.json: missing "name" field');
            }

            $themeName = $themeData['name'];
            $themeDir = dirname($themeJsonPath);

            // Check if theme already exists
            $existingTheme = $this->themeRepository->findByName($themeName);
            
            if ($existingTheme) {
                // Update existing theme
                $this->replaceThemeFiles($themeName, $themeDir);
                $theme = $this->updateThemeInDatabase($existingTheme, $themeData);
                
                Log::info("Theme updated: {$themeName}");
            } else {
                // Install new theme
                $this->copyThemeFiles($themeName, $themeDir);
                $theme = $this->registerThemeInDatabase($themeData);
                
                Log::info("Theme installed: {$themeName}");
            }

            // Create asset symlink
            $this->createAssetSymlink($themeName);

            // Cleanup temp directory
            File::deleteDirectory($tempPath);

            return $theme;

        } catch (\Exception $e) {
            // Cleanup on error
            if (File::exists($tempPath)) {
                File::deleteDirectory($tempPath);
            }
            
            Log::error('Theme installation failed', [
                'error' => $e->getMessage(),
                'file' => $zipFile->getClientOriginalName(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Find theme.json in extracted directory
     */
    protected function findThemeJson(string $path): ?string
    {
        // Check root level
        if (File::exists($path . '/theme.json')) {
            return $path . '/theme.json';
        }

        // Check one level deep (common for GitHub downloads)
        $subdirs = File::directories($path);
        foreach ($subdirs as $subdir) {
            if (File::exists($subdir . '/theme.json')) {
                return $subdir . '/theme.json';
            }
        }

        return null;
    }

    /**
     * Copy theme files to content/themes/
     */
    protected function copyThemeFiles(string $themeName, string $source): void
    {
        $destination = base_path("content/themes/{$themeName}");
        
        if (File::exists($destination)) {
            File::deleteDirectory($destination);
        }
        
        File::copyDirectory($source, $destination);
    }

    /**
     * Replace existing theme files
     */
    protected function replaceThemeFiles(string $themeName, string $source): void
    {
        $destination = base_path("content/themes/{$themeName}");
        
        // Backup old theme
        $backupPath = base_path("content/themes/{$themeName}-backup-" . time());
        if (File::exists($destination)) {
            File::copyDirectory($destination, $backupPath);
            File::deleteDirectory($destination);
        }
        
        File::copyDirectory($source, $destination);
        
        // Remove backup after successful copy
        if (File::exists($backupPath)) {
            File::deleteDirectory($backupPath);
        }
    }

    /**
     * Register theme in database
     */
    protected function registerThemeInDatabase(array $themeData): Theme
    {
        return $this->themeRepository->create([
            'name' => $themeData['name'],
            'display_name' => $themeData['display_name'] ?? $themeData['name'],
            'description' => $themeData['description'] ?? null,
            'version' => $themeData['version'] ?? '1.0.0',
            'author' => $themeData['author'] ?? null,
            'author_url' => $themeData['author_url'] ?? null,
            'screenshot' => $themeData['screenshot'] ?? null,
            'parent_theme' => $themeData['parent_theme'] ?? null,
            'settings' => isset($themeData['settings']) ? json_encode($themeData['settings']) : null,
            'is_active' => false,
        ]);
    }

    /**
     * Update theme in database
     */
    protected function updateThemeInDatabase(Theme $theme, array $themeData): Theme
    {
        $this->themeRepository->update($theme, [
            'display_name' => $themeData['display_name'] ?? $themeData['name'],
            'description' => $themeData['description'] ?? null,
            'version' => $themeData['version'] ?? '1.0.0',
            'author' => $themeData['author'] ?? null,
            'author_url' => $themeData['author_url'] ?? null,
            'screenshot' => $themeData['screenshot'] ?? null,
            'parent_theme' => $themeData['parent_theme'] ?? null,
            'settings' => isset($themeData['settings']) ? json_encode($themeData['settings']) : null,
        ]);

        return $theme->fresh();
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
        
        // Remove existing symlink/directory
        if (File::exists($target)) {
            if (is_link($target)) {
                File::delete($target);
            } else {
                File::deleteDirectory($target);
            }
        }
        
        // Create symlink if source exists
        if (File::exists($source)) {
            try {
                File::link($source, $target);
                Log::info("Created asset symlink for theme: {$themeName}");
            } catch (\Exception $e) {
                Log::warning("Could not create symlink for theme {$themeName}: " . $e->getMessage());
            }
        }
    }

    /**
     * Uninstall theme (delete files and database entry)
     */
    public function uninstallTheme(Theme $theme): bool
    {
        if ($theme->is_active) {
            throw new \Exception('Cannot uninstall active theme. Activate another theme first.');
        }

        $themePath = base_path("content/themes/{$theme->name}");
        $symlinkPath = public_path("content/themes/{$theme->name}");

        // Remove symlink
        if (File::exists($symlinkPath)) {
            if (is_link($symlinkPath)) {
                File::delete($symlinkPath);
            } else {
                File::deleteDirectory($symlinkPath);
            }
        }

        // Remove theme files
        if (File::exists($themePath)) {
            File::deleteDirectory($themePath);
        }

        // Remove from database
        $this->themeRepository->delete($theme);

        Log::info("Theme uninstalled: {$theme->name}");

        return true;
    }

    /**
     * Get available themes from filesystem
     */
    public function getAvailableThemes(): array
    {
        $themesPath = base_path('content/themes');
        $themes = [];

        if (!File::exists($themesPath)) {
            return $themes;
        }

        $folders = File::directories($themesPath);

        foreach ($folders as $folder) {
            $themeName = basename($folder);
            $themeJsonPath = $folder . '/theme.json';

            if (File::exists($themeJsonPath)) {
                $themeData = json_decode(File::get($themeJsonPath), true);
                $themeData['installed'] = $this->themeRepository->findByName($themeName) !== null;
                $themes[] = $themeData;
            }
        }

        return $themes;
    }
}

