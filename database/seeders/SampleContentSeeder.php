<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Page;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Str;

class SampleContentSeeder extends Seeder
{
    /**
     * Seed sample content for testing
     */
    public function run(): void
    {
        $developer = User::where('email', env('DEVELOPER_EMAIL', 'carmine.damore@vecardigitalprogramming.com'))->first();
        
        if (!$developer) {
            $this->command->error('⚠️  Developer user not found. Run DeveloperSeeder first.');
            return;
        }

        // Create sample categories
        $categories = [];
        $categoryNames = ['Technology', 'Design', 'Business', 'Lifestyle'];
        
        foreach ($categoryNames as $name) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => "Articles about {$name}"]
            );
            $categories[] = $category;
        }

        // Create sample tags
        $tags = [];
        $tagNames = ['Laravel', 'CMS', 'Web Development', 'Design', 'Tutorial'];
        
        foreach ($tagNames as $name) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
            $tags[] = $tag;
        }

        // Create sample posts
        $samplePosts = [
            [
                'title' => 'Welcome to VeCarCMS',
                'content' => '<p>Welcome to VeCarCMS, a powerful and modern content management system built with Laravel. This CMS combines the best of WordPress functionality with Laravel\'s robust architecture.</p><p>VeCarCMS features include:</p><ul><li>Drag & drop Page Builder (like Elementor)</li><li>Menu Builder (like WordPress)</li><li>Widget System with drag & drop</li><li>Advanced Media Library</li><li>SEO Tools</li><li>And much more!</li></ul>',
                'excerpt' => 'Discover the power of VeCarCMS - a modern Laravel-based content management system with WordPress-like features.',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'title' => 'Getting Started with Page Builder',
                'content' => '<p>The Page Builder in VeCarCMS allows you to create stunning layouts without writing code. Simply drag and drop elements, customize their appearance, and publish.</p><h2>Key Features</h2><ul><li>Visual editing</li><li>Real-time preview</li><li>Responsive design</li><li>Template library</li></ul>',
                'excerpt' => 'Learn how to use the powerful Page Builder to create beautiful pages.',
                'status' => 'published',
                'published_at' => now()->subDay(),
            ],
            [
                'title' => 'Managing Your Content',
                'content' => '<p>Content management in VeCarCMS is intuitive and powerful. You can organize your posts with categories and tags, use featured images, and optimize for SEO.</p><p>The admin panel provides all the tools you need to manage your website efficiently.</p>',
                'excerpt' => 'A comprehensive guide to managing content in VeCarCMS.',
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ],
        ];

        foreach ($samplePosts as $postData) {
            $post = Post::updateOrCreate(
                ['slug' => Str::slug($postData['title'])],
                array_merge($postData, ['author_id' => $developer->id])
            );

            // Attach random category and tags
            $post->categories()->sync([$categories[array_rand($categories)]->id]);
            $selectedTags = collect($tags)->random(2)->pluck('id')->toArray();
            $post->tags()->sync($selectedTags);
        }

        // Create sample pages
        // No sample pages here: specific page seeders handle DWNTheme structure.

        $this->command->info('✅ Sample content created successfully!');
        $this->command->newLine();
        $this->command->info("   Posts: " . count($samplePosts));
        $this->command->info("   Categories: " . count($categories));
        $this->command->info("   Tags: " . count($tags));
        $this->command->newLine();
    }
}

