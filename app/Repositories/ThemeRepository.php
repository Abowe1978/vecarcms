<?php

namespace App\Repositories;

use App\Models\Theme;
use Illuminate\Database\Eloquent\Collection;

class ThemeRepository
{
    public function __construct(
        protected Theme $theme
    ) {}

    public function all(): Collection
    {
        return $this->theme->all();
    }

    public function find(int $id): ?Theme
    {
        return $this->theme->find($id);
    }

    public function findByName(string $name): ?Theme
    {
        return $this->theme->where('name', $name)->first();
    }

    public function getActive(): ?Theme
    {
        return $this->theme->where('is_active', true)->first();
    }

    public function create(array $data): Theme
    {
        return $this->theme->create($data);
    }

    public function update(Theme $theme, array $data): bool
    {
        return $theme->update($data);
    }

    public function delete(Theme $theme): bool
    {
        return $theme->delete();
    }

    public function activate(Theme $theme): bool
    {
        return $theme->activate();
    }

    public function updateSettings(Theme $theme, array $settings): bool
    {
        return $theme->update(['settings' => json_encode($settings)]);
    }
}

