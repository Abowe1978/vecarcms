<?php

namespace App\Services\Interfaces;

use App\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;

interface TagServiceInterface
{
    /**
     * Get all tags with posts count
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllTags(int $perPage = 15): LengthAwarePaginator;
    
    /**
     * Get a tag by ID
     *
     * @param int $id
     * @return Tag|null
     */
    public function getTagById(int $id): ?Tag;
    
    /**
     * Create a new tag
     *
     * @param array $data Validated tag data
     * @return Tag
     */
    public function createTag(array $data): Tag;
    
    /**
     * Update an existing tag
     *
     * @param Tag $tag
     * @param array $data Validated tag data
     * @return Tag
     */
    public function updateTag(Tag $tag, array $data): Tag;
    
    /**
     * Delete a tag
     *
     * @param Tag $tag
     * @return bool
     */
    public function deleteTag(Tag $tag): bool;
} 