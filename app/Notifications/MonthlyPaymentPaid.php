<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MonthlyPaymentPaid extends Notification
{
    use Queueable;

    public function __construct(protected Payment $payment)
    {
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pembayaran Bulanan Diterima')
            ->line("Pembayaran bulanan sebesar Rp{$this->payment->amount} telah diterima.")
            ->action('Lihat Pembayaran', url(route('admin.pembayaran.index')))
            ->line('Silakan cek detail transaksi di dashboard admin.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'payment_id' => $this->payment->id,
            'booking_id' => $this->payment->booking_id,
            'amount' => $this->payment->amount,
            'message' => 'Pembayaran bulanan berhasil diproses oleh Midtrans.',
        ];
    }
}
