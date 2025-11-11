<?php

namespace App\View\Components\Widgets;

use App\Repositories\CategoryRepository;
use Illuminate\View\Component;

class CategoriesList extends Component
{
    public $categories;
    public $title;
    public $showCount;
    public $showEmpty;

    /**
     * Create a new component instance.
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        string $title = 'Categories',
        bool $showCount = true,
        bool $showEmpty = false
    ) {
        $this->title = $title;
        $this->showCount = $showCount;
        $this->showEmpty = $showEmpty;
        
        $query = $categoryRepository;
        
        if ($showCount) {
            $this->categories = $query->allWithCount();
        } else {
            $this->categories = $query->all();
        }
        
        // Filter empty categories if needed
        if (!$showEmpty && $showCount) {
            $this->categories = $this->categories->filter(fn($cat) => $cat->posts_count > 0);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.widgets.categories-list');
    }
}

