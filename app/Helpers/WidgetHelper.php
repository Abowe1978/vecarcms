<?php

use App\Services\WidgetService;

if (!function_exists('widget_area')) {
    /**
     * Render widgets in a zone
     *
     * @param string $zoneName Zone name (sidebar, footer, etc.)
     * @param string|null $theme Theme name (null for default)
     * @return string
     */
    function widget_area(string $zoneName, ?string $theme = null): string
    {
        $widgetService = app(WidgetService::class);
        $resolvedTheme = $theme ?? (function_exists('active_theme') ? active_theme() : null);

        return $widgetService->renderZone($zoneName, $resolvedTheme);
    }
}

if (!function_exists('has_widgets')) {
    /**
     * Check if a zone has widgets
     *
     * @param string $zoneName
     * @param string|null $theme
     * @return bool
     */
    function has_widgets(string $zoneName, ?string $theme = null): bool
    {
        $widgetService = app(WidgetService::class);
        $resolvedTheme = $theme ?? (function_exists('active_theme') ? active_theme() : null);
        $zone = $widgetService->getZoneByName($zoneName, $resolvedTheme);
        
        if (!$zone) {
            return false;
        }

        return $widgetService->getWidgetsForZone($zone->id)->isNotEmpty();
    }
}

if (!function_exists('widget_zones')) {
    /**
     * Get available widget zones
     *
     * @return array
     */
    function widget_zones(?string $theme = null): array
    {
        $widgetService = app(WidgetService::class);
        $resolvedTheme = $theme ?? (function_exists('active_theme') ? active_theme() : null);

        return $widgetService->getAllZones($resolvedTheme)
            ->mapWithKeys(fn ($zone) => [$zone->name => $zone->display_name])
            ->toArray();
    }
}

if (!function_exists('available_widgets')) {
    /**
     * Get available widget types
     */
    function available_widgets(): array
    {
        return app(WidgetService::class)->getAvailableWidgetTypes();
    }
}

