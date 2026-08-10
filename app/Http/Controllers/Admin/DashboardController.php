<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now       = now();
        $thisMonth = $now->month;
        $thisYear  = $now->year;
        $lastMonth = $now->copy()->subMonth();

        // ── Stat cards ─────────────────────────────────────────────────────
        $totalRooms    = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $availableRooms = Room::where('status', 'available')->count();

        $totalUsers    = User::where('role', 'user')->count();
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();

        // ── Revenue ────────────────────────────────────────────────────────
        $revenueThisMonth = Payment::whereYear('paid_at', $thisYear)
            ->whereMonth('paid_at', $thisMonth)
            ->where('status', 'dibayar')
            ->sum('amount');

        $revenueLastMonth = Payment::whereYear('paid_at', $lastMonth->year)
            ->whereMonth('paid_at', $lastMonth->month)
            ->where('status', 'dibayar')
            ->sum('amount');

        $revenueTotal = Payment::where('status', 'dibayar')->sum('amount');

        $revenueTrend = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : ($revenueThisMonth > 0 ? 100 : 0);

        $pendingPayments = Payment::where('status', 'pending')->count();
        $pendingAmount   = Payment::where('status', 'pending')->sum('amount');

        // ── Revenue per bulan (6 bulan terakhir) ──────────────────────────
        $monthlyRevenue = collect(range(5, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            $amount = Payment::whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
                ->where('status', 'dibayar')
                ->sum('amount');
            return [
                'month'  => $date->format('M Y'),
                'amount' => (float) $amount,
            ];
        });

        // ── Transaksi terbaru ──────────────────────────────────────────────
        $recentPayments = Payment::with(['booking.user', 'booking'])
            ->where('status', 'dibayar')
            ->latest('paid_at')
            ->take(8)
            ->get();

        // ── Pending payments ──────────────────────────────────────────────
        $pendingPaymentList = Payment::with(['booking.user', 'booking'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // ── Booking terbaru ────────────────────────────────────────────────
        $latestBookings = Booking::with(['user', 'room'])
            ->latest()
            ->take(5)
            ->get();

        // ── User terbaru ──────────────────────────────────────────────────
        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalRooms', 'occupiedRooms', 'availableRooms',
            'totalUsers', 'totalBookings', 'pendingBookings',
            'revenueThisMonth', 'revenueLastMonth', 'revenueTotal',
            'revenueTrend', 'pendingPayments', 'pendingAmount',
            'monthlyRevenue', 'recentPayments', 'pendingPaymentList',
            'latestBookings', 'recentUsers'
        ));
    }
}
