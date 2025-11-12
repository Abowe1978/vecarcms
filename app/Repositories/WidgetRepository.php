<?php

namespace App\Repositories;

use App\Models\Widget;
use App\Models\WidgetZone;
use Illuminate\Database\Eloquent\Collection;

class WidgetRepository
{
    public function __construct(
        protected Widget $widget,
        protected WidgetZone $widgetZone
    ) {}

    /**
     * Get all widget zones
     */
    public function getAllZones(?string $theme = null): Collection
    {
        $query = $this->widgetZone
            ->with('widgets')
            ->active()
            ->orderBy('name');

        if ($theme) {
            $query->where(function ($subQuery) use ($theme) {
                $subQuery->where('theme', $theme)
                         ->orWhereNull('theme');
            });
        }

        return $query->get();
    }

    /**
     * Find zone by ID
     */
    public function findZone(int $id): ?WidgetZone
    {
        return $this->widgetZone->with('widgets')->find($id);
    }

    /**
     * Find zone by name
     */
    public function findZoneByName(string $name, ?string $theme = null): ?WidgetZone
    {
        $query = $this->widgetZone->byName($name)->active();
        
        if ($theme) {
            $query->where(function ($subQuery) use ($theme) {
                $subQuery->where('theme', $theme)
                         ->orWhereNull('theme');
            })->orderByRaw('CASE WHEN theme = ? THEN 0 ELSE 1 END', [$theme]);
        } else {
            $query->whereNull('theme');
        }

        return $query->first();
    }

    /**
     * Create widget zone
     */
    public function createZone(array $data): WidgetZone
    {
        return $this->widgetZone->create($data);
    }

    /**
     * Update widget zone
     */
    public function updateZone(WidgetZone $zone, array $data): bool
    {
        return $zone->update($data);
    }

    /**
     * Delete widget zone
     */
    public function deleteZone(WidgetZone $zone): bool
    {
        return $zone->delete();
    }

    /**
     * Get widgets for a zone
     */
    public function getWidgetsForZone(int $zoneId): Collection
    {
        return $this->widget
            ->where('zone_id', $zoneId)
            ->active()
            ->orderBy('order')
            ->get();
    }

    /**
     * Find widget by ID
     */
    public function findWidget(int $id): ?Widget
    {
        return $this->widget->with('zone')->find($id);
    }

    /**
     * Create widget
     */
    public function createWidget(array $data): Widget
    {
        return $this->widget->create($data);
    }

    /**
     * Update widget
     */
    public function updateWidget(Widget $widget, array $data): bool
    {
        return $widget->update($data);
    }

    /**
     * Delete widget
     */
    public function deleteWidget(Widget $widget): bool
    {
        return $widget->delete();
    }

    /**
     * Get next order for widget in zone
     */
    public function getNextOrder(int $zoneId): int
    {
        return $this->widget
            ->where('zone_id', $zoneId)
            ->max('order') + 1 ?? 0;
    }

    /**
     * Reorder widgets in zone
     */
    public function reorderWidgets(array $widgetIds, int $zoneId): void
    {
        foreach ($widgetIds as $index => $widgetId) {
            $this->widget
                ->where('id', $widgetId)
                ->where('zone_id', $zoneId)
                ->update(['order' => $index]);
        }
    }

    /**
     * Get all widgets (admin)
     */
    public function allWidgets(): Collection
    {
        return $this->widget
            ->with('zone')
            ->orderBy('zone_id')
            ->orderBy('order')
            ->get();
    }
}

