<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Collection;

class MenuRepository
{
    public function __construct(
        protected Menu $menu,
        protected MenuItem $menuItem
    ) {}

    /**
     * Get all menus
     */
    public function all(): Collection
    {
        return $this->menu->with('items')->get();
    }

    /**
     * Find menu by ID
     */
    public function find(int $id): ?Menu
    {
        return $this->menu->with('allItems')->find($id);
    }

    /**
     * Find menu by location
     */
    public function findByLocation(string $location): ?Menu
    {
        return $this->menu
            ->location($location)
            ->active()
            ->with('activeItems')
            ->first();
    }

    /**
     * Create a menu
     */
    public function create(array $data): Menu
    {
        return $this->menu->create($data);
    }

    /**
     * Update a menu
     */
    public function update(Menu $menu, array $data): bool
    {
        return $menu->update($data);
    }

    /**
     * Delete a menu
     */
    public function delete(Menu $menu): bool
    {
        return $menu->delete();
    }

    /**
     * Find menu item by ID
     */
    public function findItem(int $id): ?MenuItem
    {
        return $this->menuItem->with(['menu', 'parent', 'children'])->find($id);
    }

    /**
     * Create a menu item
     */
    public function createItem(array $data): MenuItem
    {
        return $this->menuItem->create($data);
    }

    /**
     * Update a menu item
     */
    public function updateItem(MenuItem $item, array $data): bool
    {
        return $item->update($data);
    }

    /**
     * Delete a menu item
     */
    public function deleteItem(MenuItem $item): bool
    {
        return $item->delete() ?? false;
    }

    /**
     * Get next order for menu item
     */
    public function getNextOrder(int $menuId, ?int $parentId = null): int
    {
        return $this->menuItem
            ->where('menu_id', $menuId)
            ->where('parent_id', $parentId)
            ->max('order') + 1 ?? 0;
    }

    /**
     * Get all items for a menu
     */
    public function getMenuItems(int $menuId): Collection
    {
        return $this->menuItem
            ->where('menu_id', $menuId)
            ->orderBy('order')
            ->get();
    }
}

