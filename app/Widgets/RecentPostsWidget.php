<?php

namespace App\Widgets;

use App\Repositories\PostRepository;

class RecentPostsWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'Recent Posts';
    }

    public function getDescription(): string
    {
        return 'Display a list of your most recent posts';
    }

    public function getFormFields(): array
    {
        return [
            [
                'name' => 'count',
                'label' => 'Number of posts to show',
                'type' => 'number',
                'default' => 5,
                'min' => 1,
                'max' => 20,
            ],
            [
                'name' => 'show_date',
                'label' => 'Show publish date',
                'type' => 'checkbox',
                'default' => true,
            ],
            [
                'name' => 'show_excerpt',
                'label' => 'Show excerpt',
                'type' => 'checkbox',
                'default' => false,
            ],
        ];
    }

    public function render(): string
    {
        $postRepository = app(PostRepository::class);
        $count = (int) $this->getSetting('count', 5);
        $showDate = (bool) $this->getSetting('show_date', true);
        $showExcerpt = (bool) $this->getSetting('show_excerpt', false);

        $posts = $postRepository->getRecent($count);

        if ($posts->isEmpty()) {
            return '';
        }

        $title = $this->getTitle() ?? $this->getName();

        $html = '<div class="widget widget-recent-posts">';
        
        if ($title) {
            $html .= '<h3 class="widget-title">' . e($title) . '</h3>';
        }

        $html .= '<ul class="recent-posts-list">';

        foreach ($posts as $post) {
            $html .= '<li class="recent-post-item">';
            $html .= '<a href="' . route('post.show', $post->slug) . '" class="recent-post-link">';
            $html .= '<span class="recent-post-title">' . e($post->title) . '</span>';
            $html .= '</a>';

            if ($showDate && $post->published_at) {
                $html .= '<span class="recent-post-date">' . $post->published_at->format('d M Y') . '</span>';
            }

            if ($showExcerpt && $post->excerpt) {
                $html .= '<p class="recent-post-excerpt">' . e(str_limit($post->excerpt, 100)) . '</p>';
            }

            $html .= '</li>';
        }

        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }
}

