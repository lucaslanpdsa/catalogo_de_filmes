<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'movie_id',
        'title',
        'poster_path',
        'release_date',
        'vote_average',
    ];
}
