<?php

namespace App\Services\Admin;

use App\Repositories\Interfaces\DashboardRepositoryInterface;
use App\Services\Admin\DashboardServiceInterface;
use App\Repositories\Interfaces\PostRepositoryInterface;

class DashboardService implements DashboardServiceInterface
{
    protected $repository;
    protected $postRepository;

    public function __construct(DashboardRepositoryInterface $repository, PostRepositoryInterface $postRepository)
    {
        $this->repository = $repository;
        $this->postRepository = $postRepository;
    }

    /**
     * Get the total count of active members and their change percentage
     *
     * @return array
     */
    public function getMembersStats(): array
    {
        // Get current count (last 30 days)
        $currentCount = $this->repository->getActiveMembersCount(30);
        
        // Get previous period count (30-60 days ago)
        $previousCount = $this->repository->getActiveMembersCount(60, 30);

        return $this->calculateStats($currentCount, $previousCount);
    }

    /**
     * Get the total count of posts and their change percentage
     *
     * @return array
     */
    public function getPostsStats(): array
    {
        // Get current count (last 30 days)
        $currentCount = $this->repository->getPostsCount(30);
        
        // Get previous period count (30-60 days ago)
        $previousCount = $this->repository->getPostsCount(60, 30);

        return $this->calculateStats($currentCount, $previousCount);
    }

    /**
     * Calculate statistics based on current and previous counts
     *
     * @param int $currentCount
     * @param int $previousCount
     * @return array
     */
    protected function calculateStats(int $currentCount, int $previousCount): array
    {
        // Calculate percentage change
        $percentage = $previousCount > 0 ? (($currentCount - $previousCount) / $previousCount) * 100 : 0;
        
        // Determine trend
        $trend = $percentage > 0 ? 'up' : ($percentage < 0 ? 'down' : 'neutral');

        return [
            'total' => $currentCount,
            'percentage' => abs(round($percentage)),
            'trend' => $trend
        ];
    }

    /**
     * Get the most recent published posts
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getRecentPosts(int $limit = 5): \Illuminate\Support\Collection
    {
        return $this->postRepository->getRecentPublishedPosts($limit);
    }

    /**
     * Get dashboard statistics
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        return [
            'users' => $this->repository->getTotalUsersCount(),
            'posts' => $this->repository->getTotalPostsCount(),
            'pages' => $this->repository->getTotalPagesCount(),
            'comments' => $this->repository->getTotalCommentsCount(),
        ];
    }
} 