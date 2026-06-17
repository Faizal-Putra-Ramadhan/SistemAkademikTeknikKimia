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
        Schema::create('daftar_labs', function (Blueprint $table) {
            $table->id();
            $table->string('Nama_Laboratorium');
            $table->string('Kepala_Labolatorium');
            $table->string('Admin_Laboratorium');
            $table->string('Safety_Officer');
            $table->string('email_lab');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_labs');
    }
};
