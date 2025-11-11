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
        return $widgetService->renderZone($zoneName, $theme);
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
        $zone = $widgetService->getZoneByName($zoneName, $theme);
        
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
    function widget_zones(): array
    {
        return [
            'sidebar' => 'Sidebar',
            'footer' => 'Footer',
            'header' => 'Header',
            'before_content' => 'Before Content',
            'after_content' => 'After Content',
        ];
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

