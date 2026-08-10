<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Booking $booking): bool
    {
        return $user->role === 'admin' || $booking->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'user';
    }
}
