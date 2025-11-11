<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;

class PostService implements PostServiceInterface
{
    /**
     * @var PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * Constructor
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Get paginated posts
     *
     * @param string|null $search Search term
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getPaginatedPosts(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return $this->postRepository->getPaginatedPosts($search, $perPage);
    }

    /**
     * Get all posts with optional filtering and sorting
     */
    public function getAllPosts(int $perPage = 10, string $search = '', string $sortField = 'created_at', string $sortDirection = 'desc')
    {
        return $this->postRepository->getPaginatedPosts($search, $perPage, $sortField, $sortDirection);
    }

    /**
     * Get a post by ID
     *
     * @param int $id
     * @return Post|null
     */
    public function getPostById(int $id): ?Post
    {
        return $this->postRepository->getPostById($id);
    }

    /**
     * Create a new post
     */
    public function createPost(array $data, $featuredImage = null)
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        
        if ($featuredImage) {
            $data['featured_image'] = $this->uploadImage($featuredImage);
        }

        $data['author_id'] = auth()->id();
        
        $post = $this->postRepository->createPost($data);
        
        // Sync categories if present
        if (isset($data['categories'])) {
            $this->syncPostCategories($post, $data['categories']);
        }
        
        return $post;
    }

    /**
     * Update an existing post
     */
    public function updatePost(int $postId, array $data, $featuredImage = null)
    {
        $post = $this->getPostById($postId);

        if (!$post) {
            return null;
        }
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($featuredImage) {
            // Delete old image if exists
            if ($post->featured_image && Storage::exists(str_replace('/storage', 'public', $post->featured_image))) {
                Storage::delete(str_replace('/storage', 'public', $post->featured_image));
            }
            $data['featured_image'] = $this->uploadImage($featuredImage);
        }

        $updatedPost = $this->postRepository->updatePost($postId, $data);
        
        // Sync categories if present
        if (isset($data['categories'])) {
            $this->syncPostCategories($post, $data['categories']);
        }
        
        return $updatedPost;
    }

    /**
     * Delete a post
     *
     * @param int $postId
     * @return bool
     */
    public function deletePost(int $postId): bool
    {
        $post = $this->postRepository->findById($postId);
        
        if (!$post) {
            return false;
        }
        
        // Here we could add business logic before deletion,
        // like permission checks, related records handling, etc.
        
        return $this->postRepository->deletePost($post);
    }

    /**
     * Upload an image and return its path
     */
    protected function uploadImage($image)
    {
        $path = $image->store('posts', 'public');
        return Storage::url($path);
    }

    /**
     * Sync post categories
     * 
     * @param Post $post
     * @param array $categories
     * @return void
     */
    public function syncPostCategories(Post $post, array $categories = [])
    {
        $post->categories()->sync($categories);
    }
} 