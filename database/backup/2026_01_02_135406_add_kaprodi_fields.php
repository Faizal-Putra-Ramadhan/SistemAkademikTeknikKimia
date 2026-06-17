<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            // Tambahkan kolom baru untuk kaprodi jika belum ada
            if (! Schema::hasColumn('risk_assessments', 'kaprodi_id')) {
                $table->unsignedBigInteger('kaprodi_id')->nullable()->after('tanggal_persetujuan_kepala_lab');
                $table->boolean('persetujuan_kaprodi')->nullable()->after('kaprodi_id');
                $table->text('catatan_kaprodi')->nullable()->after('persetujuan_kaprodi');
                $table->timestamp('tanggal_persetujuan_kaprodi')->nullable()->after('catatan_kaprodi');
            }

            // UNTUK SQLITE: Jangan gunakan MODIFY.
            // Cukup ubah menjadi string biasa agar fleksibel menerima status baru.
            $table->string('status', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->dropForeign(['kaprodi_id']);
            $table->dropColumn([
                'kaprodi_id',
                'persetujuan_kaprodi',
                'catatan_kaprodi',
                'tanggal_persetujuan_kaprodi',
            ]);
        });
    }
};
