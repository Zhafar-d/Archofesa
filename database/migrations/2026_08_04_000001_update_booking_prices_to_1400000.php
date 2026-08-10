<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('bookings')->update(['monthly_rate' => 1400000]);
    }

    public function down(): void
    {
        // Rollback tidak akan mengubah kembali karena tidak ada data history
    }
};
