<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all menu items
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->with('children');
    }

    /**
     * Get all menu items (flat)
     */
    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    /**
     * Get active menu items
     */
    public function activeItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->with('activeChildren');
    }

    /**
     * Scope: Get menu by location
     */
    public function scopeLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Scope: Only active menus
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get menu by location
     */
    public static function getByLocation(string $location): ?Menu
    {
        return static::location($location)
            ->active()
            ->with('activeItems')
            ->first();
    }
}

