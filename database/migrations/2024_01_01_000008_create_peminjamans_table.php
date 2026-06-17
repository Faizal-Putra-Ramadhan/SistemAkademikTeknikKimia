<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('peminjaman_alats', function (Blueprint $table) {
            $table->id();
            $table->string('user_nama');
            $table->unsignedBigInteger('risk_assessment_id')->nullable()->index('peminjaman_alats_risk_assessment_id_foreign');
            $table->unsignedBigInteger('alat_lab_id')->index('peminjaman_alats_alat_lab_id_foreign');
            $table->unsignedBigInteger('daftar_lab_id')->nullable()->index('peminjaman_alats_daftar_lab_id_foreign');
            $table->unsignedInteger('jumlah')->default(1);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->timestamp('batas_waktu_peminjaman')->nullable();
            $table->boolean('notifikasi_7_hari_terkirim')->default(false);
            $table->timestamp('tanggal_notifikasi_7_hari')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'dikembalikan', 'ditolak'])->default('menunggu');
            $table->enum('deadline_status', ['aktif', 'habis_batas_waktu', 'perpanjangan_disetujui'])->default('aktif');
            $table->boolean('pengajuan_pengembalian')->default(false);
            $table->timestamp('tanggal_pengajuan_pengembalian')->nullable();
            $table->text('keterangan_pengembalian')->nullable();
            $table->boolean('pengembalian_disetujui')->nullable();
            $table->timestamp('tanggal_persetujuan_pengembalian')->nullable();
            $table->string('laboran_nama')->nullable();
            $table->text('catatan_laboran')->nullable();
            $table->enum('kondisi_barang', ['baik', 'rusak ringan', 'rusak berat'])->nullable();
            $table->timestamps();

            $table->foreign('alat_lab_id', 'peminjaman_alats_alat_lab_id_foreign')->references('id')->on('alat_labs')->onDelete('cascade');
            $table->foreign('daftar_lab_id', 'peminjaman_alats_daftar_lab_id_foreign')->references('id')->on('daftar_labs')->onDelete('cascade');
            $table->foreign('risk_assessment_id', 'peminjaman_alats_risk_assessment_id_foreign')->references('id')->on('risk_assessments')->onDelete('cascade');
        });

        Schema::create('peminjaman_ruangans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index('peminjaman_ruangans_user_id_index');
            $table->string('user_nama');
            $table->unsignedBigInteger('daftar_lab_id')->index('peminjaman_ruangans_daftar_lab_id_foreign');
            $table->date('tanggal');
            $table->date('tanggal_selesai');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('keperluan');
            $table->boolean('pengajuan_pengembalian')->default(false);
            $table->timestamp('tanggal_pengajuan_pengembalian')->nullable();
            $table->text('keterangan_pengembalian')->nullable();
            $table->boolean('pengembalian_disetujui')->nullable();
            $table->timestamp('tanggal_persetujuan_pengembalian')->nullable();
            $table->string('laboran_nama')->nullable();
            $table->integer('laboran_id')->nullable();
            $table->boolean('persetujuan_laboran')->nullable();
            $table->text('catatan_laboran')->nullable();
            $table->enum('kondisi_ruangan', ['baik', 'perlu pembersihan', 'rusak'])->nullable();
            $table->timestamp('tanggal_persetujuan_laboran')->nullable();
            $table->integer('kepala_lab_id')->nullable();
            $table->integer('kaprodi_id')->nullable();
            $table->boolean('notifikasi_kaprodi')->default(false);
            $table->timestamp('tanggal_notifikasi_kaprodi')->nullable();
            $table->timestamp('tanggal_kembali')->nullable();
            $table->boolean('persetujuan_kepala_lab')->nullable();
            $table->text('catatan_kepala_lab')->nullable();
            $table->timestamp('tanggal_persetujuan_kepala_lab')->nullable();
            $table->enum('status', ['menunggu', 'disetujui_laboran', 'menunggu_kepala_lab', 'disetujui', 'dikembalikan', 'ditolak'])->default('menunggu')->nullable();
            $table->unsignedBigInteger('risk_assessment_id')->nullable()->index('peminjaman_ruangans_risk_assessment_id_foreign');
            $table->timestamps();

            $table->foreign('daftar_lab_id', 'peminjaman_ruangans_daftar_lab_id_foreign')->references('id')->on('daftar_labs')->onDelete('cascade');
            $table->foreign('risk_assessment_id', 'peminjaman_ruangans_risk_assessment_id_foreign')->references('id')->on('risk_assessments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_ruangans');
        Schema::dropIfExists('peminjaman_alats');
    }
};
