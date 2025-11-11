<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService implements CategoryServiceInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;
    
    /**
     * Constructor
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    
    /**
     * Get all categories
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllCategories()
    {
        return $this->categoryRepository->getAllCategories();
    }
    
    /**
     * Validate category name
     *
     * @param string $name
     * @return array
     */
    public function validateCategoryName(string $name): array
    {
        $errors = [];
        
        if (empty($name)) {
            $errors['required'] = __('admin.categories.validation.name_required');
        } elseif (strlen($name) < 2) {
            $errors['min'] = __('admin.categories.validation.name_min');
        } elseif (strlen($name) > 255) {
            $errors['max'] = __('admin.categories.validation.name_max');
        } elseif ($this->categoryRepository->nameExists($name)) {
            $errors['unique'] = __('admin.categories.validation.name_unique');
        }
        
        return $errors;
    }
    
    /**
     * Create a new category
     *
     * @param array $data Validated data
     * @return Category The created category
     */
    public function createCategory(array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Generate a random color if not provided
        if (empty($data['color'])) {
            $data['color'] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        }
        
        return $this->categoryRepository->createCategory($data);
    }

    /**
     * Update a category
     *
     * @param Category $category The category to update
     * @param array $data Validated data
     * @return Category The updated category
     */
    public function updateCategory(Category $category, array $data)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $this->categoryRepository->updateCategory($category, $data);
    }

    /**
     * Delete a category
     *
     * @param Category $category The category to delete
     * @return bool Whether the category was deleted
     */
    public function deleteCategory(Category $category)
    {
        return $this->categoryRepository->deleteCategory($category);
    }
} 