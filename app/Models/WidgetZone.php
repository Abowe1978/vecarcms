<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WidgetZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'theme',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all widgets in this zone
     */
    public function widgets(): HasMany
    {
        return $this->hasMany(Widget::class, 'zone_id')
            ->orderBy('order');
    }

    /**
     * Get active widgets in this zone
     */
    public function activeWidgets(): HasMany
    {
        return $this->hasMany(Widget::class, 'zone_id')
            ->where('is_active', true)
            ->orderBy('order');
    }

    /**
     * Scope: Only active zones
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get zone by name
     */
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Scope: Get zones for theme
     */
    public function scopeForTheme($query, ?string $theme = null)
    {
        if ($theme) {
            return $query->where('theme', $theme);
        }

        return $query->whereNull('theme');
    }
}

