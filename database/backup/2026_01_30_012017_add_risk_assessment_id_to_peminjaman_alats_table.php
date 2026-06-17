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
        Schema::table('peminjaman_alats', function (Blueprint $table) {
            $table->foreignId('risk_assessment_id')->nullable()->after('user_nama')->constrained('risk_assessments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_alats', function (Blueprint $table) {
            $table->dropForeign(['risk_assessment_id']);
            $table->dropColumn('risk_assessment_id');
        });
    }
};
