<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('action');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        Schema::create('pengumumen', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->enum('status', ['draft', 'publish'])->default('publish');
            $table->string('author')->default('Administrator');
            $table->timestamps();
        });

        Schema::create('pending_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('phone');
            $table->string('email')->unique('pending_registrations_email_unique');
            $table->string('password');
            $table->string('role')->default('Mahasiswa');
            $table->string('nomor_identitas')->nullable();
            $table->unsignedBigInteger('parent_user_id')->nullable();
            $table->boolean('is_primary')->default(true);
            $table->string('verification_token')->unique('pending_registrations_verification_token_unique');
            $table->timestamp('token_expires_at');
            $table->boolean('is_verified')->default(false)->index('pending_registrations_is_verified_index');
            $table->timestamps();

            $table->index('email', 'pending_registrations_email_index');
            $table->index('verification_token', 'pending_registrations_verification_token_index');
        });

        Schema::create('laboran_laboratorium', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->index('laboran_laboratorium_user_id_index');
            $table->unsignedBigInteger('daftar_lab_id')->index('laboran_laboratorium_daftar_lab_id_index');
            $table->timestamps();

            $table->unique(['user_id', 'daftar_lab_id'], 'laboran_laboratorium_user_id_daftar_lab_id_unique');
            $table->foreign('daftar_lab_id', 'laboran_laboratorium_daftar_lab_id_foreign')->references('id')->on('daftar_labs')->onDelete('cascade');
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id')->index('user_roles_role_id_foreign');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'role_id'], 'user_roles_user_id_role_id_unique');
            $table->foreign('role_id', 'user_roles_role_id_foreign')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('user_id', 'user_roles_user_id_foreign')->references('id')->on('daftar_users')->onDelete('cascade');
        });

        Schema::create('daftar_laboran_laboratoriums', function (Blueprint $table) {
            $table->id();
            $table->string('Laboratorium')->nullable();
            $table->string('Nama_Laboran');
            $table->string('UserID');
            $table->string('Phone');
            $table->string('Email');
            $table->string('Role_User');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('laboran_laboratorium');
        Schema::dropIfExists('daftar_laboran_laboratoriums');
        Schema::dropIfExists('pending_registrations');
        Schema::dropIfExists('pengumumen');
        Schema::dropIfExists('activity_logs');
    }
};
