<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AnimeRecomend extends Model
{
    use HasFactory;

    protected $fillable = ['mal_id', 'title', 'title_english', 'poster', 'genres', 'background', 'progress'];

    protected static function booted()
    {
        static::saving(function ($anime) {
            if ($anime->isDirty('mal_id')) {
                $data = Http::get("https://api.jikan.moe/v4/anime/{$anime->mal_id}")->json('data');

                $anime->title = $data['title'];
                $anime->title_english = $data['title_english'];
                $anime->poster = $data['images']['webp']['image_url'];
                $anime->genres = collect($data['genres'])->pluck('name')->join(', ');
            }
        });
    }
}
