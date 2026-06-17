<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            // Ubah jadwal_wawancara menjadi JSON untuk menyimpan multiple options
            // Tapi tetap keep jadwal_wawancara untuk selected schedule
            // Tambah kolom baru untuk menyimpan semua opsi jadwal
            $table->json('jadwal_wawancara_options')->nullable()->after('tempat_wawancara');
            $table->timestamp('jadwal_wawancara_dipilih_at')->nullable()->after('jadwal_wawancara_options');
        });
    }

    public function down(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->dropColumn('jadwal_wawancara_options');
            $table->dropColumn('jadwal_wawancara_dipilih_at');
        });
    }
};
