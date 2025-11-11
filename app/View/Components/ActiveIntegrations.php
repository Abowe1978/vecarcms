<?php

namespace App\View\Components;

use App\Models\Integration;
use Illuminate\View\Component;

class ActiveIntegrations extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('admin.components.active-integrations');
    }
} 