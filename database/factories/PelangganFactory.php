<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PelangganFactory extends Factory
{
    protected $model = Pelanggan::class;

    public function definition(): array
    {
        return [
            'nama_pelanggan' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'password' => bcrypt('password'), // default password untuk factory
            'alamat' => $this->faker->address(),
            'nomor_kwh' => $this->faker->unique()->ean8(), // contoh nomor kwh
            'tarif_id' => null, // bisa di-override jika perlu relasi Tarif
            'remember_token' => Str::random(10),
        ];
    }
    
}