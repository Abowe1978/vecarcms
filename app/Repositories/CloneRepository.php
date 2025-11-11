<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\Page;
use Illuminate\Database\Eloquent\Model;

class CloneRepository
{
    /**
     * Clone a post with all relationships
     */
    public function clonePost(Post $originalPost): Post
    {
        // Clone the post
        $clonedPost = $originalPost->replicate();
        
        // Modify title and slug
        $clonedPost->title = $originalPost->title . ' - Copy';
        $clonedPost->slug = $this->generateUniqueSlug(Post::class, $originalPost->slug);
        $clonedPost->status = 'draft'; // Always draft
        $clonedPost->published_at = null;
        
        $clonedPost->save();

        // Clone relationships
        $this->clonePostRelationships($originalPost, $clonedPost);

        return $clonedPost;
    }

    /**
     * Clone a page with all relationships
     */
    public function clonePage(Page $originalPage): Page
    {
        // Clone the page
        $clonedPage = $originalPage->replicate();
        
        // Modify title and slug
        $clonedPage->title = $originalPage->title . ' - Copy';
        $clonedPage->slug = $this->generateUniqueSlug(Page::class, $originalPage->slug);
        $clonedPage->is_published = false; // Always unpublished
        $clonedPage->parent_id = $originalPage->parent_id; // Keep parent relationship
        
        $clonedPage->save();

        // Clone relationships
        $this->clonePageRelationships($originalPage, $clonedPage);

        return $clonedPage;
    }

    /**
     * Clone post relationships
     */
    protected function clonePostRelationships(Post $original, Post $cloned): void
    {
        // Clone categories
        if ($original->categories()->exists()) {
            $categoryIds = $original->categories()->pluck('categories.id')->toArray();
            $cloned->categories()->sync($categoryIds);
        }

        // Clone tags
        if ($original->tags()->exists()) {
            $tagIds = $original->tags()->pluck('tags.id')->toArray();
            $cloned->tags()->sync($tagIds);
        }

        // Clone SEO data
        if ($original->seo) {
            $seoData = $original->seo->toArray();
            unset($seoData['id'], $seoData['seoable_id'], $seoData['seoable_type'], $seoData['created_at'], $seoData['updated_at']);
            $cloned->seo()->create($seoData);
        }

        // Note: Comments are NOT cloned (they belong to original)
    }

    /**
     * Clone page relationships
     */
    protected function clonePageRelationships(Page $original, Page $cloned): void
    {
        // Clone SEO data
        if ($original->seo) {
            $seoData = $original->seo->toArray();
            unset($seoData['id'], $seoData['seoable_id'], $seoData['seoable_type'], $seoData['created_at'], $seoData['updated_at']);
            $cloned->seo()->create($seoData);
        }

        // Note: Comments are NOT cloned
        // Note: Children pages are NOT cloned (would need recursive logic)
    }

    /**
     * Generate unique slug for cloned item
     */
    protected function generateUniqueSlug(string $modelClass, string $originalSlug): string
    {
        $slug = $originalSlug . '-copy';
        $count = 2;

        while ($modelClass::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-copy-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Bulk clone posts
     */
    public function bulkClonePosts(array $postIds): array
    {
        $clonedPosts = [];

        foreach ($postIds as $postId) {
            $post = Post::find($postId);
            
            if ($post) {
                $clonedPosts[] = $this->clonePost($post);
            }
        }

        return $clonedPosts;
    }

    /**
     * Bulk clone pages
     */
    public function bulkClonePages(array $pageIds): array
    {
        $clonedPages = [];

        foreach ($pageIds as $pageId) {
            $page = Page::find($pageId);
            
            if ($page) {
                $clonedPages[] = $this->clonePage($page);
            }
        }

        return $clonedPages;
    }
}

