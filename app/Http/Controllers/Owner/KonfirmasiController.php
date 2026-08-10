<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Notifications\OwnerBookingConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class KonfirmasiController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('user', 'room')
            ->where('status', 'menunggu_konfirmasi_owner')
            ->latest()
            ->get();

        return view('owner.konfirmasi.index', compact('bookings'));
    }

    public function confirm(Booking $booking)
    {
        $booking->update([
            'status' => 'siap_huni',
        ]);

        Notification::route('mail', config('mail.from.address'))
            ->notify(new OwnerBookingConfirmed($booking));

        return redirect()->route('owner.konfirmasi.index')->with('success', 'Booking dikonfirmasi dan status diubah menjadi siap huni.');
    }
}
