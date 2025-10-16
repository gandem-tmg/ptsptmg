<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Petugas User',
            'email' => 'petugas@example.com',
            'role' => 'petugas',
        ]);

        User::factory()->create([
            'name' => 'Pemohon User',
            'email' => 'pemohon@example.com',
            'role' => 'pemohon',
        ]);

        \App\Models\Layanan::factory(5)->create();
        \App\Models\Persyaratan::factory(10)->create();
        \App\Models\Permohonan::factory(10)->create();
    }
}
