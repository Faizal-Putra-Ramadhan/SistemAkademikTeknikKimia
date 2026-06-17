<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('aktivitas_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('user_nama');
            $table->unsignedBigInteger('daftar_lab_id')->index('aktivitas_mahasiswas_daftar_lab_id_foreign');
            $table->string('jenis_aktivitas');
            $table->text('keterangan');
            $table->timestamp('waktu')->useCurrent();

            $table->foreign('daftar_lab_id', 'aktivitas_mahasiswas_daftar_lab_id_foreign')->references('id')->on('daftar_labs')->onDelete('cascade');
        });

        Schema::create('bebas_lab_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index('bebas_lab_requests_user_id_foreign');
            $table->string('user_nama');
            $table->enum('status', ['menunggu', 'disetujui'])->default('menunggu');
            $table->timestamp('kepala_lab_approved_at')->nullable();
            $table->timestamp('tanggal_berlaku_dari')->nullable();
            $table->timestamp('tanggal_berlaku_sampai')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('periode')->default(1);
            $table->timestamps();
            $table->unsignedBigInteger('risk_assessment_id')->nullable()->index('bebas_lab_requests_risk_assessment_id_foreign');

            $table->foreign('risk_assessment_id', 'bebas_lab_requests_risk_assessment_id_foreign')->references('id')->on('risk_assessments')->onDelete('cascade');
            $table->foreign('user_id', 'bebas_lab_requests_user_id_foreign')->references('id')->on('daftar_users')->onDelete('cascade');
        });

        Schema::create('bebas_lab_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bebas_lab_request_id')->index('bebas_lab_approvals_bebas_lab_request_id_foreign');
            $table->unsignedBigInteger('daftar_lab_id')->index('bebas_lab_approvals_daftar_lab_id_foreign');
            $table->string('laboran_user_id')->nullable();
            $table->string('laboran_nama')->nullable();
            $table->enum('status', ['menunggu', 'disetujui'])->default('menunggu');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('bebas_lab_request_id', 'bebas_lab_approvals_bebas_lab_request_id_foreign')->references('id')->on('bebas_lab_requests')->onDelete('cascade');
            $table->foreign('daftar_lab_id', 'bebas_lab_approvals_daftar_lab_id_foreign')->references('id')->on('daftar_labs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bebas_lab_approvals');
        Schema::dropIfExists('bebas_lab_requests');
        Schema::dropIfExists('aktivitas_mahasiswas');
    }
};
