<?php

namespace App\Services\Interfaces;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MediaServiceInterface
{
    /**
     * Upload a media file
     *
     * @param UploadedFile $file The file to upload
     * @param array $attributes Additional attributes for the media
     * @return array Response with media information
     */
    public function uploadMedia(UploadedFile $file, array $attributes = []);

    /**
     * List media with optional filters
     *
     * @param array $filters Filters to apply to the query
     * @return array List of media items
     */
    public function listMedia(array $filters = []);

    /**
     * Delete a media item
     *
     * @param Media $media The media to delete
     * @return bool Whether the deletion was successful
     */
    public function deleteMedia(Media $media);

    /**
     * Get paginated media
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedMedia(int $perPage = 24): LengthAwarePaginator;
} 