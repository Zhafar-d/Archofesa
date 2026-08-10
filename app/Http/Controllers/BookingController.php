<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $rooms = Room::query()
            ->orderBy('room_code')
            ->get();

        $bookings = Booking::query()
            ->where('user_id', Auth::id())
            ->with('room')
            ->latest()
            ->get();

        return view('bookings.index', compact('rooms', 'bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'move_in_date' => ['required', 'date'],
            'move_out_date' => ['nullable', 'date'],
        ]);

        $moveInDate = \Carbon\Carbon::parse($validated['move_in_date']);
        $moveOutDate = $moveInDate->copy()->addMonth();

        $room = Room::findOrFail($validated['room_id']);

        if (! $room->is_available) {
            return back()->with('error', 'Kamar ini sudah terisi atau di-booking. Silakan pilih kamar lain yang masih tersedia.');
        }

        Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'room_code' => $room->room_code,
            'monthly_rate' => $room->price_monthly,
            'status' => 'pending',
            'payment_status' => 'pending',
            'move_in_date' => $moveInDate->format('Y-m-d'),
            'move_out_date' => $moveOutDate->format('Y-m-d'),
            'notes' => 'Booking request submitted via web app.',
        ]);

        $room->update(['status' => 'occupied']);

        return redirect()->route('booking')->with('success', 'Booking berhasil diajukan!');
    }

    public function riwayat()
    {
        $bookings = Booking::query()
            ->where('user_id', Auth::id())
            ->with(['room', 'payments'])
            ->latest()
            ->get();

        return view('bookings.history', compact('bookings'));
    }

    public function extendForm(Booking $booking)
    {
        // Ensure user owns this booking and it's dihuni
        if ($booking->user_id !== Auth::id() || $booking->status !== 'dihuni') {
            abort(403, 'Unauthorized action.');
        }

        return view('dashboard.customer.extend', compact('booking'));
    }

    public function extend(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id() || $booking->status !== 'dihuni') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'months' => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $booking->update([
            'move_out_date' => $booking->move_out_date ? $booking->move_out_date->addMonths($validated['months']) : now()->addMonths($validated['months']),
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Pengajuan perpanjangan sewa berhasil dikirim.');
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (in_array($booking->status, ['dibatalkan', 'selesai'])) {
            return back()->with('error', 'Booking ini sudah tidak dapat dibatalkan.');
        }

        $booking->update([
            'status' => 'dibatalkan',
        ]);

        if ($booking->room) {
            $booking->room->update([
                'status' => 'available',
            ]);
        }

        // Cancel any pending payments associated with this booking
        $booking->payments()->whereIn('status', ['menunggu', 'pending'])->update(['status' => 'batal']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Halaman bukti booking (printable)
     */
    public function bukti(Booking $booking)
    {
        abort_unless($booking->user_id === Auth::id(), 403);

        $booking->loadMissing(['room', 'user', 'payments']);

        // Ambil payment yang sudah dibayar, fallback ke yang terbaru
        $payment = $booking->payments()->where('status', 'dibayar')->latest()->first()
            ?? $booking->payments()->latest()->first();

        return view('payments.success', compact('payment', 'booking'));
    }
}
