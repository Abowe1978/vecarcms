<?php

namespace App\Services;

use App\Repositories\SettingsRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingsService
{
    public function __construct(
        protected SettingsRepository $settingsRepository
    ) {}

    /**
     * Get all settings grouped by category
     */
    public function getAllGrouped()
    {
        return $this->settingsRepository->allGrouped();
    }

    /**
     * Get setting value
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->settingsRepository->get($key, $default);
    }

    /**
     * Update multiple settings
     */
    public function updateSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->settingsRepository->set($key, $value);
        }

        // Clear cache
        Cache::forget('settings.all');

        Log::info('Settings updated', [
            'count' => count($settings),
            'keys' => array_keys($settings),
        ]);
    }

    /**
     * Set a single setting
     */
    public function set(string $key, mixed $value, ?string $group = null): void
    {
        $this->settingsRepository->set($key, $value, $group);
        
        // Clear cache
        Cache::forget('settings.all');
    }

    /**
     * Remove a setting
     */
    public function remove(string $key): bool
    {
        $result = $this->settingsRepository->remove($key);
        
        // Clear cache
        Cache::forget('settings.all');
        
        return $result;
    }

    /**
     * Clear settings cache
     */
    public function clearCache(): void
    {
        Cache::forget('settings.all');
    }
}

