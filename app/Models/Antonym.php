<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antonym extends Model
{
    use HasFactory;
    protected $fillable=['word_id','antonym_word_id'];
}
