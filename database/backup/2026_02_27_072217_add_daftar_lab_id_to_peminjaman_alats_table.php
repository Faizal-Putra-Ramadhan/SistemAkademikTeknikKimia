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
            $table->foreignId('daftar_lab_id')->nullable()->after('alat_lab_id')->constrained('daftar_labs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_alats', function (Blueprint $table) {
            $table->dropForeign(['daftar_lab_id']);
            $table->dropColumn('daftar_lab_id');
        });
    }
};
