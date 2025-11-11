<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Taxonomy Registry Service
 * WordPress-like taxonomy registration system
 * 
 * Allows themes and plugins to register custom taxonomies
 */
class TaxonomyRegistryService
{
    protected array $taxonomies = [];

    public function __construct()
    {
        $this->registerCoreTaxonomies();
    }

    /**
     * Register core VeCarCMS taxonomies
     */
    protected function registerCoreTaxonomies(): void
    {
        // Category taxonomy
        $this->register('category', [
            'labels' => [
                'name' => 'Categories',
                'singular_name' => 'Category',
            ],
            'model' => \App\Models\Category::class,
            'slug_field' => 'slug',
            'relationship' => 'categories', // Relation method name on Post/Page
            'url_prefix' => null, // No prefix = /technology (not /category/technology)
            'query_var' => 'category',
            'rewrite' => [
                'slug' => '', // Empty = no prefix
                'with_front' => false,
            ],
            'hierarchical' => true,
            'public' => true,
            'show_in_menu' => true,
            'priority' => 10, // Resolution priority (lower = earlier check)
        ]);

        // Tag taxonomy
        $this->register('tag', [
            'labels' => [
                'name' => 'Tags',
                'singular_name' => 'Tag',
            ],
            'model' => \App\Models\Tag::class,
            'slug_field' => 'slug',
            'relationship' => 'tags',
            'url_prefix' => 'tag', // Keep tag prefix to avoid conflicts
            'query_var' => 'tag',
            'rewrite' => [
                'slug' => 'tag',
                'with_front' => false,
            ],
            'hierarchical' => false,
            'public' => true,
            'show_in_menu' => true,
            'priority' => 20,
        ]);

        // Future: Product Category (E-commerce)
        // $this->register('product_category', [...]);
        
        // Future: Event Category
        // $this->register('event_category', [...]);
    }

    /**
     * Register a new taxonomy (WordPress-like)
     *
     * @param string $taxonomy Taxonomy key (e.g., 'category', 'product_category')
     * @param array $args Configuration array
     * @return bool
     */
    public function register(string $taxonomy, array $args): bool
    {
        $defaults = [
            'labels' => [],
            'model' => null,
            'slug_field' => 'slug',
            'relationship' => null,
            'url_prefix' => null,
            'query_var' => $taxonomy,
            'rewrite' => [
                'slug' => $taxonomy,
                'with_front' => false,
            ],
            'hierarchical' => false,
            'public' => true,
            'show_in_menu' => true,
            'priority' => 100,
        ];

        $this->taxonomies[$taxonomy] = array_merge($defaults, $args);

        // Clear cache when new taxonomy is registered
        Cache::forget('registered_taxonomies');

        return true;
    }

    /**
     * Get all registered taxonomies
     */
    public function getAll(): array
    {
        return Cache::remember('registered_taxonomies', 3600, function () {
            // Sort by priority (lower first)
            uasort($this->taxonomies, function ($a, $b) {
                return ($a['priority'] ?? 100) <=> ($b['priority'] ?? 100);
            });
            
            return $this->taxonomies;
        });
    }

    /**
     * Get a specific taxonomy
     */
    public function get(string $taxonomy): ?array
    {
        return $this->taxonomies[$taxonomy] ?? null;
    }

    /**
     * Check if taxonomy exists
     */
    public function exists(string $taxonomy): bool
    {
        return isset($this->taxonomies[$taxonomy]);
    }

    /**
     * Get taxonomy by URL slug
     * Tries to resolve which taxonomy a URL slug belongs to
     */
    public function resolveBySlug(string $slug): ?array
    {
        foreach ($this->getAll() as $taxonomyKey => $taxonomy) {
            if (!$taxonomy['public'] || !$taxonomy['model']) {
                continue;
            }

            $modelClass = $taxonomy['model'];
            $slugField = $taxonomy['slug_field'];

            $term = $modelClass::where($slugField, $slug)->first();

            if ($term) {
                return [
                    'taxonomy' => $taxonomyKey,
                    'term' => $term,
                    'config' => $taxonomy,
                ];
            }
        }

        return null;
    }

    /**
     * Generate URL for a taxonomy term
     */
    public function getTermUrl(string $taxonomy, $term): string
    {
        $config = $this->get($taxonomy);
        
        if (!$config) {
            return url('/' . $term->slug);
        }

        $prefix = $config['url_prefix'];
        $slug = $term->{$config['slug_field']};

        if ($prefix) {
            return url("/{$prefix}/{$slug}");
        }

        return url("/{$slug}");
    }

    /**
     * Unregister a taxonomy
     */
    public function unregister(string $taxonomy): bool
    {
        if (isset($this->taxonomies[$taxonomy])) {
            unset($this->taxonomies[$taxonomy]);
            Cache::forget('registered_taxonomies');
            return true;
        }

        return false;
    }
}

