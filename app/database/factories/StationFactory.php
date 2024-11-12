<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Station>
 */
class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'location' => $this->faker->sentence(20),
            'latitude' => $this->faker->randomFloat($nbMaxDecimals = 10, $min = 1, $max = 10),
            'longitude' => $this->faker->randomFloat($nbMaxDecimals = 10, $min = 1, $max = 200)
        ];
    }
}
