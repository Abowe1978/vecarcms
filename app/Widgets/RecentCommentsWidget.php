<?php

namespace App\Widgets;

use App\Repositories\CommentRepository;
use Illuminate\Support\Str;

class RecentCommentsWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'Recent Comments';
    }

    public function getDescription(): string
    {
        return 'Display the latest approved comments from your site.';
    }

    public function getFormFields(): array
    {
        return [
            [
                'name' => 'count',
                'label' => 'Number of comments',
                'type' => 'number',
                'default' => 5,
                'min' => 1,
                'max' => 20,
            ],
            [
                'name' => 'show_excerpt',
                'label' => 'Show comment excerpt',
                'type' => 'checkbox',
                'default' => true,
            ],
            [
                'name' => 'excerpt_length',
                'label' => 'Excerpt length (characters)',
                'type' => 'number',
                'default' => 120,
                'min' => 30,
                'max' => 400,
            ],
            [
                'name' => 'show_post_title',
                'label' => 'Show related post/page title',
                'type' => 'checkbox',
                'default' => true,
            ],
        ];
    }

    public function render(): string
    {
        $count = (int) $this->getSetting('count', 5);
        $showExcerpt = (bool) $this->getSetting('show_excerpt', true);
        $excerptLength = (int) $this->getSetting('excerpt_length', 120);
        $showPostTitle = (bool) $this->getSetting('show_post_title', true);

        /** @var CommentRepository $commentRepository */
        $commentRepository = app(CommentRepository::class);
        $comments = $commentRepository->getRecent($count);

        if ($comments->isEmpty()) {
            return '';
        }

        $title = $this->getTitle() ?? $this->getName();

        $html = '<div class="widget widget-recent-comments">';

        if ($title) {
            $html .= '<h3 class="widget-title">' . e($title) . '</h3>';
        }

        $html .= '<ul class="list-unstyled mb-0">';

        foreach ($comments as $comment) {
            $author = $comment->author_display_name;
            $content = strip_tags($comment->content);
            $targetUrl = $this->resolveCommentLink($comment);

            $html .= '<li class="mb-3">';

            if ($showPostTitle && $comment->commentable) {
                $html .= '<div class="text-muted small mb-1">';
                $html .= '<i class="fas fa-comments me-1"></i>';
                $html .= 'On <strong>' . e($comment->commentable->title ?? $comment->commentable->name ?? 'Untitled') . '</strong>';
                $html .= '</div>';
            }

            $html .= '<div class="fw-semibold">' . e($author) . '</div>';

            if ($showExcerpt) {
                $excerpt = Str::limit($content, $excerptLength);
                if ($targetUrl) {
                    $html .= '<a href="' . e($targetUrl) . '" class="text-decoration-none text-muted d-block small">';
                    $html .= e($excerpt);
                    $html .= '</a>';
                } else {
                    $html .= '<p class="text-muted small mb-0">' . e($excerpt) . '</p>';
                }
            }

            $html .= '</li>';
        }

        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }

    protected function resolveCommentLink($comment): ?string
    {
        $commentable = $comment->commentable;

        if (!$commentable) {
            return null;
        }

        if (property_exists($commentable, 'slug') && $commentable->slug) {
            return url($commentable->slug) . '#comment-' . $comment->id;
        }

        if (method_exists($commentable, 'getUrl')) {
            return $commentable->getUrl() . '#comment-' . $comment->id;
        }

        return null;
    }
}


