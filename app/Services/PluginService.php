<?php

namespace App\Services;

use App\Models\Plugin;
use App\Repositories\PluginRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use ZipArchive;

class PluginService
{
    protected string $pluginsPath;

    public function __construct(
        protected PluginRepository $pluginRepository
    ) {
        $this->pluginsPath = base_path('content/plugins');
    }

    /**
     * Scan plugins directory and sync with database
     */
    public function scanPlugins(): int
    {
        if (!File::exists($this->pluginsPath)) {
            File::makeDirectory($this->pluginsPath, 0755, true);
        }

        $directories = File::directories($this->pluginsPath);
        $foundPlugins = 0;

        foreach ($directories as $directory) {
            $pluginSlug = basename($directory);
            
            // Look for main plugin file
            $mainFile = $this->findMainFile($directory);
            
            if (!$mainFile) {
                continue;
            }

            // Parse plugin header
            $header = $this->parsePluginHeader($mainFile);
            
            if (empty($header['name'])) {
                continue;
            }

            // Create or update plugin in database
            $this->pluginRepository->updateOrCreate(
                ['slug' => $pluginSlug],
                [
                    'name' => $header['name'],
                    'description' => $header['description'] ?? '',
                    'version' => $header['version'] ?? '1.0.0',
                    'author' => $header['author'] ?? '',
                    'author_uri' => $header['author_uri'] ?? '',
                    'plugin_uri' => $header['plugin_uri'] ?? '',
                    'directory' => $pluginSlug,
                    'main_file' => basename($mainFile),
                ]
            );

            $foundPlugins++;
        }

        // Remove plugins from DB that no longer exist
        $existingSlugs = collect($directories)->map(fn($dir) => basename($dir))->toArray();
        $this->pluginRepository->deleteNotInSlugs($existingSlugs);

        Cache::forget('active_plugins');

        return $foundPlugins;
    }

    /**
     * Find main plugin file
     */
    protected function findMainFile(string $directory): ?string
    {
        $files = File::files($directory);
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                if (strpos($content, 'Plugin Name:') !== false) {
                    return $file->getPathname();
                }
            }
        }

        return null;
    }

    /**
     * Parse plugin header (WordPress-style)
     */
    protected function parsePluginHeader(string $file): array
    {
        $content = File::get($file);
        $header = [];

        // Extract plugin name
        if (preg_match('/Plugin Name:\s*(.+)/i', $content, $matches)) {
            $header['name'] = trim($matches[1]);
        }

        // Extract description
        if (preg_match('/Description:\s*(.+)/i', $content, $matches)) {
            $header['description'] = trim($matches[1]);
        }

        // Extract version
        if (preg_match('/Version:\s*(.+)/i', $content, $matches)) {
            $header['version'] = trim($matches[1]);
        }

        // Extract author
        if (preg_match('/Author:\s*(.+)/i', $content, $matches)) {
            $header['author'] = trim($matches[1]);
        }

        // Extract author URI
        if (preg_match('/Author URI:\s*(.+)/i', $content, $matches)) {
            $header['author_uri'] = trim($matches[1]);
        }

        // Extract plugin URI
        if (preg_match('/Plugin URI:\s*(.+)/i', $content, $matches)) {
            $header['plugin_uri'] = trim($matches[1]);
        }

        return $header;
    }

    /**
     * Get all plugins
     */
    public function all(): Collection
    {
        return $this->pluginRepository->all();
    }

    /**
     * Activate a plugin
     */
    public function activate(Plugin $plugin): bool
    {
        $this->pluginRepository->update($plugin, ['is_active' => true]);
        Cache::forget('active_plugins');
        
        // Load plugin and call activation hook if exists
        $this->loadPlugin($plugin);
        $this->callPluginHook($plugin, 'activate');

        return true;
    }

    /**
     * Deactivate a plugin
     */
    public function deactivate(Plugin $plugin): bool
    {
        // Call deactivation hook if exists
        $this->callPluginHook($plugin, 'deactivate');
        
        $this->pluginRepository->update($plugin, ['is_active' => false]);
        Cache::forget('active_plugins');

        return true;
    }

    /**
     * Delete a plugin
     */
    public function delete(Plugin $plugin): bool
    {
        if ($plugin->is_active) {
            $this->deactivate($plugin);
        }

        $pluginPath = $this->pluginsPath . '/' . $plugin->directory;
        
        if (File::exists($pluginPath)) {
            File::deleteDirectory($pluginPath);
        }

        $this->pluginRepository->delete($plugin);
        Cache::forget('active_plugins');

        return true;
    }

    /**
     * Load active plugins
     */
    public function loadActivePlugins(): void
    {
        $activePlugins = Cache::remember('active_plugins', 3600, function () {
            return $this->pluginRepository->getActive();
        });

        foreach ($activePlugins as $plugin) {
            $this->loadPlugin($plugin);
        }
    }

    /**
     * Load a single plugin
     */
    protected function loadPlugin(Plugin $plugin): void
    {
        $mainFile = $this->pluginsPath . '/' . $plugin->directory . '/' . $plugin->main_file;
        
        if (File::exists($mainFile)) {
            require_once $mainFile;
        }
    }

    /**
     * Call plugin hook
     */
    protected function callPluginHook(Plugin $plugin, string $hook): void
    {
        $functionName = $plugin->slug . '_' . $hook;
        
        if (function_exists($functionName)) {
            call_user_func($functionName);
        }
    }

    /**
     * Install plugin from ZIP file
     */
    public function installFromZip($zipFile): array
    {
        $zip = new ZipArchive;
        
        if ($zip->open($zipFile->getPathname()) !== true) {
            return ['success' => false, 'message' => 'Cannot open ZIP file'];
        }

        // Extract to temp directory
        $tempDir = storage_path('app/temp/' . uniqid());
        File::makeDirectory($tempDir, 0755, true);
        $zip->extractTo($tempDir);
        $zip->close();

        // Find plugin directory
        $directories = File::directories($tempDir);
        
        if (empty($directories)) {
            File::deleteDirectory($tempDir);
            return ['success' => false, 'message' => 'Invalid plugin structure'];
        }

        $pluginDir = $directories[0];
        $pluginSlug = basename($pluginDir);

        // Check if plugin already exists
        if (File::exists($this->pluginsPath . '/' . $pluginSlug)) {
            File::deleteDirectory($tempDir);
            return ['success' => false, 'message' => 'Plugin already installed'];
        }

        // Move to plugins directory
        File::moveDirectory($pluginDir, $this->pluginsPath . '/' . $pluginSlug);
        File::deleteDirectory($tempDir);

        // Scan to add to database
        $this->scanPlugins();

        return ['success' => true, 'message' => 'Plugin installed successfully'];
    }

    /**
     * Update plugin configuration
     */
    public function updateConfig(Plugin $plugin, array $config): bool
    {
        return $this->pluginRepository->update($plugin, ['config' => $config]);
    }
}

