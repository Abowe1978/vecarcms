<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Page;
use App\Repositories\CloneRepository;
use Illuminate\Support\Facades\Log;

class CloneService
{
    public function __construct(
        protected CloneRepository $cloneRepository
    ) {}

    /**
     * Duplicate a post
     */
    public function duplicatePost(Post $post): Post
    {
        try {
            $clonedPost = $this->cloneRepository->clonePost($post);

            Log::info('Post duplicated successfully', [
                'original_id' => $post->id,
                'cloned_id' => $clonedPost->id,
                'user_id' => auth()->id(),
            ]);

            return $clonedPost;

        } catch (\Exception $e) {
            Log::error('Error duplicating post', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Duplicate a page
     */
    public function duplicatePage(Page $page): Page
    {
        try {
            $clonedPage = $this->cloneRepository->clonePage($page);

            Log::info('Page duplicated successfully', [
                'original_id' => $page->id,
                'cloned_id' => $clonedPage->id,
                'user_id' => auth()->id(),
            ]);

            return $clonedPage;

        } catch (\Exception $e) {
            Log::error('Error duplicating page', [
                'page_id' => $page->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Bulk duplicate posts
     */
    public function bulkDuplicatePosts(array $postIds): array
    {
        try {
            $clonedPosts = $this->cloneRepository->bulkClonePosts($postIds);

            Log::info('Bulk posts duplicated', [
                'count' => count($clonedPosts),
                'user_id' => auth()->id(),
            ]);

            return $clonedPosts;

        } catch (\Exception $e) {
            Log::error('Error bulk duplicating posts', [
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Bulk duplicate pages
     */
    public function bulkDuplicatePages(array $pageIds): array
    {
        try {
            $clonedPages = $this->cloneRepository->bulkClonePages($pageIds);

            Log::info('Bulk pages duplicated', [
                'count' => count($clonedPages),
                'user_id' => auth()->id(),
            ]);

            return $clonedPages;

        } catch (\Exception $e) {
            Log::error('Error bulk duplicating pages', [
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
}

