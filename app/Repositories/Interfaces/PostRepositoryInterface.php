<?php

namespace App\Repositories\Interfaces;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    /**
     * Get paginated posts with filtering and sorting
     *
     * @param string|null $search Search term
     * @param int $perPage Number of items per page
     * @param string $orderBy Order by field
     * @param string $direction Sort direction
     * @return LengthAwarePaginator
     */
    public function getPaginatedPosts(?string $search, int $perPage = 10, string $orderBy = 'created_at', string $direction = 'desc'): LengthAwarePaginator;
    
    /**
     * Find post by ID
     *
     * @param int $id
     * @return Post|null
     */
    public function findById(int $id): ?Post;
    
    /**
     * Delete a post
     *
     * @param Post $post
     * @return bool
     */
    public function deletePost(Post $post): bool;

    /**
     * Get the most recent published posts
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getRecentPublishedPosts(int $limit = 5): \Illuminate\Support\Collection;
} 