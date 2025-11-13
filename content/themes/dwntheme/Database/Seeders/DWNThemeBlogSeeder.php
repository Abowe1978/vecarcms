<?php

namespace Themes\DwnTheme\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;

class DWNThemeBlogSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', env('DEVELOPER_EMAIL', 'carmine.damore@vecardigitalprogramming.com'))
            ->orWhere('email', 'admin@example.com')
            ->first();

        if (!$author) {
            $this->command?->error('⚠️  No author found to attach demo posts.');
            return;
        }

        $category = Category::updateOrCreate(
            ['slug' => 'product-updates'],
            [
                'name' => 'Product Updates',
                'description' => 'News and updates from the VeCarCMS team.',
            ]
        );

        $tagNames = ['Shortcodes', 'Menus', 'Themes'];
        $tags = collect($tagNames)->map(function ($name) {
            return Tag::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        });

        $posts = [
            [
                'title' => 'Getting Started with DWNTheme',
                'content' => '<p>DWNTheme ships with a complete set of shortcodes to reproduce the entire demo layout. In this article you will learn how to activate the theme and use the essential shortcodes.</p><h2>Available shortcodes</h2><ul><li>[dwn_hero]</li><li>[dwn_features]</li><li>[dwn_reviews]</li></ul>',
                'excerpt' => 'Activate DWNTheme and recreate the demo layout with the included shortcodes.',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'title' => 'Managing Menus in the Demo',
                'content' => '<p>The demo seeders automatically generate a primary navigation and a footer menu. If you want to personalise them, open the dashboard and edit the existing structure.</p><p>If you ever need to revert to the defaults, simply rerun the seeders.</p>',
                'excerpt' => 'Understand how the preconfigured demo menus work and how to adjust them.',
                'status' => 'published',
                'published_at' => now()->subDay(),
            ],
            [
                'title' => 'Demo Content and Artisan Seeders',
                'content' => '<p>The demo mirrors the original Sigma structure, but rebuilt with the new DWNTheme. Running <code>php artisan migrate:fresh --seed</code> always gives you a clean environment with homepage, about, contact, posts, and menus ready to go.</p>',
                'excerpt' => 'Overview of the seeders you can run to restore the demo environment.',
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ],
        ];

        foreach ($posts as $data) {
            $post = Post::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                array_merge($data, ['author_id' => $author->id])
            );

            $post->categories()->sync([$category->id]);
            $post->tags()->sync($tags->pluck('id')->all());
        }
    }
}

