<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tarif>
 */
class TarifFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                    'daya' => $this->faker->unique()->randomElement([450, 900, 1300, 2200, 3500]),
                    'tarif_perkwh' => $this->faker->numberBetween(1000, 2000),
                ];

    }
}
