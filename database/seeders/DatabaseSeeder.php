<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'role' => 'user',
                'password' => bcrypt('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@archofesa.test'],
            [
                'name' => 'Admin User',
                'password' => 'admin12345',
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'owner@archofesa.test'],
            [
                'name' => 'Owner User',
                'password' => 'owner12345',
                'role' => 'owner',
            ]
        );

        $this->call([
            RoomSeeder::class,
        ]);
    }
}
