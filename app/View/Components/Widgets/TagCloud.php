<?php

namespace App\View\Components\Widgets;

use App\Repositories\TagRepository;
use Illuminate\View\Component;

class TagCloud extends Component
{
    public $tags;
    public $title;
    public $limit;

    /**
     * Create a new component instance.
     */
    public function __construct(
        TagRepository $tagRepository,
        string $title = 'Tags',
        int $limit = 20
    ) {
        $this->title = $title;
        $this->limit = $limit;
        $this->tags = $tagRepository->getPopular($limit);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.widgets.tag-cloud');
    }

    /**
     * Calculate font size based on post count
     */
    public function getFontSize($tag): string
    {
        if (!isset($tag->posts_count) || $tag->posts_count == 0) {
            return 'text-xs';
        }

        $count = $tag->posts_count;
        $maxCount = $this->tags->max('posts_count') ?? 1;
        
        $ratio = $count / $maxCount;
        
        if ($ratio > 0.75) return 'text-lg font-semibold';
        if ($ratio > 0.5) return 'text-base font-medium';
        if ($ratio > 0.25) return 'text-sm';
        
        return 'text-xs';
    }
}

