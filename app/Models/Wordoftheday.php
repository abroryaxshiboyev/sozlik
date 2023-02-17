<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wordoftheday extends Model
{
    use HasFactory;
    protected $fillable=['word_id','count'];

    public function wordday()
    {
        return $this->belongsTo(Word::class);
    }
}
