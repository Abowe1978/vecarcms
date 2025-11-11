<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LanguageSelector extends Component
{
    public array $languages;
    public string $currentLocale;

    public function __construct()
    {
        $this->languages = config('app.available_locales');
        $this->currentLocale = app()->getLocale();
    }

    public function render()
    {
        return view('admin.components.language-selector');
    }
} 