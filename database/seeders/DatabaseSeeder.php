<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            DosenLaboranLabSeeder::class,
            // Jika Anda punya Lab/Laboran testing data yang ingin diload juga, bisa di-uncomment:
            // LaboranSeeder::class,
        ]);
    }
}
