<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PluginPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create manage_plugins permission
        $permission = Permission::firstOrCreate([
            'name' => 'manage_plugins',
            'guard_name' => 'web'
        ]);

        $this->command->info('✅ Permission "manage_plugins" created');

        // Assign to admin roles
        $rolesToAssign = ['super_admin', 'developer', 'admin'];

        foreach ($rolesToAssign as $roleName) {
            $role = Role::where('name', $roleName)->first();
            
            if ($role) {
                $role->givePermissionTo($permission);
                $this->command->info("✅ Permission assigned to '{$roleName}' role");
            } else {
                $this->command->warn("⚠️  Role '{$roleName}' not found");
            }
        }

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $this->command->info('✅ Permission cache cleared');
    }
}

