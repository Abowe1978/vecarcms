<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'module_name',
        'description',
        'is_enabled',
        'is_configured',
        'config',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_configured' => 'boolean',
        'config' => 'array',
    ];

    /**
     * Scope a query to only include enabled integrations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Scope a query to only include configured integrations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConfigured($query)
    {
        return $query->where('is_configured', true);
    }

    /**
     * Scope a query to only include active integrations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_enabled', true)
                     ->where('is_configured', true);
    }
} 