<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Consult;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Category::factory()->create(['name'=>'medical']);
        Category::factory()->create(['name'=>'family']);
        Category::factory()->create(['name'=>'financial']);
        Category::factory()->create(['name'=>'psychology']);
        Category::factory()->create(['name'=>'business']);
        Consult::factory(10)->create();
    }
}
