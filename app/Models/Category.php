<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable=['latin','kiril'];
    public function words()
    {
        return $this->belongsToMany(Word::class,'word_categories');
    }
}
