<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Email laboratorium dibuat opsional (nullable).
     */
    public function up(): void
    {
        Schema::table('daftar_labs', function (Blueprint $table) {
            $table->string('email_lab')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_labs', function (Blueprint $table) {
            $table->string('email_lab')->nullable(false)->change();
        });
    }
};
