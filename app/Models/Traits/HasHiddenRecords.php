<?php

namespace App\Models\Traits;

trait HasHiddenRecords
{
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }

    public function scopeHidden($query)
    {
        return $query->where('is_hidden', true);
    }

    public function isHidden(): bool
    {
        return (bool) $this->is_hidden;
    }

    public function isVisible(): bool
    {
        return !$this->isHidden();
    }
} 