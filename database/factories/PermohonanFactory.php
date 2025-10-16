<?php

namespace Database\Factories;

use App\Models\Permohonan;
use App\Models\User;
use App\Models\Layanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permohonan>
 */
class PermohonanFactory extends Factory
{
    protected $model = Permohonan::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'layanan_id' => Layanan::factory(),
            'tanggal_pengajuan' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement(['Diajukan', 'Verifikasi', 'Proses', 'Selesai', 'Ditolak']),
            'no_tiket_admin' => $this->faker->optional()->bothify('TICKET-####'),
            'catatan_admin' => $this->faker->optional()->sentence(),
        ];
    }
}
