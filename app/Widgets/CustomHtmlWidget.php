<?php

namespace App\Widgets;

class CustomHtmlWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'Custom HTML';
    }

    public function getDescription(): string
    {
        return 'Add custom HTML code';
    }

    public function getFormFields(): array
    {
        return [
            [
                'name' => 'content',
                'label' => 'Content',
                'type' => 'textarea',
                'default' => '',
                'rows' => 10,
            ],
        ];
    }

    public function render(): string
    {
        $content = $this->getSetting('content', '');

        if (empty($content)) {
            return '';
        }

        $title = $this->getTitle();

        $html = '<div class="widget widget-custom-html">';
        
        if ($title) {
            $html .= '<h3 class="widget-title">' . e($title) . '</h3>';
        }

        $html .= '<div class="widget-content">';
        $html .= $content; // Raw HTML (admin controls this)
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}

