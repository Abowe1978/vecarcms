<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            VeCarCMSRolesSeeder::class,
            PluginPermissionSeeder::class,
            DeveloperSeeder::class,
        ]);

        if (!config('app.demo_mode')) {
            $this->command?->warn('ℹ️  Demo mode disattivata: seeders demo ignorati.');
            return;
        }

        $this->call([
            DemoAdminSeeder::class,
            SettingsSeeder::class,
            WidgetZonesSeeder::class,
            SampleContentSeeder::class,
        ]);

        $this->seedActiveThemeDemoContent();
    }

    protected function seedActiveThemeDemoContent(): void
    {
        $themeSeederPath = base_path('content/themes/dwntheme/database/seeders/ThemeDatabaseSeeder.php');

        if (!File::exists($themeSeederPath)) {
            $this->command?->warn('⚠️  Nessun ThemeDatabaseSeeder trovato per dwntheme.');
            return;
        }

        require_once $themeSeederPath;

        $class = \Themes\DwnTheme\Database\Seeders\ThemeDatabaseSeeder::class;

        if (!class_exists($class)) {
            $this->command?->error('⚠️  Impossibile caricare ThemeDatabaseSeeder dal tema dwntheme.');
            return;
        }

        $this->call($class);
    }
}
