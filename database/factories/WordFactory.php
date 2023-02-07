<?php

namespace Database\Factories;

use App\Models\Category;
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
            'latin'=>$this->faker->word,
            'kiril'=>$this->faker->word,
            'description_latin'=>$this->faker->sentence(),
            'description_kiril'=>$this->faker->sentence(),
            
        ];
    }
}
