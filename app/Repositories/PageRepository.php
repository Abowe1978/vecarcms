<?php

namespace App\Repositories;

use App\Models\Page;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PageRepository implements PageRepositoryInterface
{
    /**
     * Get paginated pages with filtering and sorting
     *
     * @param string|null $search
     * @param string $sortField
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedPages(?string $search, string $sortField, string $sortDirection, int $perPage): LengthAwarePaginator
    {
        return Page::query()
            ->with('parent')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('content', 'like', '%' . $search . '%')
                        ->orWhere('template', 'like', '%' . $search . '%');
                });
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);
    }
    
    /**
     * Get all pages
     *
     * @return Collection
     */
    public function getAllPages(): Collection
    {
        return Page::with('parent')
                ->orderBy('order')
                ->get();
    }
    
    /**
     * Get all parent pages (root level)
     *
     * @return Collection
     */
    public function getParentPages(): Collection
    {
        return Page::where('parent_id', null)
                ->orderBy('title')
                ->get();
    }

    /**
     * Get the homepage page
     *
     * @return Page|null
     */
    public function getHomepage(): ?Page
    {
        return Page::homepage()->first();
    }

    /**
     * Get the blog page
     *
     * @return Page|null
     */
    public function getBlogPage(): ?Page
    {
        return Page::blog()->first();
    }

    /**
     * Get all pages including homepage and blog for menu builder
     *
     * @return Collection
     */
    public function getAllForMenu(): Collection
    {
        return Page::where('is_published', true)
                ->orderByDesc('is_homepage') // Homepage first
                ->orderByDesc('is_blog') // Blog second
                ->orderBy('title')
                ->get();
    }
    
    /**
     * Get all potential parent pages (excluding self and children)
     *
     * @param Page $page
     * @return Collection
     */
    public function getPotentialParentPages(Page $page): Collection
    {
        return Page::where('id', '!=', $page->id)
                ->where(function($query) use ($page) {
                    $query->where('parent_id', null)
                         ->orWhere('parent_id', '!=', $page->id);
                })
                ->orderBy('title')
                ->get();
    }
    
    /**
     * Create a new page
     *
     * @param array $data
     * @return Page
     */
    public function createPage(array $data): Page
    {
        $page = new Page();
        $page->title = $data['title'];
        $page->slug = $data['slug'];
        $page->content = $data['content'] ?? null;
        $page->featured_image = $data['featured_image'] ?? null;
        $page->template = $data['template'];
        $page->is_published = $data['is_published'] ?? false;
        $page->meta_title = $data['meta_title'] ?? null;
        $page->meta_description = $data['meta_description'] ?? null;
        $page->meta_keywords = $data['meta_keywords'] ?? null;
        $page->order = $data['order'] ?? 0;
        $page->parent_id = $data['parent_id'] ?? null;
        $page->page_builder_content = $data['page_builder_content'] ?? null;
        $page->use_page_builder = $data['use_page_builder'] ?? false;
        $page->show_title = array_key_exists('show_title', $data)
            ? (bool) $data['show_title']
            : true;
        $page->save();
        
        // Handle homepage and blog flags (must be done after save)
        if (!empty($data['is_homepage'])) {
            $page->setAsHomepage();
            $page->refresh();
        }
        
        if (!empty($data['is_blog'])) {
            $page->setAsBlog();
            $page->refresh();
        }
        
        return $page;
    }
    
    /**
     * Update a page
     *
     * @param Page $page
     * @param array $data
     * @return Page
     */
    public function updatePage(Page $page, array $data): Page
    {
        $page->title = $data['title'];
        $page->slug = $data['slug'];
        $page->content = $data['content'] ?? null;
        $page->featured_image = $data['featured_image'] ?? null;
        $page->template = $data['template'];
        $page->is_published = $data['is_published'] ?? false;
        $page->meta_title = $data['meta_title'] ?? null;
        $page->meta_description = $data['meta_description'] ?? null;
        $page->meta_keywords = $data['meta_keywords'] ?? null;
        $page->order = $data['order'] ?? 0;
        $page->page_builder_content = $data['page_builder_content'] ?? null;
        $page->use_page_builder = $data['use_page_builder'] ?? false;
        $page->show_title = array_key_exists('show_title', $data)
            ? (bool) $data['show_title']
            : true;
        
        // Only update parent if it's not self-referential
        if (!isset($data['parent_id']) || $data['parent_id'] != $page->id) {
            $page->parent_id = $data['parent_id'] ?? null;
        }
        
        $page->save();
        
        // Handle homepage flag (must be done after save)
        // Unset homepage if checkbox is not checked
        if (empty($data['is_homepage']) && $page->is_homepage) {
            $page->update(['is_homepage' => false]);
        } elseif (!empty($data['is_homepage']) && !$page->is_homepage) {
            $page->setAsHomepage();
            $page->refresh();
        }
        
        // Handle blog flag (must be done after save)
        // Unset blog if checkbox is not checked
        if (empty($data['is_blog']) && $page->is_blog) {
            $page->update(['is_blog' => false]);
        } elseif (!empty($data['is_blog']) && !$page->is_blog) {
            $page->setAsBlog();
            $page->refresh();
        }
        
        return $page;
    }
    
    /**
     * Delete a page
     *
     * @param Page $page
     * @return bool
     */
    public function deletePage(Page $page): bool
    {
        // Reset parent_id for children
        Page::where('parent_id', $page->id)
            ->update(['parent_id' => null]);
            
        return $page->delete();
    }
    
    /**
     * Find a page by ID
     *
     * @param int $id
     * @return Page|null
     */
    public function findById(int $id): ?Page
    {
        return Page::find($id);
    }
    
    /**
     * Find a page by slug
     *
     * @param string $slug
     * @return Page|null
     */
    public function findBySlug(string $slug): ?Page
    {
        return Page::where('slug', $slug)->first();
    }

    /**
     * Get published pages
     *
     * @return Collection
     */
    public function getPublished(): Collection
    {
        return Page::where('is_published', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Alias for findById
     */
    public function find(int $id): ?Page
    {
        return $this->findById($id);
    }
} 