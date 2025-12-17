<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_lab_assets_and_user_activities.php
public function up()
{
    // 1. Aset / Alat Lab
    Schema::create('alat_labs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('daftar_lab_id')->constrained('daftar_labs')->onDelete('cascade');
        $table->string('nama_alat');
        $table->text('deskripsi')->nullable();
        $table->integer('jumlah_tersedia')->default(1);
        $table->string('foto')->nullable();
        $table->timestamps();
    });

    // 2. Aktivitas Mahasiswa
    Schema::create('aktivitas_mahasiswas', function (Blueprint $table) {
        $table->id();
        $table->string('user_nama'); // nanti ganti auth()->user()->name
        $table->foreignId('daftar_lab_id')->constrained('daftar_labs')->onDelete('cascade');
        $table->string('jenis_aktivitas'); // Peminjaman Alat, Pengembalian, dll
        $table->text('keterangan');
        $table->timestamp('waktu')->useCurrent();
    });

    // 3. Peminjaman Alat
    Schema::create('peminjaman_alats', function (Blueprint $table) {
        $table->id();
        $table->string('user_nama');
        $table->foreignId('alat_lab_id')->constrained('alat_labs')->onDelete('cascade');
        $table->date('tanggal_pinjam');
        $table->date('tanggal_kembali')->nullable();
        $table->enum('status', ['menunggu', 'disetujui', 'dikembalikan', 'ditolak'])->default('menunggu');
        $table->timestamps();
    });

    // 4. Peminjaman Ruangan Lab
    Schema::create('peminjaman_ruangans', function (Blueprint $table) {
        $table->id();
        $table->string('user_nama');
        $table->foreignId('daftar_lab_id')->constrained('daftar_labs')->onDelete('cascade');
        $table->date('tanggal');
        $table->time('jam_mulai');
        $table->time('jam_selesai');
        $table->text('keperluan');
        $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
        $table->timestamps();
    });

    // 1. Tabel Risk Assessment Utama
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('daftar_users')->onDelete('cascade');
            $table->string('nama');
            $table->string('nim');
            $table->string('no_kontak')->nullable();
            $table->text('alamat_kontak')->nullable();
            $table->foreignId('daftar_lab_id')->constrained('daftar_labs')->onDelete('cascade');
            $table->enum('jenis_ra', ['Penelitian', 'Praktikum', 'Lain-lain']);
            $table->string('topik_judul')->nullable();
            $table->foreignId('dosen_pembimbing_id')->nullable()->constrained('daftar_users')->onDelete('set null');
            $table->string('dosen_pembimbing_nama')->nullable();
            
            // Status dan Persetujuan
            $table->enum('status', ['draft', 'menunggu_dosen', 'menunggu_safety_officer', 'menunggu_kepala_lab', 'disetujui', 'ditolak'])->default('draft');
            
            // Penilaian Dosen Pembimbing
            $table->enum('kategori_resiko_dosen', ['tinggi', 'sedang', 'rendah'])->nullable();
            $table->boolean('persetujuan_dosen')->nullable();
            $table->text('catatan_dosen')->nullable();
            $table->timestamp('tanggal_persetujuan_dosen')->nullable();
            
            // Safety Officer
            $table->foreignId('safety_officer_id')->nullable()->constrained('daftar_users')->onDelete('set null');
            $table->string('safety_officer_nama')->nullable();
            $table->dateTime('jadwal_wawancara')->nullable();
            $table->boolean('persetujuan_safety_officer')->nullable();
            $table->text('catatan_safety_officer')->nullable();
            $table->timestamp('tanggal_persetujuan_safety_officer')->nullable();
            
            // Kepala Laboratorium
            $table->foreignId('kepala_lab_id')->nullable()->constrained('daftar_users')->onDelete('set null');
            $table->boolean('persetujuan_kepala_lab')->nullable();
            $table->text('catatan_kepala_lab')->nullable();
            $table->timestamp('tanggal_persetujuan_kepala_lab')->nullable();
            
            $table->timestamps();
        });

        // 2. Tabel Bahan Kimia dalam Risk Assessment
        Schema::create('ra_bahan_kimias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_assessment_id')->constrained('risk_assessments')->onDelete('cascade');
            $table->string('nama_bahan');
            $table->boolean('explosive')->default(false);
            $table->boolean('flammable')->default(false);
            $table->boolean('toxic')->default(false);
            $table->boolean('corrosive')->default(false);
            $table->boolean('irritant')->default(false);
            $table->boolean('oxidizing')->default(false);
            $table->text('lain_lain')->nullable();
            $table->string('msds_file')->nullable(); // Path ke file MSDS
            $table->timestamps();
        });

        // 3. Tabel Kategori Hazard Bahan
        Schema::create('ra_kategori_hazard_bahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_assessment_id')->constrained('risk_assessments')->onDelete('cascade');
            $table->enum('kategori', ['sangat_hazardous', 'hazardous', 'moderat', 'tidak_hazardous']);
            $table->timestamps();
        });

        // 4. Tabel Peralatan dan Kondisi Operasi
        Schema::create('ra_peralatan_operasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_assessment_id')->constrained('risk_assessments')->onDelete('cascade');
            $table->boolean('tekanan_tinggi')->default(false);
            $table->boolean('suhu_tinggi')->default(false);
            $table->boolean('nyala_api')->default(false);
            $table->boolean('peralatan_berputar')->default(false);
            $table->decimal('temperatur_maksimum', 8, 2)->nullable(); // dalam Celsius
            $table->decimal('tekanan_maksimum', 8, 2)->nullable(); // dalam atm
            $table->enum('kategori_hazard', ['sangat_hazardous', 'hazardous', 'moderat', 'tidak_hazardous'])->nullable();
            $table->timestamps();
        });

        // 5. Tabel Pelaku Kerja Laboratorium
        Schema::create('ra_pelaku_kerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_assessment_id')->constrained('risk_assessments')->onDelete('cascade');
            $table->boolean('menyadari_faktor_manusia')->default(false);
            $table->boolean('memahami_bahaya_diri')->default(false);
            $table->boolean('memahami_bahaya_orang_lain')->default(false);
            $table->boolean('memahami_bahaya_lingkungan')->default(false);
            $table->boolean('memahami_bahaya_peralatan')->default(false);
            $table->boolean('paham_tindakan_kecelakaan')->default(false);
            $table->enum('penilaian_keterampilan', ['ceroboh', 'kurang_terampil', 'cukup_terampil', 'sangat_terampil'])->nullable();
            $table->timestamps();
        });

        // 6. Tabel Pernyataan Mahasiswa
        Schema::create('ra_pernyataan_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_assessment_id')->constrained('risk_assessments')->onDelete('cascade');
            $table->boolean('setuju_bertanggung_jawab')->default(false);
            $table->string('tanda_tangan')->nullable(); // Path ke file signature atau base64
            $table->timestamp('tanggal_pernyataan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ra_pernyataan_mahasiswas');
        Schema::dropIfExists('ra_pelaku_kerjas');
        Schema::dropIfExists('ra_peralatan_operasis');
        Schema::dropIfExists('ra_kategori_hazard_bahan');
        Schema::dropIfExists('ra_bahan_kimias');
        Schema::dropIfExists('risk_assessments');
    }
};
