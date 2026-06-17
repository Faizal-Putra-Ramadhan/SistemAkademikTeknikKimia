<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_pengumumen_table.php
public function up()
{
    Schema::create('pengumumen', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->text('isi');
        $table->enum('status', ['draft', 'publish'])->default('publish');
        $table->string('author')->default('Administrator');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumumen');
    }
};
