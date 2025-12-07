<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peak>
 */
class PeakFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word().' Fell',
            'category' => 'Wainwright',
            'lat' => $this->faker->latitude(54.0, 55.0),
            'lon' => $this->faker->longitude(-4.8, -2.8),
            'elevation' => $this->faker->numberBetween(200, 1000),
            'notes' => null,
        ];
    }
}
