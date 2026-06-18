<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL');
        $name     = env('ADMIN_NAME');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->command->warn('AdminUserSeeder skipped: ADMIN_EMAIL and ADMIN_PASSWORD must be set in .env');
            return;
        }

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name'              => $name ?: 'Admin',
                'password'          => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['super_admin']);

        $this->command->info("Admin user ready: {$email}");
    }
}
