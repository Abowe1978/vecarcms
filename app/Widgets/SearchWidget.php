<?php

namespace App\Widgets;

class SearchWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'Search';
    }

    public function getDescription(): string
    {
        return 'A search form for your site';
    }

    public function getFormFields(): array
    {
        return [
            [
                'name' => 'placeholder',
                'label' => 'Placeholder text',
                'type' => 'text',
                'default' => 'Search...',
            ],
        ];
    }

    public function render(): string
    {
        $title = $this->getTitle() ?? $this->getName();
        $placeholder = $this->getSetting('placeholder', 'Search...');

        $html = '<div class="widget widget-search">';
        
        if ($title) {
            $html .= '<h3 class="widget-title">' . e($title) . '</h3>';
        }

        $html .= '<form role="search" method="get" action="' . route('search') . '" class="search-form">';
        $html .= '<div class="search-form-group">';
        $html .= '<input type="search" name="q" placeholder="' . e($placeholder) . '" class="search-input" value="' . e(request('q')) . '">';
        $html .= '<button type="submit" class="search-submit">Search</button>';
        $html .= '</div>';
        $html .= '</form>';
        $html .= '</div>';

        return $html;
    }
}

