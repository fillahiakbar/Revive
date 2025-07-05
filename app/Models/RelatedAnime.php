<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RelatedAnime extends Model
{
    use HasFactory;

    protected $fillable = [
        'anime_link_id',
        'mal_id',
        'title',
        'poster',
    ];

    public function animeLink()
    {
        return $this->belongsTo(AnimeLink::class);
    }
}
