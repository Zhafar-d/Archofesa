<?php

namespace App\Services;

use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapToken(Payment $payment): string
    {
        $payment->loadMissing(['booking.user']);

        // Always generate a unique reference per snap token creation attempt
        // to prevent Midtrans HTTP 400 "order_id sudah digunakan" error on page refresh or retry.
        $payment->reference = sprintf('ARCHOFESA-%s-%s', $payment->id, now()->timestamp . rand(100, 999));
        $payment->save();

        $transaction = [
            'order_id' => $payment->reference,
            'gross_amount' => (int) round($payment->amount),
        ];

        $itemDetails = [
            [
                'id' => $payment->id,
                'price' => (int) round($payment->amount),
                'quantity' => 1,
                'name' => $payment->booking ? "Payment for booking #{$payment->booking->id}" : "Payment #{$payment->id}",
            ],
        ];

        $customerDetails = [
            'first_name' => optional($payment->booking->user)->name ?: 'Customer',
            'email' => optional($payment->booking->user)->email,
        ];

        $params = [
            'transaction_details' => $transaction,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
        ];

        $snap = new Snap();

        return $snap->getSnapToken($params);
    }
}
