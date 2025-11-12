<?php

namespace App\Widgets;

class TextWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'Text';
    }

    public function getDescription(): string
    {
        return 'Display a block of text with optional HTML.';
    }

    public function getFormFields(): array
    {
        return [
            [
                'name' => 'content',
                'label' => 'Content',
                'type' => 'textarea',
                'rows' => 6,
                'default' => '',
                'placeholder' => "Write your text here...",
            ],
            [
                'name' => 'render_html',
                'label' => 'Render content as HTML',
                'type' => 'checkbox',
                'default' => false,
                'help' => 'Enable if the block contains HTML that should not be escaped.',
            ],
        ];
    }

    public function render(): string
    {
        $content = (string) $this->getSetting('content', '');

        if (trim($content) === '') {
            return '';
        }

        $title = $this->getTitle();
        $allowHtml = (bool) $this->getSetting('render_html', false);

        $html = '<div class="widget widget-text">';

        if ($title) {
            $html .= '<h3 class="widget-title">' . e($title) . '</h3>';
        }

        $html .= '<div class="widget-text-content">';
        $html .= $allowHtml ? $content : nl2br(e($content));
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}


