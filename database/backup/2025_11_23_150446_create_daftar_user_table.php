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
        Schema::create('daftar_users', function (Blueprint $table) {
            $table->id();
            $table->string('Nama');
            $table->string('Phone');
            $table->string('Email')->unique();
            $table->string('UserID')->unique(); // Tambahkan kolom UserID
            $table->string('Password'); // Tambahkan kolom Password
            $table->string('Role_User');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_users');
    }
};