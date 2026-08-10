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
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
        ]);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@archofesa.test',
            'password' => 'admin12345', // plain text — cast 'hashed' di model yang handle
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Owner User',
            'email' => 'owner@archofesa.test',
            'password' => 'owner12345', // plain text — cast 'hashed' di model yang handle
            'role' => 'owner',
        ]);

        $this->call([
            RoomSeeder::class,
        ]);
    }
}
