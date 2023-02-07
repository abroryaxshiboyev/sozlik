<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Word;
use Illuminate\Database\Eloquent\Factories\Factory;

class WordCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' =>function(){
                return Category::factory()->create()->id;
            },
            'word_id' =>function(){
                return Word::factory()->create()->id;
            },
        ];
    }
}
