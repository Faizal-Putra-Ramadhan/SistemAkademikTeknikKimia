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
                'Nama' => 'Faizal Putra Ramadhan',
                'UserID' => '2000020001',
                'Nomor_Identitas' => '2300018199',
                'Email' => '2300018199@webmail.uad.ac.id',
                'Password' => Hash::make('password'),
                'Role_User' => 'Mahasiswa',
                'Phone' => '081234567890',
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
