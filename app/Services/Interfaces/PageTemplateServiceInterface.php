<?php

namespace App\Services\Interfaces;

use Illuminate\Support\Collection;

interface PageTemplateServiceInterface
{
    /**
     * Get all available page templates
     *
     * @return Collection
     */
    public function getAvailableTemplates(): Collection;

    /**
     * Validate a template
     *
     * @param string $template
     * @return bool
     */
    public function validateTemplate(string $template): bool;

    /**
     * Get template configuration
     *
     * @param string $template
     * @return array
     */
    public function getTemplateConfig(string $template): array;
} 