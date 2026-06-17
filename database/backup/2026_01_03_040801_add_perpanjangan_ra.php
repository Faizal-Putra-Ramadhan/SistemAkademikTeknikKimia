<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            // Field untuk perpanjangan
            $table->boolean('pengajuan_perpanjangan')->default(false)->after('durasi_batas_peminjaman');
            $table->text('alasan_perpanjangan')->nullable()->after('pengajuan_perpanjangan');
            $table->datetime('tanggal_pengajuan_perpanjangan')->nullable()->after('alasan_perpanjangan');
            $table->integer('durasi_perpanjangan_diminta')->nullable()->after('tanggal_pengajuan_perpanjangan');

            // Field untuk persetujuan kaprodi
            $table->boolean('persetujuan_perpanjangan_kaprodi')->nullable()->after('durasi_perpanjangan_diminta');
            $table->text('catatan_perpanjangan_kaprodi')->nullable()->after('persetujuan_perpanjangan_kaprodi');
            $table->datetime('tanggal_persetujuan_perpanjangan')->nullable()->after('catatan_perpanjangan_kaprodi');
            $table->integer('durasi_perpanjangan_disetujui')->nullable()->after('tanggal_persetujuan_perpanjangan');

            // Riwayat perpanjangan
            $table->integer('jumlah_perpanjangan')->default(0)->after('durasi_perpanjangan_disetujui');
            $table->datetime('batas_waktu_peminjaman_sebelumnya')->nullable()->after('jumlah_perpanjangan');
        });
    }

    public function down()
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->dropColumn([
                'pengajuan_perpanjangan',
                'alasan_perpanjangan',
                'tanggal_pengajuan_perpanjangan',
                'durasi_perpanjangan_diminta',
                'persetujuan_perpanjangan_kaprodi',
                'catatan_perpanjangan_kaprodi',
                'tanggal_persetujuan_perpanjangan',
                'durasi_perpanjangan_disetujui',
                'jumlah_perpanjangan',
                'batas_waktu_peminjaman_sebelumnya',
            ]);
        });
    }
};
