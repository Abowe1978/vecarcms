<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageBuilderTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'thumbnail',
        'content',
        'is_premium',
        'is_active',
        'usage_count',
        'created_by',
    ];

    protected $casts = [
        'content' => 'array',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
}

