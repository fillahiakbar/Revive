<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Anime extends Model
{
    protected $fillable = [
        'mal_id',
        'type',
        'title',
        'title_english',
        'genres',
        'poster',
        'background',
        'progress',
        'score',
        'episodes',
    ];

    protected static function booted()
    {
        static::saving(function ($anime) {
            if ($anime->isDirty('mal_id') && $anime->mal_id) {
                try {
                    $response = Http::get("https://api.jikan.moe/v4/anime/{$anime->mal_id}");
                    if ($response->ok()) {
                        $data = $response->json('data');
                        $anime->title = $data['title'] ?? $anime->title;
                        $anime->title_english = $data['title_english'] ?? $anime->title_english;
                        $anime->poster = $data['images']['webp']['image_url'] ?? $anime->poster;
                        $anime->genres = collect($data['genres'])->pluck('name')->join(', ');
                    }
                } catch (\Exception $e) {
                    logger()->error("Failed to fetch from Jikan API: " . $e->getMessage());
                }
            }
        });
    }
}
