<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendRentalDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:send-reminders {--days=7,3,1,0 : Hari sebelum jatuh tempo yang diproses, pisahkan dengan koma}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi pengingat jatuh tempo sewa/perpanjangan ke WhatsApp penghuni yang masa sewanya hampir habis';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsAppService): int
    {
        $this->info('Memulai pengecekan booking yang mendekati masa jatuh tempo...');

        $daysOption = explode(',', (string) $this->option('days'));
        $targetDays = array_map('intval', array_map('trim', $daysOption));

        // Ambil semua booking aktif berstatus dihuni
        $activeBookings = Booking::with('user', 'room')
            ->where('status', 'dihuni')
            ->whereNotNull('move_out_date')
            ->get();

        if ($activeBookings->isEmpty()) {
            $this->info('Tidak ada penghuni aktif yang ditemukan.');
            return Command::SUCCESS;
        }

        $sentCount = 0;
        $skippedCount = 0;

        foreach ($activeBookings as $booking) {
            $daysRemaining = (int) now()->startOfDay()->diffInDays($booking->move_out_date->startOfDay(), false);

            // Cek apakah sisa hari termasuk dalam target pengingat (misal H-7, H-3, H-1, H-0 atau minus/terlewat)
            if (in_array($daysRemaining, $targetDays, true) || ($daysRemaining < 0 && in_array(0, $targetDays, true))) {
                $phone = $booking->user->phone ?? null;
                $tenantName = $booking->user->name ?? 'User #' . $booking->user_id;
                $roomCode = $booking->room->room_code ?? $booking->room_code ?? '-';

                if (empty($phone)) {
                    $this->warn("⚠️ Penghuni {$tenantName} (Kamar {$roomCode}) tidak memiliki nomor telepon. Dilewati.");
                    $skippedCount++;
                    continue;
                }

                $this->line("Mengirim pengingat ke {$tenantName} ({$phone}) - Sisa hari: {$daysRemaining}...");

                $result = $whatsAppService->sendRentalReminder($booking, $daysRemaining);

                if ($result['success']) {
                    $this->info("✅ Pengingat terkirim ke {$tenantName} ({$phone})");
                    $sentCount++;
                } else {
                    $this->error("❌ Gagal mengirim ke {$tenantName}: " . ($result['message'] ?? 'Error'));
                }
            }
        }

        $this->info("Pengecekan selesai! Berhasil dikirim: {$sentCount}, Dilewati: {$skippedCount}");

        return Command::SUCCESS;
    }
}
