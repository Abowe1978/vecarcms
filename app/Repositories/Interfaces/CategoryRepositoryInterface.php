<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    /**
     * Get all categories ordered by name with pagination
     *
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getAllCategories(int $perPage = 10): LengthAwarePaginator;
    
    /**
     * Find category by ID
     *
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category;
    
    /**
     * Create a new category
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category;
    
    /**
     * Check if category name exists
     *
     * @param string $name
     * @return bool
     */
    public function nameExists(string $name): bool;

    /**
     * Update a category
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function updateCategory(Category $category, array $data): Category;

    /**
     * Delete a category
     *
     * @param Category $category
     * @return bool
     */
    public function deleteCategory(Category $category): bool;
} 