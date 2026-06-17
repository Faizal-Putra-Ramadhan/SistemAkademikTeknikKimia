<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            // Tambah kolom tanggal selesai
            $table->date('tanggal_selesai')->after('tanggal');

            // Ubah status untuk include persetujuan kaprodi
            $table->dropColumn('status');
        });

        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu',
                'disetujui_laboran',
                'menunggu_kaprodi',
                'disetujui',
                'ditolak',
            ])->default('menunggu')->after('keperluan');

            // Tambah kolom untuk approval laboran
            $table->integer('laboran_id')->nullable()->after('status');
            $table->boolean('persetujuan_laboran')->nullable()->after('laboran_id');
            $table->text('catatan_laboran')->nullable()->after('persetujuan_laboran');
            $table->timestamp('tanggal_persetujuan_laboran')->nullable()->after('catatan_laboran');

            // Tambah kolom untuk approval kaprodi
            $table->integer('kaprodi_id')->nullable()->after('tanggal_persetujuan_laboran');
            $table->boolean('persetujuan_kaprodi')->nullable()->after('kaprodi_id');
            $table->text('catatan_kaprodi')->nullable()->after('persetujuan_kaprodi');
            $table->timestamp('tanggal_persetujuan_kaprodi')->nullable()->after('catatan_kaprodi');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_selesai',
                'laboran_id',
                'persetujuan_laboran',
                'catatan_laboran',
                'tanggal_persetujuan_laboran',
                'kaprodi_id',
                'persetujuan_kaprodi',
                'catatan_kaprodi',
                'tanggal_persetujuan_kaprodi',
            ]);
        });
    }
};
