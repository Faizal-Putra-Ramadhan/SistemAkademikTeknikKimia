<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alat_labs', function (Blueprint $table) {
            $table->foreignId('stock_group_id')
                ->nullable()
                ->after('daftar_lab_id')
                ->constrained('stock_groups')
                ->nullOnDelete();

            $table->index('stock_group_id');
        });
    }

    public function down(): void
    {
        Schema::table('alat_labs', function (Blueprint $table) {
            $table->dropForeign(['stock_group_id']);
            $table->dropIndex(['stock_group_id']);
            $table->dropColumn('stock_group_id');
        });
    }
};
