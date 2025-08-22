<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AnimeLink extends Model
{
    protected $fillable = ['mal_id', 'title', 'poster','episodes', 'synopsis', 'season', 'year', 'type', 'genres', 'title_english', 'imdb_id', 'mal_score',
    'imdb_score'];
    
     protected $casts = [
        'click_count' => 'integer',
    ];

    public function types()
    {
        return $this->belongsToMany(AnimeType::class);
    }

 public function batches()
{
    return $this->hasMany(Batch::class, 'anime_link_id');
}

protected static function booted()
{
    static::deleting(function ($animeLink) {
        $animeLink->batches()->each(function ($batch) {
            $batch->batchLinks()->delete(); // hapus link
        });
        $animeLink->batches()->delete(); // hapus batch
    });

        static::creating(function ($anime) {
        $anime->slug = Str::slug($anime->title);
    });
}



public function generateRssFile()
{
    $rssXml = View::make('rss.feed', ['anime' => $this->load('batches.batchLinks')])->render();

    $path = public_path('rss/' . $this->slug . '.xml');

    File::ensureDirectoryExists(public_path('rss'));
    File::put($path, $rssXml);
}
    

public function comments()
{
    return $this->hasMany(Comment::class, 'anime_link_id');
}

public function relatedGroup()
{
    return $this->belongsTo(\App\Models\RelateAnimeGroup::class, 'related_anime_group_id');
}

public function relatedAnimes()
{
    return $this->hasMany(RelatedAnime::class);
}



}
