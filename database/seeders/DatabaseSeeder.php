<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Generate/Install Filament Shield setup and permissions
        Artisan::call('shield:generate', [
            '--all' => true,
            '--option' => 'policies_and_permissions',
            '--panel' => 'admin',
            '--no-interaction' => true,
        ]);

        // 2. Create the Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('12345678'),
                'email_verified_at' => now(),
            ]
        );

        // 3. Assign the super_admin role to the Admin user
        Artisan::call('shield:super-admin', [
            '--user' => $admin->id,
            '--panel' => 'admin',
            '--no-interaction' => true,
        ]);

        // 4. Create 2 additional fake users
        User::factory()->count(2)->create();
        Post::factory()->count(2)->create();
    }
}
