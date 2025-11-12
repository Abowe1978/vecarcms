<?php

namespace Themes\DwnTheme\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Themes\DwnTheme\Database\Seeders\DWNThemeMediaSeeder;

class DWNThemeHomepageSeeder extends Seeder
{
    public function run(): void
    {
        $heroImage = DWNThemeMediaSeeder::mediaUrl(
            'homepage-hero',
            'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1600&q=80'
        );

        $homepageContent = trim(<<<SHORTCODES
[dwn_hero title="Launch marketing sites without bottlenecks" description="VeCarCMS pairs a Laravel-first CMS with the DWNTheme shortcode library so teams compose new pages in minutes." button1_text="Start a demo" button1_url="/contact" button2_text="Explore features" button2_url="/about" image="{$heroImage}"]

[dwn_logos title="Trusted by product-led teams worldwide"]

[dwn_features title="Everything you need to ship fast" subtitle="Design, test, and launch high-converting experiences without leaving Laravel."]

[dwn_highlights]

[dwn_reviews title="Teams deliver better experiences with VeCarCMS" subtitle="Success stories from fast-moving marketing squads" primary_button_text="See customer stories" primary_button_url="/about" secondary_button_text="Start your free trial" secondary_button_url="/contact"]

[latest-posts count="3" layout="grid"]

[dwn_features_summary title="Design, launch, and iterate with confidence" subtitle="Reusable shortcodes, flexible blocks, and analytics-ready layouts keep your marketing team moving." button_text="Book a strategy call" button_url="/contact"]

[dwn_about title="Powered by a team of builders" button_text="Meet the team" button_url="/about"]
VeCarCMS is backed by developers and designers obsessed with velocity. Every shortcode matches the DWNTheme design system and delivers full-bleed storytelling out of the box.
[/dwn_about]

[dwn_cta title="Ready to launch your next project?" description="Spin up the DWNTheme demo, invite your team, and start shipping new pages in record time." button1_text="Launch demo" button1_url="/contact" button2_text="Talk to us" button2_url="/contact"]
SHORTCODES);

        $page = Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Homepage',
                'content' => $homepageContent,
                'template' => 'home',
                'is_published' => true,
                'use_page_builder' => false,
            ]
        );

        $page->setAsHomepage();

        Page::updateOrCreate(
            ['slug' => 'blog'],
            [
                'title' => 'Blog',
                'content' => '<p>Latest news and articles from the VeCarCMS team. This is demo content – customise it with your own copy before launch.</p>',
                'template' => 'full-width',
                'is_published' => true,
                'use_page_builder' => false,
                'is_blog' => true,
            ]
        );
    }
}

