<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $method = $request->query('method');

        $payments = Payment::with('booking', 'booking.user')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($method, fn ($query) => $query->where('payment_method', $method))
            ->latest()
            ->paginate(15);

        $summary = [
            'success_count' => Payment::where('status', 'paid')->count(),
            'success_amount' => Payment::where('status', 'paid')->sum('amount'),
        ];

        return view('admin.pembayaran.index', compact('payments', 'status', 'method', 'summary'));
    }

    public function markPaid(Payment $payment)
    {
        $payment->update(['status' => 'paid', 'paid_at' => now()]);

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran COD berhasil ditandai sebagai dibayar.');
    }
}
