<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=['latin','kiril'];
    public function words()
    {
        return $this->belongsToMany(Word::class,'word_categories');
    }
}
