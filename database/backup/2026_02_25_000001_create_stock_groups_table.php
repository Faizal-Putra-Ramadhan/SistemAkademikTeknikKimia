<?php

use App\Enums\LabType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_groups', function (Blueprint $table) {
            $table->id();
            $table->string('floor');
            $table->enum('lab_type', LabType::values());
            $table->timestamps();

            $table->unique(['floor', 'lab_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_groups');
    }
};
