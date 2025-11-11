<?php

namespace App\Repositories;

use App\Models\Plugin;
use Illuminate\Support\Collection;

class PluginRepository
{
    public function __construct(
        protected Plugin $plugin
    ) {}

    /**
     * Get all plugins
     */
    public function all(): Collection
    {
        return $this->plugin->orderBy('name')->get();
    }

    /**
     * Find plugin by ID
     */
    public function find(int $id): ?Plugin
    {
        return $this->plugin->find($id);
    }

    /**
     * Find plugin by slug
     */
    public function findBySlug(string $slug): ?Plugin
    {
        return $this->plugin->where('slug', $slug)->first();
    }

    /**
     * Get active plugins
     */
    public function getActive(): Collection
    {
        return $this->plugin->where('is_active', true)->get();
    }

    /**
     * Create or update plugin
     */
    public function updateOrCreate(array $attributes, array $values): Plugin
    {
        return $this->plugin->updateOrCreate($attributes, $values);
    }

    /**
     * Update plugin
     */
    public function update(Plugin $plugin, array $data): bool
    {
        return $plugin->update($data);
    }

    /**
     * Delete plugin
     */
    public function delete(Plugin $plugin): bool
    {
        return $plugin->delete();
    }

    /**
     * Delete plugins not in slugs array
     */
    public function deleteNotInSlugs(array $slugs): int
    {
        return $this->plugin->whereNotIn('slug', $slugs)->delete();
    }

    /**
     * Check if plugin exists by slug
     */
    public function existsBySlug(string $slug): bool
    {
        return $this->plugin->where('slug', $slug)->exists();
    }
}

