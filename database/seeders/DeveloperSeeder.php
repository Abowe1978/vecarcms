<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DeveloperSeeder extends Seeder
{
    /**
     * Seed the Developer user
     * 
     * This creates a default developer account for system administration.
     * Credentials can be customized via environment variables.
     */
    public function run(): void
    {
        // Check if developer already exists
        $email = env('DEVELOPER_EMAIL', 'carmine.damore@vecardigitalprogramming.com');
        
        $developer = User::where('email', $email)->first();
        
        if ($developer) {
            $this->command->warn("⚠️  Developer user already exists: {$email}");
            return;
        }

        // Create developer user
        $developer = User::create([
            'name' => env('DEVELOPER_NAME', 'Carmine'),
            'surname' => env('DEVELOPER_SURNAME', "D'Amore"),
            'email' => $email,
            'password' => Hash::make(env('DEVELOPER_PASSWORD', 'Jfkf01b4%')),
            'email_verified_at' => now(),
            'mobile_phone' => env('DEVELOPER_PHONE', null),
            'city' => env('DEVELOPER_CITY', null),
            'country' => env('DEVELOPER_COUNTRY', 'IT'),
            'gdpr_consent' => true,
        ]);

        // Assign Developer role (full access)
        $developer->assignRole('developer');

        $this->command->info('✅ Developer user created successfully!');
        $this->command->newLine();
        $this->command->info("   Name: {$developer->name} {$developer->surname}");
        $this->command->info("   Email: {$developer->email}");
        $this->command->info("   Password: " . env('DEVELOPER_PASSWORD', 'developer123'));
        $this->command->info("   Role: Developer (Full System Access)");
        $this->command->newLine();
        $this->command->warn('⚠️  Remember to change the default password in production!');
    }
}
