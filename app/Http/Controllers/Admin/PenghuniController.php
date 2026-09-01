<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class PenghuniController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $tenants = Booking::with('user', 'room')
            ->where('status', 'dihuni')
            ->when($search, fn ($query) => $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%")))
            ->latest('move_in_date')
            ->paginate(15);

        return view('admin.penghuni.index', compact('tenants', 'search'));
    }

    public function updateDueDate(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'move_out_date' => 'required|date|after:move_in_date',
        ]);

        $booking->update($validated);

        return redirect()->route('admin.penghuni.index')->with('success', 'Tanggal jatuh tempo tagihan berhasil diperbarui.');
    }

    /**
     * Selesaikan hunian (Check-Out Penghuni).
     */
    public function checkout(Booking $booking)
    {
        $booking->update([
            'status' => 'selesai',
        ]);

        if ($booking->room) {
            $booking->room->update(['status' => 'available']);
        }

        return redirect()->route('admin.penghuni.index')->with('success', "Penghuni kamar {$booking->room_code} berhasil di-checkout. Status kamar kembali 'Tersedia'.");
    }

    /**
     * Hapus data penghuni aktif / booking (Aksi Delete).
     */
    public function destroy(Booking $booking)
    {
        $room = $booking->room;
        $roomCode = $booking->room_code;

        $booking->payments()->delete();
        $booking->delete();

        if ($room) {
            $hasOtherActiveBooking = Booking::where('room_id', $room->id)
                ->whereIn('status', ['pending', 'menunggu_pembayaran', 'dibayar', 'menunggu_konfirmasi_owner', 'siap_huni', 'dihuni'])
                ->exists();

            if (! $hasOtherActiveBooking) {
                $room->update(['status' => 'available']);
            }
        }

        return redirect()->route('admin.penghuni.index')->with('success', "Data penghuni kamar {$roomCode} berhasil dihapus dari sistem.");
    }

    /**
     * Kirim pesan pengingat jatuh tempo via WhatsApp.
     */
    public function sendReminder(Booking $booking, WhatsAppService $whatsAppService)
    {
        $result = $whatsAppService->sendRentalReminder($booking);

        if ($result['success']) {
            $waUrl = $result['wa_url'] ?? null;
            $msg = $result['message'] ?? 'Pengingat WhatsApp berhasil dikirim.';

            return redirect()->route('admin.penghuni.index')
                ->with('success', $msg)
                ->with('wa_url', $waUrl);
        }

        return redirect()->route('admin.penghuni.index')
            ->with('error', 'Gagal mengirim WhatsApp: ' . ($result['message'] ?? 'Kesalahan tidak diketahui.'))
            ->with('wa_url', $result['wa_url'] ?? null);
    }
}
