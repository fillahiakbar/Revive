<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['anime_link_id', 'name', 'episodes'];

    public function animeLink()
    {
        return $this->belongsTo(AnimeLink::class);
    }

    public function batchLinks()
{
    return $this->hasMany(BatchLink::class, 'batch_id');
}
}

