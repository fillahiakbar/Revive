<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimeRating extends Model
{
    protected $fillable = [
        'anime_link_id',
        'user_id',
        'rating',
    ];

    public function animeLink()
    {
        return $this->belongsTo(AnimeLink::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
