<?php

namespace Database\Seeders;

use App\Models\DaftarUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Membuat akun Admin default untuk mengelola sistem.
     * Ganti password setelah login pertama kali!
     */
    public function run(): void
    {
        $admin = DaftarUser::updateOrCreate(
            ['UserID' => 'ADM-0000000001'],
            [
                'Nama' => 'Administrator',
                'Email' => 'admin@reglab.uad.ac.id',
                'Phone' => '080000000001',
                'Password' => Hash::make('AdminRegLab2026!'),
                'Role_User' => 'Admin',
                'Nomor_Identitas' => '0000000000000001',
                'is_primary' => true,
                'status' => 'aktif',
            ]
        );

        // Sync multi-roles
        $admin->syncRoles(['Admin'], 'Admin');
    }
}
