<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class SlugService
{
    /**
     * Generate a unique slug for a model
     *
     * @param string $title The title to slugify
     * @param string $model The model class (e.g. App\Models\Page)
     * @param string|null $existingSlug An existing slug to use instead of title
     * @param int|null $ignoreId ID to exclude from uniqueness check
     * @return string
     */
    public function generateUniqueSlug(string $title, string $model, ?string $existingSlug = null, ?int $ignoreId = null): string
    {
        // Use existing slug if provided, otherwise generate from title
        $slug = empty($existingSlug) ? Str::slug($title) : Str::slug($existingSlug);
        
        // Check for uniqueness
        $count = 0;
        $originalSlug = $slug;
        
        $query = $model::where('slug', $slug);
        
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        
        // While slug exists, append counter and try again
        while ($query->exists()) {
            $count++;
            $slug = $originalSlug . '-' . $count;
            $query = $model::where('slug', $slug);
            
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }
        
        return $slug;
    }
} 