<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimeVisit extends Model
{
    protected $fillable = ['anime_link_id', 'visited_date', 'count'];

    public function animeLink()
    {
        return $this->belongsTo(AnimeLink::class);
    }
}
