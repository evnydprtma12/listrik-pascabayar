<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tagihan;
use App\Models\Pelanggan;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembayaran>
 */
class PembayaranFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tagihan_id' => Tagihan::factory(),
            'pelanggan_id' => Pelanggan::factory(),
            'tanggal_pembayaran' => $this->faker->dateTimeThisYear(),
            'biaya_admin' => 2500,
            'total_bayar' => 150000,
        ];
    }
}
