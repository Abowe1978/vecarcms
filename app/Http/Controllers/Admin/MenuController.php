<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function __construct(
        protected MenuService $menuService
    ) {
        $this->middleware('permission:manage_menus');
    }

    /**
     * Display a listing of menus
     */
    public function index(): View
    {
        $menus = $this->menuService->all();
        $locations = menu_locations();

        return view('admin.menus.index', compact('menus', 'locations'));
    }

    /**
     * Show the form for creating a new menu
     */
    public function create(): View
    {
        return view('admin.menus.create');
    }

    /**
     * Store a newly created menu
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:menus,name',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $menu = $this->menuService->create($validated);

        return redirect()
            ->route('admin.menus.edit', $menu)
            ->with('success', 'Menu creato con successo!');
    }

    /**
     * Show the form for editing a menu
     */
    public function edit(Menu $menu): View
    {
        // Get menu data from service
        $menuData = $this->menuService->getMenuEditData($menu);
        
        return view('admin.menus.edit', $menuData);
    }

    /**
     * Update the specified menu
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:menus,name,' . $menu->id,
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $this->menuService->update($menu, $validated);

        return back()->with('success', 'Menu aggiornato con successo!');
    }

    /**
     * Remove the specified menu
     */
    public function destroy(Menu $menu): RedirectResponse
    {
        $this->menuService->delete($menu);

        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu eliminato con successo!');
    }

    /**
     * Add item to menu (AJAX)
     */
    public function addItem(Request $request, Menu $menu): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:custom,page,post,category,tag',
            'title' => 'required_if:type,custom|string|max:255',
            'url' => 'required_if:type,custom|nullable|string',
            'object_id' => 'required_unless:type,custom|nullable|integer',
            'parent_id' => 'nullable|exists:menu_items,id',
        ]);

        $item = match($validated['type']) {
            'page' => $this->menuService->addPage($menu->id, $validated['object_id'], $validated['parent_id'] ?? null),
            'post' => $this->menuService->addPost($menu->id, $validated['object_id'], $validated['parent_id'] ?? null),
            'category' => $this->menuService->addCategory($menu->id, $validated['object_id'], $validated['parent_id'] ?? null),
            'custom' => $this->menuService->addCustomLink($menu->id, $validated['title'], $validated['url'], $validated['parent_id'] ?? null),
            default => null,
        };

        return response()->json([
            'success' => true,
            'message' => 'Item aggiunto al menu!',
            'item' => $item->load('children'),
        ]);
    }

    /**
     * Update menu item (AJAX)
     */
    public function updateItem(Request $request, Menu $menu, MenuItem $menuItem): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string',
            'target' => 'in:_self,_blank',
            'css_class' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        $this->menuService->updateItem($menuItem, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Item aggiornato!',
            'item' => $menuItem->fresh(),
        ]);
    }

    /**
     * Delete menu item (AJAX)
     */
    public function deleteItem(Menu $menu, MenuItem $menuItem): JsonResponse
    {
        try {
            $this->menuService->deleteItem($menuItem);

            return response()->json([
                'success' => true,
                'message' => 'Item eliminato!',
            ]);
        } catch (\Exception $e) {
            Log::error('Menu item delete failed', [
                'menu_id' => $menu->id,
                'menu_item_id' => $menuItem->id,
                'menu_item_menu_id' => $menuItem->menu_id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reorder menu items (AJAX)
     */
    public function reorder(Request $request, Menu $menu): JsonResponse
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array',
                'items.*.id' => 'required',
            ]);

            $this->menuService->reorderItems($validated['items'], $menu->id);

            return response()->json([
                'success' => true,
                'message' => 'Ordine menu aggiornato!',
            ]);
        } catch (\Exception $e) {
            Log::error('Menu reorder failed', [
                'menu_id' => $menu->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

