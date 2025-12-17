<?php
// database/seeders/LaboranSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DaftarUser;
use App\Models\DaftarLaboranLaboratorium;
use Illuminate\Support\Facades\Hash;

class LaboranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat user Laboran di tabel daftar_users
        $laboran1 = DaftarUser::create([
            'Nama' => 'Budi Santoso',
            'Phone' => '081234567890',
            'Email' => 'budi.laboran@university.ac.id',
            'UserID' => 'LAB-2511280001',
            'Password' => Hash::make('laboran123'), // password: laboran123
            'Role_User' => 'Laboran',
            'foto' => null,
        ]);

        // 2. Buat entry di tabel daftar_laboran_laboratoriums
        DaftarLaboranLaboratorium::create([
            'Laboratorium' => 'LAB TEKIM', // Sesuaikan dengan nama lab di database Anda
            'Nama_Laboran' => 'Budi Santoso',
            'UserID' => 'LAB-2511280001',
            'Phone' => '081234567890',
            'Email' => 'budi.laboran@university.ac.id',
            'Role_User' => 'Laboran',
        ]);

        // Laboran 2 - Lab Komputer
        $laboran2 = DaftarUser::create([
            'Nama' => 'Sri Wahyuni',
            'Phone' => '081234567891',
            'Email' => 'sri.laboran@university.ac.id',
            'UserID' => 'LAB-2511280002',
            'Password' => Hash::make('laboran123'),
            'Role_User' => 'Laboran',
            'foto' => null,
        ]);

        DaftarLaboranLaboratorium::create([
            'Laboratorium' => 'Lab Komputer',
            'Nama_Laboran' => 'Sri Wahyuni',
            'UserID' => 'LAB-2511280002',
            'Phone' => '081234567891',
            'Email' => 'sri.laboran@university.ac.id',
            'Role_User' => 'Laboran',
        ]);

        // Laboran 3 - Lab Fisika
        $laboran3 = DaftarUser::create([
            'Nama' => 'Ahmad Fauzi',
            'Phone' => '081234567892',
            'Email' => 'ahmad.laboran@university.ac.id',
            'UserID' => 'LAB-2511280003',
            'Password' => Hash::make('laboran123'),
            'Role_User' => 'Laboran',
            'foto' => null,
        ]);

        DaftarLaboranLaboratorium::create([
            'Laboratorium' => 'Lab Fisika',
            'Nama_Laboran' => 'Ahmad Fauzi',
            'UserID' => 'LAB-2511280003',
            'Phone' => '081234567892',
            'Email' => 'ahmad.laboran@university.ac.id',
            'Role_User' => 'Laboran',
        ]);

        echo "✅ Seeder Laboran berhasil dijalankan!\n";
        echo "📝 Data Login Laboran:\n\n";
        echo "1. LAB TEKIM\n";
        echo "   UserID: LAB-2511280001\n";
        echo "   Password: laboran123\n\n";
        echo "2. Lab Komputer\n";
        echo "   UserID: LAB-2511280002\n";
        echo "   Password: laboran123\n\n";
        echo "3. Lab Fisika\n";
        echo "   UserID: LAB-2511280003\n";
        echo "   Password: laboran123\n\n";
    }
}

// ============================================
// database/seeders/DatabaseSeeder.php
// Tambahkan di file ini untuk memanggil LaboranSeeder

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LaboranSeeder::class,
            // seeder lainnya...
        ]);
    }
}