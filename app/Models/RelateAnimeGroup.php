<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelateAnimeGroup extends Model
{
    protected $fillable = ['name'];

    public function relatedAnimes()
    {
        return $this->hasMany(RelatedAnime::class);
    }

    public function animeLinks()
    {
        return $this->hasMany(AnimeLink::class, 'related_anime_group_id');
    }
}
