<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OwnerBookingConfirmed extends Notification
{
    use Queueable;

    public function __construct(protected Booking $booking)
    {
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Booking siap huni oleh owner')
            ->line("Booking #{$this->booking->id} telah dikonfirmasi siap huni.")
            ->action('Lihat booking', url(route('admin.bookings.show', $this->booking)))
            ->line('Silakan tinjau dan proses selanjutnya di dashboard admin.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'room_code' => $this->booking->room_code,
            'status' => $this->booking->status,
            'message' => 'Booking menunggu verifikasi admin setelah owner konfirmasi siap huni.',
        ];
    }
}
