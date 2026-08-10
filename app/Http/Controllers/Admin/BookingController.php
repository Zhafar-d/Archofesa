<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $bookings = Booking::with('user', 'room')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, fn ($query) => $query->whereHas('user', fn ($query) => $query->where('name', 'like', "%{$search}%")))
            ->latest()
            ->paginate(15);

        return view('admin.bookings.index', compact('bookings', 'status', 'search'));
    }

    public function show(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function processPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_method' => ['required', 'in:midtrans'],
        ]);

        $payment = Payment::create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'amount' => $booking->monthly_rate,
            'currency' => 'IDR',
            'status' => 'pending',
            'payment_method' => $request->input('payment_method'),
        ]);

        $booking->update([
            'payment_status' => 'pending',
            'status' => 'menunggu_pembayaran',
        ]);

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Pembayaran dibuat dan status booking diperbarui.');
    }

    public function confirmToOwner(Booking $booking)
    {
        $booking->update([
            'status' => 'menunggu_konfirmasi_owner',
        ]);

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking diteruskan ke pemilik untuk konfirmasi.');
    }

    public function confirmReadyToOccupy(Booking $booking)
    {
        $booking->update([
            'status' => 'dihuni',
            'payment_status' => 'paid',
        ]);

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking dikonfirmasi dan status dihuni aktif.');
    }
}
