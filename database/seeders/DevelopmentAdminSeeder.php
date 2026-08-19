<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevelopmentAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Safety guard: only execute in development/local/testing environments
        if (! app()->environment('local', 'development', 'testing')) {
            $this->command?->warn('DevelopmentAdminSeeder skipped: only allowed in local/development/testing environment.');

            return;
        }

        $name = env('ADMIN_NAME', 'ANS Administrator');
        $email = env('ADMIN_EMAIL', 'admin@avenasa.co.id');
        $password = env('ADMIN_PASSWORD', 'password');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $this->command?->info("Development admin user [{$email}] successfully prepared.");
    }
}
