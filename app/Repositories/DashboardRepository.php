<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Post;
use App\Models\Page;
use App\Models\Comment;
use App\Repositories\Interfaces\DashboardRepositoryInterface;
use Carbon\Carbon;

class DashboardRepository implements DashboardRepositoryInterface
{
    /**
     * Constructor - Inject models via dependency injection
     */
    public function __construct(
        protected User $userModel,
        protected Post $postModel,
        protected Page $pageModel,
        protected Comment $commentModel
    ) {}

    /**
     * Get active users count for a specific period
     *
     * @param int $daysAgo
     * @param int|null $beforeDays
     * @return int
     */
    public function getActiveMembersCount(int $daysAgo, ?int $beforeDays = null): int
    {
        // Count all users regardless of role
        $query = $this->userModel->newQuery();

        if ($beforeDays) {
            $query->where('last_login_at', '>=', Carbon::now()->subDays($daysAgo))
                  ->where('last_login_at', '<', Carbon::now()->subDays($beforeDays));
        } else {
            $query->where('last_login_at', '>=', Carbon::now()->subDays($daysAgo));
        }

        return $query->count();
    }

    /**
     * Get posts count for a specific period
     *
     * @param int $daysAgo
     * @param int|null $beforeDays
     * @return int
     */
    public function getPostsCount(int $daysAgo, ?int $beforeDays = null): int
    {
        $query = $this->postModel->newQuery();

        if ($beforeDays) {
            $query->where('created_at', '>=', Carbon::now()->subDays($daysAgo))
                  ->where('created_at', '<', Carbon::now()->subDays($beforeDays));
        } else {
            $query->where('created_at', '>=', Carbon::now()->subDays($daysAgo));
        }

        return $query->count();
    }

    /**
     * Get total users count
     *
     * @return int
     */
    public function getTotalUsersCount(): int
    {
        return $this->userModel->count();
    }

    /**
     * Get total posts count
     *
     * @return int
     */
    public function getTotalPostsCount(): int
    {
        return $this->postModel->count();
    }

    /**
     * Get total pages count
     *
     * @return int
     */
    public function getTotalPagesCount(): int
    {
        return $this->pageModel->count();
    }

    /**
     * Get total comments count
     *
     * @return int
     */
    public function getTotalCommentsCount(): int
    {
        return $this->commentModel->count();
    }
} 