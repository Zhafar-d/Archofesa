<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected ?string $token;
    protected ?string $endpoint;
    protected string $provider;

    public function __construct()
    {
        $this->provider = config('services.whatsapp.provider', env('WA_PROVIDER', 'fonnte'));
        $this->token = config('services.whatsapp.token', env('FONNTE_TOKEN', env('WA_TOKEN')));
        $this->endpoint = config('services.whatsapp.endpoint', env('WA_ENDPOINT', 'https://api.fonnte.com/send'));
    }

    /**
     * Format phone number to standard international format (e.g. 08123... -> 628123...)
     */
    public static function formatPhoneNumber(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Hapus karakter non-digit
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // Jika diawali 0, ganti dengan 62
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        }

        // Jika diawali +62 atau 62, biarkan
        if (str_starts_with($cleaned, '62')) {
            return $cleaned;
        }

        return '62' . $cleaned;
    }

    /**
     * Buat pesan pengingat jatuh tempo / perpanjangan sewa.
     */
    public function getRentalReminderMessage(Booking $booking, ?int $daysRemaining = null): string
    {
        $tenantName = $booking->user->name ?? 'Pelanggan';
        $invoiceNumber = 'INV-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT);
        $totalAmount = 'Rp ' . number_format($booking->monthly_rate, 0, ',', '.');
        $dueDate = $booking->move_out_date ? $booking->move_out_date->translatedFormat('d F Y') : '-';
        $extendUrl = route('customer.extend.form', $booking->id);

        return "🔔 *PENGINGAT PEMBAYARAN*\n\n"
            . "Halo, *{$tenantName}* 👋\n\n"
            . "Kami ingin mengingatkan bahwa Anda memiliki tagihan yang akan segera jatuh tempo.\n\n"
            . "📋 *Detail Tagihan*\n"
            . "• Nomor Tagihan: {$invoiceNumber}\n"
            . "• Total Tagihan: *{$totalAmount}*\n"
            . "• Jatuh Tempo: *{$dueDate}*\n\n"
            . "Mohon melakukan pembayaran sebelum tanggal jatuh tempo.\n\n"
            . "🔗 *Perpanjang Masa Tinggal:*\n"
            . "Jika ingin melakukan perpanjangan masa tinggal secara online, silakan klik tautan berikut:\n"
            . "👉 {$extendUrl}\n\n"
            . "Jika pembayaran sudah dilakukan, silakan abaikan pesan ini.\n\n"
            . "Terima kasih atas perhatian dan kerja samanya. 🙏\n"
            . "*ARCHOFESA KOST*";
    }

    /**
     * Kirim pesan WhatsApp melalui Gateway API (Fonnte / Wablas / Generic).
     */
    public function sendMessage(string $targetPhone, string $message): array
    {
        $formattedPhone = self::formatPhoneNumber($targetPhone);

        if (! $formattedPhone) {
            return [
                'success' => false,
                'message' => 'Nomor WhatsApp tidak valid atau kosong.',
            ];
        }

        // Jika token API belum diset, log & sediakan link wa.me sebagai fallback
        if (empty($this->token)) {
            Log::info("WhatsApp Bot [Simulated Mode - No API Token Configured]: Target: {$formattedPhone}, Msg: " . substr($message, 0, 50) . "...");
            return [
                'success' => true,
                'simulated' => true,
                'message' => 'Simulasi berhasil (Token API belum dikonfigurasi). Pesan siap dikirim via tautan direct WhatsApp.',
                'wa_url' => $this->getDirectWhatsAppUrl($formattedPhone, $message),
            ];
        }

        try {
            if ($this->provider === 'fonnte') {
                $response = Http::withHeaders([
                    'Authorization' => $this->token,
                ])->post($this->endpoint, [
                    'target' => $formattedPhone,
                    'message' => $message,
                    'countryCode' => '62',
                ]);
            } else {
                // Generic JSON Gateway
                $response = Http::withToken($this->token)->post($this->endpoint, [
                    'phone' => $formattedPhone,
                    'message' => $message,
                ]);
            }

            if ($response->successful()) {
                Log::info("WhatsApp sent successfully to {$formattedPhone}");
                return [
                    'success' => true,
                    'data' => $response->json(),
                    'message' => 'Pesan WhatsApp berhasil dikirim.',
                ];
            }

            Log::error("WhatsApp Gateway error ({$response->status()}): " . $response->body());
            return [
                'success' => false,
                'message' => 'Gateway WhatsApp gagal mengirim: ' . $response->body(),
                'wa_url' => $this->getDirectWhatsAppUrl($formattedPhone, $message),
            ];
        } catch (\Throwable $e) {
            Log::error("WhatsApp Service Exception: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menghubungi WhatsApp API: ' . $e->getMessage(),
                'wa_url' => $this->getDirectWhatsAppUrl($formattedPhone, $message),
            ];
        }
    }

    /**
     * Kirim pengingat sewa untuk spesifik booking.
     */
    public function sendRentalReminder(Booking $booking, ?int $daysRemaining = null): array
    {
        $phone = $booking->user->phone ?? null;

        if (empty($phone)) {
            return [
                'success' => false,
                'message' => 'Penghuni belum mendaftarkan nomor telepon/WhatsApp pada profilnya.',
            ];
        }

        if ($daysRemaining === null && $booking->move_out_date) {
            $daysRemaining = (int) now()->startOfDay()->diffInDays($booking->move_out_date->startOfDay(), false);
        } else {
            $daysRemaining = $daysRemaining ?? 0;
        }

        $message = $this->getRentalReminderMessage($booking, $daysRemaining);

        return $this->sendMessage($phone, $message);
    }

    /**
     * Dapatkan link direct WhatsApp Click-to-Chat (wa.me)
     */
    public function getDirectWhatsAppUrl(string $phone, string $message): string
    {
        $formattedPhone = self::formatPhoneNumber($phone);
        return 'https://wa.me/' . $formattedPhone . '?text=' . urlencode($message);
    }
}
