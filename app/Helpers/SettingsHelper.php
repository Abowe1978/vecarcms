<?php

use App\Models\Setting;

if (!function_exists('settings')) {
    /**
     * Get a setting value
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed|array
     */
    function settings(?string $key = null, $default = null)
    {
        if ($key === null) {
            return Setting::getAutoload();
        }

        return Setting::get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $group
     * @return void
     */
    function set_setting(string $key, $value, string $type = 'string', string $group = 'general'): void
    {
        Setting::set($key, $value, $type, $group);
    }
}

if (!function_exists('has_setting')) {
    /**
     * Check if a setting exists
     *
     * @param string $key
     * @return bool
     */
    function has_setting(string $key): bool
    {
        return Setting::has($key);
    }
}

if (!function_exists('remove_setting')) {
    /**
     * Remove a setting
     *
     * @param string $key
     * @return void
     */
    function remove_setting(string $key): void
    {
        Setting::remove($key);
    }
}

