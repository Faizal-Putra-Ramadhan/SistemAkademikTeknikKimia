<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Admin, Dosen, Mahasiswa, dll
            $table->string('display_name')->nullable(); // Display name untuk UI
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default roles
        $roles = [
            ['name' => 'Admin', 'display_name' => 'Administrator'],
            ['name' => 'Dosen', 'display_name' => 'Dosen'],
            ['name' => 'Mahasiswa', 'display_name' => 'Mahasiswa'],
            ['name' => 'Laboran', 'display_name' => 'Laboran'],
            ['name' => 'Safety Officer', 'display_name' => 'Safety Officer'],
            ['name' => 'Kepala Laboratorium', 'display_name' => 'Kepala Laboratorium'],
            ['name' => 'Kaprodi', 'display_name' => 'Kaprodi'],
            ['name' => 'Peneliti Eksternal', 'display_name' => 'Peneliti Eksternal'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role['name'],
                'display_name' => $role['display_name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
