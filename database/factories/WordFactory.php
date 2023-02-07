<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Letter;
use Illuminate\Database\Eloquent\Factories\Factory;

class WordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'latin'=>Letter::get()[$r=(rand(1,37))]['latin'].Letter::get()[$r1=(rand(1,37))]['latin'],
            'kiril'=>Letter::get()[$r]['kiril'].Letter::get()[$r1]['kiril'],
            'description_latin'=>$this->faker->sentence(),
            'description_kiril'=>$this->faker->sentence(),
            
        ];
    }
}
