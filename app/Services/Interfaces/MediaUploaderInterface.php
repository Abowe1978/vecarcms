<?php

namespace App\Services\Interfaces;

use Illuminate\Http\UploadedFile;

interface MediaUploaderInterface
{
    /**
     * Upload a media file
     *
     * @param UploadedFile $file
     * @param array $attributes
     * @return array
     */
    public function upload(UploadedFile $file, array $attributes = []): array;

    /**
     * Delete a media file
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool;

    /**
     * Get the URL for a media file
     *
     * @param string $path
     * @return string
     */
    public function getUrl(string $path): string;
} 