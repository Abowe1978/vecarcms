<?php

namespace App\View\Components\Widgets;

use Illuminate\View\Component;

class Newsletter extends Component
{
    public $title;
    public $description;
    public $buttonText;
    public $placeholder;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $title = 'Newsletter',
        string $description = 'Subscribe to our newsletter for the latest updates',
        string $buttonText = 'Subscribe',
        string $placeholder = 'Your email address'
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->buttonText = $buttonText;
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.widgets.newsletter');
    }
}

