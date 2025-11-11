<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TagRepository implements TagRepositoryInterface
{
    public function __construct(
        protected Tag $tagModel
    ) {}

    /**
     * Get all tags
     */
    public function all(): Collection
    {
        return $this->tagModel->orderBy('name')->get();
    }

    /**
     * Get all tags with post count
     */
    public function allWithCount(): Collection
    {
        return $this->tagModel
            ->withCount('posts')
            ->orderBy('name')
            ->get();
    }

    /**
     * Find tag by ID
     */
    public function find(int $id): ?Tag
    {
        return $this->tagModel->find($id);
    }

    public function findById(int $id): ?Tag
    {
        return $this->find($id);
    }

    /**
     * Find tag by slug
     */
    public function findBySlug(string $slug): ?Tag
    {
        return $this->tagModel->where('slug', $slug)->first();
    }

    public function findByName(string $name): ?Tag
    {
        return $this->tagModel->where('name', $name)->first();
    }

    /**
     * Create a new tag
     */
    public function create(array $data): Tag
    {
        return $this->tagModel->create($data);
    }

    public function createTag(array $data): Tag
    {
        return $this->create($data);
    }

    /**
     * Update a tag
     */
    public function update(Tag $tag, array $data): bool
    {
        return $tag->update($data);
    }

    /**
     * Delete a tag
     */
    public function delete(Tag $tag): bool
    {
        return $tag->delete();
    }

    /**
     * Get popular tags (by post count)
     */
    public function getPopular(int $limit = 10): Collection
    {
        return $this->tagModel
            ->withCount('posts')
            ->orderByDesc('posts_count')
            ->limit($limit)
            ->get();
    }

    public function getPopularTags(int $limit = 20): Collection
    {
        return $this->getPopular($limit);
    }

    public function nameExists(string $name): bool
    {
        return $this->tagModel->where('name', $name)->exists();
    }
}
