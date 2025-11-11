<?php

use App\Services\TaxonomyRegistryService;

if (!function_exists('register_taxonomy')) {
    /**
     * Register a custom taxonomy (WordPress-like)
     *
     * @param string $taxonomy Taxonomy key
     * @param array $args Configuration
     * @return bool
     */
    function register_taxonomy(string $taxonomy, array $args = []): bool
    {
        $registry = app(TaxonomyRegistryService::class);
        return $registry->register($taxonomy, $args);
    }
}

if (!function_exists('get_taxonomy')) {
    /**
     * Get a registered taxonomy configuration
     *
     * @param string $taxonomy
     * @return array|null
     */
    function get_taxonomy(string $taxonomy): ?array
    {
        $registry = app(TaxonomyRegistryService::class);
        return $registry->get($taxonomy);
    }
}

if (!function_exists('get_taxonomies')) {
    /**
     * Get all registered taxonomies
     *
     * @return array
     */
    function get_taxonomies(): array
    {
        $registry = app(TaxonomyRegistryService::class);
        return $registry->getAll();
    }
}

if (!function_exists('get_term_link')) {
    /**
     * Get URL for a taxonomy term (WordPress-like)
     *
     * @param string $taxonomy Taxonomy key (category, tag, etc.)
     * @param mixed $term Term object or slug
     * @return string
     */
    function get_term_link(string $taxonomy, $term): string
    {
        $registry = app(TaxonomyRegistryService::class);
        return $registry->getTermUrl($taxonomy, $term);
    }
}

if (!function_exists('taxonomy_exists')) {
    /**
     * Check if taxonomy is registered
     *
     * @param string $taxonomy
     * @return bool
     */
    function taxonomy_exists(string $taxonomy): bool
    {
        $registry = app(TaxonomyRegistryService::class);
        return $registry->exists($taxonomy);
    }
}

