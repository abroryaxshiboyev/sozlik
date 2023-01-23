<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function category()
    {
        return $this->belongsToMany(Category::class,'word_categories');
    }
    public function synonyms()
    {
        return $this->belongsToMany(Word::class,'synonyms','synonym_word_id');
    }
    public function antonyms()
    {
        return $this->belongsToMany(Word::class,'antonyms','antonym_word_id');
    }
}
