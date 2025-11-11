<?php

use App\Services\ShortcodeService;
use Illuminate\Support\Facades\Blade;

if (!function_exists('do_shortcode')) {
    /**
     * Process shortcodes in content (WordPress-like)
     *
     * @param string $content
     * @return string
     */
    function do_shortcode(string $content): string
    {
        $shortcodeService = app(ShortcodeService::class);
        $shortcodeService->begin();
        $processed = $shortcodeService->process($content);

        $needsRendering = str_contains($processed, "@include('shortcodes.render'");

        $output = $needsRendering ? Blade::render($processed) : $processed;
        $shortcodeService->end();

        return $output;
    }
}

if (!function_exists('register_shortcode')) {
    /**
     * Register a new shortcode
     *
     * @param string $tag
     * @param callable $callback
     * @return void
     */
    function register_shortcode(string $tag, callable $callback): void
    {
        $shortcodeService = app(ShortcodeService::class);
        $shortcodeService->register($tag, $callback);
    }
}

if (!function_exists('shortcode_exists')) {
    /**
     * Check if a shortcode exists
     *
     * @param string $tag
     * @return bool
     */
    function shortcode_exists(string $tag): bool
    {
        $shortcodeService = app(ShortcodeService::class);
        return $shortcodeService->exists($tag);
    }
}

if (!function_exists('strip_shortcodes')) {
    /**
     * Remove all shortcodes from content
     *
     * @param string $content
     * @return string
     */
    function strip_shortcodes(string $content): string
    {
        $shortcodeService = app(ShortcodeService::class);
        return $shortcodeService->strip($content);
    }
}

if (!function_exists('get_registered_shortcodes')) {
    /**
     * Get all registered shortcodes
     *
     * @return array
     */
    function get_registered_shortcodes(): array
    {
        $shortcodeService = app(ShortcodeService::class);
        return $shortcodeService->getAll();
    }
}

if (!function_exists('shortcode_view')) {
    /**
     * Create a structured response for shortcode rendering
     *
     * @param string $view
     * @param array $data
     * @return array
     */
    function shortcode_view(string $view, array $data = []): array
    {
        return [
            'view' => $view,
            'data' => $data,
        ];
    }
}
