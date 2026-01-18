<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Use upsert to avoid duplicate key errors (e.g., when the same emails already exist).
        User::upsert([
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'username' => 'superadmin',
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teknisi',
                'email' => 'teknisi@example.com',
                'username' => 'teknisi',
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['email'], ['name', 'username', 'password']);

        $this->command->info('Users table seeded with Super Admin and Teknisi!');
    }
}
