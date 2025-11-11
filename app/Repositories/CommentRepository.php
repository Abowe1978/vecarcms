<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CommentRepository
{
    public function __construct(
        protected Comment $comment
    ) {}

    /**
     * Get all comments with pagination
     */
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return $this->comment
            ->with(['user', 'commentable', 'approvedBy'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get pending comments
     */
    public function getPending(int $perPage = 20): LengthAwarePaginator
    {
        return $this->comment
            ->with(['user', 'commentable'])
            ->pending()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get approved comments
     */
    public function getApproved(int $perPage = 20): LengthAwarePaginator
    {
        return $this->comment
            ->with(['user', 'commentable'])
            ->approved()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get spam comments
     */
    public function getSpam(int $perPage = 20): LengthAwarePaginator
    {
        return $this->comment
            ->with(['user', 'commentable'])
            ->where('status', 'spam')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get trashed comments
     */
    public function getTrashed(int $perPage = 20): LengthAwarePaginator
    {
        return $this->comment
            ->with(['user', 'commentable'])
            ->where('status', 'trash')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get comments for a specific item (post, page)
     */
    public function getForCommentable(string $type, int $id): Collection
    {
        return $this->comment
            ->with(['user', 'replies.user'])
            ->forCommentable($type, $id)
            ->approved()
            ->topLevel()
            ->latest()
            ->get();
    }

    /**
     * Find comment by ID
     */
    public function find(int $id): ?Comment
    {
        return $this->comment
            ->with(['user', 'commentable', 'parent'])
            ->find($id);
    }

    /**
     * Create a new comment
     */
    public function create(array $data): Comment
    {
        return $this->comment->create($data);
    }

    /**
     * Update a comment
     */
    public function update(Comment $comment, array $data): bool
    {
        return $comment->update($data);
    }

    /**
     * Delete a comment
     */
    public function delete(Comment $comment): bool
    {
        return $comment->delete();
    }

    /**
     * Force delete a comment
     */
    public function forceDelete(Comment $comment): bool
    {
        return $comment->forceDelete();
    }

    /**
     * Get comments count by status
     */
    public function getCountByStatus(): array
    {
        return [
            'all' => $this->comment->count(),
            'approved' => $this->comment->approved()->count(),
            'pending' => $this->comment->pending()->count(),
            'spam' => $this->comment->where('status', 'spam')->count(),
            'trash' => $this->comment->where('status', 'trash')->count(),
        ];
    }

    /**
     * Get recent comments
     */
    public function getRecent(int $limit = 5): Collection
    {
        return $this->comment
            ->with(['user', 'commentable'])
            ->approved()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Search comments
     */
    public function search(string $query, int $perPage = 20): LengthAwarePaginator
    {
        return $this->comment
            ->with(['user', 'commentable'])
            ->where(function ($q) use ($query) {
                $q->where('content', 'like', "%{$query}%")
                    ->orWhere('author_name', 'like', "%{$query}%")
                    ->orWhere('author_email', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate($perPage);
    }
}

