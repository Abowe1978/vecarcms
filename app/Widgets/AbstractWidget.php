<?php

namespace App\Widgets;

use App\Models\Widget;

abstract class AbstractWidget
{
    protected Widget $widget;

    public function __construct(Widget $widget)
    {
        $this->widget = $widget;
    }

    /**
     * Get widget name (for display in admin)
     */
    abstract public function getName(): string;

    /**
     * Get widget description
     */
    abstract public function getDescription(): string;

    /**
     * Get widget form fields (for admin configuration)
     */
    abstract public function getFormFields(): array;

    /**
     * Render widget HTML
     */
    abstract public function render(): string;

    /**
     * Get widget setting
     */
    protected function getSetting(string $key, $default = null)
    {
        return $this->widget->getSetting($key, $default);
    }

    /**
     * Get widget title
     */
    protected function getTitle(): ?string
    {
        return $this->widget->title;
    }

    /**
     * Check if widget should be displayed
     */
    public function shouldDisplay(): bool
    {
        return $this->widget->isVisible();
    }
}

