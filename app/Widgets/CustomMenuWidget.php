<?php

namespace App\Widgets;

class CustomMenuWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'Custom Menu';
    }

    public function getDescription(): string
    {
        return 'Render one of your registered menu locations.';
    }

    public function getFormFields(): array
    {
        $locations = collect(menu_locations())
            ->map(fn ($label, $value) => ['value' => $value, 'label' => $label])
            ->values()
            ->all();

        return [
            [
                'name' => 'menu_location',
                'label' => 'Menu Location',
                'type' => 'select',
                'options' => $locations,
                'default' => 'footer',
            ],
            [
                'name' => 'menu_class',
                'label' => 'Menu Class',
                'type' => 'text',
                'default' => '',
                'help' => 'CSS classes applied to the list element.',
            ],
            [
                'name' => 'framework',
                'label' => 'Menu Style',
                'type' => 'select',
                'default' => 'simple',
                'options' => [
                    ['value' => 'simple', 'label' => 'Simple list'],
                ],
            ],
            [
                'name' => 'fallback_message',
                'label' => 'Fallback Message',
                'type' => 'text',
                'default' => '',
                'placeholder' => 'Optional message when the menu is empty.',
            ],
        ];
    }

    public function render(): string
    {
        $location = (string) $this->getSetting('menu_location', '');

        if ($location === '') {
            return '';
        }

        $title = $this->getTitle() ?? $this->getName();
        $class = trim((string) $this->getSetting('menu_class', ''));
        $location = trim($location);

        if ($class === '' && str_starts_with($location, 'footer')) {
            $class = 'list-unstyled footer-nav';
        } elseif ($class === '') {
            $class = 'list-unstyled';
        }
        $fallback = (string) $this->getSetting('fallback_message', '');

        $menu = get_menu($location);

        if (!$menu || $menu->activeItems->isEmpty()) {
            if ($fallback === '') {
                return '';
            }

            return '<div class="widget widget-menu"><p class="text-muted small">' . e($fallback) . '</p></div>';
        }

        $items = $menu->activeItems
            ->whereNull('parent_id')
            ->sortBy('order');

        $html = '<div class="widget widget-menu">';

        if ($title) {
            $html .= '<h3 class="widget-title">' . e($title) . '</h3>';
        }

        $html .= $this->renderItems($items, $class);
        $html .= '</div>';

        return $html;
    }

    protected function renderItems($items, string $class): string
    {
        if ($items->isEmpty()) {
            return '';
        }

        $html = '<ul class="' . e($class) . '">';

        foreach ($items as $item) {
            if (!$item->isVisible()) {
                continue;
            }

            $url = $item->computed_url ?: '#';
            $target = $item->target ?? '_self';

            $html .= '<li>';
            $html .= '<a href="' . e($url) . '" target="' . e($target) . '" class="text-muted text-decoration-none">';
            $html .= e($item->title);
            $html .= '</a>';

            if ($item->activeChildren->isNotEmpty()) {
                $html .= $this->renderItems(
                    $item->activeChildren->sortBy('order'),
                    $class
                );
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }
}


