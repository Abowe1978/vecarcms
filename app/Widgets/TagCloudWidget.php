<?php

namespace App\Widgets;

use App\Repositories\TagRepository;

class TagCloudWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'Tag Cloud';
    }

    public function getDescription(): string
    {
        return 'Display your most used tags in a cloud';
    }

    public function getFormFields(): array
    {
        return [
            [
                'name' => 'max_tags',
                'label' => 'Maximum tags to show',
                'type' => 'number',
                'default' => 20,
                'min' => 5,
                'max' => 50,
            ],
        ];
    }

    public function render(): string
    {
        $tagRepository = app(TagRepository::class);
        $maxTags = (int) $this->getSetting('max_tags', 20);

        $tags = $tagRepository->all()->take($maxTags);

        if ($tags->isEmpty()) {
            return '';
        }

        $title = $this->getTitle() ?? $this->getName();

        $html = '<div class="widget widget-tag-cloud">';
        
        if ($title) {
            $html .= '<h3 class="widget-title">' . e($title) . '</h3>';
        }

        $html .= '<div class="tag-cloud">';

        foreach ($tags as $tag) {
            $html .= '<a href="' . route('blog.tag', $tag->slug) . '" class="tag-cloud-link">';
            $html .= e($tag->name);
            $html .= '</a> ';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}

