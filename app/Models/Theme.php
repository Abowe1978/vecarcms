<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'version',
        'author',
        'author_url',
        'screenshot',
        'settings',
        'is_active',
        'parent_theme',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get active theme
     */
    public static function active(): ?self
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Activate this theme
     */
    public function activate(): bool
    {
        // Deactivate all other themes
        static::where('is_active', true)->update(['is_active' => false]);
        
        // Activate this one
        return $this->update(['is_active' => true]);
    }

    /**
     * Check if is child theme
     */
    public function isChildTheme(): bool
    {
        return !empty($this->parent_theme);
    }

    /**
     * Get parent theme
     */
    public function parentTheme(): ?self
    {
        return $this->parent_theme 
            ? static::where('name', $this->parent_theme)->first() 
            : null;
    }

    /**
     * Get theme path
     */
    public function getPathAttribute(): string
    {
        return resource_path("themes/{$this->name}");
    }
}

