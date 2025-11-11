<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ButtonLink extends Component
{
    /**
     * The button link href.
     *
     * @var string
     */
    public $href;

    /**
     * The button color.
     *
     * @var string
     */
    public $color;

    /**
     * Create a new component instance.
     *
     * @param  string  $href
     * @param  string  $color
     * @return void
     */
    public function __construct($href, $color = 'primary')
    {
        $this->href = $href;
        $this->color = $color;
    }

    /**
     * Get the color classes for the button.
     *
     * @return string
     */
    public function colorClasses()
    {
        return [
            'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
            'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
            'success' => 'bg-green-600 hover:bg-green-700 text-white',
            'danger' => 'bg-red-600 hover:bg-red-700 text-white',
            'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white',
            'info' => 'bg-sky-500 hover:bg-sky-600 text-white',
        ][$this->color] ?? 'bg-blue-600 hover:bg-blue-700 text-white';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button-link');
    }
} 