<?php

namespace Database\Seeders;

use App\Models\DaftarUser;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role Mahasiswa ada
        Role::firstOrCreate(['name' => 'Mahasiswa']);

        $mahasiswaData = [
            [
                'Nama' => 'Mahasiswa Dummy 1',
                'UserID' => '2000020001',
                'Nomor_Identitas' => '2000020001',
                'Email' => 'mahasiswa1@mhs.uad.ac.id',
                'Password' => Hash::make('password'),
                'Role_User' => 'Mahasiswa',
                'Phone' => '081234567890',
                'status' => 'Aktif',
                'is_primary' => true,
            ],
            [
                'Nama' => 'Mahasiswa Dummy 2',
                'UserID' => '2000020002',
                'Nomor_Identitas' => '2000020002',
                'Email' => 'mahasiswa2@mhs.uad.ac.id',
                'Password' => Hash::make('password'),
                'Role_User' => 'Mahasiswa',
                'Phone' => '081234567891',
                'status' => 'Aktif',
                'is_primary' => true,
            ],
            [
                'Nama' => 'Mahasiswa Dummy 3',
                'UserID' => '2000020003',
                'Nomor_Identitas' => '2000020003',
                'Email' => 'mahasiswa3@mhs.uad.ac.id',
                'Password' => Hash::make('password'),
                'Role_User' => 'Mahasiswa',
                'Phone' => '081234567892',
                'status' => 'Aktif',
                'is_primary' => true,
            ],
        ];

        foreach ($mahasiswaData as $data) {
            $user = DaftarUser::updateOrCreate(
                ['UserID' => $data['UserID']],
                $data
            );

            // Assign role ke user
            $user->assignRole('Mahasiswa', true);
        }
    }
}
