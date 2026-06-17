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
        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            // Kolom untuk pengajuan pengembalian ruangan
            if (! Schema::hasColumn('peminjaman_ruangans', 'pengajuan_pengembalian')) {
                $table->boolean('pengajuan_pengembalian')->default(false)->after('status');
            }
            if (! Schema::hasColumn('peminjaman_ruangans', 'tanggal_pengajuan_pengembalian')) {
                $table->timestamp('tanggal_pengajuan_pengembalian')->nullable()->after('pengajuan_pengembalian');
            }
            if (! Schema::hasColumn('peminjaman_ruangans', 'keterangan_pengembalian')) {
                $table->text('keterangan_pengembalian')->nullable()->after('tanggal_pengajuan_pengembalian');
            }

            // Kolom untuk persetujuan laboran
            if (! Schema::hasColumn('peminjaman_ruangans', 'pengembalian_disetujui')) {
                $table->boolean('pengembalian_disetujui')->nullable()->after('keterangan_pengembalian');
            }
            if (! Schema::hasColumn('peminjaman_ruangans', 'tanggal_persetujuan_pengembalian')) {
                $table->timestamp('tanggal_persetujuan_pengembalian')->nullable()->after('pengembalian_disetujui');
            }
            if (! Schema::hasColumn('peminjaman_ruangans', 'laboran_nama')) {
                $table->string('laboran_nama')->nullable()->after('tanggal_persetujuan_pengembalian');
            }
            if (! Schema::hasColumn('peminjaman_ruangans', 'catatan_laboran')) {
                $table->text('catatan_laboran')->nullable()->after('laboran_nama');
            }

            // Kolom untuk kondisi ruangan saat dikembalikan
            if (! Schema::hasColumn('peminjaman_ruangans', 'kondisi_ruangan')) {
                $table->enum('kondisi_ruangan', ['baik', 'perlu pembersihan', 'rusak'])->nullable()->after('catatan_laboran');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman_ruangans', 'pengajuan_pengembalian')) {
                $table->dropColumn('pengajuan_pengembalian');
            }
            if (Schema::hasColumn('peminjaman_ruangans', 'tanggal_pengajuan_pengembalian')) {
                $table->dropColumn('tanggal_pengajuan_pengembalian');
            }
            if (Schema::hasColumn('peminjaman_ruangans', 'keterangan_pengembalian')) {
                $table->dropColumn('keterangan_pengembalian');
            }
            if (Schema::hasColumn('peminjaman_ruangans', 'pengembalian_disetujui')) {
                $table->dropColumn('pengembalian_disetujui');
            }
            if (Schema::hasColumn('peminjaman_ruangans', 'tanggal_persetujuan_pengembalian')) {
                $table->dropColumn('tanggal_persetujuan_pengembalian');
            }
            if (Schema::hasColumn('peminjaman_ruangans', 'laboran_nama')) {
                $table->dropColumn('laboran_nama');
            }
            if (Schema::hasColumn('peminjaman_ruangans', 'catatan_laboran')) {
                $table->dropColumn('catatan_laboran');
            }
            if (Schema::hasColumn('peminjaman_ruangans', 'kondisi_ruangan')) {
                $table->dropColumn('kondisi_ruangan');
            }
        });
    }
};
