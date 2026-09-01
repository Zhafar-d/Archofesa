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

    public function markPaid(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'paid', 'paid_at' => now()]);

        // Update status booking juga jika ada
        if ($payment->booking) {
            $payment->booking->update(['payment_status' => 'paid']);
        }

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil ditandai sebagai dibayar.');
    }

    /**
     * Hapus data transaksi pembayaran (Aksi Delete Admin).
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $paymentId = $payment->id;
        $payment->delete();

        return redirect()->route('admin.pembayaran.index')->with('success', "Data pembayaran #{$paymentId} berhasil dihapus dari sistem.");
    }
}
