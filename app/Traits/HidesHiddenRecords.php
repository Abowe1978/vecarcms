<?php

namespace App\Traits;

trait HidesHiddenRecords
{
    /**
     * Scope a query to exclude hidden records unless the user is a developer.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExcludeHidden($query)
    {
        if (!auth()->user()?->hasRole('developer')) {
            return $query->where('is_hidden', false);
        }
        return $query;
    }
} 