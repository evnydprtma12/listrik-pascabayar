<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tagihan>
 */
class TagihanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'penggunaan_id' => \App\Models\Penggunaan::factory(),
            'pelanggan_id' => \App\Models\Pelanggan::factory(),
            'bulan' => now()->format('F'),
            'tahun' => now()->year,
            'jumlah_meter' => $this->faker->numberBetween(50, 500),
            'status_tagihan' => 'Belum Dibayar',
        ];
    }
}
