<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedAnime extends Model
{
    protected $fillable = [
        'relate_anime_group_id',
        'anime_link_id',
        'mal_id',
        'poster',
        'title',
        'title_english',
        'relation_title',
    ];

    public function group()
    {
        return $this->belongsTo(RelateAnimeGroup::class, 'relate_anime_group_id');
    }


    public function animeLink()
    {
        return $this->belongsTo(AnimeLink::class, 'anime_link_id');
    }
}
