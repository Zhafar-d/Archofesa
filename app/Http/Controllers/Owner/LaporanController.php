<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));

        $payments = Payment::with('booking', 'booking.user')
            ->where('status', 'paid')
            ->whereYear('paid_at', substr($month, 0, 4))
            ->whereMonth('paid_at', substr($month, 5, 2))
            ->latest()
            ->paginate(15);

        return view('owner.laporan.index', compact('payments', 'month'));
    }
}
