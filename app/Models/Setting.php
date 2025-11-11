<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'autoload',
        'description',
    ];

    protected $casts = [
        'autoload' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        // Clear cache quando un setting viene aggiornato/creato/eliminato
        static::saved(function () {
            Cache::forget('settings.all');
            Cache::forget('settings.autoload');
        });

        static::deleted(function () {
            Cache::forget('settings.all');
            Cache::forget('settings.autoload');
        });
    }

    /**
     * Get the value with proper type casting
     */
    public function getValueAttribute($value)
    {
        return match ($this->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'array', 'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Set the value with proper type casting
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match ($this->type) {
            'boolean' => $value ? '1' : '0',
            'array', 'json' => json_encode($value),
            default => $value,
        };
    }

    /**
     * Scope to get settings by group
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope to get autoload settings
     */
    public function scopeAutoload($query)
    {
        return $query->where('autoload', true);
    }

    /**
     * Get a specific setting value
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );
    }

    /**
     * Check if a setting exists
     */
    public static function has(string $key): bool
    {
        return static::where('key', $key)->exists();
    }

    /**
     * Remove a setting
     */
    public static function remove(string $key): void
    {
        static::where('key', $key)->delete();
    }

    /**
     * Get all settings as key-value array
     */
    public static function allAsArray(): array
    {
        return Cache::remember('settings.all', 3600, function () {
            return static::query()
                ->get()
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Get all autoload settings as key-value array
     */
    public static function getAutoload(): array
    {
        return Cache::remember('settings.autoload', 3600, function () {
            return static::autoload()
                ->get()
                ->pluck('value', 'key')
                ->toArray();
        });
    }
}

