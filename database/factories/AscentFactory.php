<?php

namespace Database\Factories;

use App\Models\Peak;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ascent>
 */
class AscentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'peak_id' => Peak::factory(),
            'date' => $this->faker->date(),
            'notes' => $this->faker->sentence(),
        ];
    }
}
