<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            if (! Schema::hasColumn('peminjaman_ruangans', 'risk_assessment_id')) {
                $table->foreignId('risk_assessment_id')->nullable()->constrained('risk_assessments')->onDelete('cascade')->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['risk_assessment_id']);
            $table->dropColumn('risk_assessment_id');
        });
    }
};
