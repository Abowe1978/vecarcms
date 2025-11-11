<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUploader implements MediaUploaderInterface
{
    /**
     * Upload a media file
     *
     * @param UploadedFile $file
     * @param array $attributes
     * @return array
     */
    public function upload(UploadedFile $file, array $attributes = []): array
    {
        $path = $this->generatePath($file);
        $filename = $this->generateFilename($file);
        $fullPath = $path . '/' . $filename;

        // Store the file
        Storage::disk('public')->putFileAs($path, $file, $filename);

        return [
            'path' => $fullPath,
            'url' => $this->getUrl($fullPath),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'original_name' => $file->getClientOriginalName(),
            'attributes' => $attributes
        ];
    }

    /**
     * Delete a media file
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    /**
     * Get the URL for a media file
     *
     * @param string $path
     * @return string
     */
    public function getUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Generate a unique filename
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFilename(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Generate the storage path
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generatePath(UploadedFile $file): string
    {
        $type = explode('/', $file->getMimeType())[0];
        return 'media/' . $type . '/' . date('Y/m/d');
    }
} 