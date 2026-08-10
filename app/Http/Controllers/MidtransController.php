<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\MonthlyPaymentPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');

        $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.config('midtrans.server_key'));

        if (! hash_equals($expectedSignature, (string) $signatureKey)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payment = Payment::where('reference', $orderId)->first();

        if (! $payment && preg_match('/^ARCHOFESA-(\d+)-/', (string) $orderId, $matches)) {
            $payment = Payment::find($matches[1]);
        }

        if (! $payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $transactionStatus = $request->input('transaction_status');
        $notificationPayload = $request->all();

        if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
            $payment->update([
                'status' => 'dibayar',
                'paid_at' => now(),
                'gateway_response' => json_encode($notificationPayload, JSON_UNESCAPED_UNICODE),
            ]);

            $booking = $payment->booking;

            if ($booking && in_array($booking->status, ['pending', 'menunggu_pembayaran'], true)) {
                $booking->update([
                    'status' => 'dibayar',
                    'payment_status' => 'paid',
                ]);
            } elseif ($booking) {
                $admins = User::where('role', 'admin')->get();
                Notification::send($admins, new MonthlyPaymentPaid($payment));
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'], true)) {
            $payment->update([
                'status' => 'dibatalkan',
                'gateway_response' => json_encode($notificationPayload, JSON_UNESCAPED_UNICODE),
            ]);
        } else {
            $payment->update([
                'gateway_response' => json_encode($notificationPayload, JSON_UNESCAPED_UNICODE),
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
