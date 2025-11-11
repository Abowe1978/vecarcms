<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'referer',
        'status',
        'read_at',
        'read_by',
        'admin_notes',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the user who read/handled this submission
     */
    public function readByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    /**
     * Mark as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
            'read_by' => auth()->id(),
        ]);
    }

    /**
     * Mark as replied
     */
    public function markAsReplied(): void
    {
        $this->update(['status' => 'replied']);
    }

    /**
     * Mark as spam
     */
    public function markAsSpam(): void
    {
        $this->update(['status' => 'spam']);
    }

    /**
     * Scope: New submissions
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope: Read submissions
     */
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    /**
     * Check if is new
     */
    public function isNew(): bool
    {
        return $this->status === 'new';
    }
}

