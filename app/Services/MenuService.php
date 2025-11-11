<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Repositories\MenuRepository;
use App\Repositories\PageRepository;
use App\Repositories\PostRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MenuService
{
    public function __construct(
        protected MenuRepository $menuRepository,
        protected PageRepository $pageRepository,
        protected PostRepository $postRepository,
        protected CategoryRepository $categoryRepository
    ) {}

    /**
     * Get all menus
     */
    public function all(): Collection
    {
        return $this->menuRepository->all();
    }

    /**
     * Find menu by ID
     */
    public function find(int $id): ?Menu
    {
        return $this->menuRepository->find($id);
    }

    /**
     * Get menu by location (with caching)
     */
    public function getByLocation(string $location): ?Menu
    {
        return Cache::remember("menu.location.{$location}", 3600, function () use ($location) {
            return $this->menuRepository->findByLocation($location);
        });
    }

    /**
     * Create a new menu
     */
    public function create(array $data): Menu
    {
        return $this->menuRepository->create($data);
    }

    /**
     * Update a menu
     */
    public function update(Menu $menu, array $data): bool
    {
        $result = $this->menuRepository->update($menu, $data);
        $this->clearCache($menu);
        return $result;
    }

    /**
     * Delete a menu
     */
    public function delete(Menu $menu): bool
    {
        $this->clearCache($menu);
        return $this->menuRepository->delete($menu);
    }

    /**
     * Create a menu item
     */
    public function createItem(array $data): MenuItem
    {
        $item = $this->menuRepository->createItem($data);
        $this->clearCacheByMenuId($item->menu_id);
        
        return $item;
    }

    /**
     * Update a menu item
     */
    public function updateItem(MenuItem $item, array $data): bool
    {
        $result = $this->menuRepository->updateItem($item, $data);
        $this->clearCacheByMenuId($item->menu_id);
        return $result;
    }

    /**
     * Delete a menu item
     */
    public function deleteItem(MenuItem $item): bool
    {
        // Forza il refresh del modello dal database per assicurarsi di avere i dati corretti
        $item->refresh();
        
        // Salva menu_id PRIMA di eliminare l'item
        $menuId = $item->menu_id;
        
        // Verifica che menu_id sia valido
        if (!$menuId || $menuId <= 0) {
            Log::error('MenuItem has invalid menu_id', [
                'item_id' => $item->id,
                'menu_id' => $menuId,
                'item_attributes' => $item->getAttributes(),
            ]);
            throw new \Exception("Menu item (ID: {$item->id}) has no valid menu_id");
        }
        
        $result = $this->menuRepository->deleteItem($item);
        
        // Clear cache solo se l'eliminazione è andata a buon fine
        if ($result) {
            $this->clearCacheByMenuId($menuId);
        }
        
        return $result;
    }

    /**
     * Reorder menu items
     */
    public function reorderItems(array $items, int $menuId, ?int $parentId = null): void
    {
        foreach ($items as $index => $itemData) {
            // Skip if id is not set
            if (!isset($itemData['id'])) {
                Log::warning('Reorder item missing ID', ['itemData' => $itemData]);
                continue;
            }
            
            $item = $this->menuRepository->findItem($itemData['id']);
            
            if (!$item) {
                Log::warning('Menu item not found for reordering', [
                    'item_id' => $itemData['id'],
                    'menu_id' => $menuId
                ]);
                continue;
            }
            
            // Update the item
            $this->menuRepository->updateItem($item, [
                'order' => $index,
                'parent_id' => $parentId,
            ]);

            // Recursively reorder children
            if (isset($itemData['children']) && !empty($itemData['children'])) {
                $this->reorderItems($itemData['children'], $menuId, $item->id);
            }
        }

        $this->clearCacheByMenuId($menuId);
    }

    /**
     * Add page to menu
     */
    public function addPage(int $menuId, int $pageId, ?int $parentId = null): MenuItem
    {
        // Get page from PageRepository
        $page = $this->pageRepository->find($pageId);
        
        if (!$page) {
            throw new \Exception("Page not found");
        }

        return $this->createItem([
            'menu_id' => $menuId,
            'parent_id' => $parentId,
            'title' => $page->title,
            'type' => 'page',
            'object_id' => $page->id,
            'order' => $this->menuRepository->getNextOrder($menuId, $parentId),
        ]);
    }

    /**
     * Add post to menu
     */
    public function addPost(int $menuId, int $postId, ?int $parentId = null): MenuItem
    {
        // Get post from PostRepository
        $post = $this->postRepository->find($postId);
        
        if (!$post) {
            throw new \Exception("Post not found");
        }

        return $this->createItem([
            'menu_id' => $menuId,
            'parent_id' => $parentId,
            'title' => $post->title,
            'type' => 'post',
            'object_id' => $post->id,
            'order' => $this->menuRepository->getNextOrder($menuId, $parentId),
        ]);
    }

    /**
     * Add category to menu
     */
    public function addCategory(int $menuId, int $categoryId, ?int $parentId = null): MenuItem
    {
        // Get category from CategoryRepository
        $category = $this->categoryRepository->find($categoryId);
        
        if (!$category) {
            throw new \Exception("Category not found");
        }

        return $this->createItem([
            'menu_id' => $menuId,
            'parent_id' => $parentId,
            'title' => $category->name,
            'type' => 'category',
            'object_id' => $category->id,
            'order' => $this->menuRepository->getNextOrder($menuId, $parentId),
        ]);
    }

    /**
     * Add custom link to menu
     */
    public function addCustomLink(int $menuId, string $title, string $url, ?int $parentId = null): MenuItem
    {
        return $this->createItem([
            'menu_id' => $menuId,
            'parent_id' => $parentId,
            'title' => $title,
            'url' => $url,
            'type' => 'custom',
            'order' => $this->menuRepository->getNextOrder($menuId, $parentId),
        ]);
    }

    /**
     * Get data for menu edit page
     */
    public function getMenuEditData(Menu $menu): array
    {
        $menu->load('allItems');
        
        return [
            'menu' => $menu,
            'pages' => $this->pageRepository->getAllForMenu(),
            'posts' => $this->postRepository->getRecent(20),
            'categories' => $this->categoryRepository->all(),
            'locations' => menu_locations(),
        ];
    }

    /**
     * Clear menu cache
     */
    protected function clearCache(Menu $menu): void
    {
        if ($menu->location) {
            Cache::forget("menu.location.{$menu->location}");
        }
        Cache::forget("menu.{$menu->id}");
    }

    /**
     * Clear cache by menu ID
     */
    protected function clearCacheByMenuId(int $menuId): void
    {
        $menu = $this->menuRepository->find($menuId);
        if ($menu) {
            $this->clearCache($menu);
        }
    }

    /**
     * Render menu HTML
     */
    public function render(Menu $menu, array $options = []): string
    {
        $items = $menu->activeItems->where('parent_id', null);
        
        if ($items->isEmpty()) {
            return '';
        }

        // Get class from options or use default
        $class = $options['class'] ?? 'navbar-nav';
        $framework = $options['framework'] ?? 'bootstrap'; // 'bootstrap' or 'tailwind'

        return $this->renderItems($items, $class, $framework);
    }

    /**
     * Recursively render menu items
     */
    protected function renderItems(Collection $items, string $class, string $framework = 'bootstrap', int $depth = 0): string
    {
        if ($items->isEmpty()) {
            return '';
        }

        // Start UL wrapper for Bootstrap
        $html = $depth === 0 ? "<ul class=\"{$class}\">" : "<ul class=\"dropdown-menu\">";

        foreach ($items as $item) {
            if (!$item->isVisible()) {
                continue;
            }

            $hasChildren = $item->activeChildren->isNotEmpty();
            $icon = $item->icon ? "<i class=\"{$item->icon} me-2\"></i>" : '';
            $target = $item->target ?? '_self';
            
            // Apply custom CSS class if provided
            $itemClass = $item->css_class ?? '';
            
            if ($framework === 'bootstrap') {
                // Bootstrap 5 styling
                if ($depth === 0) {
                    $html .= "<li class=\"nav-item" . ($hasChildren ? ' dropdown' : '') . " {$itemClass}\">";
                    $html .= "<a href=\"{$item->computed_url}\" target=\"{$target}\" class=\"nav-link" . ($hasChildren ? ' dropdown-toggle' : '') . "\"" . ($hasChildren ? ' data-bs-toggle="dropdown"' : '') . ">";
                    $html .= "{$icon}{$item->title}";
                    $html .= "</a>";
                } else {
                    $html .= "<li><a href=\"{$item->computed_url}\" target=\"{$target}\" class=\"dropdown-item {$itemClass}\">";
                    $html .= "{$icon}{$item->title}";
                    $html .= "</a></li>";
                }
            } else {
                // Tailwind styling (legacy)
                $html .= "<li><a href=\"{$item->computed_url}\" target=\"{$target}\" class=\"text-gray-700 hover:text-purple-600 font-medium transition {$itemClass}\">";
                $html .= "{$icon}{$item->title}";
                $html .= "</a></li>";
            }

            // Render children recursively if needed (for submenu support)
            if ($hasChildren && $framework === 'bootstrap') {
                $html .= $this->renderItems($item->activeChildren, $class, $framework, $depth + 1);
            }
            
            if ($depth === 0) {
                $html .= "</li>";
            }
        }

        $html .= "</ul>";
        return $html;
    }
}

