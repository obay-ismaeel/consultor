<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Openning;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expert>
 */
class ExpertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // for($i=1; $i<8; $i++)
        //     Openning::create([
        //         'expert_id' => $expert->id,
        //         'day' => $i
        //     ]);
        return [
            'user_id'=>User::factory()->create(['is_expert'=>1])->id,
            'address'=>fake()->address(),
            'image_path' => "default.png",
            'experience' => fake()->text()
        ];
    }
}
