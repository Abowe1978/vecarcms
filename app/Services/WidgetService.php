<?php

namespace App\Services;

use App\Models\Widget;
use App\Models\WidgetZone;
use App\Repositories\WidgetRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class WidgetService
{
    public function __construct(
        protected WidgetRepository $widgetRepository
    ) {}

    /**
     * Get all widget zones
     */
    public function getAllZones(): Collection
    {
        return $this->widgetRepository->getAllZones();
    }

    /**
     * Find zone by ID
     */
    public function findZone(int $id): ?WidgetZone
    {
        return $this->widgetRepository->findZone($id);
    }

    /**
     * Get zone by name (with caching)
     */
    public function getZoneByName(string $name, ?string $theme = null): ?WidgetZone
    {
        $cacheKey = "widget.zone.{$name}." . ($theme ?? 'default');
        
        return Cache::remember($cacheKey, 3600, function () use ($name, $theme) {
            return $this->widgetRepository->findZoneByName($name, $theme);
        });
    }

    /**
     * Create widget zone
     */
    public function createZone(array $data): WidgetZone
    {
        return $this->widgetRepository->createZone($data);
    }

    /**
     * Update widget zone
     */
    public function updateZone(WidgetZone $zone, array $data): bool
    {
        $result = $this->widgetRepository->updateZone($zone, $data);
        $this->clearZoneCache($zone);
        return $result;
    }

    /**
     * Delete widget zone
     */
    public function deleteZone(WidgetZone $zone): bool
    {
        $this->clearZoneCache($zone);
        return $this->widgetRepository->deleteZone($zone);
    }

    /**
     * Get widgets for a zone
     */
    public function getWidgetsForZone(int $zoneId): Collection
    {
        return $this->widgetRepository->getWidgetsForZone($zoneId);
    }

    /**
     * Create widget
     */
    public function createWidget(array $data): Widget
    {
        $widget = $this->widgetRepository->createWidget($data);
        $this->clearZoneCacheById($widget->zone_id);
        return $widget;
    }

    /**
     * Update widget
     */
    public function updateWidget(Widget $widget, array $data): bool
    {
        $result = $this->widgetRepository->updateWidget($widget, $data);
        $this->clearZoneCacheById($widget->zone_id);
        return $result;
    }

    /**
     * Delete widget
     */
    public function deleteWidget(Widget $widget): bool
    {
        $zoneId = $widget->zone_id;
        $result = $this->widgetRepository->deleteWidget($widget);
        $this->clearZoneCacheById($zoneId);
        return $result;
    }

    /**
     * Reorder widgets in zone
     */
    public function reorderWidgets(array $widgetIds, int $zoneId): void
    {
        $this->widgetRepository->reorderWidgets($widgetIds, $zoneId);
        $this->clearZoneCacheById($zoneId);
    }

    /**
     * Render widgets in a zone
     */
    public function renderZone(string $zoneName, ?string $theme = null): string
    {
        $zone = $this->getZoneByName($zoneName, $theme);

        if (!$zone) {
            return '';
        }

        $widgets = $this->getWidgetsForZone($zone->id);

        if ($widgets->isEmpty()) {
            return '';
        }

        $html = '<div class="widget-zone widget-zone-' . $zoneName . '">';

        foreach ($widgets as $widgetModel) {
            if (!$widgetModel->isVisible()) {
                continue;
            }

            $widgetInstance = $widgetModel->getWidgetInstance();
            
            if ($widgetInstance && $widgetInstance->shouldDisplay()) {
                $html .= $widgetInstance->render();
            }
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Get available widget types
     */
    public function getAvailableWidgetTypes(): array
    {
        return [
            'recent_posts' => [
                'name' => 'Recent Posts',
                'description' => 'Show a list of the latest published posts.',
                'icon' => 'fas fa-newspaper',
            ],
            'categories' => [
                'name' => 'Categories',
                'description' => 'Display a list of blog categories.',
                'icon' => 'fas fa-folder-open',
            ],
            'tag_cloud' => [
                'name' => 'Tag Cloud',
                'description' => 'Visual cloud of the most used tags.',
                'icon' => 'fas fa-tags',
            ],
            'custom_html' => [
                'name' => 'Custom HTML',
                'description' => 'Add any custom HTML markup.',
                'icon' => 'fas fa-code',
            ],
            'search' => [
                'name' => 'Search',
                'description' => 'A search field for your visitors.',
                'icon' => 'fas fa-search',
            ],
            'text' => [
                'name' => 'Text',
                'description' => 'Simple text block with optional formatting.',
                'icon' => 'fas fa-font',
            ],
            'image' => [
                'name' => 'Image',
                'description' => 'Display a single image with optional link.',
                'icon' => 'fas fa-image',
            ],
            'custom_menu' => [
                'name' => 'Custom Menu',
                'description' => 'Render a selected navigation menu.',
                'icon' => 'fas fa-bars',
            ],
            'recent_comments' => [
                'name' => 'Recent Comments',
                'description' => 'Show the most recent comments from your site.',
                'icon' => 'fas fa-comments',
            ],
            'archives' => [
                'name' => 'Archives',
                'description' => 'Monthly archive of site posts.',
                'icon' => 'fas fa-archive',
            ],
            'social_links' => [
                'name' => 'Social Links',
                'description' => 'List of your social media profiles.',
                'icon' => 'fas fa-share-alt',
            ],
            'newsletter' => [
                'name' => 'Newsletter Signup',
                'description' => 'Embed a simple newsletter subscription form.',
                'icon' => 'fas fa-envelope-open-text',
            ],
        ];
    }

    /**
     * Get widget class for type
     */
    public function getWidgetClass(string $type): ?string
    {
        $className = 'App\\Widgets\\' . str_replace('_', '', ucwords($type, '_')) . 'Widget';

        return class_exists($className) ? $className : null;
    }

    /**
     * Clear zone cache
     */
    protected function clearZoneCache(WidgetZone $zone): void
    {
        $cacheKey = "widget.zone.{$zone->name}." . ($zone->theme ?? 'default');
        Cache::forget($cacheKey);
    }

    /**
     * Clear zone cache by ID
     */
    protected function clearZoneCacheById(int $zoneId): void
    {
        $zone = $this->widgetRepository->findZone($zoneId);
        if ($zone) {
            $this->clearZoneCache($zone);
        }
    }

    /**
     * Get all widgets (admin)
     */
    public function getAllWidgets(): Collection
    {
        return $this->widgetRepository->allWidgets();
    }
}

