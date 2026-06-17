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
        Schema::table('daftar_users', function (Blueprint $table) {
            // Kolom untuk linking akun
            $table->unsignedBigInteger('parent_user_id')->nullable()->after('id');
            $table->boolean('is_primary')->default(true)->after('parent_user_id');

            // Foreign key constraint
            $table->foreign('parent_user_id')
                ->references('id')
                ->on('daftar_users')
                ->onDelete('cascade');

            // Index untuk performa
            $table->index('parent_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_users', function (Blueprint $table) {
            $table->dropForeign(['parent_user_id']);
            $table->dropColumn(['parent_user_id', 'is_primary']);
        });
    }
};
