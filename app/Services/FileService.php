<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService implements FileServiceInterface
{
    private const DEFAULT_DISK = 'public';

    /**
     * Store a file
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string|null $disk
     */
    public function store(UploadedFile $file, string $path, ?string $disk = null): string
    {
        $disk = $disk ?? self::DEFAULT_DISK;
        $filename = $this->generateUniqueFilename($file);
        $fullPath = trim($path, '/') . '/' . $filename;

        Storage::disk($disk)->putFileAs(
            dirname($fullPath),
            $file,
            basename($fullPath)
        );

        return $fullPath;
    }

    /**
     * Delete a file
     *
     * @param string $path
     * @param string|null $disk
     */
    public function delete(string $path, ?string $disk = null): bool
    {
        return Storage::disk($disk ?? self::DEFAULT_DISK)->delete($path);
    }

    /**
     * Get file URL
     *
     * @param string $path
     * @param string|null $disk
     */
    public function url(string $path, ?string $disk = null): string
    {
        return Storage::disk($disk ?? self::DEFAULT_DISK)->url($path);
    }

    /**
     * Get file size
     *
     * @param string $path
     * @param string|null $disk
     */
    public function size(string $path, ?string $disk = null): int
    {
        return Storage::disk($disk ?? self::DEFAULT_DISK)->size($path);
    }

    /**
     * Get file mime type
     *
     * @param string $path
     * @param string|null $disk
     */
    public function mimeType(string $path, ?string $disk = null): string
    {
        return Storage::disk($disk ?? self::DEFAULT_DISK)->mimeType($path);
    }

    /**
     * Generate a unique filename
     */
    private function generateUniqueFilename(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }
} 