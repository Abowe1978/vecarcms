<?php

namespace App\View\Components\Widgets;

use Illuminate\View\Component;

class SearchBox extends Component
{
    public $title;
    public $placeholder;
    public $showButton;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $title = 'Search',
        string $placeholder = 'Search...',
        bool $showButton = true
    ) {
        $this->title = $title;
        $this->placeholder = $placeholder;
        $this->showButton = $showButton;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.widgets.search-box');
    }
}

