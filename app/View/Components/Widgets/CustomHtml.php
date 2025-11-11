<?php

namespace App\View\Components\Widgets;

use Illuminate\View\Component;

class CustomHtml extends Component
{
    public $title;
    public $content;
    public $showTitle;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $content = '',
        string $title = '',
        bool $showTitle = true
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->showTitle = $showTitle;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.widgets.custom-html');
    }
}

