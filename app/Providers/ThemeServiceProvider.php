<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register theme views paths
        $this->registerThemeViews();
    }

    /**
     * Register all theme view paths from content/themes/
     * WordPress-like architecture
     */
    protected function registerThemeViews(): void
    {
        $themesPath = base_path('content/themes');
        
        if (!File::exists($themesPath)) {
            File::makeDirectory($themesPath, 0755, true);
            return;
        }

        // Scan all themes and register their view paths
        $themes = File::directories($themesPath);

        foreach ($themes as $themePath) {
            $themeName = basename($themePath);
            
            // Register namespace for this theme
            // This allows: view('themes.vecartheme.views.home')
            View::addNamespace("themes.{$themeName}", $themePath);
        }
    }
}

