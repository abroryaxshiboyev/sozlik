<?php

namespace Database\Factories;

use App\Models\Word;
use Illuminate\Database\Eloquent\Factories\Factory;

class AntonymFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'word_id' =>function(){
                return Word::factory()->create()->id;
            },
            'antonym_word_id' =>function(){
                return Word::factory()->create()->id;
            },
        ];
    }
}
