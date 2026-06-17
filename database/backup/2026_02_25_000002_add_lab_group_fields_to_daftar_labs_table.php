<?php

use App\Enums\LabType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daftar_labs', function (Blueprint $table) {
            $table->string('floor')->nullable()->after('Nama_Laboratorium');
            $table->enum('lab_type', LabType::values())->nullable()->after('floor');
            $table->foreignId('stock_group_id')
                ->nullable()
                ->after('lab_type')
                ->constrained('stock_groups')
                ->nullOnDelete();

            $table->index(['floor', 'lab_type']);
        });
    }

    public function down(): void
    {
        Schema::table('daftar_labs', function (Blueprint $table) {
            $table->dropForeign(['stock_group_id']);
            $table->dropIndex(['floor', 'lab_type']);
            $table->dropColumn(['floor', 'lab_type', 'stock_group_id']);
        });
    }
};
