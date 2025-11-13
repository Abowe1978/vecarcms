<?php

namespace Themes\DwnTheme\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Facades\File;

class DWNThemeSitemapSeeder extends Seeder
{
    public function run(): void
    {
        $pages = Page::where('is_published', true)
            ->orderBy('title')
            ->get(['title', 'slug']);

        $output = "<h1>Site Map</h1>\n<ul>\n";

        foreach ($pages as $page) {
            $url = $page->slug === 'home' ? url('/') : url('/' . $page->slug);
            $output .= "    <li><a href=\"{$url}\">{$page->title}</a></li>\n";
        }

        $output .= "</ul>\n";

        $this->command?->line($output);

        File::put(base_path('storage/app/demo-sitemap.html'), $output);
    }
}

