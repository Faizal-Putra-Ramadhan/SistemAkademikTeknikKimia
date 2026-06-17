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
        Schema::table('risk_assessments', function (Blueprint $table) {
            if (! Schema::hasColumn('risk_assessments', 'kepala_lab_nama')) {
                $table->string('kepala_lab_nama')->nullable()->after('kepala_lab_id');
            }
            if (! Schema::hasColumn('risk_assessments', 'nomor_identitas_dosen')) {
                $table->string('nomor_identitas_dosen')->nullable()->after('dosen_pembimbing_nama');
            }
            if (! Schema::hasColumn('risk_assessments', 'nomor_identitas_safety_officer')) {
                $table->string('nomor_identitas_safety_officer')->nullable()->after('safety_officer_nama');
            }
            if (! Schema::hasColumn('risk_assessments', 'nomor_identitas_kepala_lab')) {
                $table->string('nomor_identitas_kepala_lab')->nullable()->after('kepala_lab_nama');
            }
            if (! Schema::hasColumn('risk_assessments', 'nomor_identitas_kaprodi')) {
                $table->string('nomor_identitas_kaprodi')->nullable()->after('kaprodi_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            if (Schema::hasColumn('risk_assessments', 'nomor_identitas_kaprodi')) {
                $table->dropColumn('nomor_identitas_kaprodi');
            }
            if (Schema::hasColumn('risk_assessments', 'nomor_identitas_kepala_lab')) {
                $table->dropColumn('nomor_identitas_kepala_lab');
            }
            if (Schema::hasColumn('risk_assessments', 'nomor_identitas_safety_officer')) {
                $table->dropColumn('nomor_identitas_safety_officer');
            }
            if (Schema::hasColumn('risk_assessments', 'nomor_identitas_dosen')) {
                $table->dropColumn('nomor_identitas_dosen');
            }
            if (Schema::hasColumn('risk_assessments', 'kepala_lab_nama')) {
                $table->dropColumn('kepala_lab_nama');
            }
        });
    }
};
