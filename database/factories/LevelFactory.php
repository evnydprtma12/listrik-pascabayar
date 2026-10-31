<?php

namespace Database\Factories;

use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

class LevelFactory extends Factory
{
    protected $model = Level::class;

    /**
     * Define the model's default state.
     * Kita bisa beri nilai default di sini, atau biarkan kosong jika Anda selalu menggunakan state.
     * Untuk kasus ini, mari kita tetapkan 'petugas' sebagai default jika tidak ada state yang dipanggil.
     * Atau, Anda bisa menggunakan faker untuk nama_level yang lebih umum.
     */
    public function definition(): array
    {
        return [
            'nama_level' => $this->faker->randomElement(['petugas', 'administrator']),
            // Atau jika Anda ingin default yang lebih jelas:
            // 'nama_level' => 'petugas',
        ];
    }

    /**
     * Indicate that the level is an administrator.
     */
    public function administrator(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'nama_level' => 'administrator',
            ];
        });
    }

    /**
     * Indicate that the level is a petugas.
     */
    public function petugas(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'nama_level' => 'petugas',
            ];
        });
    }
}