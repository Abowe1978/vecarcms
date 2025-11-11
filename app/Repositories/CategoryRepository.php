<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all categories ordered by name with pagination
     *
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getAllCategories(int $perPage = 10): LengthAwarePaginator
    {
        return Category::withCount('posts')
            ->orderBy('name')
            ->paginate($perPage);
    }
    
    /**
     * Find category by ID
     *
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }
    
    /**
     * Create a new category
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }
    
    /**
     * Check if category name exists
     *
     * @param string $name
     * @return bool
     */
    public function nameExists(string $name): bool
    {
        return Category::where('name', $name)->exists();
    }

    /**
     * Update a category
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function updateCategory(Category $category, array $data): Category
    {
        $category->update($data);
        return $category->fresh();
    }

    /**
     * Delete a category
     *
     * @param Category $category
     * @return bool
     */
    public function deleteCategory(Category $category): bool
    {
        return $category->delete();
    }

    /**
     * Get all categories (no pagination)
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Category::orderBy('name')->get();
    }

    /**
     * Get all categories with post count
     *
     * @return Collection
     */
    public function allWithCount(): Collection
    {
        return Category::withCount('posts')->orderBy('name')->get();
    }

    /**
     * Alias for findById
     */
    public function find(int $id): ?Category
    {
        return $this->findById($id);
    }
} 