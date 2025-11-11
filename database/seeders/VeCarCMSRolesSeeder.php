<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class VeCarCMSRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==============================================
        // PERMISSIONS
        // ==============================================

        $permissions = [
            // System Management
            'manage_system',
            'manage_modules',
            'manage_roles',
            'manage_permissions',
            
            // User Management
            'manage_users',
            'manage_admins',
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Content Management
            'manage_posts',
            'view_posts',
            'create_posts',
            'edit_posts',
            'edit_own_posts',
            'delete_posts',
            'delete_own_posts',
            'publish_posts',
            
            'manage_pages',
            'view_pages',
            'create_pages',
            'edit_pages',
            'edit_own_pages',
            'delete_pages',
            'delete_own_pages',
            'publish_pages',
            
            'manage_categories',
            'manage_tags',
            
            // Comments
            'manage_comments',
            'view_comments',
            'create_comments',
            'edit_comments',
            'delete_comments',
            'moderate_comments',
            
            // Media Library
            'manage_media',
            'view_media',
            'upload_media',
            'edit_media',
            'delete_media',
            
            // Menu Builder
            'manage_menus',
            
            // Widget System
            'manage_widgets',
            
            // Theme Management
            'manage_themes',
            'customize_themes',
            
            // Page Builder
            'use_page_builder',
            
            // Clone/Duplicate
            'duplicate_posts',
            'duplicate_pages',
            
            // SEO
            'manage_seo',
            
            // Settings
            'manage_settings',
            'view_settings',
            
            // Analytics
            'view_analytics',
            'view_dashboard',
            
            // Translations
            'manage_translations',
            
            // Forms
            'manage_forms',
            'view_submissions',
            
            // Integrations
            'manage_integrations',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // ==============================================
        // ROLES
        // ==============================================

        // 1. DEVELOPER - Full system access
        $developer = Role::create(['name' => 'developer']);
        $developer->givePermissionTo(Permission::all());

        // 2. SUPER ADMIN - All content + user management
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo([
            // Users
            'manage_users',
            'manage_admins',
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // All Content
            'manage_posts',
            'view_posts',
            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',
            
            'manage_pages',
            'view_pages',
            'create_pages',
            'edit_pages',
            'delete_pages',
            'publish_pages',
            
            'manage_categories',
            'manage_tags',
            
            // Comments
            'manage_comments',
            'view_comments',
            'edit_comments',
            'delete_comments',
            'moderate_comments',
            
            // Media
            'manage_media',
            'view_media',
            'upload_media',
            'edit_media',
            'delete_media',
            
            // Menu & Widgets
            'manage_menus',
            'manage_widgets',
            
            // Themes
            'manage_themes',
            'customize_themes',
            
            // Page Builder
            'use_page_builder',
            
            // Clone
            'duplicate_posts',
            'duplicate_pages',
            
            // SEO
            'manage_seo',
            
            // Settings
            'manage_settings',
            'view_settings',
            
            // Analytics
            'view_analytics',
            'view_dashboard',
            
            // Translations
            'manage_translations',
            
            // Forms
            'manage_forms',
            'view_submissions',
            
            // Integrations
            'manage_integrations',
        ]);

        // 3. ADMIN - Content management + limited user management
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            // Limited user management (no admins)
            'view_users',
            'create_users',
            'edit_users',
            
            // All Content
            'manage_posts',
            'view_posts',
            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',
            
            'manage_pages',
            'view_pages',
            'create_pages',
            'edit_pages',
            'delete_pages',
            'publish_pages',
            
            'manage_categories',
            'manage_tags',
            
            // Comments
            'manage_comments',
            'view_comments',
            'edit_comments',
            'delete_comments',
            'moderate_comments',
            
            // Media
            'manage_media',
            'view_media',
            'upload_media',
            'edit_media',
            'delete_media',
            
            // Menu & Widgets
            'manage_menus',
            'manage_widgets',
            
            // Theme customization (not management)
            'customize_themes',
            
            // Page Builder
            'use_page_builder',
            
            // Clone
            'duplicate_posts',
            'duplicate_pages',
            
            // SEO
            'manage_seo',
            
            // Settings (view only)
            'view_settings',
            
            // Analytics
            'view_analytics',
            'view_dashboard',
            
            // Forms
            'manage_forms',
            'view_submissions',
        ]);

        // 4. MANAGER - Limited content management
        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            // Own Content Only
            'view_posts',
            'create_posts',
            'edit_own_posts',
            'delete_own_posts',
            
            'view_pages',
            'create_pages',
            'edit_own_pages',
            'delete_own_pages',
            
            'manage_categories',
            'manage_tags',
            
            // Comments (view and create)
            'view_comments',
            'create_comments',
            
            // Media
            'view_media',
            'upload_media',
            'edit_media',
            
            // Page Builder
            'use_page_builder',
            
            // Clone own content
            'duplicate_posts',
            'duplicate_pages',
            
            // Dashboard
            'view_dashboard',
        ]);

        // 5. USER - Basic access
        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            // Read only
            'view_posts',
            'view_pages',
            
            // Comments
            'view_comments',
            'create_comments',
            
            // Dashboard (own profile)
            'view_dashboard',
        ]);

        $this->command->info('✅ VeCarCMS roles and permissions created successfully!');
        $this->command->info('   - Developer: Full access');
        $this->command->info('   - Super Admin: All content + user management');
        $this->command->info('   - Admin: Content management');
        $this->command->info('   - Manager: Limited content (own posts/pages)');
        $this->command->info('   - User: Read only + comments');
    }
}

