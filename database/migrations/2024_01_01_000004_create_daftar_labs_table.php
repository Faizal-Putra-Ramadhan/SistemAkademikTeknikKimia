<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('daftar_labs', function (Blueprint $table) {
            $table->id();
            $table->string('Nama_Laboratorium');
            $table->string('floor')->nullable();
            $table->enum('lab_type', ['penelitian', 'pendidikan'])->nullable();
            $table->unsignedBigInteger('stock_group_id')->nullable()->index('daftar_labs_stock_group_id_foreign');
            $table->string('Kepala_Labolatorium');
            $table->string('Admin_Laboratorium');
            $table->string('Safety_Officer')->nullable();
            $table->string('email_lab')->nullable();
            $table->timestamps();

            $table->index(['floor', 'lab_type'], 'daftar_labs_floor_lab_type_index');
            $table->foreign('stock_group_id', 'daftar_labs_stock_group_id_foreign')->references('id')->on('stock_groups')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daftar_labs');
    }
};
