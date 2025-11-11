<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Seo extends Model
{
    protected $table = 'seo';

    protected $fillable = [
        'seoable_id',
        'seoable_type',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'index',
        'follow',
        'schema_markup',
    ];

    protected $casts = [
        'index' => 'boolean',
        'follow' => 'boolean',
        'schema_markup' => 'array',
    ];

    /**
     * Get the seoable entity
     */
    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get meta robots value
     */
    public function getMetaRobotsAttribute(): string
    {
        $robots = [];

        if (!$this->index) {
            $robots[] = 'noindex';
        }

        if (!$this->follow) {
            $robots[] = 'nofollow';
        }

        return empty($robots) ? 'index, follow' : implode(', ', $robots);
    }

    /**
     * Get the effective meta title (with fallback)
     */
    public function getEffectiveMetaTitleAttribute(): string
    {
        return $this->meta_title 
            ?? $this->seoable->title 
            ?? settings('seo_default_meta_title', config('app.name'));
    }

    /**
     * Get the effective meta description (with fallback)
     */
    public function getEffectiveMetaDescriptionAttribute(): string
    {
        return $this->meta_description 
            ?? $this->seoable->excerpt 
            ?? settings('seo_default_meta_description', '');
    }

    /**
     * Get the effective OG title (with fallback)
     */
    public function getEffectiveOgTitleAttribute(): string
    {
        return $this->og_title 
            ?? $this->meta_title 
            ?? $this->seoable->title 
            ?? settings('seo_default_meta_title', config('app.name'));
    }

    /**
     * Get the effective OG description (with fallback)
     */
    public function getEffectiveOgDescriptionAttribute(): string
    {
        return $this->og_description 
            ?? $this->meta_description 
            ?? $this->seoable->excerpt 
            ?? settings('seo_default_meta_description', '');
    }

    /**
     * Get the effective OG image (with fallback)
     */
    public function getEffectiveOgImageAttribute(): ?string
    {
        if ($this->og_image) {
            return $this->og_image;
        }

        // Try to get from seoable (post/page featured image)
        if (method_exists($this->seoable, 'getImageUrl')) {
            return $this->seoable->getImageUrl();
        }

        return null;
    }
}

