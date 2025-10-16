<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Layanan>
 */
class LayananFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_layanan' => $this->faker->sentence(3),
            'deskripsi' => $this->faker->paragraph(),
            'kode_layanan' => strtoupper($this->faker->unique()->lexify('???')),
        ];
    }
}
