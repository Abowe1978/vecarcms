<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DemoAdminSeeder extends Seeder
{
    /**
     * Seed the database with the demo admin user.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Demo',
                'surname' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $user->syncRoles(['admin']);
    }
}


