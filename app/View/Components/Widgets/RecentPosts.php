<?php

namespace App\View\Components\Widgets;

use App\Repositories\PostRepository;
use Illuminate\View\Component;

class RecentPosts extends Component
{
    public $posts;
    public $title;
    public $limit;
    public $showThumbnail;
    public $showDate;

    /**
     * Create a new component instance.
     */
    public function __construct(
        PostRepository $postRepository,
        string $title = 'Recent Posts',
        int $limit = 5,
        bool $showThumbnail = true,
        bool $showDate = true
    ) {
        $this->title = $title;
        $this->limit = $limit;
        $this->showThumbnail = $showThumbnail;
        $this->showDate = $showDate;
        $this->posts = $postRepository->getRecent($limit);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.widgets.recent-posts');
    }
}

