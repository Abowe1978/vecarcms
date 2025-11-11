<?php

namespace App\Services;

use App\Models\PageBuilderTemplate;
use Illuminate\Database\Eloquent\Collection;

class PageBuilderService
{
    /**
     * Get all templates by type
     */
    public function getTemplates(string $type = 'page'): Collection
    {
        return PageBuilderTemplate::where('type', $type)
            ->where('is_active', true)
            ->orderBy('usage_count', 'desc')
            ->get();
    }

    /**
     * Create template from content
     */
    public function createTemplate(array $data): PageBuilderTemplate
    {
        return PageBuilderTemplate::create([
            ...$data,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Apply template to model
     */
    public function applyTemplate(PageBuilderTemplate $template, $model): void
    {
        $template->incrementUsage();
        
        $model->update([
            'page_builder_content' => $template->content,
            'use_page_builder' => true,
        ]);
    }

    /**
     * Save page builder content
     */
    public function saveContent($model, array $content): bool
    {
        return $model->update([
            'page_builder_content' => $content,
            'use_page_builder' => true,
        ]);
    }

    /**
     * Render page builder content to HTML
     */
    public function render(array $content): string
    {
        $html = '';
        
        foreach ($content as $section) {
            $html .= $this->renderSection($section);
        }
        
        return $html;
    }

    protected function renderSection(array $section): string
    {
        $html = '<section class="pb-section">';
        
        foreach ($section['columns'] ?? [] as $column) {
            $html .= $this->renderColumn($column);
        }
        
        $html .= '</section>';
        
        return $html;
    }

    protected function renderColumn(array $column): string
    {
        $html = '<div class="pb-column">';
        
        foreach ($column['elements'] ?? [] as $element) {
            $html .= $this->renderElement($element);
        }
        
        $html .= '</div>';
        
        return $html;
    }

    protected function renderElement(array $element): string
    {
        return match($element['type']) {
            'heading' => "<h{$element['level']}>{$element['content']}</h{$element['level']}>",
            'text' => "<p>{$element['content']}</p>",
            'image' => "<img src='{$element['src']}' alt='{$element['alt']}'>",
            'button' => "<a href='{$element['url']}' class='btn'>{$element['text']}</a>",
            default => '',
        };
    }
}

