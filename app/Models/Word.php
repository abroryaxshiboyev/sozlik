<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Word extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['user_id','latin','kiril','description_latin','description_kiril','audio','count','example_latin','example_kiril'];

    public function categories()
    {
        return $this->belongsToMany(Category::class,'word_categories')->as('categories')->withTrashed();
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
