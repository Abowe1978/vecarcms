<?php

use App\Models\Theme;

if (!function_exists('active_theme')) {
    /**
     * Get active theme name
     */
    function active_theme(): string
    {
        $theme = cache()->remember('active_theme_object', 3600, function () {
            return Theme::where('is_active', true)->first();
        });
        
        return $theme ? $theme->name : 'dwntheme';
    }
}

if (!function_exists('active_theme_object')) {
    /**
     * Get active theme object
     */
    function active_theme_object(): ?Theme
    {
        return cache()->remember('active_theme_object', 3600, function () {
            return Theme::where('is_active', true)->first();
        });
    }
}

if (!function_exists('theme_asset')) {
    /**
     * Get theme asset URL (from public/content/themes/)
     */
    function theme_asset(string $path): string
    {
        $themeName = active_theme();
        return asset("content/themes/{$themeName}/{$path}");
    }
}

if (!function_exists('theme_view')) {
    /**
     * Get theme view path
     */
    function theme_view(string $view): string
    {
        $themeName = active_theme();
        return "themes.{$themeName}.{$view}";
    }
}

