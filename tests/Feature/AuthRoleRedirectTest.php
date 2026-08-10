<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRoleRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_is_redirected_to_admin_dashboard_after_login(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin-login@example.com',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_owner_user_is_redirected_to_owner_dashboard_after_login(): void
    {
        $user = User::factory()->create([
            'name' => 'Owner User',
            'email' => 'owner-login@example.com',
            'role' => 'owner',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/owner/dashboard');
    }
}
