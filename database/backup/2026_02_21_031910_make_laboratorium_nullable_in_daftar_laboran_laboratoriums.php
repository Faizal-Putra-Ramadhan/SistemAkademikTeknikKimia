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
        Schema::table('daftar_laboran_laboratoriums', function (Blueprint $table) {
            $table->string('Laboratorium')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_laboran_laboratoriums', function (Blueprint $table) {
            $table->string('Laboratorium')->nullable(false)->change();
        });
    }
};
