<?php

namespace App\Services\Interfaces;

use Illuminate\Http\UploadedFile;

interface FileServiceInterface
{
    /**
     * Store a file
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string|null $disk
     */
    public function store(UploadedFile $file, string $path, ?string $disk = null): string;

    /**
     * Delete a file
     *
     * @param string $path
     * @param string|null $disk
     */
    public function delete(string $path, ?string $disk = null): bool;

    /**
     * Get file URL
     *
     * @param string $path
     * @param string|null $disk
     */
    public function url(string $path, ?string $disk = null): string;

    /**
     * Get file size
     *
     * @param string $path
     * @param string|null $disk
     */
    public function size(string $path, ?string $disk = null): int;

    /**
     * Get file mime type
     *
     * @param string $path
     * @param string|null $disk
     */
    public function mimeType(string $path, ?string $disk = null): string;
} 