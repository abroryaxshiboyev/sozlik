<?php

namespace Database\Factories;

use App\Models\Word;
use Illuminate\Database\Eloquent\Factories\Factory;

class SynonymFactory extends Factory
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
            'synonym_word_id' =>function(){
                return Word::factory()->create()->id;
            },
        ];
    }
}
