<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
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
            'category_id' => fake()->numberBetween(1,5),
            'price' => fake()->numberBetween(50000,200000)
        ];
    }
}
