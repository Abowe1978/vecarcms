<?php

namespace Themes\DwnTheme\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class DWNThemeLegalPagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'content' => '<p>This privacy policy explains how VeCarCMS collects, uses, stores, and protects personal data. It is an example document for demo purposes only. Replace this content with your organisation\'s official privacy policy before going live.</p>',
            ],
            [
                'slug' => 'terms-of-service',
                'title' => 'Terms of Service',
                'content' => '<p>These demo terms of service outline the rules for using the VeCarCMS demo site. They are provided as placeholder content. Ensure you review and publish your own legal terms before launching your project.</p>',
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                [
                    'title' => $pageData['title'],
                    'content' => $pageData['content'],
                    'template' => 'full-width',
                    'is_published' => true,
                    'use_page_builder' => false,
                    'meta_description' => $pageData['title'] . ' for the VeCarCMS demo site.',
                ]
            );
        }
    }
}

