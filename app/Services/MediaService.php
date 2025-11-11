<?php

namespace App\Services;

use App\Repositories\Interfaces\MediaRepositoryInterface;
use App\Services\Interfaces\MediaServiceInterface;
use App\Models\Media;
use App\Formatters\FileFormatter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MediaService implements MediaServiceInterface
{
    protected MediaRepositoryInterface $mediaRepository;

    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * Upload a media file
     *
     * @param UploadedFile $file The file to upload
     * @param array $attributes Additional attributes for the media
     * @return array Response with media information
     */
    public function uploadMedia(UploadedFile $file, array $attributes = [])
    {
        $originalName = $file->getClientOriginalName();
        $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads', $fileName, 'public');

        // Crea il record media tramite repository
        $media = $this->mediaRepository->createMedia([
            'name' => pathinfo($originalName, PATHINFO_FILENAME),
            'file_name' => $fileName,
            'mime_type' => $file->getMimeType(),
            'path' => $path,
            'disk' => 'public',
            'size' => $file->getSize(),
            'user_id' => 1, // Default user ID for media uploads
            'source' => $attributes['source'] ?? 'media_library',
        ]);

        return [
            'id' => $media->id,
            'name' => $media->name,
            'location' => $media->url,
            'path' => $media->path,
            'success' => true,
            'message' => __('admin.media.upload_success')
        ];
    }

    /**
     * List media with optional filters
     *
     * @param array $filters Filters to apply to the query
     * @return array List of media items
     */
    public function listMedia(array $filters = [])
    {
        $media = $this->mediaRepository->listMedia($filters);
        return $media->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'url' => $item->url,
                'path' => $item->path,
                'size' => FileFormatter::formatSize($item->size),
                'created_at' => $item->created_at->format('Y-m-d H:i:s')
            ];
        })->toArray();
    }

    /**
     * Delete a media item
     *
     * @param Media $media The media to delete
     * @return bool Whether the deletion was successful
     */
    public function deleteMedia(Media $media)
    {
        try {
            if (Storage::disk($media->disk)->exists($media->path)) {
                Storage::disk($media->disk)->delete($media->path);
            }
            return $this->mediaRepository->deleteMedia($media);
        } catch (\Exception $e) {
            Log::error('Error deleting media: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get paginated media
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedMedia(int $perPage = 24): LengthAwarePaginator
    {
        return $this->mediaRepository->getPaginated(['per_page' => $perPage]);
    }
} 