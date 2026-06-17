<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            DB::table('roles')->updateOrInsert(
            ['name' => $role['name']], // check condition
            [
                'display_name' => $role['display_name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
            );
        }
    }
}
