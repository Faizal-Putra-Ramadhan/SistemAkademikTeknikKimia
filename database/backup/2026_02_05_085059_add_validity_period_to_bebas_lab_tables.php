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
        Schema::table('bebas_lab_requests', function (Blueprint $table) {
            $table->timestamp('tanggal_berlaku_dari')->nullable()->after('kepala_lab_approved_at');
            $table->timestamp('tanggal_berlaku_sampai')->nullable()->after('tanggal_berlaku_dari');
            $table->boolean('is_active')->default(true)->after('tanggal_berlaku_sampai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bebas_lab_requests', function (Blueprint $table) {
            $table->dropColumn(['tanggal_berlaku_dari', 'tanggal_berlaku_sampai', 'is_active']);
        });
    }
};
