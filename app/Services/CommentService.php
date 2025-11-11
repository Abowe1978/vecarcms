<?php

namespace App\Services;

use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CommentService
{
    public function __construct(
        protected CommentRepository $commentRepository
    ) {}

    /**
     * Create a new comment
     */
    public function createComment(array $data): Comment
    {
        // Auto-approve if setting is enabled
        $autoApprove = settings('comments_auto_approve', false);
        
        $commentData = [
            'commentable_id' => $data['commentable_id'],
            'commentable_type' => $data['commentable_type'],
            'parent_id' => $data['parent_id'] ?? null,
            'content' => $data['content'],
            'status' => $autoApprove ? 'approved' : 'pending',
            'author_ip' => request()->ip(),
            'author_user_agent' => request()->userAgent(),
        ];

        // If user is authenticated
        if (auth()->check()) {
            $commentData['user_id'] = auth()->id();
        } else {
            // Guest comment (if allowed)
            $commentData['author_name'] = $data['author_name'];
            $commentData['author_email'] = $data['author_email'];
            $commentData['author_url'] = $data['author_url'] ?? null;
        }

        $comment = $this->commentRepository->create($commentData);

        // Send notification to admin if moderation is enabled
        if (!$autoApprove && settings('comments_moderation_email', true)) {
            $this->sendModerationNotification($comment);
        }

        return $comment;
    }

    /**
     * Update a comment
     */
    public function updateComment(Comment $comment, array $data): bool
    {
        return $this->commentRepository->update($comment, [
            'content' => $data['content'],
        ]);
    }

    /**
     * Approve a comment
     */
    public function approveComment(Comment $comment): void
    {
        $comment->approve();
        
        Log::info('Comment approved', [
            'comment_id' => $comment->id,
            'approved_by' => auth()->id(),
        ]);
    }

    /**
     * Approve multiple comments
     */
    public function approveMultiple(array $commentIds): int
    {
        $count = 0;
        
        foreach ($commentIds as $id) {
            $comment = $this->commentRepository->find($id);
            
            if ($comment) {
                $this->approveComment($comment);
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Mark comment as spam
     */
    public function markAsSpam(Comment $comment): void
    {
        $comment->markAsSpam();
        
        Log::info('Comment marked as spam', [
            'comment_id' => $comment->id,
            'marked_by' => auth()->id(),
        ]);
    }

    /**
     * Move comment to trash
     */
    public function moveToTrash(Comment $comment): void
    {
        $comment->moveToTrash();
        
        Log::info('Comment moved to trash', [
            'comment_id' => $comment->id,
            'trashed_by' => auth()->id(),
        ]);
    }

    /**
     * Delete comment permanently
     */
    public function deleteComment(Comment $comment): bool
    {
        Log::info('Comment deleted', [
            'comment_id' => $comment->id,
            'deleted_by' => auth()->id(),
        ]);
        
        return $this->commentRepository->forceDelete($comment);
    }

    /**
     * Delete multiple comments
     */
    public function deleteMultiple(array $commentIds): int
    {
        $count = 0;
        
        foreach ($commentIds as $id) {
            $comment = $this->commentRepository->find($id);
            
            if ($comment) {
                $this->deleteComment($comment);
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Get comments for moderation
     */
    public function getForModeration(int $perPage = 20)
    {
        return $this->commentRepository->getPending($perPage);
    }

    /**
     * Get comment statistics
     */
    public function getStatistics(): array
    {
        return $this->commentRepository->getCountByStatus();
    }

    /**
     * Get comment counts for admin dashboard
     */
    public function getCommentCounts(): array
    {
        return $this->commentRepository->getCountByStatus();
    }

    /**
     * Get comments for admin with filtering
     */
    public function getCommentsForAdmin(string $status = 'all', int $perPage = 20)
    {
        return match($status) {
            'pending' => $this->commentRepository->getPending($perPage),
            'approved' => $this->commentRepository->getApproved($perPage),
            'spam' => $this->commentRepository->getSpam($perPage),
            'trash' => $this->commentRepository->getTrashed($perPage),
            default => $this->commentRepository->paginate($perPage),
        };
    }

    /**
     * Simple spam detection
     */
    public function detectSpam(string $content, ?string $authorEmail = null): bool
    {
        // Simple spam keywords detection
        $spamKeywords = ['viagra', 'cialis', 'casino', 'poker', 'porn', 'xxx'];
        
        $contentLower = strtolower($content);
        
        foreach ($spamKeywords as $keyword) {
            if (str_contains($contentLower, $keyword)) {
                return true;
            }
        }

        // Check for excessive links
        $linkCount = substr_count($contentLower, 'http');
        if ($linkCount > 3) {
            return true;
        }

        // Check for disposable email domains (if guest)
        if ($authorEmail) {
            $disposableDomains = ['tempmail.com', '10minutemail.com', 'guerrillamail.com'];
            $emailDomain = substr(strrchr($authorEmail, "@"), 1);
            
            if (in_array($emailDomain, $disposableDomains)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Send moderation notification to admin
     */
    protected function sendModerationNotification(Comment $comment): void
    {
        try {
            // Get admin emails from settings
            $adminEmail = settings('site_email', config('mail.from.address'));
            
            // TODO: Create email notification class
            // For now, just log
            Log::info('Comment awaiting moderation', [
                'comment_id' => $comment->id,
                'author' => $comment->author_display_name,
                'commentable' => get_class($comment->commentable),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error sending comment moderation notification', [
                'error' => $e->getMessage(),
                'comment_id' => $comment->id,
            ]);
        }
    }

    /**
     * Get recent comments
     */
    public function getRecent(int $limit = 5): Collection
    {
        return $this->commentRepository->getRecent($limit);
    }

    /**
     * Get comments for a post/page
     */
    public function getCommentsFor(string $type, int $id): Collection
    {
        return $this->commentRepository->getForCommentable($type, $id);
    }

    /**
     * Count total comments for item
     */
    public function countCommentsFor(string $type, int $id): int
    {
        return Comment::forCommentable($type, $id)
            ->approved()
            ->count();
    }
}

