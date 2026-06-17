<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            // Ubah approval flow: Kaprodi → Kepala Lab
            // Rename columns untuk clarity
            $table->renameColumn('kaprodi_id', 'kepala_lab_id');
            $table->renameColumn('persetujuan_kaprodi', 'persetujuan_kepala_lab');
            $table->renameColumn('catatan_kaprodi', 'catatan_kepala_lab');
            $table->renameColumn('tanggal_persetujuan_kaprodi', 'tanggal_persetujuan_kepala_lab');

            // Ubah status enum
            $table->dropColumn('status');
        });

        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu',
                'disetujui_laboran',
                'menunggu_kepala_lab',
                'disetujui',
                'ditolak',
            ])->default('menunggu');

            // Add kaprodi notification column (read-only, notification only)
            $table->integer('kaprodi_id')->nullable()->after('kepala_lab_id');
            $table->boolean('notifikasi_kaprodi')->default(false)->after('kaprodi_id');
            $table->timestamp('tanggal_notifikasi_kaprodi')->nullable()->after('notifikasi_kaprodi');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            $table->renameColumn('kepala_lab_id', 'kaprodi_id');
            $table->renameColumn('persetujuan_kepala_lab', 'persetujuan_kaprodi');
            $table->renameColumn('catatan_kepala_lab', 'catatan_kaprodi');
            $table->renameColumn('tanggal_persetujuan_kepala_lab', 'tanggal_persetujuan_kaprodi');

            $table->dropColumn(['status', 'notifikasi_kaprodi', 'tanggal_notifikasi_kaprodi']);
        });

        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu',
                'disetujui_laboran',
                'menunggu_kaprodi',
                'disetujui',
                'ditolak',
            ])->default('menunggu');
        });
    }
};
