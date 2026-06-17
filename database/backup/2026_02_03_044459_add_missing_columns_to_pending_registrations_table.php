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
        Schema::table('pending_registrations', function (Blueprint $table) {
            // Tambah kolom yang hilang jika belum ada
            if (! Schema::hasColumn('pending_registrations', 'role')) {
                $table->string('role')->default('Mahasiswa')->after('password');
            }
            if (! Schema::hasColumn('pending_registrations', 'nomor_identitas')) {
                $table->string('nomor_identitas')->nullable()->after('role');
            }
            if (! Schema::hasColumn('pending_registrations', 'parent_user_id')) {
                $table->unsignedBigInteger('parent_user_id')->nullable()->after('nomor_identitas');
            }
            if (! Schema::hasColumn('pending_registrations', 'is_primary')) {
                $table->boolean('is_primary')->default(true)->after('parent_user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pending_registrations', function (Blueprint $table) {
            // Hapus kolom jika ada
            $columns = ['role', 'nomor_identitas', 'parent_user_id', 'is_primary'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pending_registrations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
