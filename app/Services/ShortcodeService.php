<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ShortcodeService
{
    /**
     * Registered shortcodes
     *
     * @var array
     */
    protected array $shortcodes = [];

    /**
     * Stack of deferred shortcode payloads for nested rendering
     *
     * @var array<int, array<string, array>>
     */
    protected array $deferredStack = [];

    /**
     * Register a shortcode
     *
     * @param string $tag Shortcode tag (e.g., 'latest_posts')
     * @param callable $callback Function that returns the HTML
     * @return void
     */
    public function register(string $tag, callable $callback): void
    {
        $this->shortcodes[$tag] = $callback;
    }

    /**
     * Remove a shortcode
     *
     * @param string $tag
     * @return void
     */
    public function remove(string $tag): void
    {
        unset($this->shortcodes[$tag]);
    }

    /**
     * Check if a shortcode exists
     *
     * @param string $tag
     * @return bool
     */
    public function exists(string $tag): bool
    {
        return isset($this->shortcodes[$tag]);
    }

    /**
     * Process shortcodes in content
     *
     * @param string $content
     * @return string
     */
    public function process(string $content): string
    {
        if (empty($content)) {
            return $content;
        }

        // Pattern per shortcode: [tag attr="value"] o [tag attr="value"]content[/tag]
        $pattern = '/\[([A-Za-z0-9_-]+)(?:\s+([^\]]*))?\](?:([^\[]*)\[\/\1\])?/s';

        return preg_replace_callback($pattern, function ($matches) {
            $tag = $matches[1];
            $attrString = $matches[2] ?? '';
            $content = $matches[3] ?? null;

            if (!$this->exists($tag)) {
                return $matches[0]; // Return original if shortcode not found
            }

            // Parse attributes
            $attributes = $this->parseAttributes($attrString);

            // Call the shortcode callback with error handling
            try {
                $result = call_user_func($this->shortcodes[$tag], $attributes, $content);

                // If the callback returns a structured payload, store it for deferred include
                if (is_array($result) && isset($result['view'])) {
                    $id = $this->storeDeferred($result['view'], $result['data'] ?? []);
                    return "@include('shortcodes.render', ['__shortcode_id' => '{$id}'])";
                }

                // Allow callbacks to return raw HTML/string if needed
                if (is_string($result)) {
                    return $result;
                }

                Log::warning("Shortcode [{$tag}] returned unsupported response", ['result' => $result]);
                return '';
            } catch (\Throwable $e) {
                Log::error("Shortcode error [{$tag}]: " . $e->getMessage(), [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return "<!-- Shortcode Error [{$tag}]: " . htmlspecialchars($e->getMessage()) . " -->";
            }
        }, $content);
    }

    /**
     * Start a new shortcode rendering context
     */
    public function begin(): void
    {
        $this->deferredStack[] = [];
    }

    /**
     * End the current shortcode rendering context
     */
    public function end(): void
    {
        if (!empty($this->deferredStack)) {
            array_pop($this->deferredStack);
        }
    }

    /**
     * Store a deferred include payload and return its identifier
     */
    protected function storeDeferred(string $view, array $data = []): string
    {
        if (empty($this->deferredStack)) {
            $this->begin();
        }

        $id = uniqid('sc_', true);
        $index = count($this->deferredStack) - 1;
        $this->deferredStack[$index][$id] = [
            'view' => $view,
            'data' => $data,
        ];

        return $id;
    }

    /**
     * Resolve a deferred include payload by identifier
     */
    public function resolveDeferred(?string $id): ?array
    {
        if (!$id) {
            return null;
        }

        for ($index = count($this->deferredStack) - 1; $index >= 0; $index--) {
            if (isset($this->deferredStack[$index][$id])) {
                $payload = $this->deferredStack[$index][$id];
                unset($this->deferredStack[$index][$id]);
                return $payload;
            }
        }

        return null;
    }

    /**
     * Parse shortcode attributes
     *
     * @param string $text
     * @return array
     */
    protected function parseAttributes(string $text): array
    {
        $attributes = [];
        $text = trim($text);

        if (empty($text)) {
            return $attributes;
        }

        // Pattern: attr="value" o attr='value' o attr=value (supporta spazi e virgolette)
        $pattern = '/([A-Za-z0-9_-]+)\s*=\s*("([^"]*)"|\'([^\']*)\'|([^\s"\']+))/';

        if (preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $value = $match[3] ?? $match[4] ?? $match[5] ?? '';
                $attributes[$match[1]] = $value;
            }
        }

        return $attributes;
    }

    /**
     * Get all registered shortcodes
     *
     * @return array
     */
    public function getAll(): array
    {
        return array_keys($this->shortcodes);
    }

    /**
     * Strip all shortcodes from content
     *
     * @param string $content
     * @return string
     */
    public function strip(string $content): string
    {
        $pattern = '/\[([A-Za-z0-9_-]+)(?:\s+([^\]]*))?\](?:([^\[]*)\[\/\1\])?/s';
        return preg_replace($pattern, '', $content);
    }
}

