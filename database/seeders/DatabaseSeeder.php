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
        $this->call([
            // VeCarCMS - Core Seeders
            VeCarCMSRolesSeeder::class,
            DeveloperSeeder::class,
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
            
            
            // OLD RREC Seeders (commented out - will be removed)
            //PlanSeeder::class,
            //PlansAndPaymentPlansSeeder::class,
            //RolesAndPermissionsSeeder::class, // OLD RREC roles
            //UsersTableSeeder::class,
            //CategorySeeder::class,
            //SectionSeeder::class,
            //PostSeeder::class,
            //MediaSeeder::class,
            //MemberSectionSeeder::class,
            //MakeSeeder::class,
            //VehicleSeeder::class,
            //UserVehicleSeeder::class,
            //UserPlanSeeder::class,
            //PaymentSeeder::class,
            //CarDirectorySeeder::class,
        ]);
    }
}
