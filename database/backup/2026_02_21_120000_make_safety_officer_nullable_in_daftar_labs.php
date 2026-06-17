<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Safety Officer satu untuk semua laboratorium (tidak per lab).
     * Kolom Safety_Officer di daftar_labs dibuat nullable dan tidak lagi diisi per lab.
     */
    public function up(): void
    {
        Schema::table('daftar_labs', function (Blueprint $table) {
            $table->string('Safety_Officer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_labs', function (Blueprint $table) {
            $table->string('Safety_Officer')->nullable(false)->change();
        });
    }
};
