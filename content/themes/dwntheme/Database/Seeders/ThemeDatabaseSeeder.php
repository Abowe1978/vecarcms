<?php

namespace Themes\DwnTheme\Database\Seeders;

use Illuminate\Database\Seeder;

class ThemeDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ThemeSeeder::class,
            DWNThemeMediaSeeder::class,
            DWNThemeHomepageSeeder::class,
            DWNThemeAboutPageSeeder::class,
            DWNThemeContactPageSeeder::class,
            DWNThemeMenuSeeder::class,
            DWNThemeBlogSeeder::class,
            DWNThemeLegalPagesSeeder::class,
            DWNThemeSitemapSeeder::class,
            DWNThemeWidgetSeeder::class,
        ]);
    }
}

