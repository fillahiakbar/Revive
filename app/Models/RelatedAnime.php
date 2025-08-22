<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedAnime extends Model
{
    protected $fillable = [
  'relate_anime_group_id','mal_id','poster','title','title_english'
];

    public function relatedAnimes()
    {
        return $this->hasMany(RelatedAnime::class);
    }
        // === Tambahkan ini ===
    public function animeLink()
    {
        // sesuaikan FK & owner key kalau beda
        return $this->belongsTo(AnimeLink::class, 'mal_id', 'mal_id');
    }
}
