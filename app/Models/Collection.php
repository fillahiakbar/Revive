<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'poster',
    ];

    public function animeLinks()
    {
        return $this->belongsToMany(
            \App\Models\AnimeLink::class,
            'anime_link_collection',
            'collection_id',
            'anime_link_id'
        )
            ->withPivot('sort_order', 'collection_label')
            ->orderByPivot('sort_order', 'asc')
            ->withTimestamps();
    }
    public function getPosterUrlAttribute()
    {
        if (!$this->poster) {
            return asset('img/default-poster.jpg');
        }
        if (filter_var($this->poster, FILTER_VALIDATE_URL)) {
            return $this->poster;
        }
        return asset('storage/' . $this->poster);
    }
}
