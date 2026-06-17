<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('daftar_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_user_id')->nullable()->index('daftar_users_parent_user_id_index');
            $table->enum('status', ['pending', 'aktif', 'nonaktif'])->default('pending');
            $table->boolean('is_primary')->default(true);
            $table->string('Nama');
            $table->string('Phone');
            $table->string('Email')->unique('daftar_users_email_unique');
            $table->string('UserID')->unique('daftar_users_userid_unique');
            $table->string('nomor_identitas')->nullable();
            $table->string('Password');
            $table->string('Role_User');
            $table->string('foto')->nullable();
            $table->timestamps();

            $table->foreign('parent_user_id', 'daftar_users_parent_user_id_foreign')->references('id')->on('daftar_users')->onDelete('cascade');
        });

        // Memastikan tabel users bawaan laravel juga tergabung disini jika ada relasi, tapi karena tidak ada custom khusus, biarkan terpisah (atau disatukan)
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('daftar_users');
        Schema::dropIfExists('users');
    }
};
