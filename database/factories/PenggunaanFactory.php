<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penggunaan>
 */
class PenggunaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                'pelanggan_id' => \App\Models\Pelanggan::factory(),
                'bulan' => $this->faker->unique()->randomElement([
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ]),
                'tahun' => 2025,
                'meter_awal' => $this->faker->numberBetween(100, 500),
                'meter_akhir' => $this->faker->numberBetween(600, 1000),
            ];
    }
}
