<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE peminjaman_ruangans MODIFY COLUMN status ENUM('menunggu', 'disetujui_laboran', 'menunggu_kepala_lab', 'disetujui', 'dikembalikan', 'ditolak') DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE peminjaman_ruangans MODIFY COLUMN status ENUM('menunggu', 'disetujui_laboran', 'menunggu_kepala_lab', 'disetujui', 'ditolak') DEFAULT 'menunggu'");
    }
};
