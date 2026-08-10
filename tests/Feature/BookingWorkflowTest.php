<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_dashboard_shows_latest_bookings_from_database(): void
    {
        $owner = User::factory()->create([
            'name' => 'Owner User',
            'email' => 'owner@example.com',
            'role' => 'owner',
        ]);

        $room = Room::create([
            'room_code' => 'A-101',
            'size' => '3x4m',
            'price_monthly' => 950000,
            'status' => 'available',
            'description' => 'Bright room',
        ]);

        $tenant = User::factory()->create([
            'name' => 'Tenant User',
            'email' => 'tenant@example.com',
            'role' => 'customer',
        ]);

        $booking = Booking::create([
            'user_id' => $tenant->id,
            'room_id' => $room->id,
            'room_code' => $room->room_code,
            'monthly_rate' => $room->price_monthly,
            'status' => 'pending',
            'payment_status' => 'pending',
            'move_in_date' => now()->addDays(5)->toDateString(),
            'move_out_date' => now()->addMonths(1)->toDateString(),
            'notes' => 'Needs review',
        ]);

        $response = $this->actingAs($owner)->get('/owner/dashboard');

        $response->assertStatus(200);
        $response->assertSee($booking->room_code);
        $response->assertSee('Needs review');
    }
}
