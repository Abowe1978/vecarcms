<?php

namespace App\Repositories\Interfaces;

use App\Models\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PageRepositoryInterface
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
    public function getPaginatedPages(?string $search, string $sortField, string $sortDirection, int $perPage): LengthAwarePaginator;
    
    /**
     * Get all pages
     *
     * @return Collection
     */
    public function getAllPages(): Collection;
    
    /**
     * Get all parent pages (root level)
     *
     * @return Collection
     */
    public function getParentPages(): Collection;
    
    /**
     * Get all potential parent pages (excluding self and children)
     *
     * @param Page $page
     * @return Collection
     */
    public function getPotentialParentPages(Page $page): Collection;
    
    /**
     * Create a new page
     *
     * @param array $data
     * @return Page
     */
    public function createPage(array $data): Page;
    
    /**
     * Update a page
     *
     * @param Page $page
     * @param array $data
     * @return Page
     */
    public function updatePage(Page $page, array $data): Page;
    
    /**
     * Delete a page
     *
     * @param Page $page
     * @return bool
     */
    public function deletePage(Page $page): bool;
    
    /**
     * Find a page by ID
     *
     * @param int $id
     * @return Page|null
     */
    public function findById(int $id): ?Page;
    
    /**
     * Find a page by slug
     *
     * @param string $slug
     * @return Page|null
     */
    public function findBySlug(string $slug): ?Page;
} 