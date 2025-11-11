<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Revision extends Model
{
    use HasFactory;

    protected $fillable = [
        'revisionable_id',
        'revisionable_type',
        'user_id',
        'type',
        'content',
        'title',
        'summary',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Get the revisionable entity
     */
    public function revisionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created this revision
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this is an auto-save revision
     */
    public function isAutoSave(): bool
    {
        return $this->type === 'auto_save';
    }

    /**
     * Check if this is a manual revision
     */
    public function isManual(): bool
    {
        return $this->type === 'manual';
    }

    /**
     * Get revision author name
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->user?->name ?? 'System';
    }

    /**
     * Scope: Only manual revisions
     */
    public function scopeManual($query)
    {
        return $query->where('type', 'manual');
    }

    /**
     * Scope: Only auto-save revisions
     */
    public function scopeAutoSave($query)
    {
        return $query->where('type', 'auto_save');
    }
}

