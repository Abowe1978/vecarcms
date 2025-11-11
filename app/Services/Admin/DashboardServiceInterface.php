<?php

namespace App\Services\Admin;

interface DashboardServiceInterface
{
    /**
     * Get the total count of active members
     *
     * @return array
     */
    public function getMembersStats(): array;

    /**
     * Get the total count of posts
     *
     * @return array
     */
    public function getPostsStats(): array;

    /**
     * Get the most recent published posts
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getRecentPosts(int $limit = 5): \Illuminate\Support\Collection;

    /**
     * Get dashboard statistics
     *
     * @return array
     */
    public function getDashboardStats(): array;
} 