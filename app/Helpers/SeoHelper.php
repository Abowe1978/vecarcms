<?php

use Illuminate\Support\Facades\Request;

if (!function_exists('breadcrumbs')) {
    /**
     * Generate breadcrumbs for current page
     * Auto-detects context from route and models
     * Supports Posts with Categories (including parent/child hierarchies)
     *
     * @param mixed $model Optional model (Post, Page, Category, Tag) for context
     * @return array
     */
    function breadcrumbs($model = null): array
    {
        $breadcrumbs = [
            ['title' => 'Home', 'url' => url('/')]
        ];

        $route = Request::route();
        $routeName = $route ? $route->getName() : null;
        $path = Request::path();

        // Handle Category archives
        if ($model && $model instanceof \App\Models\Category) {
            $breadcrumbs[] = ['title' => 'Blog', 'url' => url('/blog')];
            $breadcrumbs[] = ['title' => $model->name, 'url' => get_term_link('category', $model)];
            return $breadcrumbs;
        }

        // Handle Tag archives
        if ($model && $model instanceof \App\Models\Tag) {
            $breadcrumbs[] = ['title' => 'Blog', 'url' => url('/blog')];
            $breadcrumbs[] = ['title' => '#' . $model->name, 'url' => get_term_link('tag', $model)];
            return $breadcrumbs;
        }

        // Handle Post pages
        if ($model && $model instanceof \App\Models\Post) {
            // Add Blog link
            $breadcrumbs[] = [
                'title' => 'Blog',
                'url' => url('/blog')
            ];

            // Get primary category (first one)
            $category = $model->categories->first();
            
            if ($category) {
                // Check if category has parent support
                if (method_exists($category, 'parent') && $category->parent) {
                    // Build category hierarchy (parent -> child)
                    $categoryPath = [];
                    $currentCategory = $category;
                    
                    // Walk up the parent tree
                    while ($currentCategory) {
                        array_unshift($categoryPath, $currentCategory);
                        $currentCategory = $currentCategory->parent ?? null;
                    }
                    
                    // Add categories to breadcrumbs
                    foreach ($categoryPath as $cat) {
                        $breadcrumbs[] = [
                            'title' => $cat->name,
                            'url' => get_term_link('category', $cat)
                        ];
                    }
                } else {
                    // Simple category (no parent support yet)
                    $breadcrumbs[] = [
                        'title' => $category->name,
                        'url' => get_term_link('category', $category)
                    ];
                }
            }

            // Add current post (without link)
            $breadcrumbs[] = [
                'title' => $model->title,
                'url' => url('/' . $model->slug)
            ];

            return $breadcrumbs;
        }

        // Handle Page pages
        if ($model && $model instanceof \App\Models\Page) {
            // Build page hierarchy if it has parents
            $pagePath = [];
            $currentPage = $model;
            
            while ($currentPage) {
                array_unshift($pagePath, $currentPage);
                $currentPage = $currentPage->parent ?? null;
            }
            
            // Add pages to breadcrumbs
            foreach ($pagePath as $page) {
                $breadcrumbs[] = [
                    'title' => $page->title,
                    'url' => url('/' . $page->slug)
                ];
            }

            return $breadcrumbs;
        }

        // Auto-detect based on route name
        if ($routeName === 'blog' || $path === 'blog') {
            $breadcrumbs[] = ['title' => 'Blog', 'url' => url('/blog')];
            return $breadcrumbs;
        }

        if ($routeName === 'search' || str_starts_with($path, 'search')) {
            $breadcrumbs[] = ['title' => 'Search Results', 'url' => route('search')];
            return $breadcrumbs;
        }

        // Skip if homepage
        if ($path === '/') {
            return $breadcrumbs;
        }

        // Default: use URL segments as fallback
        $segments = explode('/', $path);
        $url = '';

        foreach ($segments as $segment) {
            if (empty($segment)) continue;
            
            $url .= '/' . $segment;
            $breadcrumbs[] = [
                'title' => ucwords(str_replace('-', ' ', $segment)),
                'url' => url($url)
            ];
        }

        return $breadcrumbs;
    }
}

if (!function_exists('render_breadcrumbs')) {
    /**
     * Render breadcrumbs HTML
     * Auto-detects context and builds breadcrumbs intelligently
     *
     * @param mixed $model Optional model (Post, Page, Category, Tag) for context
     * @param array $options Optional styling options
     * @return string
     */
    function render_breadcrumbs($model = null, array $options = []): string
    {
        $breadcrumbs = breadcrumbs($model);
        
        if (count($breadcrumbs) <= 1) {
            return '';
        }

        $class = $options['class'] ?? 'd-flex align-items-center gap-2 text-muted small';
        $separator = $options['separator'] ?? '<svg style="width: 16px; height: 16px; display: inline-block;" class="text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';

        $html = "<nav class=\"{$class}\" aria-label=\"Breadcrumb\">";
        $html .= '<ol class="d-flex align-items-center gap-2 list-unstyled mb-0">';

        $count = count($breadcrumbs);
        foreach ($breadcrumbs as $index => $crumb) {
            $isLast = ($index === $count - 1);
            
            $html .= '<li class="d-flex align-items-center">';
            
            if (!$isLast) {
                $html .= '<a href="' . $crumb['url'] . '" class="text-decoration-none text-muted hover-primary">' . $crumb['title'] . '</a>';
                $html .= '<span class="mx-2">' . $separator . '</span>';
            } else {
                $html .= '<span class="text-dark fw-medium">' . $crumb['title'] . '</span>';
            }
            
            $html .= '</li>';
        }

        $html .= '</ol>';
        $html .= '</nav>';

        return $html;
    }
}

if (!function_exists('schema_org')) {
    /**
     * Generate Schema.org JSON-LD for a model
     *
     * @param mixed $model
     * @param string $type
     * @return string
     */
    function schema_org($model, string $type = 'auto'): string
    {
        if ($type === 'auto') {
            $type = class_basename($model);
        }

        $schema = match($type) {
            'Post' => schema_article($model),
            'Page' => schema_webpage($model),
            'Organization' => schema_organization(),
            default => null
        };

        if (!$schema) {
            return '';
        }

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }
}

if (!function_exists('schema_article')) {
    /**
     * Generate Article schema for a post
     */
    function schema_article($post): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->excerpt ?? strip_tags($post->content),
            'image' => $post->featured_image ? url($post->getImageUrl()) : null,
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author->name ?? 'Unknown'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => settings('site_name', config('app.name')),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => url(settings('site_logo', '/images/logo.png'))
                ]
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => url('/' . $post->slug)
            ]
        ];
    }
}

if (!function_exists('schema_webpage')) {
    /**
     * Generate WebPage schema for a page
     */
    function schema_webpage($page): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $page->title,
            'description' => $page->meta_description ?? strip_tags($page->content),
            'url' => url('/' . $page->slug),
            'datePublished' => $page->created_at->toIso8601String(),
            'dateModified' => $page->updated_at->toIso8601String(),
        ];
    }
}

if (!function_exists('schema_organization')) {
    /**
     * Generate Organization schema
     */
    function schema_organization(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => settings('site_name', config('app.name')),
            'url' => url('/'),
            'logo' => url(settings('site_logo', '/images/logo.png')),
            'description' => settings('site_description', ''),
            'sameAs' => array_filter([
                settings('social_facebook'),
                settings('social_twitter'),
                settings('social_instagram'),
                settings('social_linkedin'),
            ])
        ];
    }
}
