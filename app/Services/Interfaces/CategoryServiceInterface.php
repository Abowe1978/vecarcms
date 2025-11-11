<?php

namespace App\Services\Interfaces;

use App\Models\Category;

interface CategoryServiceInterface
{
    /**
     * Get all categories
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllCategories();

    /**
     * Validate category name
     *
     * @param string $name
     * @return array
     */
    public function validateCategoryName(string $name): array;

    /**
     * Create a new category
     *
     * @param array $data Validated data
     * @return Category The created category
     */
    public function createCategory(array $data);

    /**
     * Update a category
     *
     * @param Category $category The category to update
     * @param array $data Validated data
     * @return Category The updated category
     */
    public function updateCategory(Category $category, array $data);

    /**
     * Delete a category
     *
     * @param Category $category The category to delete
     * @return bool Whether the category was deleted
     */
    public function deleteCategory(Category $category);
} 