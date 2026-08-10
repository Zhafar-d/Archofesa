<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $totalRooms = Room::count();

        $monthlyRevenue = Payment::whereYear('paid_at', now()->year)
            ->whereMonth('paid_at', now()->month)
            ->where('status', 'paid')
            ->sum('amount');

        $roomStatuses = Room::orderBy('room_code')->get()->map(fn ($room) => [
            'code' => $room->room_code,
            'status' => $room->status,
        ]);

        $pendingConfirmations = Booking::with('user', 'room')
            ->where('status', 'menunggu_konfirmasi_owner')
            ->latest()
            ->get();

        return view('owner.dashboard.index', compact(
            'occupiedRooms',
            'totalRooms',
            'monthlyRevenue',
            'roomStatuses',
            'pendingConfirmations'
        ));
    }
}
