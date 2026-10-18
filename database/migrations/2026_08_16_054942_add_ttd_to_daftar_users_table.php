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
        // Kolom 'ttd' sudah ada di migration gabungan 2024_01_01_000002.
        // Guard ini mencegah error "duplicate column name" saat migrate
        // pada database baru (mis. deployment ke VPS).
        if (! Schema::hasColumn('daftar_users', 'ttd')) {
            Schema::table('daftar_users', function (Blueprint $table) {
                $table->string('ttd')->nullable()->after('foto');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('daftar_users', 'ttd')) {
            Schema::table('daftar_users', function (Blueprint $table) {
                $table->dropColumn('ttd');
            });
        }
    }
};
