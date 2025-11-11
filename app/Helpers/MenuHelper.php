<?php

use App\Models\Menu;
use App\Services\MenuService;

if (!function_exists('menu')) {
    /**
     * Get and render a menu by location
     *
     * @param string $location Menu location (header, footer, sidebar, etc.)
     * @param array $options Rendering options
     * @return string
     */
    function menu(string $location, array $options = []): string
    {
        $menuService = app(MenuService::class);
        $menu = $menuService->getByLocation($location);

        if (!$menu) {
            return '';
        }

        return $menuService->render($menu, $options);
    }
}

if (!function_exists('has_menu')) {
    /**
     * Check if a menu exists for the given location
     *
     * @param string $location
     * @return bool
     */
    function has_menu(string $location): bool
    {
        $menuService = app(MenuService::class);
        return $menuService->getByLocation($location) !== null;
    }
}

if (!function_exists('get_menu')) {
    /**
     * Get menu by location
     *
     * @param string $location
     * @return Menu|null
     */
    function get_menu(string $location): ?Menu
    {
        $menuService = app(MenuService::class);
        return $menuService->getByLocation($location);
    }
}

if (!function_exists('menu_locations')) {
    /**
     * Get available menu locations
     *
     * @return array
     */
    function menu_locations(): array
    {
        return [
            'primary' => 'Primary Menu (Header)',
            'header' => 'Header Menu',
            'footer' => 'Footer Menu',
            'sidebar' => 'Sidebar Menu',
            'mobile' => 'Mobile Menu',
            'social' => 'Social Links',
        ];
    }
}

