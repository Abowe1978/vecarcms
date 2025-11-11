<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Traits\HasSeo;
use App\Models\Traits\HasRevisions;

class Page extends Model
{
    use HasFactory, SoftDeletes, HasSeo, HasRevisions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'template',
        'is_published',
        'is_homepage',
        'is_blog',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'order',
        'parent_id',
        'page_builder_content',
        'use_page_builder',
        'show_title',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_published' => 'boolean',
        'is_homepage' => 'boolean',
        'is_blog' => 'boolean',
        'page_builder_content' => 'array',
        'use_page_builder' => 'boolean',
        'show_title' => 'boolean',
    ];

    /**
     * The attributes that should be default values.
     *
     * @var array
     */
    protected $attributes = [
        'show_title' => true,
    ];

    /**
     * Get the URL for the page's featured image.
     *
     * @return string
     */
    public function getImageUrl()
    {
        if (!$this->featured_image) {
            return null;
        }

        // Se è già un URL o un percorso assoluto
        if (Str::startsWith($this->featured_image, ['http://', 'https://', '/'])) {
            return $this->featured_image;
        }

        // Altrimenti costruisci l'URL basato sul percorso relativo
        return '/storage/' . $this->featured_image;
    }

    /**
     * Scope to get the homepage
     */
    public function scopeHomepage($query)
    {
        return $query->where('is_homepage', true)->where('is_published', true);
    }

    /**
     * Scope to get the blog page
     */
    public function scopeBlog($query)
    {
        return $query->where('is_blog', true)->where('is_published', true);
    }

    /**
     * Set this page as the homepage (and unset any other homepage)
     */
    public function setAsHomepage(): void
    {
        // Remove homepage status from all other pages
        self::where('is_homepage', true)->update(['is_homepage' => false]);
        
        // Set this page as homepage
        $this->update(['is_homepage' => true, 'is_published' => true]);
    }

    /**
     * Set this page as the blog page (and unset any other blog page)
     */
    public function setAsBlog(): void
    {
        // Remove blog status from all other pages
        self::where('is_blog', true)->update(['is_blog' => false]);
        
        // Set this page as blog
        $this->update(['is_blog' => true, 'is_published' => true]);
    }

    /**
     * Check if this is the homepage
     */
    public function isHomepage(): bool
    {
        return $this->is_homepage === true;
    }

    /**
     * Check if this is the blog page
     */
    public function isBlog(): bool
    {
        return $this->is_blog === true;
    }

    /**
     * Get the parent page.
     */
    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    /**
     * Get the child pages.
     */
    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    /**
     * Scope a query to only include published pages.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to order pages by their display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
