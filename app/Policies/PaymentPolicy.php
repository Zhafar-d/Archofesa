<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Payment $payment): bool
    {
        return $user->role === 'admin' || $payment->user_id === $user->id;
    }

    public function pay(User $user, Payment $payment): bool
    {
        return $user->role === 'user' && $payment->user_id === $user->id && $payment->status === 'pending';
    }
}
