<?php

namespace App\Repositories\Interfaces;

use App\Models\Tag;
use Illuminate\Support\Collection;

interface TagRepositoryInterface
{
    /**
     * Get popular tags with posts count
     *
     * @param int $limit
     * @return Collection
     */
    public function getPopularTags(int $limit = 20): Collection;
    
    /**
     * Find tag by ID
     *
     * @param int $id
     * @return Tag|null
     */
    public function findById(int $id): ?Tag;
    
    /**
     * Find tag by name
     *
     * @param string $name
     * @return Tag|null
     */
    public function findByName(string $name): ?Tag;
    
    /**
     * Check if tag name exists
     *
     * @param string $name
     * @return bool
     */
    public function nameExists(string $name): bool;
    
    /**
     * Create a new tag
     *
     * @param array $data
     * @return Tag
     */
    public function createTag(array $data): Tag;
} 