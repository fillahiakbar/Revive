<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['anime_link_id','user_id', 'body']; 
    public function user()
{
    return $this->belongsTo(User::class);
}

public function animeLink()
{
    return $this->belongsTo(AnimeLink::class);
}

}
