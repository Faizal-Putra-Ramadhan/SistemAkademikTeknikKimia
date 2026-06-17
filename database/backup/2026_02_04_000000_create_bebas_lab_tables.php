<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bebas_lab_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('daftar_users')->onDelete('cascade');
            $table->string('user_nama');
            $table->enum('status', ['menunggu', 'disetujui'])->default('menunggu');
            $table->timestamp('kepala_lab_approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('bebas_lab_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bebas_lab_request_id')->constrained('bebas_lab_requests')->onDelete('cascade');
            $table->foreignId('daftar_lab_id')->constrained('daftar_labs')->onDelete('cascade');
            $table->string('laboran_user_id')->nullable(); // Menggunakan string untuk menyimpan UserID
            $table->string('laboran_nama')->nullable();
            $table->enum('status', ['menunggu', 'disetujui'])->default('menunggu');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bebas_lab_approvals');
        Schema::dropIfExists('bebas_lab_requests');
    }
};
