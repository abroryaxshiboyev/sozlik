<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wordoftheday extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=['word_id','count'];

    public function wordday()
    {
        return $this->belongsTo(Word::class);
    }
}
