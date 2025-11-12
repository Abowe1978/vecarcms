<?php

namespace App\Widgets;

class ImageWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'Image';
    }

    public function getDescription(): string
    {
        return 'Display a single image with optional caption and link.';
    }

    public function getFormFields(): array
    {
        return [
            [
                'name' => 'image_url',
                'label' => 'Image URL',
                'type' => 'text',
                'default' => '',
                'placeholder' => 'https://example.com/image.jpg',
                'help' => 'Paste a public URL or upload media and copy the link.',
            ],
            [
                'name' => 'alt_text',
                'label' => 'Alternative Text',
                'type' => 'text',
                'default' => '',
                'placeholder' => 'Describe the image for accessibility',
            ],
            [
                'name' => 'link_url',
                'label' => 'Link URL (optional)',
                'type' => 'text',
                'default' => '',
                'placeholder' => 'https://example.com',
            ],
            [
                'name' => 'open_in_new_tab',
                'label' => 'Open link in new tab',
                'type' => 'checkbox',
                'default' => false,
            ],
            [
                'name' => 'caption',
                'label' => 'Caption',
                'type' => 'text',
                'default' => '',
                'placeholder' => 'Optional caption below the image',
            ],
            [
                'name' => 'alignment',
                'label' => 'Alignment',
                'type' => 'select',
                'default' => 'center',
                'options' => [
                    ['value' => 'left', 'label' => 'Left'],
                    ['value' => 'center', 'label' => 'Center'],
                    ['value' => 'right', 'label' => 'Right'],
                ],
            ],
            [
                'name' => 'max_width',
                'label' => 'Max Width (px)',
                'type' => 'number',
                'default' => null,
                'min' => 50,
                'max' => 2000,
                'help' => 'Optional maximum width. Leave empty for natural width.',
            ],
        ];
    }

    public function render(): string
    {
        $imageUrl = (string) $this->getSetting('image_url', '');

        if (trim($imageUrl) === '') {
            return '';
        }

        $title = $this->getTitle();
        $altText = (string) $this->getSetting('alt_text', '');
        $linkUrl = (string) $this->getSetting('link_url', '');
        $caption = (string) $this->getSetting('caption', '');
        $alignment = (string) $this->getSetting('alignment', 'center');
        $maxWidth = $this->getSetting('max_width');
        $openInNewTab = (bool) $this->getSetting('open_in_new_tab', false);

        $alignmentClass = match ($alignment) {
            'left' => 'text-start',
            'right' => 'text-end',
            default => 'text-center',
        };

        $style = $maxWidth ? 'style="max-width:' . (int) $maxWidth . 'px;"' : '';

        $html = '<div class="widget widget-image ' . $alignmentClass . '">';

        if ($title) {
            $html .= '<h3 class="widget-title">' . e($title) . '</h3>';
        }

        $imageTag = '<img src="' . e($imageUrl) . '" alt="' . e($altText) . '" class="img-fluid" ' . $style . '>';

        if ($linkUrl) {
            $target = $openInNewTab ? ' target="_blank" rel="noopener noreferrer"' : '';
            $imageTag = '<a href="' . e($linkUrl) . '"' . $target . '>' . $imageTag . '</a>';
        }

        $html .= $imageTag;

        if ($caption) {
            $html .= '<p class="widget-caption text-muted small mt-2">' . e($caption) . '</p>';
        }

        $html .= '</div>';

        return $html;
    }
}


