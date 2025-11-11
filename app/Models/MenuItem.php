<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'target',
        'type',
        'object_id',
        'order',
        'is_active',
        'css_class',
        'icon',
        'visibility_rules',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'visibility_rules' => 'array',
        'order' => 'integer',
    ];

    protected $appends = [
        'computed_url',
        'depth',
    ];

    /**
     * Get the menu this item belongs to
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get parent menu item
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get child menu items
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->orderBy('order')
            ->with('children');
    }

    /**
     * Get active child menu items
     */
    public function activeChildren(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->with('activeChildren');
    }

    /**
     * Get the computed URL based on type
     */
    public function getComputedUrlAttribute(): string
    {
        // If custom type, return the URL directly
        if ($this->type === 'custom') {
            return $this->url ?? '#';
        }

        // Otherwise, generate URL from linked object
        return match($this->type) {
            'page' => $this->getPageUrl(),
            'post' => $this->getPostUrl(),
            'category' => $this->getCategoryUrl(),
            'tag' => $this->getTagUrl(),
            default => $this->url ?? '#',
        };
    }

    /**
     * Get URL for linked page
     */
    protected function getPageUrl(): string
    {
        if (!$this->object_id) {
            return '#';
        }

        $page = Page::find($this->object_id);
        
        if (!$page) {
            return '#';
        }
        
        // If homepage, return root URL
        if ($page->is_homepage) {
            return url('/');
        }
        
        // Otherwise return slug-based URL
        return url('/' . $page->slug);
    }

    /**
     * Get URL for linked post
     */
    protected function getPostUrl(): string
    {
        if (!$this->object_id) {
            return '#';
        }

        $post = Post::find($this->object_id);
        return $post ? url('/' . $post->slug) : '#';
    }

    /**
     * Get URL for linked category
     */
    protected function getCategoryUrl(): string
    {
        if (!$this->object_id) {
            return '#';
        }

        $category = Category::find($this->object_id);
        
        if (!$category) {
            return '#';
        }
        
        // Use taxonomy helper for clean URLs
        return get_term_link('category', $category);
    }

    /**
     * Get URL for linked tag
     */
    protected function getTagUrl(): string
    {
        if (!$this->object_id) {
            return '#';
        }

        $tag = Tag::find($this->object_id);
        
        if (!$tag) {
            return '#';
        }
        
        // Use taxonomy helper for clean URLs
        return get_term_link('tag', $tag);
    }

    /**
     * Get menu item depth (nesting level)
     */
    public function getDepthAttribute(): int
    {
        $depth = 0;
        $item = $this;

        while ($item->parent) {
            $depth++;
            $item = $item->parent;
        }

        return $depth;
    }

    /**
     * Check if menu item is visible for current user
     */
    public function isVisible(): bool
    {
        if (!$this->is_active) {
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

        return true;
    }

    /**
     * Scope: Only active items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Top-level items only
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}

