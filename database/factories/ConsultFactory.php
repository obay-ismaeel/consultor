<?php

namespace Database\Factories;

use App\Models\Expert;
use App\Models\Service;
use App\Models\User;
use App\Models\Openning;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Consult>
 */
class ConsultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        //I've compared the $aptnmt to the date now() in order to fill the last two fields
        $expert = Expert::factory()->create();
        $cat_id = fake()->numberBetween(1,5);
        Service::factory()->create([ 'category_id'=>$cat_id, 'expert_id'=>$expert->id ]);
        for($i=1; $i<8; $i++)
            Openning::factory()->create([
                'expert_id' => $expert->id,
                'day' => $i
            ]);

        return [
            'user_id' => User::factory()->create( ['is_expert'=>0] )->id,
            'expert_id' => $expert->id,
            'category_id' => $cat_id,
            'appointment' => $apnmt = fake()->date(),  
            'hour' => fake()->time('H'),
            'is_completed' => 0 /*$apnmt > now() ? false : true*/ ,                  
            'rating' => $apnmt > now() ? null : fake()->numberBetween(0,50)
        ];
    }
}
