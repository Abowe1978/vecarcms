<?php

namespace App\Services\Interfaces;

interface SlugServiceInterface
{
    /**
     * Generate a unique slug for a model
     *
     * @param string $title The title to generate the slug from
     * @param string $model The model class to check uniqueness against
     * @param string|null $currentSlug The current slug (for updates)
     * @param int|null $excludeId The ID to exclude from uniqueness check
     * @return string
     */
    public function generateUniqueSlug(
        string $title,
        string $model,
        ?string $currentSlug = null,
        ?int $excludeId = null
    ): string;

    /**
     * Check if a slug is unique for a model
     *
     * @param string $slug
     * @param string $model
     * @param int|null $excludeId
     * @return bool
     */
    public function isUniqueSlug(string $slug, string $model, ?int $excludeId = null): bool;
} 