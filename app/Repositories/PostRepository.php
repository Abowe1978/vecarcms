<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get paginated posts with filtering and sorting
     *
     * @param string|null $search Search term
     * @param int $perPage Number of items per page
     * @param string $orderBy Order by field
     * @param string $direction Sort direction
     * @return LengthAwarePaginator
     */
    public function getPaginatedPosts(?string $search, int $perPage = 10, string $orderBy = 'created_at', string $direction = 'desc'): LengthAwarePaginator
    {
        return $this->post->newQuery()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('content', 'like', '%' . $search . '%')
                        ->orWhere('excerpt', 'like', '%' . $search . '%');
                });
            })
            ->orderBy($orderBy, $direction)
            ->paginate($perPage);
    }
    
    /**
     * Create a new post
     *
     * @param array $data
     * @return Post
     */
    public function createPost(array $data): Post
    {
        return $this->post->create($data);
    }
    
    /**
     * Update a post
     *
     * @param int $postId
     * @param array $data
     * @return Post|null
     */
    public function updatePost(int $postId, array $data): ?Post
    {
        $post = $this->findById($postId);
        
        if (!$post) {
            return null;
        }
        
        $post->update($data);
        return $post->fresh();
    }
    
    /**
     * Find post by ID
     *
     * @param int $id
     * @return Post|null
     */
    public function findById(int $id): ?Post
    {
        return $this->post->find($id);
    }
    
    /**
     * Get post by ID (alias for findById)
     *
     * @param int $id
     * @return Post|null
     */
    public function getPostById(int $id): ?Post
    {
        return $this->findById($id);
    }
    
    /**
     * Delete a post
     *
     * @param Post $post
     * @return bool
     */
    public function deletePost(Post $post): bool
    {
        return $post->delete();
    }

    /**
     * Get the most recent published posts
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getRecentPublishedPosts(int $limit = 5): \Illuminate\Support\Collection
    {
        return $this->post->where('status', 'published')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Alias for getRecentPublishedPosts
     */
    public function getRecent(int $limit = 20): \Illuminate\Support\Collection
    {
        return $this->getRecentPublishedPosts($limit);
    }

    /**
     * Alias for findById
     */
    public function find(int $id): ?Post
    {
        return $this->findById($id);
    }
} 