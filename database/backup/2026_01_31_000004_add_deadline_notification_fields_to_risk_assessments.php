<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            // Add columns untuk tracking deadline notification
            $table->boolean('notifikasi_deadline_terkirim')->default(false)->after('jumlah_perpanjangan');
            $table->timestamp('tanggal_notifikasi_deadline')->nullable()->after('notifikasi_deadline_terkirim');
        });
    }

    public function down(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->dropColumn([
                'notifikasi_deadline_terkirim',
                'tanggal_notifikasi_deadline',
            ]);
        });
    }
};
