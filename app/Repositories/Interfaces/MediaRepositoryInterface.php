<?php

namespace App\Repositories\Interfaces;

use App\Models\Media;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface MediaRepositoryInterface
{
    /**
     * Get all media
     */
    public function getAll(): Collection;

    /**
     * Get paginated media with optional filters
     *
     * @param array<string, mixed> $filters
     */
    public function getPaginated(array $filters = []): LengthAwarePaginator;

    /**
     * Find media by ID
     */
    public function findById(int $id): ?Media;

    /**
     * Create a new media
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): Media;

    /**
     * Update a media
     *
     * @param array<string, mixed> $data
     */
    public function update(Media $media, array $data): Media;

    /**
     * Delete a media
     */
    public function delete(Media $media): bool;

    /**
     * Search media by term
     *
     * @param string $term
     * @param array<string, mixed> $filters
     */
    public function search(string $term, array $filters = []): Collection;

    /**
     * Get media by type
     *
     * @param string $type
     */
    public function getByType(string $type): Collection;

    /**
     * Create a new media record
     *
     * @param array $data
     * @return Media
     */
    public function createMedia(array $data): Media;

    /**
     * List media with optional filters
     *
     * @param array $filters
     * @return \Illuminate\Support\Collection
     */
    public function listMedia(array $filters = []);

    /**
     * Delete a media record
     *
     * @param Media $media
     * @return bool
     */
    public function deleteMedia(Media $media): bool;
} 