<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class PenghuniController extends Controller
{
    public function index(Request $request)
    {
        $tenants = Booking::with('user', 'room')
            ->where('status', 'dihuni')
            ->latest()
            ->paginate(15);

        return view('admin.penghuni.index', compact('tenants'));
    }

    public function updateDueDate(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'move_out_date' => 'required|date|after:move_in_date',
        ]);

        $booking->update($validated);

        return redirect()->route('admin.penghuni.index')->with('success', 'Tanggal jatuh tempo tagihan berhasil diperbarui.');
    }
}
