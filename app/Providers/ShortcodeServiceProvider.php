<?php

namespace App\Providers;

use App\Services\ShortcodeService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ShortcodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ShortcodeService::class, function ($app) {
            return new ShortcodeService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register global shortcodes
        $this->registerGlobalShortcodes();
        
        // Register theme-specific shortcodes (lazy loaded)
        // Only load when processing shortcodes, not during boot
        $this->app->booted(function () {
            if (app()->runningInConsole()) {
                return; // Skip in console
            }
            $this->registerThemeShortcodes();
        });
    }

    /**
     * Register global shortcodes available everywhere
     */
    protected function registerGlobalShortcodes(): void
    {
        $shortcode = app(ShortcodeService::class);

        // Latest Posts Shortcode
        $latestPostsHandler = function ($atts, $content = null) {
            $limit = $atts['limit'] ?? 3;
            $category = $atts['category'] ?? null;
            $layout = $atts['layout'] ?? 'grid'; // grid, list, carousel
            
            $posts = \App\Models\Post::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->limit($limit);
            
            if ($category) {
                $posts->whereHas('categories', function($q) use ($category) {
                    $q->where('slug', $category);
                });
            }
            
            $posts = $posts->get();
            
            return shortcode_view($this->resolveShortcodeView('latest-posts'), compact('posts', 'layout'));
        };

        $shortcode->register('latest_posts', $latestPostsHandler);
        $shortcode->register('latest-posts', $latestPostsHandler);

        // Backward-compatible alias with hyphen
        $shortcode->register('latest-posts', function ($atts, $content = null) use ($shortcode) {
            $attrString = $this->attributesToString($atts);
            $inner = trim($content ?? '');
            $shortcodeString = '[latest_posts' . ($attrString ? ' ' . $attrString : '') . ']'
                . $inner
                . '[/latest_posts]';

            return $shortcode->process($shortcodeString);
        });

        // Hero Section Shortcode
        $shortcode->register('hero', function ($atts, $content = null) {
            $title = $atts['title'] ?? 'Welcome';
            $subtitle = $atts['subtitle'] ?? '';
            $image = $atts['image'] ?? '';
            $button_text = $atts['button_text'] ?? 'Learn More';
            $button_url = $atts['button_url'] ?? '#';
            $background = $atts['background'] ?? 'primary';
            
            return shortcode_view($this->resolveShortcodeView('hero'), compact(
                'title', 'subtitle', 'image', 'button_text', 'button_url', 'background', 'content'
            ));
        });

        // Features Grid Shortcode
        $shortcode->register('features', function ($atts, $content = null) {
            $columns = $atts['columns'] ?? 3;
            $title = $atts['title'] ?? 'Our Features';
            $subtitle = $atts['subtitle'] ?? '';
            
            return shortcode_view($this->resolveShortcodeView('features'), compact('columns', 'title', 'subtitle', 'content'));
        });

        // CTA Section Shortcode
        $shortcode->register('cta', function ($atts, $content = null) {
            $title = $atts['title'] ?? 'Ready to Get Started?';
            $subtitle = $atts['subtitle'] ?? '';
            $button_text = $atts['button_text'] ?? 'Get Started';
            $button_url = $atts['button_url'] ?? '/contact';
            $background = $atts['background'] ?? 'primary';
            
            return shortcode_view($this->resolveShortcodeView('cta'), compact('title', 'subtitle', 'button_text', 'button_url', 'background', 'content'));
        });

        // Contact Form Shortcode
        $shortcode->register('contact_form', function ($atts, $content = null) {
            $title = $atts['title'] ?? '';
            $show_map = isset($atts['show_map']) ? filter_var($atts['show_map'], FILTER_VALIDATE_BOOLEAN) : true;
            
            return shortcode_view($this->resolveShortcodeView('contact-form'), compact('title', 'show_map'));
        });

        // Posts Grid Shortcode
        $shortcode->register('posts_grid', function ($atts, $content = null) {
            $limit = $atts['limit'] ?? 6;
            $category = $atts['category'] ?? null;
            $columns = $atts['columns'] ?? 3;
            $show_excerpt = isset($atts['show_excerpt']) ? filter_var($atts['show_excerpt'], FILTER_VALIDATE_BOOLEAN) : true;
            
            $posts = \App\Models\Post::where('status', 'published')
                ->with(['author', 'categories'])
                ->orderBy('published_at', 'desc')
                ->limit($limit);
            
            if ($category) {
                $posts->whereHas('categories', function($q) use ($category) {
                    $q->where('slug', $category);
                });
            }
            
            $posts = $posts->get();
            
            return shortcode_view($this->resolveShortcodeView('posts-grid'), compact('posts', 'columns', 'show_excerpt'));
        });

        // Categories List Shortcode
        $shortcode->register('categories_list', function ($atts, $content = null) {
            $show_count = isset($atts['show_count']) ? filter_var($atts['show_count'], FILTER_VALIDATE_BOOLEAN) : true;
            $limit = $atts['limit'] ?? null;
            
            $categories = \App\Models\Category::withCount('posts')
                ->orderBy('name');
            
            if ($limit) {
                $categories->limit($limit);
            }
            
            $categories = $categories->get();
            
            return shortcode_view($this->resolveShortcodeView('categories-list'), compact('categories', 'show_count'));
        });

        // Newsletter Signup Shortcode
        $shortcode->register('newsletter', function ($atts, $content = null) {
            $title = $atts['title'] ?? 'Subscribe to Our Newsletter';
            $subtitle = $atts['subtitle'] ?? 'Get the latest updates delivered to your inbox';
            $style = $atts['style'] ?? 'default'; // default, inline, minimal
            
            return shortcode_view($this->resolveShortcodeView('newsletter'), compact('title', 'subtitle', 'style'));
        });

        // Testimonials Shortcode
        $shortcode->register('testimonials', function ($atts, $content = null) {
            $limit = $atts['limit'] ?? 3;
            $layout = $atts['layout'] ?? 'grid'; // grid, slider
            
            // Hardcoded testimonials for now (can be moved to DB later)
            $testimonials = [
                [
                    'name' => 'John Doe',
                    'role' => 'CEO, Company Inc.',
                    'avatar' => 'https://ui-avatars.com/api/?name=John+Doe&size=64',
                    'content' => 'This CMS has completely transformed how we manage our content. Highly recommended!',
                    'rating' => 5
                ],
                [
                    'name' => 'Jane Smith',
                    'role' => 'Marketing Director',
                    'avatar' => 'https://ui-avatars.com/api/?name=Jane+Smith&size=64',
                    'content' => 'Easy to use, powerful features, and excellent support. Perfect for our needs.',
                    'rating' => 5
                ],
                [
                    'name' => 'Mike Johnson',
                    'role' => 'Developer',
                    'avatar' => 'https://ui-avatars.com/api/?name=Mike+Johnson&size=64',
                    'content' => 'Clean code, Laravel-based, and WordPress-like flexibility. Best of both worlds!',
                    'rating' => 5
                ]
            ];
            
            $testimonials = array_slice($testimonials, 0, $limit);
            
            return shortcode_view($this->resolveShortcodeView('testimonials'), compact('testimonials', 'layout'));
        });

        // Stats/Counter Shortcode
        $shortcode->register('stats', function ($atts, $content = null) {
            $columns = $atts['columns'] ?? 4;
            
            // Example stats (can be dynamic from settings)
            $stats = [
                ['value' => '1000+', 'label' => 'Happy Customers'],
                ['value' => '50+', 'label' => 'Team Members'],
                ['value' => '100%', 'label' => 'Satisfaction'],
                ['value' => '24/7', 'label' => 'Support']
            ];
            
            return shortcode_view($this->resolveShortcodeView('stats'), compact('stats', 'columns'));
        });

        // Button Shortcode
        $shortcode->register('button', function ($atts, $content = null) {
            $url = $atts['url'] ?? '#';
            $text = $content ?? ($atts['text'] ?? 'Click Here');
            $style = $atts['style'] ?? 'primary'; // primary, secondary, success, etc.
            $size = $atts['size'] ?? 'md'; // sm, md, lg
            $icon = $atts['icon'] ?? '';
            
            return shortcode_view($this->resolveShortcodeView('button'), compact('url', 'text', 'style', 'size', 'icon'));
        });

        // Alert/Notice Shortcode
        $shortcode->register('alert', function ($atts, $content = null) {
            $type = $atts['type'] ?? 'info'; // success, info, warning, danger
            $dismissible = isset($atts['dismissible']) ? filter_var($atts['dismissible'], FILTER_VALIDATE_BOOLEAN) : false;
            $icon = $atts['icon'] ?? '';
            
            return shortcode_view($this->resolveShortcodeView('alert'), compact('content', 'type', 'dismissible', 'icon'));
        });
    }

    /**
     * Register theme-specific shortcodes
     */
    protected function registerThemeShortcodes(): void
    {
        $themeName = active_theme();
        
        // Load theme shortcodes file if exists
        $shortcodesFile = base_path("content/themes/{$themeName}/shortcodes.php");
        
        if (file_exists($shortcodesFile)) {
            require_once $shortcodesFile;
        }
    }

    /**
     * Resolve the Blade view path for a shortcode, allowing theme overrides.
     */
    protected function resolveShortcodeView(string $view): string
    {
        $globalView = "shortcodes.{$view}";
        $themeView = null;

        if (function_exists('active_theme')) {
            $themeName = active_theme();
            if ($themeName) {
                $candidate = "themes.{$themeName}::shortcodes.{$view}";
                if (View::exists($candidate)) {
                    return $candidate;
                }
                $themeView = $candidate;
            }
        }

        if (View::exists($globalView)) {
            return $globalView;
        }

        return $themeView ?? $globalView;
    }

}

