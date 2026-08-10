<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDashboardDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_dashboard_shows_property_information_and_empty_states_when_no_records_exist(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->get('/customer/dashboard');

        $response->assertOk();
        $response->assertSee('ARCHOFESA KOST');
        $response->assertSee('18');
        $response->assertSee('Family Room');
        $response->assertSee('Student Room');
        $response->assertSee('No active booking');
        $response->assertSee('Reviews');
        $response->assertSee('No reviews available yet');
    }
}
