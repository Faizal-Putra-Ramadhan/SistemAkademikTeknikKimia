<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DaftarLab>
 */
class DaftarLabFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Nama_Laboratorium' => fake()->sentence(),
            'Kepala_Labolatorium' => fake()->name(),
            'Admin_Laboratorium' => fake()->name(),
            'Safety_Officer' => fake()->name(),
            'email_lab' => fake()->unique()->safeEmail()
        ];
    }
}
