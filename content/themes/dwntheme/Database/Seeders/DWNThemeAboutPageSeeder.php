<?php

namespace Themes\DwnTheme\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Themes\DwnTheme\Database\Seeders\DWNThemeMediaSeeder;

class DWNThemeAboutPageSeeder extends Seeder
{
    public function run(): void
    {
        $storyImage = DWNThemeMediaSeeder::mediaUrl(
            'about-story',
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1600&q=80'
        );

        $aboutContent = trim(<<<SHORTCODES
[dwn_about_header title="About us" subtitle="Launched in 2017, VeCarCMS helps teams design digital experiences that are accessible to everyone."]
[dwn_about_gallery]
[dwn_about_stats locations="12" customers="75K" staff="160"]
[dwn_about_story eyebrow="How it started" title="Our story" image="{$storyImage}"]
<p>In 2015 our founders realised that teams needed better tools to deliver better results. From that insight, VeCarCMS was born: a complete toolkit for building pages, funnels, and portals without slowing down marketing and development.</p>
<p>Today we keep improving the platform with new integrations, automation, and performance enhancements. Thousands of professionals rely on VeCarCMS to launch projects faster while maintaining the highest standards for quality and reliability.</p>
[/dwn_about_story]
[dwn_about_team title="Our team"]
[dwn_about_careers text="Want to join our team?" link_text="We are hiring" link_url="/careers"]
SHORTCODES);

        Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About Us',
                'content' => $aboutContent,
                'template' => 'full-width',
                'is_published' => true,
                'use_page_builder' => false,
                'show_title' => false,
            ]
        );
    }
}

