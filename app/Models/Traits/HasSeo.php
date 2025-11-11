<?php

namespace App\Models\Traits;

use App\Models\Seo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    /**
     * Get the SEO data for the model
     */
    public function seo(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    /**
     * Get or create SEO data
     */
    public function getOrCreateSeo(): Seo
    {
        return $this->seo ?? $this->seo()->create([
            'meta_title' => $this->title ?? null,
            'meta_description' => $this->excerpt ?? null,
        ]);
    }

    /**
     * Update SEO data
     */
    public function updateSeo(array $data): Seo
    {
        if ($this->seo) {
            $this->seo->update($data);
            return $this->seo;
        }

        return $this->seo()->create($data);
    }

    /**
     * Get effective meta title (with fallback)
     */
    public function getMetaTitleAttribute(): string
    {
        return $this->seo?->effective_meta_title 
            ?? $this->title 
            ?? settings('seo_default_meta_title', config('app.name'));
    }

    /**
     * Get effective meta description (with fallback)
     */
    public function getMetaDescriptionAttribute(): string
    {
        return $this->seo?->effective_meta_description 
            ?? ($this->excerpt ?? '') 
            ?? settings('seo_default_meta_description', '');
    }
}

