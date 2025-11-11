<?php

namespace App\Services\Interfaces;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostServiceInterface
{
    /**
     * Get all posts with optional filtering and sorting
     */
    public function getAllPosts(int $perPage = 10, string $search = '', string $sortField = 'created_at', string $sortDirection = 'desc');

    /**
     * Get a post by ID
     *
     * @param int $id
     * @return Post|null
     */
    public function getPostById(int $id): ?Post;

    /**
     * Create a new post
     * 
     * @param array $data Validated post data
     * @param \Illuminate\Http\UploadedFile|null $featuredImage
     * @return Post
     */
    public function createPost(array $data, $featuredImage = null);

    /**
     * Update an existing post
     * 
     * @param int $postId
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $featuredImage
     * @return Post|null
     */
    public function updatePost(int $postId, array $data, $featuredImage = null);

    /**
     * Delete a post
     */
    public function deletePost(int $postId);

    /**
     * Sync post categories
     * 
     * @param Post $post
     * @param array $categories
     * @return void
     */
    public function syncPostCategories(Post $post, array $categories = []);
} 