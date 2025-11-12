<?php

namespace App\Widgets;

use Illuminate\Support\Arr;

class SocialLinksWidget extends AbstractWidget
{
    protected array $networks = [
        'facebook' => ['label' => 'Facebook', 'icon' => 'ri-facebook-fill'],
        'twitter' => ['label' => 'X (Twitter)', 'icon' => 'ri-twitter-fill'],
        'instagram' => ['label' => 'Instagram', 'icon' => 'ri-instagram-line'],
        'linkedin' => ['label' => 'LinkedIn', 'icon' => 'ri-linkedin-fill'],
        'youtube' => ['label' => 'YouTube', 'icon' => 'ri-youtube-fill'],
        'tiktok' => ['label' => 'TikTok', 'icon' => 'ri-tiktok-fill'],
        'github' => ['label' => 'GitHub', 'icon' => 'ri-github-fill'],
    ];

    public function getName(): string
    {
        return 'Social Links';
    }

    public function getDescription(): string
    {
        return 'Display a list of social network icons with optional labels.';
    }

    public function getFormFields(): array
    {
        $fields = [
            [
                'name' => 'use_global_links',
                'label' => 'Use global social URLs from settings',
                'type' => 'checkbox',
                'default' => true,
            ],
            [
                'name' => 'show_labels',
                'label' => 'Show labels below icons',
                'type' => 'checkbox',
                'default' => false,
            ],
            [
                'name' => 'icon_shape',
                'label' => 'Icon Shape',
                'type' => 'select',
                'default' => 'circle',
                'options' => [
                    ['value' => 'circle', 'label' => 'Filled circle'],
                    ['value' => 'outline', 'label' => 'Outlined circle'],
                    ['value' => 'plain', 'label' => 'Plain icon'],
                ],
            ],
            [
                'name' => 'icon_size',
                'label' => 'Icon Size',
                'type' => 'select',
                'default' => 'md',
                'options' => [
                    ['value' => 'sm', 'label' => 'Small'],
                    ['value' => 'md', 'label' => 'Medium'],
                    ['value' => 'lg', 'label' => 'Large'],
                ],
            ],
        ];

        foreach ($this->networks as $key => $network) {
            $fields[] = [
                'name' => "{$key}_url",
                'label' => "{$network['label']} URL",
                'type' => 'text',
                'default' => '',
                'placeholder' => "https://{$key}.com/yourprofile",
                'help' => 'Leave empty to hide.',
            ];
        }

        return $fields;
    }

    public function render(): string
    {
        $useGlobal = (bool) $this->getSetting('use_global_links', true);
        $showLabels = (bool) $this->getSetting('show_labels', false);
        $iconShape = (string) $this->getSetting('icon_shape', 'circle');
        $iconSize = (string) $this->getSetting('icon_size', 'md');

        $links = collect($this->networks)->map(function ($config, $key) use ($useGlobal) {
            $global = settings("social_{$key}");
            $local = $this->getSetting("{$key}_url", '');

            $url = $useGlobal ? ($global ?: $local) : $local;

            if (!$url) {
                return null;
            }

            return [
                'label' => $config['label'],
                'icon' => $config['icon'],
                'url' => $url,
            ];
        })->filter()->values();

        if ($links->isEmpty()) {
            return '';
        }

        $title = $this->getTitle() ?? $this->getName();

        $shapeClasses = [
            'circle' => 'bg-white text-primary border border-white border-opacity-10',
            'outline' => 'border border-white border-opacity-40 text-white bg-transparent',
            'plain' => 'text-white bg-transparent',
        ];

        $sizeClasses = [
            'sm' => 'w-8 h-8 text-sm',
            'md' => 'w-10 h-10 text-base',
            'lg' => 'w-12 h-12 text-lg',
        ];

        $iconClass = $shapeClasses[$iconShape] ?? $shapeClasses['circle'];
        $sizeClass = $sizeClasses[$iconSize] ?? $sizeClasses['md'];

        $html = '<div class="widget widget-social-links">';

        if ($title) {
            $html .= '<h3 class="widget-title">' . e($title) . '</h3>';
        }

        $html .= '<div class="d-flex flex-wrap gap-2 widget-social-links__icons">';

        foreach ($links as $link) {
            $html .= '<a href="' . e($link['url']) . '" target="_blank" rel="noopener noreferrer"';
            $html .= ' class="d-inline-flex align-items-center justify-content-center rounded-circle ' . $iconClass . ' ' . $sizeClass . ' transition-all widget-social-icon"';
            $html .= ' style="min-width:2.75rem;min-height:2.75rem;"';
            $html .= ' title="' . e($link['label']) . '">';
            $html .= '<i class="' . e($link['icon']) . '"></i>';
            $html .= '</a>';
        }

        $html .= '</div>';

        if ($showLabels) {
            $html .= '<ul class="list-unstyled mt-3 mb-0">';
            foreach ($links as $link) {
            $html .= '<li class="mb-1">';
                $html .= '<a href="' . e($link['url']) . '" target="_blank" rel="noopener noreferrer" class="d-inline-flex align-items-center text-muted text-sm">';
                $html .= '<i class="' . e($link['icon']) . ' me-2"></i>';
                $html .= '<span>' . e($link['label']) . '</span>';
                $html .= '</a>';
                $html .= '</li>';
            }
            $html .= '</ul>';
        }

        $html .= '</div>';

        return $html;
    }
}


