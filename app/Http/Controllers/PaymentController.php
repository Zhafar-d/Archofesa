<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function pay(Payment $payment, MidtransService $midtrans)
    {
        $user = Auth::user();

        abort_unless($user && $payment->user_id === $user->id, 403);
        abort_unless(in_array($payment->status, ['pending', 'menunggu_pembayaran']), 403, 'Pembayaran sudah diproses.');

        // Otomatis set payment method ke midtrans jika belum diset
        if (! $payment->payment_method) {
            $payment->update(['payment_method' => 'midtrans']);
        }

        $snapToken = $midtrans->createSnapToken($payment);

        return view('payments.pay', compact('payment', 'snapToken'));
    }

    /**
     * Dipanggil via POST dari frontend setelah Midtrans onSuccess/onPending
     * untuk update status di DB (workaround untuk localhost tanpa webhook)
     */
    public function confirm(Request $request, Payment $payment)
    {
        $user = Auth::user();
        abort_unless($user && $payment->user_id === $user->id, 403);

        $result    = $request->input('result', []);
        $txStatus  = $result['transaction_status'] ?? 'settlement';
        $orderId   = $result['order_id'] ?? null;

        // Verifikasi order_id cocok (cocokkan persis atau pastikan prefix ARCHOFESA-{id}- sesuai)
        if ($orderId && $payment->reference && $orderId !== $payment->reference) {
            if (! preg_match('/^ARCHOFESA-'.$payment->id.'-\d+$/', $orderId)) {
                return response()->json(['ok' => false, 'message' => 'Order ID mismatch'], 422);
            }
        }

        if (in_array($txStatus, ['settlement', 'capture'])) {
            $payment->update([
                'status'           => 'dibayar',
                'paid_at'          => now(),
                'gateway_response' => json_encode($result),
            ]);

            $booking = $payment->booking;
            if ($booking && in_array($booking->status, ['pending', 'menunggu_pembayaran'])) {
                $booking->update([
                    'status'         => 'dibayar',
                    'payment_status' => 'paid',
                ]);
            }

            return response()->json([
                'ok'          => true,
                'redirect'    => route('payment.success', $payment),
            ]);
        }

        // pending / challenge
        $payment->update([
            'gateway_response' => json_encode($result),
        ]);

        return response()->json([
            'ok'       => true,
            'redirect' => route('customer.payments') . '?status=pending',
        ]);
    }

    /**
     * Halaman sukses + bukti booking setelah pembayaran berhasil
     */
    public function success(Payment $payment)
    {
        $user = Auth::user();
        abort_unless($user && $payment->user_id === $user->id, 403);

        $payment->loadMissing(['booking.room', 'booking.user']);

        return view('payments.success', compact('payment'));
    }
}
