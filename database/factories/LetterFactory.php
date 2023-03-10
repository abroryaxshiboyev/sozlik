<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LetterFactory extends Factory
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
            'kiril'=>$this->faker->word
        ];
    }
}
