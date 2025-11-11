<?php

namespace App\Repositories\Interfaces;

interface DashboardRepositoryInterface
{
    /**
     * Get active members count for a specific period
     *
     * @param int $daysAgo
     * @param int|null $beforeDays
     * @return int
     */
    public function getActiveMembersCount(int $daysAgo, ?int $beforeDays = null): int;

    /**
     * Get posts count for a specific period
     *
     * @param int $daysAgo
     * @param int|null $beforeDays
     * @return int
     */
    public function getPostsCount(int $daysAgo, ?int $beforeDays = null): int;

    /**
     * Get total users count
     *
     * @return int
     */
    public function getTotalUsersCount(): int;

    /**
     * Get total posts count
     *
     * @return int
     */
    public function getTotalPostsCount(): int;

    /**
     * Get total pages count
     *
     * @return int
     */
    public function getTotalPagesCount(): int;

    /**
     * Get total comments count
     *
     * @return int
     */
    public function getTotalCommentsCount(): int;
} 