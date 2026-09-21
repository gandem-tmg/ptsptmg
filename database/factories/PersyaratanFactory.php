<?php

namespace Database\Factories;

use App\Models\Layanan;
use App\Models\Persyaratan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Persyaratan>
 */
class PersyaratanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Persyaratan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'layanan_id' => Layanan::factory(),
            'nama_persyaratan' => $this->faker->sentence(),
            'deskripsi' => $this->faker->paragraph(),
            'wajib' => $this->faker->boolean(),
        ];
    }
}
