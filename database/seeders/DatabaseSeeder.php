<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Always seed core roles/permissions and developer account
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
            ThemeSeeder::class,
            DWNThemeHomepageSeeder::class,
            DWNThemeAboutPageSeeder::class,
            DWNThemeContactPageSeeder::class,
            DWNThemeMenuSeeder::class,
            DWNThemeBlogSeeder::class,
            DWNThemeLegalPagesSeeder::class,
            SampleContentSeeder::class,
            DWNThemeSitemapSeeder::class,
        ]);
    }
}
