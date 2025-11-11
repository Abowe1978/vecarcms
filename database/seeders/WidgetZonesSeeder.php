<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WidgetZone;

class WidgetZonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = [
            [
                'name' => 'sidebar',
                'display_name' => 'Sidebar',
                'description' => 'Main sidebar widget area',
                'theme' => null,
                'is_active' => true,
            ],
            [
                'name' => 'footer',
                'display_name' => 'Footer',
                'description' => 'Footer widget area',
                'theme' => null,
                'is_active' => true,
            ],
            [
                'name' => 'header',
                'display_name' => 'Header',
                'description' => 'Header widget area',
                'theme' => null,
                'is_active' => true,
            ],
            [
                'name' => 'before_content',
                'display_name' => 'Before Content',
                'description' => 'Widget area before main content',
                'theme' => null,
                'is_active' => true,
            ],
            [
                'name' => 'after_content',
                'display_name' => 'After Content',
                'description' => 'Widget area after main content',
                'theme' => null,
                'is_active' => true,
            ],
        ];

        foreach ($zones as $zone) {
            WidgetZone::updateOrCreate(
                ['name' => $zone['name'], 'theme' => $zone['theme']],
                $zone
            );
        }

        $this->command->info('✅ Widget zones seeded successfully!');
        $this->command->info('   - Sidebar');
        $this->command->info('   - Footer');
        $this->command->info('   - Header');
        $this->command->info('   - Before Content');
        $this->command->info('   - After Content');
    }
}

