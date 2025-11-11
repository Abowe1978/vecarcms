<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Page;
use App\Models\Category;
use App\Models\Tag;
use App\Services\RouteResolverService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function __construct(
        protected RouteResolverService $routeResolver
    ) {}

    /**
     * Display the homepage
     */
    public function home(): View
    {
        // Check if there's a custom homepage set
        $page = Page::homepage()->first();
        
        if ($page) {
            // If homepage template is 'home', use home.blade.php and pass page + posts
            if ($page->template === 'home') {
                $posts = Post::where('status', 'published')
                    ->orderBy('published_at', 'desc')
                    ->limit(6)
                    ->get();
                $themeName = active_theme();
                return view("themes.{$themeName}::views.home", compact('page', 'posts'));
            }
            
            // Otherwise use the page's template
            return $this->renderPage($page);
        }
        
        // Fallback: show default home with latest posts
        $posts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        $themeName = active_theme();
        return view("themes.{$themeName}::views.home", compact('posts'));
    }

    /**
     * Display blog archive
     */
    public function blog(Request $request): View
    {
        // Check if there's a custom blog page set
        $blogPage = Page::blog()->first();
        
        $posts = Post::where('status', 'published')
            ->with(['author', 'categories', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $themeName = active_theme();
        
        // If blog page exists, pass it to the view
        if ($blogPage) {
            return view("themes.{$themeName}::views.blog", compact('posts', 'blogPage'));
        }
        
        return view("themes.{$themeName}::views.blog", compact('posts'));
    }

    /**
     * Display single post
     */
    public function post(string $slug): View
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with(['author', 'categories', 'tags'])
            ->firstOrFail();

        // Determine which template to use (default or full-width)
        $template = $post->template ?? 'default';
        $themeName = active_theme();
        
        $templateViews = [
            'default' => 'post',
            'full-width' => 'post-full-width',
        ];
        
        $viewName = $templateViews[$template] ?? 'post';

        return view("themes.{$themeName}::views.{$viewName}", compact('post'));
    }

    /**
     * Display single page
     */
    public function page(string $slug): View
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Determine which template to use
        $template = $page->template ?? 'default';
        $themeName = active_theme();
        
        // Map template keys to view files
        $templateViews = [
            'default' => 'page',
            'home' => 'home', // Use home.blade.php for homepage
            'full-width' => 'page-full-width',
            'landing' => 'page-landing',
            'contact' => 'page-contact',
        ];
        
        $viewName = $templateViews[$template] ?? 'page';
        
        return view("themes.{$themeName}::views.{$viewName}", compact('page'));
    }

    /**
     * Display taxonomy archive (WordPress-like)
     * Handles categories, tags, and any custom taxonomies
     * 
     * @param string $slug Taxonomy term slug
     * @param string $taxonomy Taxonomy type (category, tag, product_category, etc.)
     */
    public function taxonomy(string $slug, string $taxonomy): View
    {
        // Resolve taxonomy term using the registry
        $resolved = $this->routeResolver->resolve($slug, $taxonomy);
        
        if ($resolved['type'] !== 'taxonomy') {
            abort(404);
        }

        $term = $resolved['model'];
        $config = $resolved['config'];
        $taxonomyKey = $resolved['taxonomy'];

        // Get posts for this term
        $relationshipMethod = $config['relationship'] ?? 'posts';
        
        $posts = $term->{$relationshipMethod}()
            ->where('status', 'published')
            ->with(['author', 'categories', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Determine view name (e.g., 'category', 'tag', 'product_category')
        $viewName = $taxonomyKey;
        $themeName = active_theme();

        // Try specific view first, fallback to generic 'archive'
        if (!view()->exists("themes.{$themeName}::views.{$viewName}")) {
            $viewName = 'archive'; // Generic taxonomy archive view
        }

        return view("themes.{$themeName}::views.{$viewName}", [
            $taxonomyKey => $term, // $category, $tag, etc.
            'term' => $term,
            'posts' => $posts,
            'taxonomy' => $taxonomyKey,
            'config' => $config,
        ]);
    }

    /**
     * Search
     */
    public function search(Request $request): View
    {
        $query = $request->get('q', '');
        
        $posts = Post::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->with(['author', 'categories', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $themeName = active_theme();
        return view("themes.{$themeName}::views.search", compact('posts', 'query'));
    }

    /**
     * Dynamic content resolver (WordPress-like)
     * Resolves URLs like /about, /my-post, etc.
     */
    public function dynamicContent(string $slug, ?string $parent = null): View
    {
        $themeName = active_theme();

        // If parent is provided, look for nested page
        if ($parent) {
            $parentPage = Page::where('slug', $parent)
                ->where('is_published', true)
                ->firstOrFail();

            $page = Page::where('slug', $slug)
                ->where('parent_id', $parentPage->id)
                ->where('is_published', true)
                ->firstOrFail();

            $template = $page->template ?? 'default';
            $templateViews = [
                'default' => 'page',
                'full-width' => 'page-full-width',
                'landing' => 'page-landing',
                'contact' => 'page-contact',
            ];
            $viewName = $templateViews[$template] ?? 'page';

            return view("themes.{$themeName}::views.{$viewName}", compact('page'));
        }

        // Resolve single slug dynamically
        $resolved = $this->routeResolver->resolve($slug);

        // Route to appropriate view based on type
        return match($resolved['type']) {
            'page' => $this->renderPage($resolved['model']),
            'post' => $this->renderPost($resolved['model']),
            'taxonomy' => $this->renderTaxonomy($resolved),
            default => abort(404)
        };
    }

    /**
     * Render a taxonomy archive
     */
    protected function renderTaxonomy(array $resolved): View
    {
        $term = $resolved['model'];
        $config = $resolved['config'];
        $taxonomyKey = $resolved['taxonomy'];

        // Get posts for this term
        $relationshipMethod = $config['relationship'] ?? 'posts';
        
        $posts = $term->{$relationshipMethod}()
            ->where('status', 'published')
            ->with(['author', 'categories', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Determine view name
        $viewName = $taxonomyKey;
        $themeName = active_theme();

        // Try specific view first, fallback to generic 'archive'
        if (!view()->exists("themes.{$themeName}::views.{$viewName}")) {
            $viewName = 'archive';
        }

        return view("themes.{$themeName}::views.{$viewName}", [
            $taxonomyKey => $term,
            'term' => $term,
            'posts' => $posts,
            'taxonomy' => $taxonomyKey,
            'config' => $config,
        ]);
    }

    /**
     * Render a page with appropriate template
     */
    protected function renderPage(Page $page): View
    {
        $themeName = active_theme();
        $template = $page->template ?? 'default';
        
        $templateViews = [
            'default' => 'page',
            'home' => 'home', // Use home.blade.php for homepage
            'full-width' => 'page-full-width',
            'landing' => 'page-landing',
            'contact' => 'page-contact',
        ];
        
        $viewName = $templateViews[$template] ?? 'page';
        
        return view("themes.{$themeName}::views.{$viewName}", compact('page'));
    }

    /**
     * Render a post with appropriate template
     */
    protected function renderPost(Post $post): View
    {
        $themeName = active_theme();
        $template = $post->template ?? 'default';
        
        $templateViews = [
            'default' => 'post',
            'full-width' => 'post-full-width',
        ];
        
        $viewName = $templateViews[$template] ?? 'post';
        
        return view("themes.{$themeName}::views.{$viewName}", compact('post'));
    }
}

