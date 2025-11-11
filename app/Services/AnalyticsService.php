<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CommentRepository;
use App\Repositories\ContactRepository;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    public function __construct(
        protected PostRepository $postRepository,
        protected CategoryRepository $categoryRepository,
        protected CommentRepository $commentRepository,
        protected ContactRepository $contactRepository
    ) {}

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return Cache::remember('dashboard.stats', 600, function () {
            return [
                'posts' => $this->getPostStats(),
                'comments' => $this->getCommentStats(),
                'contacts' => $this->getContactStats(),
                'recent_activity' => $this->getRecentActivity(),
            ];
        });
    }

    /**
     * Get post statistics
     */
    protected function getPostStats(): array
    {
        // Use repository to get data
        $recentPosts = $this->postRepository->getRecent(5);
        
        return [
            'recent' => $recentPosts,
            'count' => $recentPosts->count(),
        ];
    }

    /**
     * Get comment statistics
     */
    protected function getCommentStats(): array
    {
        return $this->commentRepository->getCountByStatus();
    }

    /**
     * Get contact form statistics
     */
    protected function getContactStats(): array
    {
        return $this->contactRepository->getCountByStatus();
    }

    /**
     * Get recent activity
     */
    protected function getRecentActivity(): array
    {
        return [
            'posts' => $this->postRepository->getRecent(3),
            'comments' => $this->commentRepository->getRecent(3),
        ];
    }

    /**
     * Clear analytics cache
     */
    public function clearCache(): void
    {
        Cache::forget('dashboard.stats');
    }

    /**
     * Get Google Analytics data (if configured)
     */
    public function getGoogleAnalyticsData(): ?array
    {
        $gaId = settings('google_analytics_id');
        
        if (!$gaId || !settings('analytics_enabled', true)) {
            return null;
        }

        // TODO: Implement Google Analytics API integration
        // For now, return placeholder
        return [
            'pageviews' => 0,
            'sessions' => 0,
            'users' => 0,
        ];
    }
}

