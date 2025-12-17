<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DaftarLaboranLaboratorium>
 */
class DaftarLaboranLaboratoriumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Laboratorium' => fake()->sentence(),
            'Nama_Laboran' => fake()->name(),
            'UserID' => str_replace('-', '.', Str::slug(fake()->name)),
            'Phone' => fake()->name(),
            'Email' => fake()->unique()->safeEmail(),
            'Role_User' => fake()->randomElement(['Admin', 'Tendik', 'Dosen', 'Mahasiswa'])
        ];
    }
}
