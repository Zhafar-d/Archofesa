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
            'owner_notes' => 'Telah disetujui & diverifikasi oleh pemilik kos.',
        ]);

        Notification::route('mail', config('mail.from.address'))
            ->notify(new OwnerBookingConfirmed($booking));

        return redirect()->route('owner.konfirmasi.index')->with('success', 'Booking berhasil dikonfirmasi dan status diubah menjadi SIAP HUNI.');
    }

    /**
     * Tolak konfirmasi booking oleh pemilik kos (Aksi Tolak / Reject).
     */
    public function reject(Request $request, Booking $booking)
    {
        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $reason = $request->input('reason', 'Kamar belum siap huni / ada perbaikan fisik oleh pemilik.');

        $booking->update([
            'status' => 'dibatalkan',
            'owner_notes' => 'Ditolak Pemilik: ' . $reason,
        ]);

        if ($booking->room) {
            $booking->room->update(['status' => 'available']);
        }

        return redirect()->route('owner.konfirmasi.index')->with('success', "Booking #{$booking->id} telah ditolak dan status kamar dikembalikan ke 'Tersedia'.");
    }
}
