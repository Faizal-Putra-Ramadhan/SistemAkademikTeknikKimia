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
        Schema::create('laboran_laboratorium', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // UserID dari daftar_laboran_laboratoriums
            $table->foreignId('daftar_lab_id')->constrained('daftar_labs')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint: satu laboran tidak bisa memiliki duplikat laboratorium yang sama
            $table->unique(['user_id', 'daftar_lab_id']);

            // Index untuk performa
            $table->index('user_id');
            $table->index('daftar_lab_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboran_laboratorium');
    }
};
