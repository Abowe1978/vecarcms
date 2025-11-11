<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingsRepository
{
    public function __construct(
        protected Setting $setting
    ) {}

    /**
     * Get all settings
     */
    public function all(): Collection
    {
        return $this->setting->all();
    }

    /**
     * Get settings grouped by group
     */
    public function allGrouped(): Collection
    {
        return $this->setting->all()->groupBy('group');
    }

    /**
     * Find setting by key
     */
    public function findByKey(string $key): ?Setting
    {
        return $this->setting->where('key', $key)->first();
    }

    /**
     * Update or create setting
     */
    public function set(string $key, mixed $value, ?string $group = null, ?string $type = null): Setting
    {
        // Find existing setting to preserve type if not provided
        $existing = $this->findByKey($key);
        
        $data = [
            'value' => $value,
            'group' => $group ?? ($existing?->group ?? 'general'),
        ];
        
        // Preserve type if not explicitly provided
        if ($type) {
            $data['type'] = $type;
        } elseif (!$existing) {
            // Auto-detect type for new settings
            $data['type'] = match(true) {
                is_bool($value) => 'boolean',
                is_int($value) => 'integer',
                is_array($value) => 'json',
                default => 'string'
            };
        }
        // If existing and no type provided, don't update type (preserve it)
        
        return $this->setting->updateOrCreate(
            ['key' => $key],
            $data
        );
    }

    /**
     * Get setting value by key
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $setting = $this->findByKey($key);
        return $setting?->value ?? $default;
    }

    /**
     * Delete setting by key
     */
    public function remove(string $key): bool
    {
        return $this->setting->where('key', $key)->delete();
    }
}

