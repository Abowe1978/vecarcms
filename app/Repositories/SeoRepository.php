<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Support\Collection;

class SeoRepository
{
    public function __construct(
        protected Setting $setting
    ) {}

    /**
     * Get all SEO settings
     */
    public function getSeoSettings(): Collection
    {
        return $this->setting
            ->where('group', 'seo')
            ->get();
    }

    /**
     * Get SEO settings as key-value array
     */
    public function getSeoSettingsArray(): array
    {
        return $this->setting
            ->where('group', 'seo')
            ->get()
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Update a setting value
     */
    public function updateSetting(string $key, mixed $value): void
    {
        $this->setting->updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => 'seo',
            ]
        );
    }

    /**
     * Get robots.txt content
     */
    public function getRobotsTxt(): ?string
    {
        $setting = $this->setting->where('key', 'robots_txt_content')->first();
        return $setting?->value;
    }
}

