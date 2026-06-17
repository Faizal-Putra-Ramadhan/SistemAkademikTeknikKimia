<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing Role_User data to user_roles table
        $users = DB::table('daftar_users')->get();

        foreach ($users as $user) {
            // Find role by name
            $role = DB::table('roles')->where('name', $user->Role_User)->first();

            if ($role) {
                // Insert into user_roles with is_primary = true
                DB::table('user_roles')->insert([
                    'user_id' => $user->id,
                    'role_id' => $role->id,
                    'is_primary' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear user_roles table
        DB::table('user_roles')->truncate();
    }
};
