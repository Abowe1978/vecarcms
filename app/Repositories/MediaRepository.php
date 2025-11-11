<?php

namespace App\Repositories;

use App\Models\Media;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MediaRepository implements MediaRepositoryInterface
{
    public function __construct(
        private readonly Media $model
    ) {}

    /**
     * Get all media
     */
    public function getAll(): Collection
    {
        return $this->model->latest()->get();
    }

    /**
     * Get paginated media with optional filters
     *
     * @param array<string, mixed> $filters
     */
    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        return $this->model->query()
            ->when(
                $filters['search'] ?? null,
                fn ($query, $search) => $query->where('name', 'like', "%{$search}%")
                    ->orWhere('original_name', 'like', "%{$search}%")
                    ->orWhere('mime_type', 'like', "%{$search}%")
            )
            ->when(
                $filters['type'] ?? null,
                fn ($query, $type) => $query->where('mime_type', 'like', $type . '%')
            )
            ->when(
                $filters['source'] ?? null,
                fn ($query, $source) => $query->where('source', $source)
            )
            ->latest()
            ->paginate($filters['per_page'] ?? 24);
    }

    /**
     * Find media by ID
     */
    public function findById(int $id): ?Media
    {
        return $this->model->find($id);
    }

    /**
     * Create a new media
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): Media
    {
        return $this->model->create($data);
    }

    /**
     * Update a media
     *
     * @param array<string, mixed> $data
     */
    public function update(Media $media, array $data): Media
    {
        $media->update($data);
        return $media->fresh();
    }

    /**
     * Delete a media
     */
    public function delete(Media $media): bool
    {
        return $media->delete();
    }

    /**
     * Search media by term
     *
     * @param string $term
     * @param array<string, mixed> $filters
     */
    public function search(string $term, array $filters = []): Collection
    {
        return $this->model->query()
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('original_name', 'like', "%{$term}%")
                    ->orWhere('mime_type', 'like', "%{$term}%");
            })
            ->when(
                $filters['type'] ?? null,
                fn ($query, $type) => $query->where('mime_type', 'like', $type . '%')
            )
            ->when(
                $filters['source'] ?? null,
                fn ($query, $source) => $query->where('source', $source)
            )
            ->latest()
            ->get();
    }

    /**
     * Get media by type
     *
     * @param string $type
     */
    public function getByType(string $type): Collection
    {
        return $this->model->where('mime_type', 'like', $type . '%')
            ->latest()
            ->get();
    }

    public function createMedia(array $data): Media
    {
        return $this->model->create($data);
    }

    public function listMedia(array $filters = [])
    {
        $query = $this->model->latest();
        if (isset($filters['source'])) {
            $query->where('source', $filters['source']);
        }
        return $query->get();
    }

    public function deleteMedia(Media $media): bool
    {
        return $media->delete();
    }
} 