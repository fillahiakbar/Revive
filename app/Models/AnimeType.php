<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimeType extends Model
{
    protected $fillable = ['mal_id', 'name', 'color'];

    public function animeLinks()
    {
        return $this->belongsToMany(AnimeLink::class);
    }
}
