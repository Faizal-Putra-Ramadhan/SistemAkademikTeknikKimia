<?php

// database/migrations/2024_12_01_000001_create_pending_registrations_table.php

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
        Schema::create('pending_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('Mahasiswa'); // Tambahan
            $table->string('nomor_identitas')->nullable(); // Tambahan
            $table->unsignedBigInteger('parent_user_id')->nullable(); // Tambahan
            $table->boolean('is_primary')->default(true); // Tambahan
            $table->string('verification_token')->unique();
            $table->timestamp('token_expires_at');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            // Tambahan indexes untuk performa
            $table->index('email');
            $table->index('verification_token');
            $table->index('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_registrations');
    }
};
