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
        // Tambahkan kolom nomor_identitas ke tabel daftar_users
        Schema::table('daftar_users', function (Blueprint $table) {
            $table->string('nomor_identitas')->nullable()->after('UserID');
        });

        // Tambahkan kolom tempat_wawancara ke tabel risk_assessments
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->string('tempat_wawancara')->nullable()->after('jadwal_wawancara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus kolom nomor_identitas dari tabel daftar_users
        Schema::table('daftar_users', function (Blueprint $table) {
            $table->dropColumn('nomor_identitas');
        });

        // Hapus kolom tempat_wawancara dari tabel risk_assessments
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->dropColumn('tempat_wawancara');
        });
    }
};
