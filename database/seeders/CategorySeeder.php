<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate existing categories
        Category::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Create categories
        $categories = [
            [
                'name' => 'Events',
                'slug' => 'events',
                'description' => 'Meetups, launches, and happenings across the VeCarCMS community.',
                'color' => '#4F46E5', // Indigo
            ],
            [
                'name' => 'Product History',
                'slug' => 'product-history',
                'description' => 'Highlights from the evolution of VeCarCMS and its milestones.',
                'color' => '#B45309', // Amber dark
            ],
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Deep-dives into the technical side of VeCarCMS and new features.',
                'color' => '#059669', // Emerald
            ],
            [
                'name' => 'Best Practices',
                'slug' => 'best-practices',
                'description' => 'Guides and workflows to keep your VeCarCMS projects in top shape.',
                'color' => '#B91C1C', // Red
            ],
            [
                'name' => 'Use Cases',
                'slug' => 'use-cases',
                'description' => 'Stories from teams using VeCarCMS in the real world.',
                'color' => '#7E22CE', // Purple
            ],
            [
                'name' => 'Releases',
                'slug' => 'releases',
                'description' => 'Release notes and announcements for the latest VeCarCMS versions.',
                'color' => '#1E40AF', // Blue
            ],
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
        
        $this->command->info('Created ' . count($categories) . ' demo categories.');
    }
}
