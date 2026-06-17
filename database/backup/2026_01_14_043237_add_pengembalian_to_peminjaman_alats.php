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
        Schema::table('peminjaman_alats', function (Blueprint $table) {
            // Kolom untuk pengajuan pengembalian
            $table->boolean('pengajuan_pengembalian')->default(false)->after('status');
            $table->timestamp('tanggal_pengajuan_pengembalian')->nullable()->after('pengajuan_pengembalian');
            $table->text('keterangan_pengembalian')->nullable()->after('tanggal_pengajuan_pengembalian');

            // Kolom untuk persetujuan laboran
            $table->boolean('pengembalian_disetujui')->nullable()->after('keterangan_pengembalian');
            $table->timestamp('tanggal_persetujuan_pengembalian')->nullable()->after('pengembalian_disetujui');
            $table->string('laboran_nama')->nullable()->after('tanggal_persetujuan_pengembalian');
            $table->text('catatan_laboran')->nullable()->after('laboran_nama');

            // Kolom untuk kondisi barang saat dikembalikan
            $table->enum('kondisi_barang', ['baik', 'rusak ringan', 'rusak berat'])->nullable()->after('catatan_laboran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_alats', function (Blueprint $table) {
            $table->dropColumn([
                'pengajuan_pengembalian',
                'tanggal_pengajuan_pengembalian',
                'keterangan_pengembalian',
                'pengembalian_disetujui',
                'tanggal_persetujuan_pengembalian',
                'laboran_nama',
                'catatan_laboran',
                'kondisi_barang',
            ]);
        });
    }
};
