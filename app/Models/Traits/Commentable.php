<?php

namespace App\Models\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Commentable
{
    /**
     * Get all comments for the model
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get all approved comments
     */
    public function approvedComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->approved()
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get all comments (including replies)
     */
    public function allComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get total comments count (including replies)
     */
    public function getTotalCommentsCountAttribute(): int
    {
        return $this->allComments()->approved()->count();
    }

    /**
     * Check if comments are enabled for this model
     */
    public function commentsEnabled(): bool
    {
        return settings('comments_enabled', true);
    }

    /**
     * Check if comments are open (can add new comments)
     */
    public function commentsOpen(): bool
    {
        // Could be extended to check publish date, close after X days, etc.
        return $this->commentsEnabled();
    }
}

