<?php

namespace Themes\DwnTheme\Database\Seeders;

use Illuminate\Database\Seeder;

require_once __DIR__ . '/ThemeSeeder.php';
require_once __DIR__ . '/DWNThemeHomepageSeeder.php';
require_once __DIR__ . '/DWNThemeAboutPageSeeder.php';
require_once __DIR__ . '/DWNThemeContactPageSeeder.php';
require_once __DIR__ . '/DWNThemeMenuSeeder.php';
require_once __DIR__ . '/DWNThemeBlogSeeder.php';
require_once __DIR__ . '/DWNThemeLegalPagesSeeder.php';
require_once __DIR__ . '/DWNThemeSitemapSeeder.php';

class ThemeDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ThemeSeeder::class,
            DWNThemeHomepageSeeder::class,
            DWNThemeAboutPageSeeder::class,
            DWNThemeContactPageSeeder::class,
            DWNThemeMenuSeeder::class,
            DWNThemeBlogSeeder::class,
            DWNThemeLegalPagesSeeder::class,
            DWNThemeSitemapSeeder::class,
        ]);
    }
}

