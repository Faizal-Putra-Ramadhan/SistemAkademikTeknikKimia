<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bebas_lab_requests', function (Blueprint $table) {
            $table->foreignId('risk_assessment_id')->nullable()->constrained('risk_assessments')->onDelete('cascade')->after('user_nama');
        });
    }

    public function down(): void
    {
        Schema::table('bebas_lab_requests', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['risk_assessment_id']);
            $table->dropColumn('risk_assessment_id');
        });
    }
};
