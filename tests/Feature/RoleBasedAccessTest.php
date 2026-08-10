<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_is_redirected_when_trying_to_access_owner_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->get('/owner/dashboard');

        $response->assertRedirect('/dashboard');
    }

    public function test_owner_can_access_owner_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Owner User',
            'email' => 'owner@example.com',
            'role' => 'owner',
        ]);

        $response = $this->actingAs($user)->get('/owner/dashboard');

        $response->assertStatus(200);
    }
}
