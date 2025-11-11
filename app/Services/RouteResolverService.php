<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class RouteResolverService
{
    public function __construct(
        protected TaxonomyRegistryService $taxonomyRegistry
    ) {}

    /**
     * Resolve a dynamic slug to its content type
     * WordPress-like URL resolution with flexible taxonomy support
     *
     * Priority order (configurable):
     * 1. Page (highest priority - static content)
     * 2. Post (blog content)
     * 3. Registered Taxonomies (by priority: category, tag, custom taxonomies)
     * 4. Custom Post Types (future: product, event, etc.)
     * 5. 404
     *
     * @param string $slug
     * @param string|null $type Type hint for direct resolution
     * @return array ['type' => 'page|post|taxonomy', 'model' => Model, 'taxonomy' => null|string]
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function resolve(string $slug, ?string $type = null): array
    {
        // Cache key based on slug and type
        $cacheKey = "route_resolve:" . ($type ?? 'auto') . ":{$slug}";
        
        return Cache::remember($cacheKey, 3600, function () use ($slug, $type) {
            // If type is specified, resolve directly (faster)
            if ($type) {
                return $this->resolveByType($slug, $type);
            }

            // Priority resolution (WordPress-like)
            
            // 1. Check for Page first (highest priority - static pages)
            $page = Page::where('slug', $slug)
                ->where('is_published', true)
                ->first();
            
            if ($page) {
                return ['type' => 'page', 'model' => $page, 'taxonomy' => null];
            }

            // 2. Check for Post (blog content)
            $post = Post::where('slug', $slug)
                ->where('status', 'published')
                ->with(['author', 'categories', 'tags'])
                ->first();
            
            if ($post) {
                return ['type' => 'post', 'model' => $post, 'taxonomy' => null];
            }

            // 3. Check registered taxonomies (categories, tags, custom taxonomies)
            $taxonomyMatch = $this->taxonomyRegistry->resolveBySlug($slug);
            
            if ($taxonomyMatch) {
                return [
                    'type' => 'taxonomy',
                    'model' => $taxonomyMatch['term'],
                    'taxonomy' => $taxonomyMatch['taxonomy'],
                    'config' => $taxonomyMatch['config'],
                ];
            }

            // 4. Future: Check custom post types
            // Example: Product, Event, etc.
            // $customPostType = $this->resolveCustomPostType($slug);

            // 5. Not found - throw 404
            abort(404);
        });
    }

    /**
     * Resolve by specific type hint
     * Used when type is known (e.g., from URL prefix)
     */
    protected function resolveByType(string $slug, string $type): array
    {
        // Check if it's a registered taxonomy
        $taxonomy = $this->taxonomyRegistry->get($type);
        
        if ($taxonomy && $taxonomy['model']) {
            $modelClass = $taxonomy['model'];
            $slugField = $taxonomy['slug_field'];
            
            $term = $modelClass::where($slugField, $slug)->firstOrFail();
            
            return [
                'type' => 'taxonomy',
                'model' => $term,
                'taxonomy' => $type,
                'config' => $taxonomy,
            ];
        }

        // Check core content types
        return match($type) {
            'page' => [
                'type' => 'page',
                'model' => Page::where('slug', $slug)
                    ->where('is_published', true)
                    ->firstOrFail(),
                'taxonomy' => null,
            ],
            'post' => [
                'type' => 'post',
                'model' => Post::where('slug', $slug)
                    ->where('status', 'published')
                    ->with(['author', 'categories', 'tags'])
                    ->firstOrFail(),
                'taxonomy' => null,
            ],
            default => abort(404)
        };
    }

    /**
     * Get permalink structure from settings
     */
    public function getPermalinkStructure(string $type = 'post'): string
    {
        $structures = [
            'post' => settings('permalink_post_structure', '/%postname%/'),
            'page' => settings('permalink_page_structure', '/%pagename%/'),
            'category' => settings('permalink_category_structure', '/category/%category%/'),
            'tag' => settings('permalink_tag_structure', '/tag/%tag%/'),
        ];

        return $structures[$type] ?? '/%postname%/';
    }

    /**
     * Generate URL for a model
     * WordPress-like permalink generation
     */
    public function generateUrl($model, ?string $type = null): string
    {
        if (!$type) {
            $type = class_basename($model);
            $type = strtolower($type);
        }

        $structure = $this->getPermalinkStructure($type);

        // Replace placeholders
        $url = match($type) {
            'post' => str_replace(
                ['%postname%', '%post_id%', '%year%', '%month%', '%day%'],
                [
                    $model->slug,
                    $model->id,
                    $model->published_at?->format('Y') ?? date('Y'),
                    $model->published_at?->format('m') ?? date('m'),
                    $model->published_at?->format('d') ?? date('d'),
                ],
                $structure
            ),
            'page' => str_replace('%pagename%', $model->slug, $structure),
            'category' => str_replace('%category%', $model->slug, $structure),
            'tag' => str_replace('%tag%', $model->slug, $structure),
            default => '/' . $model->slug
        };

        return url($url);
    }

    /**
     * Clear route resolution cache
     */
    public function clearCache(): void
    {
        Cache::forget('route_resolve:*');
    }
}

