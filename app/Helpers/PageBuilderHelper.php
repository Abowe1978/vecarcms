<?php

if (!function_exists('render_page_builder_content')) {
    /**
     * Render Page Builder content from JSON to HTML
     *
     * @param mixed $content JSON string or array
     * @return string
     */
    function render_page_builder_content($content): string
    {
        if (empty($content)) {
            return '';
        }

        // Parse JSON if string
        if (is_string($content)) {
            $content = json_decode($content, true);
        }

        if (!is_array($content)) {
            return '';
        }

        // Extract HTML and CSS from GrapesJS project data
        $html = $content['html'] ?? '';
        $css = $content['css'] ?? '';
        
        // If html is empty but we have pages/components structure, convert it
        if (empty($html) && isset($content['pages']) && is_array($content['pages'])) {
            $html = convertGrapesJSComponentsToHtml($content);
        }

        // Wrap CSS in style tags if present
        $output = '';
        if (!empty($css)) {
            $output .= '<style>' . $css . '</style>';
        }

        // Add HTML content
        $output .= $html;

        return $output;
    }
}

if (!function_exists('convertGrapesJSComponentsToHtml')) {
    /**
     * Convert GrapesJS components structure to HTML
     *
     * @param array $projectData
     * @return string
     */
    function convertGrapesJSComponentsToHtml(array $projectData): string
    {
        if (!isset($projectData['pages']) || !is_array($projectData['pages'])) {
            return '';
        }

        $html = '';
        
        foreach ($projectData['pages'] as $page) {
            if (isset($page['component'])) {
                $html .= renderComponent($page['component']);
            }
        }
        
        return $html;
    }
}

if (!function_exists('renderComponent')) {
    /**
     * Recursively render a GrapesJS component to HTML
     *
     * @param array $component
     * @return string
     */
    function renderComponent(array $component): string
    {
        $tagName = $component['tagName'] ?? 'div';
        $content = $component['content'] ?? '';
        $classes = isset($component['classes']) && is_array($component['classes']) 
            ? implode(' ', $component['classes']) 
            : ($component['attributes']['class'] ?? '');
        $attributes = $component['attributes'] ?? [];
        
        // Build attributes string
        $attrsStr = '';
        foreach ($attributes as $key => $value) {
            if ($key !== 'class') {
                $attrsStr .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
            }
        }
        
        if ($classes) {
            $attrsStr .= ' class="' . htmlspecialchars($classes) . '"';
        }
        
        // Start tag
        $html = "<{$tagName}{$attrsStr}>";
        
        // Add content
        $html .= $content;
        
        // Render children recursively
        if (isset($component['components']) && is_array($component['components'])) {
            foreach ($component['components'] as $child) {
                $html .= renderComponent($child);
            }
        }
        
        // Close tag (skip for void elements)
        $voidElements = ['img', 'br', 'hr', 'input', 'meta', 'link'];
        if (!in_array($tagName, $voidElements)) {
            $html .= "</{$tagName}>";
        }
        
        return $html;
    }
}

if (!function_exists('theme_setting')) {
    /**
     * Get theme setting value
     *
     * @param string $key Dot notation key (e.g. 'colors.primary')
     * @param mixed $default
     * @return mixed
     */
    function theme_setting(string $key, $default = null)
    {
        $theme = active_theme_object();
        
        if (!$theme || !isset($theme->settings)) {
            return $default;
        }

        $settings = is_string($theme->settings) 
            ? json_decode($theme->settings, true) 
            : $theme->settings;
        
        if (!is_array($settings)) {
            return $default;
        }
        
        // Support dot notation
        $keys = explode('.', $key);
        $value = $settings;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
}

