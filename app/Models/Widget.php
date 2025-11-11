<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
        'type',
        'title',
        'settings',
        'order',
        'is_active',
        'visibility_rules',
        'show_from',
        'show_until',
    ];

    protected $casts = [
        'settings' => 'array',
        'visibility_rules' => 'array',
        'is_active' => 'boolean',
        'show_from' => 'datetime',
        'show_until' => 'datetime',
        'order' => 'integer',
    ];

    /**
     * Get the zone this widget belongs to
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(WidgetZone::class, 'zone_id');
    }

    /**
     * Get widget setting
     */
    public function getSetting(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * Set widget setting
     */
    public function setSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->settings = $settings;
        $this->save();
    }

    /**
     * Check if widget is visible now
     */
    public function isVisibleNow(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check scheduling
        $now = now();
        
        if ($this->show_from && $now->lt($this->show_from)) {
            return false;
        }

        if ($this->show_until && $now->gt($this->show_until)) {
            return false;
        }

        return true;
    }

    /**
     * Check if widget is visible for current context
     */
    public function isVisible(): bool
    {
        if (!$this->isVisibleNow()) {
            return false;
        }

        // Check visibility rules
        if (!$this->visibility_rules) {
            return true;
        }

        $rules = $this->visibility_rules;

        // Check logged in/out rule
        if (isset($rules['logged_in'])) {
            if ($rules['logged_in'] && !auth()->check()) {
                return false;
            }
            if (!$rules['logged_in'] && auth()->check()) {
                return false;
            }
        }

        // Check role-based visibility
        if (isset($rules['roles']) && auth()->check()) {
            if (!auth()->user()->hasAnyRole($rules['roles'])) {
                return false;
            }
        }

        // Check page/post specific visibility
        if (isset($rules['show_on'])) {
            $currentRoute = request()->route()->getName();
            
            if (!in_array($currentRoute, $rules['show_on'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Scope: Only active widgets
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get widgets by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get widget class instance
     */
    public function getWidgetInstance()
    {
        $className = 'App\\Widgets\\' . studly_case($this->type) . 'Widget';

        if (!class_exists($className)) {
            return null;
        }

        return new $className($this);
    }
}

