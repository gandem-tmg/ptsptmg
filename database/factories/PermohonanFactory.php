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
            // CATATAN: nilai enum status harus persis sama (huruf kecil semua)
            // dengan yang didefinisikan di migration. Nilai lama di sini
            // ('Diajukan', dst — berhuruf kapital) kebetulan lolos di MySQL
            // (collation case-insensitive) tapi gagal di SQLite yang dipakai
            // test (CHECK constraint case-sensitive) — makanya diperbaiki.
            'status' => 'diajukan',
            'sumber_pengajuan' => 'online',
            'no_tiket' => 'TKT-' . strtoupper(uniqid()),
            'no_tiket_admin' => $this->faker->optional()->bothify('TICKET-####'),
            'catatan_admin' => $this->faker->optional()->sentence(),
            'unit_kerja' => $this->faker->randomElement(['Sub bagian TU', 'Penma', 'PAIS', 'PdPontren', 'BIMAS Islam', 'PLHUT']),
        ];
    }
}
