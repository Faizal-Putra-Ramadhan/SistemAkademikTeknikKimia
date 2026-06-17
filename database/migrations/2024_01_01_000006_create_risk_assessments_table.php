<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->string('id_ra')->nullable()->unique('risk_assessments_id_ra_unique');
            $table->unsignedBigInteger('user_id')->index('risk_assessments_user_id_foreign');
            $table->string('nama');
            $table->string('nim');
            $table->string('no_kontak')->nullable();
            $table->text('alamat_kontak')->nullable();
            $table->unsignedBigInteger('daftar_lab_id')->index('risk_assessments_daftar_lab_id_foreign');
            $table->enum('jenis_ra', ['Penelitian', 'Praktikum', 'Lain-lain']);
            $table->string('topik_judul')->nullable();
            $table->unsignedBigInteger('dosen_pembimbing_id')->nullable()->index('risk_assessments_dosen_pembimbing_id_foreign');
            $table->string('dosen_pembimbing_nama')->nullable();
            $table->string('nomor_identitas_dosen')->nullable();
            $table->string('status', 50);

            // Penilaian Dosen Pembimbing
            $table->enum('kategori_resiko_dosen', ['tinggi', 'sedang', 'rendah'])->nullable();
            $table->boolean('persetujuan_dosen')->nullable();
            $table->text('catatan_dosen')->nullable();
            $table->timestamp('tanggal_persetujuan_dosen')->nullable();

            // Safety Officer
            $table->unsignedBigInteger('safety_officer_id')->nullable()->index('risk_assessments_safety_officer_id_foreign');
            $table->string('safety_officer_nama')->nullable();
            $table->string('nomor_identitas_safety_officer')->nullable();
            $table->dateTime('jadwal_wawancara')->nullable();
            $table->string('tempat_wawancara')->nullable();
            $table->json('jadwal_wawancara_options')->nullable();
            $table->timestamp('jadwal_wawancara_dipilih_at')->nullable();
            $table->boolean('persetujuan_safety_officer')->nullable();
            $table->text('catatan_safety_officer')->nullable();
            $table->timestamp('tanggal_persetujuan_safety_officer')->nullable();

            // Kepala Laboratorium
            $table->unsignedBigInteger('kepala_lab_id')->nullable()->index('risk_assessments_kepala_lab_id_foreign');
            $table->string('kepala_lab_nama')->nullable();
            $table->string('nomor_identitas_kepala_lab')->nullable();
            $table->boolean('persetujuan_kepala_lab')->nullable();
            $table->text('catatan_kepala_lab')->nullable();
            $table->timestamp('tanggal_persetujuan_kepala_lab')->nullable();

            // Kaprodi
            $table->unsignedBigInteger('kaprodi_id')->nullable();
            $table->string('kaprodi_nama')->nullable();
            $table->string('nomor_identitas_kaprodi')->nullable();
            $table->boolean('persetujuan_kaprodi')->nullable();
            $table->text('catatan_kaprodi')->nullable();
            $table->timestamp('tanggal_persetujuan_kaprodi')->nullable();

            // Peminjaman tracking
            $table->dateTime('batas_waktu_peminjaman')->nullable();
            $table->integer('durasi_batas_peminjaman')->default(4);
            $table->boolean('pengajuan_perpanjangan')->default(false);
            $table->text('alasan_perpanjangan')->nullable();
            $table->dateTime('tanggal_pengajuan_perpanjangan')->nullable();
            $table->integer('durasi_perpanjangan_diminta')->nullable();
            $table->boolean('persetujuan_perpanjangan_kaprodi')->nullable();
            $table->text('catatan_perpanjangan_kaprodi')->nullable();
            $table->dateTime('tanggal_persetujuan_perpanjangan')->nullable();
            $table->integer('durasi_perpanjangan_disetujui')->nullable();
            $table->integer('jumlah_perpanjangan')->default(0);
            $table->boolean('notifikasi_deadline_terkirim')->default(false);
            $table->timestamp('tanggal_notifikasi_deadline')->nullable();
            $table->dateTime('batas_waktu_peminjaman_sebelumnya')->nullable();

            $table->timestamps();

            // Constraints
            $table->foreign('daftar_lab_id', 'risk_assessments_daftar_lab_id_foreign')->references('id')->on('daftar_labs')->onDelete('cascade');
            $table->foreign('dosen_pembimbing_id', 'risk_assessments_dosen_pembimbing_id_foreign')->references('id')->on('daftar_users')->onDelete('set null');
            $table->foreign('kepala_lab_id', 'risk_assessments_kepala_lab_id_foreign')->references('id')->on('daftar_users')->onDelete('set null');
            $table->foreign('safety_officer_id', 'risk_assessments_safety_officer_id_foreign')->references('id')->on('daftar_users')->onDelete('set null');
            $table->foreign('user_id', 'risk_assessments_user_id_foreign')->references('id')->on('daftar_users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_assessments');
    }
};
