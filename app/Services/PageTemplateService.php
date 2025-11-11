<?php

namespace App\Services;

class PageTemplateService
{
    /**
     * Get available templates for pages
     * 
     * @return array
     */
    public function getAvailableTemplates(): array
    {
        return [
            'default' => 'Default',
            'full-width' => 'Full Width',
            'sidebar-left' => 'Sidebar Left',
            'sidebar-right' => 'Sidebar Right',
            'contact' => 'Contact',
            'landing' => 'Landing Page',
        ];
    }
    
    /**
     * Check if a template is valid
     * 
     * @param string $template
     * @return bool
     */
    public function isValidTemplate(string $template): bool
    {
        return array_key_exists($template, $this->getAvailableTemplates());
    }
    
    /**
     * Get template name
     * 
     * @param string $key
     * @return string|null
     */
    public function getTemplateName(string $key): ?string
    {
        $templates = $this->getAvailableTemplates();
        return $templates[$key] ?? null;
    }
} 