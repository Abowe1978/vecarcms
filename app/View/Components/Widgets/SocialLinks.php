<?php

namespace App\View\Components\Widgets;

use Illuminate\View\Component;

class SocialLinks extends Component
{
    public $title;
    public $links;
    public $showLabels;
    public $size;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $title = 'Follow Us',
        bool $showLabels = false,
        string $size = 'md'
    ) {
        $this->title = $title;
        $this->showLabels = $showLabels;
        $this->size = $size;
        
        // Get social links from settings
        $this->links = $this->getSocialLinks();
    }

    /**
     * Get social links from settings
     */
    protected function getSocialLinks(): array
    {
        $links = [];
        
        $socials = [
            'facebook' => ['icon' => 'fab fa-facebook-f', 'label' => 'Facebook'],
            'twitter' => ['icon' => 'fab fa-twitter', 'label' => 'Twitter'],
            'instagram' => ['icon' => 'fab fa-instagram', 'label' => 'Instagram'],
            'linkedin' => ['icon' => 'fab fa-linkedin-in', 'label' => 'LinkedIn'],
            'youtube' => ['icon' => 'fab fa-youtube', 'label' => 'YouTube'],
            'github' => ['icon' => 'fab fa-github', 'label' => 'GitHub'],
        ];

        foreach ($socials as $key => $data) {
            $url = settings("social_{$key}");
            if ($url) {
                $links[] = [
                    'url' => $url,
                    'icon' => $data['icon'],
                    'label' => $data['label'],
                    'name' => $key
                ];
            }
        }

        return $links;
    }

    /**
     * Get icon size class
     */
    public function getIconSize(): string
    {
        return match($this->size) {
            'sm' => 'w-8 h-8 text-sm',
            'lg' => 'w-12 h-12 text-xl',
            default => 'w-10 h-10 text-base',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.widgets.social-links');
    }
}

