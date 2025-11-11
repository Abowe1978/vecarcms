<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\Interfaces\CategoryServiceInterface;

class CategorySelector extends Component
{
    public $selectedCategories = [];
    public $showNewCategoryInput = false;
    public $newCategoryName = '';
    public $message = '';
    public $messageType = '';
    
    /**
     * @var CategoryServiceInterface
     */
    protected $categoryService;
    
    /**
     * Constructor with dependency injection
     */
    public function boot(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function mount($selectedCategories = [])
    {
        $this->selectedCategories = $selectedCategories;
    }

    public function toggleNewCategoryInput()
    {
        $this->showNewCategoryInput = !$this->showNewCategoryInput;
        $this->newCategoryName = '';
        $this->message = '';
    }

    public function addNewCategory()
    {
        // Validate the category name
        $errors = $this->categoryService->validateCategoryName($this->newCategoryName);
        
        if (!empty($errors)) {
            // Show the first error
            $this->message = reset($errors);
            $this->messageType = 'error';
            return;
        }

        // Create the category
        $category = $this->categoryService->createCategory(['name' => $this->newCategoryName]);

        // Add to selected categories
        $this->selectedCategories[] = $category->id;
        
        // Reset form and show success message
        $this->newCategoryName = '';
        $this->showNewCategoryInput = false;
        $this->message = __('admin.categories.success.quick_created');
        $this->messageType = 'success';

        // Dispatch event
        $this->dispatch('categoryAdded');
    }

    public function toggleCategory($categoryId)
    {
        if (in_array($categoryId, $this->selectedCategories)) {
            $this->selectedCategories = array_diff($this->selectedCategories, [$categoryId]);
        } else {
            $this->selectedCategories[] = $categoryId;
        }
    }

    public function render()
    {
        return view('livewire.category-selector', [
            'categories' => $this->categoryService->getAllCategories()
        ]);
    }
}
