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
        Schema::create('daftar_laboran_laboratoriums', function (Blueprint $table) {
            $table->id();
            $table->string('Laboratorium');
            $table->string('Nama_Laboran');
            $table->string('UserID');
            $table->string('Phone');
            $table->string('Email');
            $table->string('Role_User');
             
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_laboran_laboratoriums');
    }
};
