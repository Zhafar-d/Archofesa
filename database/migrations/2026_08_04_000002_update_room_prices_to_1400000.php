<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('rooms')->update(['price_monthly' => 1400000]);
    }

    public function down(): void
    {
        // Rollback tidak akan mengubah kembali karena tidak ada data history
    }
};
