<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_alats', function (Blueprint $table) {
            // Add columns untuk tracking deadline
            $table->timestamp('batas_waktu_peminjaman')->nullable()->after('tanggal_kembali');
            $table->boolean('notifikasi_7_hari_terkirim')->default(false)->after('batas_waktu_peminjaman');
            $table->timestamp('tanggal_notifikasi_7_hari')->nullable()->after('notifikasi_7_hari_terkirim');

            // Status untuk deadline
            $table->enum('deadline_status', [
                'aktif',
                'habis_batas_waktu',
                'perpanjangan_disetujui',
            ])->default('aktif')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_alats', function (Blueprint $table) {
            $table->dropColumn([
                'batas_waktu_peminjaman',
                'notifikasi_7_hari_terkirim',
                'tanggal_notifikasi_7_hari',
                'deadline_status',
            ]);
        });
    }
};
