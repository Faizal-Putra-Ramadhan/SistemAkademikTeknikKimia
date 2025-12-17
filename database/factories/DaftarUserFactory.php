<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DaftarUser>
 */
class DaftarUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Nama' => fake()->name(),
            'Phone' => fake()->phoneNumber(),
            'Email' => fake()->email(),
            'Role_User' => fake()->randomElement(['Admin', 'Tendik', 'Dosen', 'Mahasiswa'])
        ];
    }
}
