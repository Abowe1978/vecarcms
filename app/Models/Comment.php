<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'commentable_id',
        'commentable_type',
        'parent_id',
        'user_id',
        'author_name',
        'author_email',
        'author_url',
        'author_ip',
        'author_user_agent',
        'content',
        'status',
        'approved_at',
        'approved_by',
        'likes',
        'is_featured',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'is_featured' => 'boolean',
        'likes' => 'integer',
    ];

    protected $appends = [
        'author_gravatar',
        'author_display_name',
    ];

    /**
     * Get the commentable entity (Post, Page, etc.)
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the comment author (if registered user)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who approved the comment
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get parent comment (for nested comments)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get child comments (replies)
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->with('replies')
            ->where('status', 'approved')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get all replies (including pending, for admin)
     */
    public function allReplies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->with('allReplies')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Scope: Only approved comments
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Only pending comments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Top-level comments (no parent)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope: For a specific commentable
     */
    public function scopeForCommentable($query, $commentableType, $commentableId)
    {
        return $query->where('commentable_type', $commentableType)
            ->where('commentable_id', $commentableId);
    }

    /**
     * Check if comment is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if comment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if comment is spam
     */
    public function isSpam(): bool
    {
        return $this->status === 'spam';
    }

    /**
     * Approve the comment
     */
    public function approve(): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);
    }

    /**
     * Mark as spam
     */
    public function markAsSpam(): void
    {
        $this->update(['status' => 'spam']);
    }

    /**
     * Move to trash
     */
    public function moveToTrash(): void
    {
        $this->update(['status' => 'trash']);
    }

    /**
     * Get author's display name
     */
    public function getAuthorDisplayNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }

        return $this->author_name ?? 'Anonymous';
    }

    /**
     * Get author's email for Gravatar
     */
    public function getAuthorGravatarAttribute(): string
    {
        $email = $this->user ? $this->user->email : $this->author_email;
        
        if (!$email) {
            $email = 'anonymous@example.com';
        }

        $hash = md5(strtolower(trim($email)));
        
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=80";
    }

    /**
     * Get comment depth (nesting level)
     */
    public function getDepth(): int
    {
        $depth = 0;
        $comment = $this;

        while ($comment->parent) {
            $depth++;
            $comment = $comment->parent;
        }

        return $depth;
    }

    /**
     * Check if user can edit this comment
     */
    public function canEdit(User $user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        // Admin can edit any comment
        if ($user->can('manage_comments')) {
            return true;
        }

        // User can edit own comments within 15 minutes
        if ($this->user_id === $user->id) {
            return $this->created_at->diffInMinutes(now()) <= 15;
        }

        return false;
    }

    /**
     * Check if user can delete this comment
     */
    public function canDelete(User $user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        // Admin can delete any comment
        if ($user->can('delete_comments')) {
            return true;
        }

        // User can delete own comments
        return $this->user_id === $user->id;
    }
}

