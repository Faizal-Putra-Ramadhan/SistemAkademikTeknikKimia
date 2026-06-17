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
            if (! Schema::hasColumn('risk_assessments', 'kaprodi_nama')) {
                $table->string('kaprodi_nama')->nullable()->after('kaprodi_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            if (Schema::hasColumn('risk_assessments', 'kaprodi_nama')) {
                $table->dropColumn('kaprodi_nama');
            }
        });
    }
};
