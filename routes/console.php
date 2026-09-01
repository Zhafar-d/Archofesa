<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwal otomatis kirim pengingat WhatsApp jatuh tempo sewa setiap hari jam 08:00 WIB
Schedule::command('booking:send-reminders')->dailyAt('08:00');
