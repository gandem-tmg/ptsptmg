<?php

namespace Database\Factories;

use App\Models\Seksi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seksi>
 */
class SeksiFactory extends Factory
{
    protected $model = Seksi::class;

    public function definition(): array
    {
        return [
            'nama_seksi' => 'Seksi ' . $this->faker->unique()->word(),
            'kode_seksi' => strtoupper($this->faker->unique()->lexify('????')),
            'keterangan' => $this->faker->optional()->sentence(),
        ];
    }
}
