<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Openning>
 */
class OpenningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'expert_id' => fake()->numberBetween(1,10),
            'day' => fake()->randomElement(['sunday','monday','tuesday','wednesday','thursday','friday','saturday']),
            'hours' => fake()->regexify('[0-1]{24}')
        ];
    }
}
