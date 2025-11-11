<?php

/**
 * DWN Theme Shortcodes
 * Theme-specific shortcodes for advanced layouts
 */

if (!function_exists('register_dwn_shortcode')) {
    /**
     * Register theme shortcodes with both dwn_ and legacy sigma_ prefixes.
     */
    function register_dwn_shortcode(string $name, callable $callback): void
    {
        register_shortcode("dwn_{$name}", $callback);
        register_shortcode("sigma_{$name}", $callback);
    }
}

// DWN Hero with Background Image
register_dwn_shortcode('hero', function ($atts, $content = null) {
    $title = $atts['title'] ?? 'Welcome';
    $description = $atts['description'] ?? ($atts['subtitle'] ?? '');
    $background_image = $atts['background_image'] ?? '';
    $overlay = $atts['overlay'] ?? 'dark'; // dark, light, gradient
    $height = $atts['height'] ?? 'large'; // small, medium, large
    $button_text = $atts['button_text'] ?? ($atts['button1_text'] ?? '');
    $button_url = $atts['button_url'] ?? ($atts['button1_url'] ?? '');
    $button1_text = $atts['button1_text'] ?? $button_text;
    $button1_url = $atts['button1_url'] ?? $button_url;
    $button2_text = $atts['button2_text'] ?? '';
    $button2_url = $atts['button2_url'] ?? '';
    $image = $atts['image'] ?? ($background_image ?: null);
    
    return shortcode_view('themes.dwntheme::shortcodes.dwn-hero', compact(
        'title', 'description', 'image', 'overlay', 'height',
        'button_text', 'button_url', 'button1_text', 'button1_url', 'button2_text', 'button2_url', 'content'
    ));
});

// DWN Features Grid (advanced version)
register_dwn_shortcode('features', function ($atts, $content = null) {
    $title = $atts['title'] ?? 'Our Features';
    $subtitle = $atts['subtitle'] ?? '';
    $layout = $atts['layout'] ?? 'icons'; // icons, cards, minimal
    $columns = $atts['columns'] ?? 3;
    
    return shortcode_view('themes.dwntheme::shortcodes.dwn-features', compact(
        'title', 'subtitle', 'layout', 'columns', 'content'
    ));
});

// DWN Pricing Table
register_dwn_shortcode('pricing', function ($atts, $content = null) {
    $columns = $atts['columns'] ?? 3;
    $featured = $atts['featured'] ?? '2'; // Which column is featured
    
    // Example pricing plans (can be from DB)
    $plans = [
        [
            'name' => 'Basic',
            'price' => '29',
            'period' => 'month',
            'features' => ['10 Pages', '5 GB Storage', 'Email Support'],
            'button_text' => 'Get Started',
            'button_url' => '/contact'
        ],
        [
            'name' => 'Professional',
            'price' => '79',
            'period' => 'month',
            'features' => ['Unlimited Pages', '50 GB Storage', 'Priority Support', 'Custom Domain'],
            'button_text' => 'Get Started',
            'button_url' => '/contact',
            'featured' => true
        ],
        [
            'name' => 'Enterprise',
            'price' => '199',
            'period' => 'month',
            'features' => ['Unlimited Everything', 'Dedicated Support', 'Custom Development', 'SLA'],
            'button_text' => 'Contact Sales',
            'button_url' => '/contact'
        ]
    ];
    
    return shortcode_view('themes.dwntheme::shortcodes.dwn-pricing', compact('plans', 'columns', 'featured'));
});

// DWN Team Members
register_dwn_shortcode('team', function ($atts, $content = null) {
    $columns = $atts['columns'] ?? 4;
    $style = $atts['style'] ?? 'cards'; // cards, minimal, detailed
    
    // Example team members (can be from DB)
    $team = [
        [
            'name' => 'John Doe',
            'role' => 'CEO & Founder',
            'avatar' => 'https://ui-avatars.com/api/?name=John+Doe&size=256',
            'bio' => 'Leading the vision and strategy',
            'social' => [
                'twitter' => '#',
                'linkedin' => '#'
            ]
        ],
        [
            'name' => 'Jane Smith',
            'role' => 'CTO',
            'avatar' => 'https://ui-avatars.com/api/?name=Jane+Smith&size=256',
            'bio' => 'Building amazing technology',
            'social' => [
                'twitter' => '#',
                'linkedin' => '#',
                'github' => '#'
            ]
        ]
    ];
    
    return shortcode_view('themes.dwntheme::shortcodes.dwn-team', compact('team', 'columns', 'style'));
});

// DWN Timeline
register_dwn_shortcode('timeline', function ($atts, $content = null) {
    $style = $atts['style'] ?? 'vertical'; // vertical, horizontal
    
    $events = [
        ['year' => '2020', 'title' => 'Founded', 'description' => 'VeCarCMS was born'],
        ['year' => '2021', 'title' => 'First Release', 'description' => 'Version 1.0 launched'],
        ['year' => '2023', 'title' => 'Major Update', 'description' => 'Laravel 11 + Vue 3'],
        ['year' => '2025', 'title' => 'Enterprise Ready', 'description' => 'Serving thousands of users']
    ];
    
    return shortcode_view('themes.dwntheme::shortcodes.dwn-timeline', compact('events', 'style'));
});

// DWN Icon Box
register_dwn_shortcode('icon_box', function ($atts, $content = null) {
    $icon = $atts['icon'] ?? 'ri-star-line';
    $title = $atts['title'] ?? '';
    $link = $atts['link'] ?? '';
    $style = $atts['style'] ?? 'default'; // default, minimal, bordered
    
    return shortcode_view('themes.dwntheme::shortcodes.dwn-icon-box', compact(
        'icon', 'title', 'content', 'link', 'style'
    ));
});

// DWN Accordion/FAQ
register_dwn_shortcode('accordion', function ($atts, $content = null) {
    $id = $atts['id'] ?? 'accordion-' . uniqid();
    
    // Parse items from content or use default
    $items = [
        ['title' => 'What is VeCarCMS?', 'content' => 'VeCarCMS is a powerful Laravel-first CMS designed for modern teams.'],
        ['title' => 'How do I get started?', 'content' => 'Simply create an account and start building your site!'],
        ['title' => 'Is it free?', 'content' => 'We offer both free and premium plans to suit your needs.']
    ];
    
    return shortcode_view('themes.dwntheme::shortcodes.dwn-accordion', compact('items', 'id'));
});

// Latest Posts
register_shortcode('latest-posts', function ($atts, $content = null) {
    $count = $atts['count'] ?? 3;
    $layout = $atts['layout'] ?? 'grid'; // grid or list
    $posts = \App\Models\Post::where('status', 'published')->latest('published_at')->limit($count)->get();
    
    return shortcode_view('themes.dwntheme::shortcodes.latest-posts', compact('posts', 'layout'));
});

// CTA (Call to Action)
register_dwn_shortcode('cta', function ($atts, $content = null) {
    $title = $atts['title'] ?? 'Ready to Get Started?';
    $description = $atts['description'] ?? '';
    $button1_text = $atts['button1_text'] ?? '';
    $button1_url = $atts['button1_url'] ?? '';
    $button2_text = $atts['button2_text'] ?? '';
    $button2_url = $atts['button2_url'] ?? '';
    $background = $atts['background'] ?? 'primary'; // primary, dark, gradient
    
    return shortcode_view('themes.dwntheme::shortcodes.cta', compact(
        'title', 'description', 'button1_text', 'button1_url', 'button2_text', 'button2_url', 'background'
    ));
});

register_dwn_shortcode('logos', function ($atts, $content = null) {
    $title = $atts['title'] ?? null;
    $logos = isset($atts['logos']) ? array_map('trim', explode(',', $atts['logos'])) : null;

    return shortcode_view('themes.dwntheme::shortcodes.dwn-logos', compact('title', 'logos'));
});

register_dwn_shortcode('highlights', function ($atts, $content = null) {
    return shortcode_view('themes.dwntheme::shortcodes.dwn-highlights', []);
});

register_dwn_shortcode('reviews', function ($atts, $content = null) {
    $title = $atts['title'] ?? null;
    $subtitle = $atts['subtitle'] ?? null;
    $primary_button_text = $atts['primary_button_text'] ?? null;
    $primary_button_url = $atts['primary_button_url'] ?? null;
    $secondary_button_text = $atts['secondary_button_text'] ?? null;
    $secondary_button_url = $atts['secondary_button_url'] ?? null;

    return shortcode_view('themes.dwntheme::shortcodes.dwn-reviews', compact(
        'title', 'subtitle', 'primary_button_text', 'primary_button_url', 'secondary_button_text', 'secondary_button_url'
    ));
});

register_dwn_shortcode('features_summary', function ($atts, $content = null) {
    $title = $atts['title'] ?? null;
    $subtitle = $atts['subtitle'] ?? null;
    $button_text = $atts['button_text'] ?? null;
    $button_url = $atts['button_url'] ?? null;

    return shortcode_view('themes.dwntheme::shortcodes.dwn-features-summary', compact(
        'title', 'subtitle', 'button_text', 'button_url'
    ));
});

register_dwn_shortcode('about', function ($atts, $content = null) {
    $title = $atts['title'] ?? null;
    $button_text = $atts['button_text'] ?? null;
    $button_url = $atts['button_url'] ?? null;
    $image = $atts['image'] ?? null;

    return shortcode_view('themes.dwntheme::shortcodes.dwn-about', compact(
        'title', 'button_text', 'button_url', 'image', 'content'
    ));
});

register_dwn_shortcode('about_header', function ($atts, $content = null) {
    $title = $atts['title'] ?? 'About Us';
    $subtitle = $atts['subtitle'] ?? '';

    return shortcode_view('themes.dwntheme::shortcodes.dwn-about-header', compact('title', 'subtitle'));
});

register_dwn_shortcode('about_gallery', function ($atts, $content = null) {
    $images = isset($atts['images']) ? array_map('trim', explode(',', $atts['images'])) : null;

    return shortcode_view('themes.dwntheme::shortcodes.dwn-about-gallery', compact('images'));
});

register_dwn_shortcode('about_stats', function ($atts, $content = null) {
    $stats = [
        ['value' => $atts['locations'] ?? '12', 'label' => 'Locations'],
        ['value' => $atts['customers'] ?? '75K', 'label' => 'Customers'],
        ['value' => $atts['staff'] ?? '160', 'label' => 'Staff'],
    ];

    return shortcode_view('themes.dwntheme::shortcodes.dwn-about-stats', compact('stats'));
});

register_dwn_shortcode('about_story', function ($atts, $content = null) {
    $eyebrow = $atts['eyebrow'] ?? null;
    $title = $atts['title'] ?? null;
    $image = $atts['image'] ?? null;
    $reverse = filter_var($atts['reverse'] ?? false, FILTER_VALIDATE_BOOLEAN);

    return shortcode_view('themes.dwntheme::shortcodes.dwn-about-story', compact(
        'eyebrow', 'title', 'image', 'content', 'reverse'
    ));
});

register_dwn_shortcode('about_team', function ($atts, $content = null) {
    $title = $atts['title'] ?? 'Our team';
    $members = isset($atts['members']) ? json_decode($atts['members'], true) : null;

    return shortcode_view('themes.dwntheme::shortcodes.dwn-about-team', compact('title', 'members'));
});

register_dwn_shortcode('about_careers', function ($atts, $content = null) {
    $text = $atts['text'] ?? 'Want to join our team?';
    $link_text = $atts['link_text'] ?? 'We are hiring';
    $link_url = $atts['link_url'] ?? '#';

    return shortcode_view('themes.dwntheme::shortcodes.dwn-about-careers', compact('text', 'link_text', 'link_url'));
});

