<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Widget;
use App\Models\WidgetZone;
use App\Services\WidgetService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WidgetController extends Controller
{
    public function __construct(
        protected WidgetService $widgetService
    ) {
        $this->middleware('permission:manage_widgets');
    }

    /**
     * Display widget management interface (WordPress-like)
     */
    public function index(): View
    {
        $zones = $this->widgetService->getAllZones();
        $availableWidgets = available_widgets();

        return view('admin.widgets.index', compact('zones', 'availableWidgets'));
    }

    /**
     * Get widget for editing (AJAX)
     */
    public function edit(Widget $widget): JsonResponse
    {
        return response()->json([
            'success' => true,
            'widget' => $widget->load('zone'),
        ]);
    }

    /**
     * Create a new widget (AJAX)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:widget_zones,id',
            'type' => 'required|string',
            'title' => 'nullable|string|max:255',
            'config' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['order'] = $this->widgetService->getWidgetsForZone($validated['zone_id'])->count();
        $validated['settings'] = json_encode($validated['config'] ?? []);
        unset($validated['config']);

        $widget = $this->widgetService->createWidget($validated);

        return response()->json([
            'success' => true,
            'message' => 'Widget creato con successo!',
            'widget' => $widget->load('zone'),
        ]);
    }

    /**
     * Update widget (AJAX)
     */
    public function update(Request $request, Widget $widget): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $this->widgetService->updateWidget($widget, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Widget aggiornato!',
            'widget' => $widget->fresh(),
        ]);
    }

    /**
     * Delete widget (AJAX)
     */
    public function destroy(Widget $widget): JsonResponse
    {
        $this->widgetService->deleteWidget($widget);

        return response()->json([
            'success' => true,
            'message' => 'Widget eliminato!',
        ]);
    }

    /**
     * Reorder widgets in zone (AJAX)
     */
    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:widget_zones,id',
            'widget_ids' => 'required|array',
        ]);

        $this->widgetService->reorderWidgets($validated['widget_ids'], $validated['zone_id']);

        return response()->json([
            'success' => true,
            'message' => 'Ordine widget aggiornato!',
        ]);
    }

    /**
     * Move widget to different zone (AJAX)
     */
    public function move(Request $request, Widget $widget): JsonResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:widget_zones,id',
        ]);

        $oldZoneId = $widget->zone_id;
        
        $this->widgetService->updateWidget($widget, [
            'zone_id' => $validated['zone_id'],
            'order' => $this->widgetService->getWidgetsForZone($validated['zone_id'])->count(),
        ]);

        // Clear cache for both zones
        Cache::forget("widget.zone.*.{$oldZoneId}");
        Cache::forget("widget.zone.*.{$validated['zone_id']}");

        return response()->json([
            'success' => true,
            'message' => 'Widget spostato!',
        ]);
    }

    /**
     * Get widget form fields (AJAX)
     */
    public function getFormFields(string $type): JsonResponse
    {
        $widgetClass = $this->widgetService->getWidgetClass($type);

        if (!$widgetClass) {
            return response()->json([
                'success' => false,
                'message' => 'Widget type not found',
            ], 404);
        }

        // Create temporary widget instance to get form fields
        $tempWidget = new \App\Models\Widget(['type' => $type]);
        $instance = new $widgetClass($tempWidget);

        return response()->json([
            'success' => true,
            'fields' => $instance->getFormFields(),
            'name' => $instance->getName(),
            'description' => $instance->getDescription(),
        ]);
    }
}

