<?php

namespace App\Widgets;

use App\Repositories\CategoryRepository;

class CategoriesWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'Categories';
    }

    public function getDescription(): string
    {
        return 'Display a list of post categories';
    }

    public function getFormFields(): array
    {
        return [
            [
                'name' => 'show_count',
                'label' => 'Show post count',
                'type' => 'checkbox',
                'default' => true,
            ],
            [
                'name' => 'hierarchical',
                'label' => 'Show hierarchy',
                'type' => 'checkbox',
                'default' => false,
            ],
        ];
    }

    public function render(): string
    {
        $categoryRepository = app(CategoryRepository::class);
        $showCount = (bool) $this->getSetting('show_count', true);

        $categories = $categoryRepository->all();

        if ($categories->isEmpty()) {
            return '';
        }

        $title = $this->getTitle() ?? $this->getName();

        $html = '<div class="widget widget-categories">';
        
        if ($title) {
            $html .= '<h3 class="widget-title">' . e($title) . '</h3>';
        }

        $html .= '<ul class="categories-list">';

        foreach ($categories as $category) {
            $html .= '<li class="category-item">';
            $html .= '<a href="' . route('blog.category', $category->slug) . '">';
            $html .= e($category->name);
            
            if ($showCount) {
                $html .= ' <span class="category-count">(' . $category->posts_count . ')</span>';
            }
            
            $html .= '</a>';
            $html .= '</li>';
        }

        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }
}

