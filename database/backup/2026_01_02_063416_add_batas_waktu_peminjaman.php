<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            // Tambahkan kolom batas_waktu_peminjaman (4 bulan setelah disetujui)
            $table->datetime('batas_waktu_peminjaman')->nullable()->after('tanggal_persetujuan_kepala_lab');

            // Tambahkan kolom untuk menyimpan durasi batas waktu (dalam bulan)
            // Default 4 bulan, nanti bisa diubah oleh Kaprodi
            $table->integer('durasi_batas_peminjaman')->default(4)->after('batas_waktu_peminjaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->dropColumn(['batas_waktu_peminjaman', 'durasi_batas_peminjaman']);
        });
    }
};
