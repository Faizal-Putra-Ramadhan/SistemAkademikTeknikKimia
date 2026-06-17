<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('alat_labs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daftar_lab_id')->nullable()->index('alat_labs_daftar_lab_id_foreign');
            $table->unsignedBigInteger('stock_group_id')->nullable()->index('alat_labs_stock_group_id_index');
            $table->string('nama_alat');
            $table->text('deskripsi')->nullable();
            $table->integer('jumlah_tersedia')->default(1);
            $table->string('foto')->nullable();
            $table->timestamps();

            $table->foreign('daftar_lab_id', 'alat_labs_daftar_lab_id_foreign')->references('id')->on('daftar_labs')->onDelete('cascade');
            $table->foreign('stock_group_id', 'alat_labs_stock_group_id_foreign')->references('id')->on('stock_groups')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alat_labs');
    }
};
