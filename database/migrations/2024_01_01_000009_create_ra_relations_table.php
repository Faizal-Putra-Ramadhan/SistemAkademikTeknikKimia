<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('ra_bahan_kimias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('risk_assessment_id')->index('ra_bahan_kimias_risk_assessment_id_foreign');
            $table->string('nama_bahan');
            $table->boolean('explosive')->default(false);
            $table->boolean('flammable')->default(false);
            $table->boolean('toxic')->default(false);
            $table->boolean('corrosive')->default(false);
            $table->boolean('irritant')->default(false);
            $table->boolean('oxidizing')->default(false);
            $table->text('lain_lain')->nullable();
            $table->string('msds_file')->nullable();
            $table->timestamps();

            $table->foreign('risk_assessment_id', 'ra_bahan_kimias_risk_assessment_id_foreign')->references('id')->on('risk_assessments')->onDelete('cascade');
        });

        Schema::create('ra_kategori_hazard_bahan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('risk_assessment_id')->index('ra_kategori_hazard_bahan_risk_assessment_id_foreign');
            $table->enum('kategori', ['sangat_hazardous', 'hazardous', 'moderat', 'tidak_hazardous']);
            $table->timestamps();

            $table->foreign('risk_assessment_id', 'ra_kategori_hazard_bahan_risk_assessment_id_foreign')->references('id')->on('risk_assessments')->onDelete('cascade');
        });

        Schema::create('ra_pelaku_kerjas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('risk_assessment_id')->index('ra_pelaku_kerjas_risk_assessment_id_foreign');
            $table->boolean('menyadari_faktor_manusia')->default(false);
            $table->boolean('memahami_bahaya_diri')->default(false);
            $table->boolean('memahami_bahaya_orang_lain')->default(false);
            $table->boolean('memahami_bahaya_lingkungan')->default(false);
            $table->boolean('memahami_bahaya_peralatan')->default(false);
            $table->boolean('paham_tindakan_kecelakaan')->default(false);
            $table->enum('penilaian_keterampilan', ['ceroboh', 'kurang_terampil', 'cukup_terampil', 'sangat_terampil'])->nullable();
            $table->timestamps();

            $table->foreign('risk_assessment_id', 'ra_pelaku_kerjas_risk_assessment_id_foreign')->references('id')->on('risk_assessments')->onDelete('cascade');
        });

        Schema::create('ra_peralatan_operasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('risk_assessment_id')->index('ra_peralatan_operasis_risk_assessment_id_foreign');
            $table->boolean('tekanan_tinggi')->default(false);
            $table->boolean('suhu_tinggi')->default(false);
            $table->boolean('nyala_api')->default(false);
            $table->boolean('peralatan_berputar')->default(false);
            $table->decimal('temperatur_maksimum', 8, 2)->nullable();
            $table->decimal('tekanan_maksimum', 8, 2)->nullable();
            $table->enum('kategori_hazard', ['sangat_hazardous', 'hazardous', 'moderat', 'tidak_hazardous'])->nullable();
            $table->timestamps();

            $table->foreign('risk_assessment_id', 'ra_peralatan_operasis_risk_assessment_id_foreign')->references('id')->on('risk_assessments')->onDelete('cascade');
        });

        Schema::create('ra_pernyataan_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('risk_assessment_id')->index('ra_pernyataan_mahasiswas_risk_assessment_id_foreign');
            $table->boolean('setuju_bertanggung_jawab')->default(false);
            $table->string('tanda_tangan')->nullable();
            $table->timestamp('tanggal_pernyataan')->nullable();
            $table->timestamps();

            $table->foreign('risk_assessment_id', 'ra_pernyataan_mahasiswas_risk_assessment_id_foreign')->references('id')->on('risk_assessments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ra_pernyataan_mahasiswas');
        Schema::dropIfExists('ra_peralatan_operasis');
        Schema::dropIfExists('ra_pelaku_kerjas');
        Schema::dropIfExists('ra_kategori_hazard_bahan');
        Schema::dropIfExists('ra_bahan_kimias');
    }
};
